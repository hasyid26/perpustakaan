{{-- resources/views/peminjaman/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ Auth::user()->isPeminjam() ? 'Peminjaman Saya' : 'Data Peminjaman' }}
            </h2>
            @if(Auth::user()->isPeminjam())
                <a href="{{ route('peminjaman.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Pinjam Buku
                </a>
            @endif
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">

            {{-- Alert Messages --}}
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Tab Navigation (Admin/Petugas) --}}
            @if(Auth::user()->isAdministrator() || Auth::user()->isPetugas())
                <div class="mb-6 border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8">

                        {{-- Request Peminjaman --}}
                        <a href="{{ route('peminjaman.index', ['tab' => 'pending_pinjam']) }}"
                           class="@if(request('tab') == 'pending_pinjam') border-purple-500 text-purple-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Request Peminjaman
                            @php
                                $reqPinjamCount = \App\Models\Peminjaman::where('status', 'pending')->whereNull('petugas_pinjam_id')->count();
                            @endphp
                            @if($reqPinjamCount > 0)
                                <span class="ml-2 bg-purple-100 text-purple-900 py-0.5 px-2.5 rounded-full text-xs font-semibold">{{ $reqPinjamCount }}</span>
                            @endif
                        </a>

                        {{-- Request Pengembalian --}}
                        <a href="{{ route('peminjaman.index', ['tab' => 'pending_kembali']) }}"
                           class="@if(request('tab') == 'pending_kembali') border-orange-500 text-orange-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Request Pengembalian
                            @php
                                $reqKembaliCount = \App\Models\Peminjaman::whereNotNull('tanggal_pengembalian_aktual')
                                                    ->whereNull('petugas_kembali_id')->count();
                            @endphp
                            @if($reqKembaliCount > 0)
                                <span class="ml-2 bg-orange-100 text-orange-900 py-0.5 px-2.5 rounded-full text-xs font-semibold">{{ $reqKembaliCount }}</span>
                            @endif
                        </a>

                        {{-- Semua Peminjaman --}}
                        <a href="{{ route('peminjaman.index') }}" 
                           class="@if(!request('tab') || request('tab') == 'all') border-blue-500 text-blue-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Semua Peminjaman
                            @if($peminjaman->total() > 0 && (!request('tab') || request('tab') == 'all'))
                                <span class="ml-2 bg-gray-100 text-gray-900 py-0.5 px-2.5 rounded-full text-xs">{{ $peminjaman->total() }}</span>
                            @endif
                        </a>
                    </nav>
                </div>
            @endif

            {{-- Filter --}}
            <form method="GET" class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                @if(request('tab'))
                    <input type="hidden" name="tab" value="{{ request('tab') }}">
                @endif
                <input type="text" name="search" placeholder="Cari kode, peminjam, buku..." 
                       value="{{ request('search') }}"
                       class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                    <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
                <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Filter
                </button>
            </form>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                            @if(!Auth::user()->isPeminjam())
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Peminjam</th>
                            @endif
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Buku</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tgl Pinjam</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tgl Kembali</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            @if(!Auth::user()->isPeminjam())
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Denda</th>
                            @endif
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($peminjaman as $p)
                            <tr class="{{ $p->isTerlambat() && $p->status == 'dipinjam' ? 'bg-red-50' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $p->kode_peminjaman }}</td>
                                @if(!Auth::user()->isPeminjam())
                                    <td class="px-6 py-4 text-sm">{{ $p->user->name }}</td>
                                @endif
                                <td class="px-6 py-4 text-sm">
                                    <div class="font-medium">{{ $p->buku->judul }}</div>
                                    <div class="text-gray-500 text-xs">{{ $p->buku->penulis }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $p->tanggal_peminjaman?->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $p->tanggal_pengembalian_rencana?->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($p->status == 'pending')
                                        <span class="px-2 py-1 text-xs rounded-full bg-orange-100 text-orange-800 font-semibold">Pending</span>
                                    @elseif($p->status == 'dipinjam')
                                        @if($p->isTerlambat())
                                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Terlambat</span>
                                        @else
                                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Dipinjam</span>
                                        @endif
                                    @elseif($p->status == 'ditolak')
                                        <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Ditolak</span>
                                    @elseif($p->status == 'dikembalikan')
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Dikembalikan</span>
                                    @endif
                                </td>
                                @if(!Auth::user()->isPeminjam())
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        {{ $p->denda > 0 ? 'Rp ' . number_format($p->denda, 0, ',', '.') : '-' }}
                                    </td>
                                @endif
                                <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                    <a href="{{ route('peminjaman.show', $p) }}" class="text-blue-600 hover:text-blue-900">Detail</a>

                                    {{-- Request Peminjaman --}}
                                    @if((Auth::user()->isAdministrator() || Auth::user()->isPetugas()) && request('tab') == 'pending_pinjam' && $p->status == 'pending')
                                        <button onclick="openApproveModal({{ $p->id }}, '{{ $p->kode_peminjaman }}', '{{ $p->user->name }}', '{{ $p->buku->judul }}')" 
                                                class="text-green-600 hover:text-green-900 font-semibold">Setujui</button>
                                        <button onclick="openRejectModal({{ $p->id }}, '{{ $p->kode_peminjaman }}', '{{ $p->user->name }}')" 
                                                class="text-red-600 hover:text-red-900 font-semibold">Tolak</button>
                                    @endif

                                    {{-- Request Pengembalian --}}
                                    @if((Auth::user()->isAdministrator() || Auth::user()->isPetugas()) && request('tab') == 'pending_kembali' && $p->tanggal_pengembalian_aktual && !$p->petugas_kembali_id)
                                        <button onclick="openApprovePengembalianModal({{ $p->id }}, '{{ $p->kode_peminjaman }}', '{{ $p->user->name }}', '{{ $p->buku->judul }}')" 
                                                class="text-green-600 hover:text-green-900 font-semibold">Setujui</button>
                                        <button onclick="openRejectPengembalianModal({{ $p->id }}, '{{ $p->kode_peminjaman }}', '{{ $p->user->name }}')" 
                                                class="text-red-600 hover:text-red-900 font-semibold">Tolak</button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ Auth::user()->isPeminjam() ? '6' : '8' }}" class="px-6 py-8 text-center text-gray-500">
                                    <p class="mt-2">Tidak ada data peminjaman</p>
                                    @if(Auth::user()->isPeminjam())
                                        <a href="{{ route('peminjaman.create') }}" class="mt-2 inline-block text-blue-600 hover:text-blue-800">
                                            Pinjam buku sekarang →
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $peminjaman->links() }}
            </div>
        </div>
    </div>

    {{-- Modal Approve/Reject Peminjaman & Pengembalian --}}
    @include('peminjaman.modals')

</x-app-layout>
