<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanUserController extends Controller
{
    /**
     * Tampilkan daftar peminjaman milik user
     */
    public function index(Request $request)
    {
        $query = Peminjaman::with(['user', 'buku', 'petugasPinjam', 'petugasKembali'])
                           ->where('user_id', Auth::id());

        // Filter status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_peminjaman', 'like', "%{$search}%")
                  ->orWhereHas('buku', function($q) use ($search) {
                      $q->where('judul', 'like', "%{$search}%");
                  });
            });
        }

        $peminjaman = $query->latest()->paginate(10)->withQueryString();

        return view('peminjaman_user.index', compact('peminjaman'));
    }

    /**
     * Form pengajuan peminjaman buku
     */
    public function create()
    {
        // FIX: kolom stok = jumlah_tersedia
        $bukus = Buku::where('jumlah_tersedia', '>', 0)
                     ->orderBy('judul', 'asc')
                     ->get();

        return view('peminjaman_user.create', compact('bukus'));
    }

    /**
     * Simpan pengajuan peminjaman
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'buku_id' => 'required|exists:bukus,id',
            'tanggal_peminjaman' => 'required|date',
            'tanggal_pengembalian_rencana' => 'required|date|after:tanggal_peminjaman',
            'catatan' => 'nullable|string'
        ]);

        $buku = Buku::findOrFail($validated['buku_id']);

        // FIX: cek stok dari jumlah_tersedia
        if ($buku->jumlah_tersedia < 1) {
            return back()->withInput()->with('error', 'Buku tidak tersedia!');
        }

        // Generate kode peminjaman (format: PJM-00001)
        $last = Peminjaman::latest('id')->first();
        $number = $last ? intval(substr($last->kode_peminjaman, 4)) + 1 : 1;

        $kode = 'PJM-' . str_pad($number, 5, '0', STR_PAD_LEFT);

        // Simpan pengajuan
        $peminjaman = Peminjaman::create([
            'kode_peminjaman' => $kode,
            'user_id' => Auth::id(),
            'buku_id' => $validated['buku_id'],
            'tanggal_peminjaman' => $validated['tanggal_peminjaman'],
            'tanggal_pengembalian_rencana' => $validated['tanggal_pengembalian_rencana'],
            'catatan' => $validated['catatan'] ?? null,
            'status' => 'pending',
            'petugas_pinjam_id' => null,
        ]);

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Pengajuan Peminjaman',
            'deskripsi' => "Mengajukan peminjaman buku: {$buku->judul} ({$kode})"
        ]);

        return redirect()->route('peminjaman-user.index')
            ->with('success', 'Pengajuan peminjaman berhasil dikirim. Menunggu persetujuan petugas.');
    }

    /**
     * Detail peminjaman user
     */
    public function show(Peminjaman $peminjaman)
    {
        if ($peminjaman->user_id !== Auth::id()) {
            return redirect()->route('peminjaman-user.index')
                ->with('error', 'Anda tidak memiliki akses!');
        }

        $peminjaman->load(['user', 'buku', 'petugasPinjam', 'petugasKembali']);

        return view('peminjaman_user.show', compact('peminjaman'));
    }

    /**
     * Request pengembalian
     */
    public function requestPengembalian(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->user_id !== Auth::id()) {
            return back()->with('error', 'Tidak memiliki akses!');
        }

        if ($peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Buku tidak dalam status dipinjam!');
        }

        if ($peminjaman->tanggal_pengembalian_aktual !== null) {
            return back()->with('error', 'Anda sudah mengajukan request!');
        }

        $validated = $request->validate([
            'catatan' => 'nullable|string'
        ]);

        $peminjaman->update([
            'tanggal_pengembalian_aktual' => now()->format('Y-m-d'),
            'catatan' => $validated['catatan'] ?? $peminjaman->catatan
        ]);

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Request Pengembalian',
            'deskripsi' =>
                "Request pengembalian buku: {$peminjaman->buku->judul} ({$peminjaman->kode_peminjaman})"
        ]);

        return back()->with('success', 'Request pengembalian berhasil dikirim.');
    }

    /**
     * Batalkan request pengembalian
     */
    public function cancelRequest(Peminjaman $peminjaman)
    {
        if ($peminjaman->user_id !== Auth::id()) {
            return back()->with('error', 'Tidak memiliki akses!');
        }

        if ($peminjaman->tanggal_pengembalian_aktual === null ||
            $peminjaman->petugas_kembali_id !== null) {
            return back()->with('error', 'Tidak ada request yang dapat dibatalkan!');
        }

        $peminjaman->update([
            'tanggal_pengembalian_aktual' => null,
            'catatan' => null
        ]);

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Batal Request Pengembalian',
            'deskripsi' =>
                "Membatalkan request pengembalian buku: {$peminjaman->buku->judul}"
        ]);

        return back()->with('success', 'Request pengembalian dibatalkan.');
    }
}
