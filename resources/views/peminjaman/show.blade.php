{{-- resources/views/peminjaman/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Peminjaman: {{ $peminjaman->kode_peminjaman }}
            </h2>
            <a href="{{ route('peminjaman.index') }}" 
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">

        <!-- Status Card -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Status Peminjaman</h3>

                        @if($peminjaman->status == 'dipinjam')
                            @if($peminjaman->isTerlambat())
                                <span class="px-4 py-2 text-sm rounded-full bg-red-100 text-red-800 font-semibold">
                                    ⚠️ Terlambat
                                </span>
                            @else
                                <span class="px-4 py-2 text-sm rounded-full bg-yellow-100 text-yellow-800 font-semibold">
                                    📖 Sedang Dipinjam
                                </span>
                            @endif
                        @elseif($peminjaman->status == 'request_pengembalian')
                            <span class="px-4 py-2 text-sm rounded-full bg-blue-100 text-blue-800 font-semibold">
                                🔄 Menunggu Persetujuan Pengembalian
                            </span>
                        @elseif($peminjaman->status == 'dikembalikan')
                            <span class="px-4 py-2 text-sm rounded-full bg-green-100 text-green-800 font-semibold">
                                ✅ Sudah Dikembalikan
                            </span>
                        @else
                            <span class="px-4 py-2 text-sm rounded-full bg-gray-100 text-gray-800 font-semibold">
                                {{ ucfirst($peminjaman->status) }}
                            </span>
                        @endif
                    </div>

                    {{-- TOMBOL UNTUK PETUGAS --}}
                    @if(Auth::user()->isAdministrator() || Auth::user()->isPetugas())

                        @if($peminjaman->status == 'request_pengembalian')
                            <div class="flex gap-2">
                                <form action="{{ route('peminjaman.approve', $peminjaman) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            onclick="return confirm('Setujui pengembalian buku ini?')"
                                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                        ✅ Setujui Pengembalian
                                    </button>
                                </form>

                                <form action="{{ route('peminjaman.reject', $peminjaman) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            onclick="return confirm('Tolak pengembalian buku ini?')"
                                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                        ❌ Tolak
                                    </button>
                                </form>
                            </div>
                        @endif

                    @endif

                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Informasi Peminjam -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">👤 Informasi Peminjam</h3>
                    <div class="space-y-3">
                        <div>
                            <span class="text-sm text-gray-600">Nama:</span>
                            <p class="font-medium">{{ $peminjaman->user?->name ?? '-' }}</p>
                        </div>

                        <div>
                            <span class="text-sm text-gray-600">No Identitas:</span>
                            <p class="font-medium">{{ $peminjaman->user?->no_identitas ?? '-' }}</p>
                        </div>

                        <div>
                            <span class="text-sm text-gray-600">Email:</span>
                            <p class="font-medium">{{ $peminjaman->user?->email ?? '-' }}</p>
                        </div>

                        <div>
                            <span class="text-sm text-gray-600">No Telepon:</span>
                            <p class="font-medium">{{ $peminjaman->user?->no_telepon ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Buku -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">📚 Informasi Buku</h3>
                    <div class="space-y-3">
                        <div>
                            <span class="text-sm text-gray-600">Judul:</span>
                            <p class="font-medium">{{ $peminjaman->buku?->judul ?? '-' }}</p>
                        </div>

                        <div>
                            <span class="text-sm text-gray-600">Penulis:</span>
                            <p class="font-medium">{{ $peminjaman->buku?->penulis ?? '-' }}</p>
                        </div>

                        <div>
                            <span class="text-sm text-gray-600">Penerbit:</span>
                            <p class="font-medium">{{ $peminjaman->buku?->penerbit ?? '-' }}</p>
                        </div>

                        <div>
                            <span class="text-sm text-gray-600">Kode Buku:</span>
                            <p class="font-medium">{{ $peminjaman->buku?->kode_buku ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Detail Peminjaman -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">📋 Detail Peminjaman</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <span class="text-sm text-gray-600">Kode Peminjaman:</span>
                        <p class="font-medium text-lg">{{ $peminjaman->kode_peminjaman }}</p>
                    </div>

                    <div>
                        <span class="text-sm text-gray-600">Tanggal Peminjaman:</span>
                        <p class="font-medium">
                            {{ optional($peminjaman->tanggal_peminjaman)->format('d F Y') ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <span class="text-sm text-gray-600">Tanggal Pengembalian Rencana:</span>
                        <p class="font-medium">
                            {{ optional($peminjaman->tanggal_pengembalian_rencana)->format('d F Y') ?? '-' }}
                        </p>
                    </div>

                    @if($peminjaman->tanggal_pengembalian_aktual)
                        <div>
                            <span class="text-sm text-gray-600">Tanggal Pengembalian Aktual:</span>
                            <p class="font-medium">
                                {{ $peminjaman->tanggal_pengembalian_aktual->format('d F Y') }}
                            </p>
                        </div>
                    @endif

                    <div>
                        <span class="text-sm text-gray-600">Petugas Peminjaman:</span>
                        <p class="font-medium">{{ $peminjaman->petugasPinjam?->name ?? '-' }}</p>
                    </div>

                    <div>
                        <span class="text-sm text-gray-600">Petugas Pengembalian:</span>
                        <p class="font-medium">{{ $peminjaman->petugasKembali?->name ?? '-' }}</p>
                    </div>

                    @if($peminjaman->denda > 0)
                        <div>
                            <span class="text-sm text-gray-600">Denda Keterlambatan:</span>
                            <p class="font-medium text-red-600 text-lg">
                                Rp {{ number_format($peminjaman->denda, 0, ',', '.') }}
                            </p>
                        </div>
                    @endif

                    @if($peminjaman->catatan)
                        <div class="md:col-span-2">
                            <span class="text-sm text-gray-600">Catatan:</span>
                            <p class="font-medium whitespace-pre-line">
                                {{ $peminjaman->catatan }}
                            </p>
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>
</x-app-layout>
