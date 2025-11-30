<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        // Data umum untuk semua role
        $data = [
            'total_buku' => Buku::count(),
            'total_peminjaman_aktif' => Peminjaman::where('status', 'dipinjam')->count(),
        ];

        // Data tambahan untuk Administrator & Petugas
        if ($user->role === 'administrator' || $user->role === 'petugas') {
            $data['total_anggota'] = User::where('role', 'peminjam')->count();
            
            // Total buku yang tersedia
            $data['buku_tersedia'] = Buku::where('jumlah_tersedia', '>', 0)->count();
            
            // Request pengembalian yang pending (belum diproses)
            $data['request_pending'] = Peminjaman::whereNotNull('tanggal_pengembalian_aktual')
                                                  ->whereNull('petugas_kembali_id')
                                                  ->count();
            
            // Total peminjaman terlambat
            $data['peminjaman_terlambat'] = Peminjaman::where('status', 'dipinjam')
                ->where('tanggal_pengembalian_rencana', '<', now())
                ->count();
            
            // Peminjaman terbaru untuk petugas/admin
            $data['peminjaman_terbaru'] = Peminjaman::with(['user', 'buku'])
                ->latest()
                ->take(5)
                ->get();
        } 
        // Data untuk Peminjam
        else {
            // Peminjaman terbaru milik user
            $data['peminjaman_saya'] = Peminjaman::with('buku')
                ->where('user_id', $user->id)
                ->latest()
                ->take(5)
                ->get();
            
            // Total peminjaman aktif milik user
            $data['total_dipinjam'] = Peminjaman::where('user_id', $user->id)
                ->where('status', 'dipinjam')
                ->count();
            
            // Total peminjaman selesai milik user
            $data['total_peminjaman_selesai'] = Peminjaman::where('user_id', $user->id)
                ->where('status', 'dikembalikan')
                ->count();
            
            // Total peminjaman terlambat milik user
            $data['total_terlambat'] = Peminjaman::where('user_id', $user->id)
                ->where('status', 'dipinjam')
                ->where('tanggal_pengembalian_rencana', '<', now())
                ->count();
        }

        return view('dashboard', $data);
    }
}