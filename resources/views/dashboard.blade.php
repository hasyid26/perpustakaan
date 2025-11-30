{{-- resources/views/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="space-y-6">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Card 1: Total Buku -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-gray-600 text-sm font-medium">Total Buku</div>
                        <div class="text-3xl font-bold text-blue-600 mt-2">{{ $total_buku }}</div>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Card 2: Peminjaman Aktif -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-gray-600 text-sm font-medium">
                            {{ auth()->user()->isPeminjam() ? 'Sedang Dipinjam' : 'Peminjaman Aktif' }}
                        </div>
                        <div class="text-3xl font-bold text-green-600 mt-2">
                            {{ auth()->user()->isPeminjam() ? ($total_dipinjam ?? 0) : $total_peminjaman_aktif }}
                        </div>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Card 3: Berbeda untuk Admin/Petugas vs Peminjam -->
            @if(auth()->user()->isAdministrator() || auth()->user()->isPetugas())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Total Anggota</div>
                            <div class="text-3xl font-bold text-purple-600 mt-2">{{ $total_anggota }}</div>
                        </div>
                        <div class="bg-purple-100 p-3 rounded-full">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Peminjaman Selesai</div>
                            <div class="text-3xl font-bold text-indigo-600 mt-2">{{ $total_peminjaman_selesai ?? 0 }}</div>
                        </div>
                        <div class="bg-indigo-100 p-3 rounded-full">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Quick Stats untuk Admin/Petugas -->
        @if(auth()->user()->isAdministrator() || auth()->user()->isPetugas())
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan Sistem</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-gray-600 text-sm">Buku Tersedia</div>
                                    <div class="text-2xl font-bold text-green-600 mt-1">{{ $buku_tersedia ?? 0 }}</div>
                                </div>
                                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        </div>
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-gray-600 text-sm">Sedang Dipinjam</div>
                                    <div class="text-2xl font-bold text-yellow-600 mt-1">{{ $total_peminjaman_aktif }}</div>
                                </div>
                                <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-gray-600 text-sm">Request Pending</div>
                                    <div class="text-2xl font-bold text-orange-600 mt-1">{{ $request_pending ?? 0 }}</div>
                                </div>
                                <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-gray-600 text-sm">Terlambat</div>
                                    <div class="text-2xl font-bold text-red-600 mt-1">{{ $peminjaman_terlambat ?? 0 }}</div>
                                </div>
                                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Recent Activity -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">
                        @if(auth()->user()->isPeminjam())
                            Peminjaman Terbaru Saya
                        @else
                            Peminjaman Terbaru
                        @endif
                    </h3>
                    @if(auth()->user()->isPeminjam())
                        <a href="{{ route('peminjaman-user.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Pinjam Buku
                        </a>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                                @if(!auth()->user()->isPeminjam())
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peminjam</th>
                                @endif
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Buku</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pinjam</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Kembali</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse((auth()->user()->isPeminjam() ? $peminjaman_saya : $peminjaman_terbaru) as $p)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $p->kode_peminjaman }}
                                    </td>
                                    @if(!auth()->user()->isPeminjam())
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ $p->user->name }}
                                        </td>
                                    @endif
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        <div class="font-medium">{{ $p->buku->judul }}</div>
                                        <div class="text-gray-500 text-xs">{{ $p->buku->penulis }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ $p->tanggal_peminjaman->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ $p->tanggal_pengembalian_rencana->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($p->tanggal_pengembalian_aktual && !$p->petugas_kembali_id)
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                                Menunggu Persetujuan
                                            </span>
                                        @elseif($p->status == 'dipinjam')
                                            @if($p->isTerlambat())
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                    Terlambat
                                                </span>
                                            @else
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Dipinjam
                                                </span>
                                            @endif
                                        @else
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                Dikembalikan
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ auth()->user()->isPeminjam() ? '5' : '6' }}" class="px-6 py-8 text-center text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <p class="mb-2">Belum ada peminjaman</p>
                                        @if(auth()->user()->isPeminjam())
                                            <a href="{{ route('peminjaman-user.create') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                Pinjam buku sekarang →
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Link ke halaman list peminjaman --}}
                @if(auth()->user()->isPeminjam() && isset($peminjaman_saya) && $peminjaman_saya->count() > 0)
                    <div class="mt-4 text-center">
                        <a href="{{ route('peminjaman-user.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Lihat Semua Peminjaman Saya →
                        </a>
                    </div>
                @elseif(!auth()->user()->isPeminjam() && isset($peminjaman_terbaru) && $peminjaman_terbaru->count() > 0)
                    <div class="mt-4 text-center">
                        <a href="{{ route('peminjaman.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Lihat Semua Peminjaman →
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions untuk Peminjam -->
        @if(auth()->user()->isPeminjam())
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Akses Cepat</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('koleksi.buku') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                            <div class="bg-blue-100 p-3 rounded-lg mr-4">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">Cari Buku</div>
                                <div class="text-sm text-gray-500">Telusuri koleksi perpustakaan</div>
                            </div>
                        </a>
                        <a href="{{ route('peminjaman-user.index') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                            <div class="bg-green-100 p-3 rounded-lg mr-4">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">Riwayat Peminjaman</div>
                                <div class="text-sm text-gray-500">Lihat peminjaman Anda</div>
                            </div>
                        </a>
                        <a href="{{ route('peminjaman-user.create') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                            <div class="bg-purple-100 p-3 rounded-lg mr-4">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">Pinjam Buku Baru</div>
                                <div class="text-sm text-gray-500">Tambah peminjaman baru</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>