<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    /**
     * Tampilkan daftar peminjaman
     */
    public function index(Request $request)
    {
        $query = Peminjaman::with(['user', 'buku', 'petugasPinjam', 'petugasKembali']);

        if ($request->tab == 'pending_pinjam') {
            $query->where('status', 'pending')->whereNull('petugas_pinjam_id');
        } elseif ($request->tab == 'pending_kembali') {
            $query->whereNotNull('tanggal_pengembalian_aktual')
                  ->whereNull('petugas_kembali_id');
        }

        if ($request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_peminjaman', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($x) => $x->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('buku', fn($x) => $x->where('judul', 'like', "%{$search}%"));
            });
        }

        $peminjaman = $query->latest()->paginate(10)->withQueryString();

        return view('peminjaman.index', compact('peminjaman'));
    }

    /**
     * Detail peminjaman
     */
    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load(['user', 'buku', 'petugasPinjam', 'petugasKembali']);
        return view('peminjaman.show', compact('peminjaman'));
    }

    /* ============================================================
     | PETUGAS: APPROVE PEMINJAMAN
     |============================================================ */
    public function approvePeminjaman(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'pending' || $peminjaman->petugas_pinjam_id !== null) {
            return back()->with('error', 'Request tidak valid!');
        }

        $validated = $request->validate([
            'tanggal_peminjaman' => 'required|date',
            'tanggal_pengembalian_rencana' => 'required|date|after:tanggal_peminjaman',
            'catatan_petugas' => 'nullable|string',
        ]);

        // cek stok buku
        if ($peminjaman->buku->jumlah_tersedia < 1) {
            return back()->with('error', 'Stok buku tidak tersedia!');
        }

        // gabungkan catatan
        $catatan = $peminjaman->catatan;
        if ($validated['catatan_petugas']) {
            $catatan .= ($catatan ? "\n\n" : "") . "Catatan Petugas: " . $validated['catatan_petugas'];
        }

        $peminjaman->update([
            'tanggal_peminjaman' => $validated['tanggal_peminjaman'],
            'tanggal_pengembalian_rencana' => $validated['tanggal_pengembalian_rencana'],
            'status' => 'dipinjam',
            'petugas_pinjam_id' => Auth::id(),
            'catatan' => $catatan,
        ]);

        // kurangi stok buku
        $peminjaman->buku->decrement('jumlah_tersedia', 1);

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Approve Peminjaman',
            'deskripsi' => "Approve peminjaman buku: {$peminjaman->buku->judul}",
        ]);

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman disetujui!');
    }

    /* ============================================================
     | PETUGAS: REJECT PEMINJAMAN
     |============================================================ */
    public function rejectPeminjaman(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'pending' || $peminjaman->petugas_pinjam_id !== null) {
            return back()->with('error', 'Tidak ada request yang diproses!');
        }

        $validated = $request->validate([
            'alasan_penolakan' => 'required|string|min:10',
        ]);

        $catatan = $peminjaman->catatan;
        $catatan .= ($catatan ? "\n\n" : "") . "❌ DITOLAK: " . $validated['alasan_penolakan'];

        $peminjaman->update([
            'status' => 'ditolak',
            'catatan' => $catatan,
        ]);

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Reject Peminjaman',
            'deskripsi' => "Menolak peminjaman: {$peminjaman->buku->judul}",
        ]);

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman ditolak.');
    }

    /* ============================================================
     | PETUGAS: APPROVE PENGEMBALIAN
     |============================================================ */
    public function approvePengembalian(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->tanggal_pengembalian_aktual === null || $peminjaman->petugas_kembali_id !== null) {
            return back()->with('error', 'Request tidak valid!');
        }

        $validated = $request->validate([
            'tanggal_pengembalian_aktual' => 'required|date',
            'catatan' => 'nullable|string',
        ]);

        $catatan = $peminjaman->catatan;
        if ($validated['catatan']) {
            $catatan .= ($catatan ? "\n\n" : "") . "Catatan Petugas: " . $validated['catatan'];
        }

        $tglKembali = Carbon::parse($validated['tanggal_pengembalian_aktual']);
        $tglRencana = Carbon::parse($peminjaman->tanggal_pengembalian_rencana);

        // Hitung denda hanya jika terlambat
        $hariTerlambat = $tglKembali->greaterThan($tglRencana) ? $tglKembali->diffInDays($tglRencana) : 0;
        $denda = $hariTerlambat * 1000;

        $peminjaman->update([
            'tanggal_pengembalian_aktual' => $validated['tanggal_pengembalian_aktual'],
            'status' => 'dikembalikan',
            'petugas_kembali_id' => Auth::id(),
            'catatan' => $catatan,
            'denda' => $denda,
        ]);

        // kembalikan stok buku
        $peminjaman->buku->increment('jumlah_tersedia', 1);

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Approve Pengembalian',
            'deskripsi' => "Approve pengembalian buku: {$peminjaman->buku->judul}",
        ]);

        return redirect()->route('peminjaman.index')->with('success', 'Pengembalian disetujui!');
    }

    /* ============================================================
     | PETUGAS: REJECT PENGEMBALIAN
     |============================================================ */
    public function rejectPengembalian(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->tanggal_pengembalian_aktual === null || $peminjaman->petugas_kembali_id !== null) {
            return back()->with('error', 'Tidak ada request yang diproses!');
        }

        $validated = $request->validate([
            'alasan_penolakan' => 'required|string|min:10',
        ]);

        $catatan = $peminjaman->catatan;
        $catatan .= ($catatan ? "\n\n" : "") . "❌ DITOLAK: " . $validated['alasan_penolakan'];

        $peminjaman->update([
            'tanggal_pengembalian_aktual' => null,
            'catatan' => $catatan,
        ]);

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Reject Pengembalian',
            'deskripsi' => "Reject pengembalian: {$peminjaman->buku->judul}",
        ]);

        return redirect()->route('peminjaman.index')->with('success', 'Pengembalian ditolak.');
    }
}
