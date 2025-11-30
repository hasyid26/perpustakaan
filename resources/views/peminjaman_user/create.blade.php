{{-- resources/views/peminjaman/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Pinjam Buku Baru
            </h2>
            <a href="{{ route('peminjaman.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <!-- Alert Messages -->
                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Form Peminjaman -->
                <form action="{{ route('peminjaman-user.store') }}" method="POST">
                 @csrf

                    <!-- Pilih Buku -->
                    <div>
                        <label for="buku_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Pilih Buku <span class="text-red-500">*</span>
                        </label>
                        <select name="buku_id" id="buku_id" required 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                onchange="updateBukuInfo()">
                            <option value="">-- Pilih Buku --</option>
                            @foreach($bukus as $buku)
                                <option value="{{ $buku->id }}" 
                                        data-judul="{{ $buku->judul }}"
                                        data-penulis="{{ $buku->penulis }}"
                                        data-penerbit="{{ $buku->penerbit }}"
                                        data-stok="{{ $buku->jumlah_tersedia }}"
                                        {{ old('buku_id') == $buku->id ? 'selected' : '' }}>
                                    {{ $buku->judul }} - {{ $buku->penulis }} (Stok: {{ $buku->jumlah_tersedia }})
                                </option>
                            @endforeach
                        </select>
                        
                        <!-- Info Buku yang Dipilih -->
                        <div id="bukuInfo" class="hidden mt-3 p-4 bg-blue-50 rounded-md border border-blue-200">
                            <h4 class="font-semibold text-blue-900 mb-2">Informasi Buku:</h4>
                            <div class="text-sm text-blue-800 space-y-1">
                                <p><span class="font-medium">Judul:</span> <span id="infoBukuJudul"></span></p>
                                <p><span class="font-medium">Penulis:</span> <span id="infoBukuPenulis"></span></p>
                                <p><span class="font-medium">Penerbit:</span> <span id="infoBukuPenerbit"></span></p>
                                <p><span class="font-medium">Stok Tersedia:</span> <span id="infoBukuStok"></span> unit</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tanggal Peminjaman -->
                    <div>
                        <label for="tanggal_peminjaman" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Peminjaman <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               name="tanggal_peminjaman" 
                               id="tanggal_peminjaman" 
                               value="{{ old('tanggal_peminjaman', date('Y-m-d')) }}"
                               min="{{ date('Y-m-d') }}"
                               max="{{ date('Y-m-d', strtotime('+7 days')) }}"
                               required
                               onchange="updateTanggalKembali()"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <p class="mt-1 text-xs text-gray-500">Maksimal 7 hari dari hari ini</p>
                    </div>

                    <!-- Tanggal Pengembalian Rencana -->
                    <div>
                        <label for="tanggal_pengembalian_rencana" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Pengembalian (Rencana) <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               name="tanggal_pengembalian_rencana" 
                               id="tanggal_pengembalian_rencana" 
                               value="{{ old('tanggal_pengembalian_rencana', date('Y-m-d', strtotime('+7 days'))) }}"
                               required
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <p class="mt-1 text-xs text-gray-500">Pilih tanggal rencana pengembalian buku</p>
                        <p id="durasiPeminjaman" class="mt-1 text-sm font-medium text-blue-600"></p>
                    </div>

                    <!-- Catatan (Optional) -->
                    <div>
                        <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan (Opsional)
                        </label>
                        <textarea name="catatan" 
                                  id="catatan" 
                                  rows="4"
                                  placeholder="Tambahkan catatan jika diperlukan..."
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('catatan') }}</textarea>
                    </div>

                    <!-- Informasi Denda -->
                    <div class="p-4 bg-yellow-50 rounded-md border border-yellow-200">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <h4 class="font-semibold text-yellow-900 mb-1">Perhatian!</h4>
                                <ul class="text-sm text-yellow-800 space-y-1">
                                    <li>• Harap kembalikan buku tepat waktu</li>
                                    <li>• Keterlambatan akan dikenakan denda <strong>Rp 1.000/hari</strong></li>
                                    <li>• Jaga kondisi buku agar tetap baik</li>
                                    <li>• Ajukan request pengembalian saat Anda mengembalikan buku</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-3 pt-4 border-t">
                        <a href="{{ route('peminjaman.index') }}" 
                           class="px-6 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            Ajukan Peminjaman
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Update info buku saat dipilih
        function updateBukuInfo() {
            const select = document.getElementById('buku_id');
            const selectedOption = select.options[select.selectedIndex];
            const bukuInfo = document.getElementById('bukuInfo');
            
            if (selectedOption.value) {
                document.getElementById('infoBukuJudul').textContent = selectedOption.dataset.judul;
                document.getElementById('infoBukuPenulis').textContent = selectedOption.dataset.penulis;
                document.getElementById('infoBukuPenerbit').textContent = selectedOption.dataset.penerbit;
                document.getElementById('infoBukuStok').textContent = selectedOption.dataset.stok;
                bukuInfo.classList.remove('hidden');
            } else {
                bukuInfo.classList.add('hidden');
            }
        }

        // Update minimum tanggal kembali berdasarkan tanggal pinjam
        function updateTanggalKembali() {
            const tanggalPinjam = document.getElementById('tanggal_peminjaman').value;
            const tanggalKembali = document.getElementById('tanggal_pengembalian_rencana');
            
            if (tanggalPinjam) {
                // Set minimum tanggal kembali = tanggal pinjam + 1 hari
                const minDate = new Date(tanggalPinjam);
                minDate.setDate(minDate.getDate() + 1);
                tanggalKembali.min = minDate.toISOString().split('T')[0];
                
                // Jika tanggal kembali sudah terisi dan lebih kecil dari minimum, update
                if (tanggalKembali.value && new Date(tanggalKembali.value) <= new Date(tanggalPinjam)) {
                    const defaultDate = new Date(tanggalPinjam);
                    defaultDate.setDate(defaultDate.getDate() + 7);
                    tanggalKembali.value = defaultDate.toISOString().split('T')[0];
                }
            }
            
            updateDurasi();
        }

        // Hitung durasi peminjaman
        function updateDurasi() {
            const tanggalPinjam = document.getElementById('tanggal_peminjaman').value;
            const tanggalKembali = document.getElementById('tanggal_pengembalian_rencana').value;
            const durasiElement = document.getElementById('durasiPeminjaman');
            
            if (tanggalPinjam && tanggalKembali) {
                const date1 = new Date(tanggalPinjam);
                const date2 = new Date(tanggalKembali);
                const diffTime = date2 - date1;
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                
                if (diffDays > 0) {
                    durasiElement.textContent = `Durasi peminjaman: ${diffDays} hari`;
                    durasiElement.classList.remove('text-red-600');
                    durasiElement.classList.add('text-blue-600');
                } else {
                    durasiElement.textContent = 'Tanggal pengembalian harus setelah tanggal peminjaman!';
                    durasiElement.classList.remove('text-blue-600');
                    durasiElement.classList.add('text-red-600');
                }
            } else {
                durasiElement.textContent = '';
            }
        }

        // Event listeners
        document.getElementById('tanggal_peminjaman').addEventListener('change', updateTanggalKembali);
        document.getElementById('tanggal_pengembalian_rencana').addEventListener('change', updateDurasi);

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            updateTanggalKembali();
            updateDurasi();
            
            // Jika ada buku yang sudah dipilih (dari old input), tampilkan infonya
            const bukuId = document.getElementById('buku_id').value;
            if (bukuId) {
                updateBukuInfo();
            }
        });
    </script>
</x-app-layout>