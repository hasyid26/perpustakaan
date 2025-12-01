<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Buku') }}
        </h2>
    </x-slot>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Form Section -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <div class="flex items-center mb-4">
                                <svg class="w-5 h-5 mr-2 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-800">Form Edit Buku</h3>
                            </div>

                            <form action="{{ route('buku.update', $buku->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <!-- Kode Buku -->
                                    <div>
                                        <label for="kode_buku" class="block text-sm font-medium text-gray-700 mb-2">
                                            Kode Buku <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" 
                                               name="kode_buku" 
                                               id="kode_buku" 
                                               value="{{ old('kode_buku', $buku->kode_buku) }}"
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('kode_buku') border-red-500 @enderror"
                                               required>
                                        @error('kode_buku')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Kategori -->
                                    <div>
                                        <label for="kategori_id" class="block text-sm font-medium text-gray-700 mb-2">
                                            Kategori <span class="text-red-500">*</span>
                                        </label>
                                        <select name="kategori_id" 
                                                id="kategori_id" 
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('kategori_id') border-red-500 @enderror"
                                                required>
                                            <option value="">Pilih Kategori</option>
                                            @foreach($kategoris as $kategori)
                                                <option value="{{ $kategori->id }}" 
                                                    {{ old('kategori_id', $buku->kategori_id) == $kategori->id ? 'selected' : '' }}>
                                                    {{ $kategori->nama_kategori }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('kategori_id')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Judul Buku -->
                                <div class="mb-6">
                                    <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">
                                        Judul Buku <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           name="judul" 
                                           id="judul" 
                                           value="{{ old('judul', $buku->judul) }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('judul') border-red-500 @enderror"
                                           required>
                                    @error('judul')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <!-- Penulis -->
                                    <div>
                                        <label for="penulis" class="block text-sm font-medium text-gray-700 mb-2">
                                            Penulis <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" 
                                               name="penulis" 
                                               id="penulis" 
                                               value="{{ old('penulis', $buku->penulis) }}"
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('penulis') border-red-500 @enderror"
                                               required>
                                        @error('penulis')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Penerbit -->
                                    <div>
                                        <label for="penerbit" class="block text-sm font-medium text-gray-700 mb-2">
                                            Penerbit <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" 
                                               name="penerbit" 
                                               id="penerbit" 
                                               value="{{ old('penerbit', $buku->penerbit) }}"
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('penerbit') border-red-500 @enderror"
                                               required>
                                        @error('penerbit')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <!-- Tahun Terbit -->
                                    <div>
                                        <label for="tahun_terbit" class="block text-sm font-medium text-gray-700 mb-2">
                                            Tahun Terbit <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" 
                                               name="tahun_terbit" 
                                               id="tahun_terbit" 
                                               value="{{ old('tahun_terbit', $buku->tahun_terbit) }}"
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('tahun_terbit') border-red-500 @enderror"
                                               required>
                                        @error('tahun_terbit')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Jumlah Total -->
                                    <div>
                                        <label for="jumlah_total" class="block text-sm font-medium text-gray-700 mb-2">
                                            Jumlah Total <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" 
                                               name="jumlah_total" 
                                               id="jumlah_total" 
                                               value="{{ old('jumlah_total', $buku->jumlah_total) }}"
                                               min="1"
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('jumlah_total') border-red-500 @enderror"
                                               required>
                                        <p class="mt-1 text-xs text-gray-500">Jumlah tersedia saat ini: {{ $buku->jumlah_tersedia }}</p>
                                        @error('jumlah_total')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Deskripsi -->
                                <div class="mb-6">
                                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                                        Deskripsi
                                    </label>
                                    <textarea name="deskripsi" 
                                              id="deskripsi" 
                                              rows="4"
                                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi', $buku->deskripsi) }}</textarea>
                                    @error('deskripsi')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Cover Buku -->
                                <div class="mb-6">
                                    <label for="cover" class="block text-sm font-medium text-gray-700 mb-2">
                                        Cover Buku
                                    </label>
                                    
                                    @if($buku->cover)
                                        <div class="mb-3">
                                            <img src="{{ asset('storage/' . $buku->cover) }}" 
                                                 alt="Cover {{ $buku->judul }}" 
                                                 class="rounded-lg shadow-md border border-gray-200"
                                                 style="max-width: 200px;">
                                            <p class="text-xs text-gray-500 mt-2">Cover saat ini</p>
                                        </div>
                                    @endif

                                    <input type="file" 
                                           name="cover" 
                                           id="cover" 
                                           accept="image/jpeg,image/png,image/jpg"
                                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('cover') border-red-500 @enderror">
                                    <p class="mt-1 text-xs text-gray-500">Format: JPG, JPEG, PNG. Maksimal 2MB. Kosongkan jika tidak ingin mengubah cover.</p>
                                    @error('cover')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Buttons -->
                                <div class="flex items-center gap-3">
                                    <button type="submit" 
                                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Update Buku
                                    </button>
                                    <a href="{{ route('buku.index') }}" 
                                       class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                        </svg>
                                        Kembali
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Info Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Informasi Buku -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-4 bg-blue-600 border-b border-blue-700">
                            <h3 class="text-white font-semibold flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Informasi Buku
                            </h3>
                        </div>
                        <div class="p-4">
                            <table class="w-full text-sm">
                                <tr class="border-b">
                                    <td class="py-2 font-medium text-gray-700">Kode Buku:</td>
                                    <td class="py-2 text-gray-900">{{ $buku->kode_buku }}</td>
                                </tr>
                                <tr class="border-b">
                                    <td class="py-2 font-medium text-gray-700">Kategori:</td>
                                    <td class="py-2 text-gray-900">{{ $buku->kategori->nama_kategori }}</td>
                                </tr>
                                <tr class="border-b">
                                    <td class="py-2 font-medium text-gray-700">Jumlah Total:</td>
                                    <td class="py-2 text-gray-900">{{ $buku->jumlah_total }}</td>
                                </tr>
                                <tr class="border-b">
                                    <td class="py-2 font-medium text-gray-700">Jumlah Tersedia:</td>
                                    <td class="py-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $buku->jumlah_tersedia > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $buku->jumlah_tersedia }}
                                        </span>
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <td class="py-2 font-medium text-gray-700">Sedang Dipinjam:</td>
                                    <td class="py-2 text-gray-900">{{ $buku->jumlah_total - $buku->jumlah_tersedia }}</td>
                                </tr>
                                <tr class="border-b">
                                    <td class="py-2 font-medium text-gray-700">Ditambahkan:</td>
                                    <td class="py-2 text-gray-900">{{ $buku->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2 font-medium text-gray-700">Terakhir Update:</td>
                                    <td class="py-2 text-gray-900">{{ $buku->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Catatan Penting -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-4 bg-yellow-500 border-b border-yellow-600">
                            <h3 class="text-white font-semibold flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                Catatan Penting
                            </h3>
                        </div>
                        <div class="p-4">
                            <ul class="text-sm space-y-2 text-gray-700">
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 mr-2 mt-0.5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Field bertanda <span class="text-red-500 font-bold">*</span> wajib diisi
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 mr-2 mt-0.5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Kode buku harus unik
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 mr-2 mt-0.5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Jumlah total tidak boleh kurang dari jumlah buku yang sedang dipinjam
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 mr-2 mt-0.5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Cover buku bersifat opsional
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 mr-2 mt-0.5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Pastikan data sudah benar sebelum menyimpan
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Preview image sebelum upload
        document.getElementById('cover').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Hapus preview lama jika ada
                    const oldPreview = document.querySelector('.preview-new');
                    if (oldPreview) {
                        oldPreview.remove();
                    }
                    
                    // Buat preview baru
                    const previewContainer = document.createElement('div');
                    previewContainer.className = 'preview-new mt-3';
                    previewContainer.innerHTML = `
                        <img src="${e.target.result}" 
                             alt="Preview" 
                             class="rounded-lg shadow-md border border-gray-200"
                             style="max-width: 200px;">
                        <p class="text-xs text-gray-500 mt-2">Preview cover baru</p>
                    `;
                    
                    document.getElementById('cover').parentElement.appendChild(previewContainer);
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
    @endpush
</x-app-layout>