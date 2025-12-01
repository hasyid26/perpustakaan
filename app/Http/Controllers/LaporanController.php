<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with(['user', 'buku', 'petugasPinjam', 'petugasKembali']);

        if ($request->filled('tanggal_peminjaman_awal') && $request->filled('tanggal_peminjaman_akhir')) {
            $query->whereBetween('tanggal_peminjaman', [
                $request->tanggal_peminjaman_awal,
                $request->tanggal_peminjaman_akhir
            ]);
        }

        if ($request->filled('tanggal_kembali_awal') && $request->filled('tanggal_kembali_akhir')) {
            $query->whereBetween('tanggal_pengembalian_aktual', [
                $request->tanggal_kembali_awal,
                $request->tanggal_kembali_akhir
            ]);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('user')) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', '%' . $request->user . '%'));
        }

        if ($request->filled('buku')) {
            $query->whereHas('buku', fn($q) => $q->where('judul', 'like', '%' . $request->buku . '%'));
        }

        if ($request->filled('petugas')) {
            $query->where(fn($q) => 
                $q->whereHas('petugasPinjam', fn($x) => $x->where('name', 'like', '%' . $request->petugas . '%'))
                  ->orWhereHas('petugasKembali', fn($x) => $x->where('name', 'like', '%' . $request->petugas . '%')));
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn($q) => 
                $q->where('kode_peminjaman', 'like', "%$search%")
                  ->orWhereHas('user', fn($x) => $x->where('name', 'like', "%$search%"))
                  ->orWhereHas('buku', fn($x) => $x->where('judul', 'like', "%$search%")));
            
        }

        $laporan = $query->latest('tanggal_peminjaman')->paginate(15)->withQueryString();
        return view('laporan.index', compact('laporan'));
    }

    public function show(Peminjaman $laporan)
    {
        $laporan->load(['user', 'buku.kategori', 'petugasPinjam', 'petugasKembali']);
        return view('laporan.show', compact('laporan'));
    }

    public function exportPdf(Request $request)
    {
        $query = Peminjaman::with(['user', 'buku', 'petugasPinjam', 'petugasKembali']);

        if ($request->filled('tanggal_peminjaman_awal') && $request->filled('tanggal_peminjaman_akhir')) {
            $query->whereBetween('tanggal_peminjaman', [
                $request->tanggal_peminjaman_awal,
                $request->tanggal_peminjaman_akhir
            ]);
        }

        if ($request->filled('tanggal_kembali_awal') && $request->filled('tanggal_kembali_akhir')) {
            $query->whereBetween('tanggal_pengembalian_aktual', [
                $request->tanggal_kembali_awal,
                $request->tanggal_kembali_akhir
            ]);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('user')) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', '%' . $request->user . '%'));
        }

        if ($request->filled('buku')) {
            $query->whereHas('buku', fn($q) => $q->where('judul', 'like', '%' . $request->buku . '%'));
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn($q) => 
                $q->where('kode_peminjaman', 'like', "%$search%")
                  ->orWhereHas('user', fn($x) => $x->where('name', 'like', "%$search%"))
                  ->orWhereHas('buku', fn($x) => $x->where('judul', 'like', "%$search%")));
        }

        $laporan = $query->latest('tanggal_peminjaman')->get();

        // Set timezone ke Asia/Jakarta (WIB)
        $tanggalSekarang = Carbon::now('Asia/Jakarta');

        $data = [
            'laporan' => $laporan,
            // Tanggal cetak dengan zona waktu Indonesia (WIB)
            'tanggal_cetak' => $tanggalSekarang->format('d/m/Y H:i:s'),
            'filters' => [
                'tanggal_awal' => $request->tanggal_peminjaman_awal,
                'tanggal_akhir' => $request->tanggal_peminjaman_akhir,
                'status' => $request->status,
            ]
        ];

        $pdf = Pdf::loadView('laporan.pdf', $data)
                  ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-peminjaman-' . $tanggalSekarang->format('Y-m-d_H-i-s') . '.pdf');
    }
}