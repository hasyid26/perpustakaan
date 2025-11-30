<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Peminjaman Saya
            </h2>
            <a href="{{ route('peminjaman-user.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Pinjam Buku
            </a>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <!-- Alert Messages -->
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

            <!-- Filter -->
            <form method="GET" class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <input type="text" name="search" placeholder="Cari kode peminjaman, judul buku..." 
                       value="{{ request('search') }}"
                       class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                
                <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                    <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                </select>
                
                <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Filter
                </button>
            </form>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Buku</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tgl Pinjam</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tgl Kembali</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($peminjaman as $p)
                            <tr class="{{ $p->isTerlambat() && $p->status == 'dipinjam' ? 'bg-red-50' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $p->kode_peminjaman }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="font-medium">{{ $p->buku->judul }}</div>
                                    <div class="text-gray-500 text-xs">{{ $p->buku->penulis }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $p->tanggal_peminjaman->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div>{{ $p->tanggal_pengembalian_rencana->format('d/m/Y') }}</div>
                                    @if($p->tanggal_pengembalian_aktual)
                                        <small class="text-green-600">Aktual: {{ $p->tanggal_pengembalian_aktual->format('d/m/Y') }}</small>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($p->status == 'pending')
                                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                            Menunggu Persetujuan Petugas
                                        </span>
                                    @elseif($p->tanggal_pengembalian_aktual && !$p->petugas_kembali_id)
                                        <span class="px-2 py-1 text-xs rounded-full bg-orange-100 text-orange-800 font-semibold">
                                            Menunggu Persetujuan
                                        </span>
                                    @elseif($p->status == 'dipinjam')
                                        @if($p->isTerlambat())
                                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Terlambat</span>
                                        @else
                                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Dipinjam</span>
                                        @endif
                                    @else
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Dikembalikan</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                    @if($p->status == 'dipinjam' && !$p->tanggal_pengembalian_aktual)
                                        <button onclick="openRequestModal({{ $p->id }}, '{{ $p->kode_peminjaman }}')" 
                                                class="px-3 py-1 bg-orange-500 text-white rounded-md text-xs hover:bg-orange-600">
                                            Ajukan Pengembalian
                                        </button>
                                    @elseif($p->tanggal_pengembalian_aktual && !$p->petugas_kembali_id)
                                        <span class="px-2 py-1 text-xs rounded-full bg-orange-100 text-orange-800 font-semibold">
                                            Request Terkirim
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="mt-2">Anda belum memiliki peminjaman</p>
                                    <a href="{{ route('peminjaman-user.create') }}" class="mt-2 inline-block text-blue-600 hover:text-blue-800">
                                        Pinjam buku sekarang →
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $peminjaman->links() }}
            </div>
        </div>
    </div>

    {{-- Modal Request Pengembalian --}}
    <div id="requestModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Request Pengembalian Buku</h3>
                <form id="requestForm" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kode Peminjaman</label>
                        <input type="text" id="requestKode" readonly class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                        <textarea name="catatan" rows="3" 
                                  placeholder="Tambahkan catatan untuk petugas..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeRequestModal()" 
                                class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">Batal</button>
                        <button type="submit" 
                                class="px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600">Kirim Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openRequestModal(id, kode) {
            document.getElementById('requestModal').classList.remove('hidden');
            document.getElementById('requestKode').value = kode;
            document.getElementById('requestForm').action = `/peminjaman-user/${id}/request-pengembalian`;
        }

        function closeRequestModal() {
            document.getElementById('requestModal').classList.add('hidden');
        }

        document.getElementById('requestModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeRequestModal();
        });
    </script>
</x-app-layout>
