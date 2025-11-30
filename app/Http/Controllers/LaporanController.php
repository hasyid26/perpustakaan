<?php
namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan.index');
    }

    public function peminjaman(Request $request)
    {
        $validated = $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => 'nullable|in:dipinjam,dikembalikan,terlambat'
        ]);

        $query = Peminjaman::with(['user', 'buku'])
            ->whereBetween('tanggal_peminjaman', [
                $validated['tanggal_mulai'], 
                $validated['tanggal_akhir']
            ]);

        if (isset($validated['status'])) {
            $query->where('status', $validated['status']);
        }

        $peminjaman = $query->get();

        $pdf = Pdf::loadView('laporan.peminjaman', [
            'peminjaman' => $peminjaman,
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_akhir' => $validated['tanggal_akhir']
        ]);

        return $pdf->download('laporan-peminjaman-' . now()->format('Y-m-d') . '.pdf');
    }

    public function buku()
    {
        $bukus = Buku::with('kategori')->get();

        $pdf = Pdf::loadView('laporan.buku', [
            'bukus' => $bukus
        ]);

        return $pdf->download('laporan-buku-' . now()->format('Y-m-d') . '.pdf');
    }

    public function anggota()
    {
        $users = User::where('role', 'peminjam')
            ->withCount(['peminjaman'])
            ->get();

        $pdf = Pdf::loadView('laporan.anggota', [
            'users' => $users
        ]);

        return $pdf->download('laporan-anggota-' . now()->format('Y-m-d') . '.pdf');
    }
}