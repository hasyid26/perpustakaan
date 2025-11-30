{{-- resources/views/peminjaman/modals.blade.php --}}

@if(Auth::user()->isAdministrator() || Auth::user()->isPetugas())
    {{-- Modal Approve Peminjaman --}}
    <div id="approveModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-medium text-gray-900 mb-2 text-center">Setujui Request Peminjaman</h3>
            <div class="mb-4 p-3 bg-gray-50 rounded-md text-sm">
                <p class="text-gray-600">Peminjam: <span id="approvePeminjam" class="font-semibold text-gray-900"></span></p>
                <p class="text-gray-600">Buku: <span id="approveBuku" class="font-semibold text-gray-900"></span></p>
                <p class="text-gray-600">Kode: <span id="approveKodeDisplay" class="font-semibold text-gray-900"></span></p>
            </div>
            <form id="approveForm" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Tanggal Peminjaman</label>
                    <input type="date" name="tanggal_peminjaman" class="w-full border px-3 py-2 rounded-md" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Tanggal Pengembalian Rencana</label>
                    <input type="date" name="tanggal_pengembalian_rencana" class="w-full border px-3 py-2 rounded-md" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Catatan Petugas (Opsional)</label>
                    <textarea name="catatan_petugas" rows="3" class="w-full border px-3 py-2 rounded-md"></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeApproveModal()" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition">Setujui Request</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Reject Peminjaman --}}
    <div id="rejectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-medium text-gray-900 mb-2 text-center">Tolak Request Peminjaman</h3>
            <div class="mb-4 p-3 bg-gray-50 rounded-md text-sm">
                <p class="text-gray-600">Peminjam: <span id="rejectPeminjam" class="font-semibold text-gray-900"></span></p>
                <p class="text-gray-600">Kode: <span id="rejectKodeDisplay" class="font-semibold text-gray-900"></span></p>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan <span class="text-red-500">*</span></label>
                    <textarea name="alasan_penolakan" rows="4" required placeholder="Jelaskan alasan penolakan..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500"></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeRejectModal()" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition">Tolak Request</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Approve Pengembalian --}}
    <div id="approvePengembalianModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-medium text-gray-900 mb-2 text-center">Setujui Request Pengembalian</h3>
            <div class="mb-4 p-3 bg-gray-50 rounded-md text-sm">
                <p class="text-gray-600">Peminjam: <span id="approvePengembalianPeminjam" class="font-semibold text-gray-900"></span></p>
                <p class="text-gray-600">Buku: <span id="approvePengembalianBuku" class="font-semibold text-gray-900"></span></p>
                <p class="text-gray-600">Kode: <span id="approvePengembalianKodeDisplay" class="font-semibold text-gray-900"></span></p>
            </div>
            <form id="approvePengembalianForm" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Tanggal Pengembalian Aktual</label>
                    <input type="date" name="tanggal_pengembalian_aktual" class="w-full border px-3 py-2 rounded-md" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Catatan Petugas (Opsional)</label>
                    <textarea name="catatan" rows="3" class="w-full border px-3 py-2 rounded-md"></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeApprovePengembalianModal()" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition">Setujui Request</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Reject Pengembalian --}}
    <div id="rejectPengembalianModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-medium text-gray-900 mb-2 text-center">Tolak Request Pengembalian</h3>
            <div class="mb-4 p-3 bg-gray-50 rounded-md text-sm">
                <p class="text-gray-600">Peminjam: <span id="rejectPengembalianPeminjam" class="font-semibold text-gray-900"></span></p>
                <p class="text-gray-600">Kode: <span id="rejectPengembalianKodeDisplay" class="font-semibold text-gray-900"></span></p>
            </div>
            <form id="rejectPengembalianForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan <span class="text-red-500">*</span></label>
                    <textarea name="alasan_penolakan" rows="4" required placeholder="Jelaskan alasan penolakan..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500"></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeRejectPengembalianModal()" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition">Tolak Request</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal Peminjaman
        function openApproveModal(id, kode, peminjam, buku) {
            document.getElementById('approveModal').classList.remove('hidden');
            document.getElementById('approveKodeDisplay').textContent = kode;
            document.getElementById('approvePeminjam').textContent = peminjam;
            document.getElementById('approveBuku').textContent = buku;
            document.getElementById('approveForm').action = `/peminjaman/${id}/approve`;
        }
        function closeApproveModal() { document.getElementById('approveModal').classList.add('hidden'); }

        function openRejectModal(id, kode, peminjam) {
            document.getElementById('rejectModal').classList.remove('hidden');
            document.getElementById('rejectKodeDisplay').textContent = kode;
            document.getElementById('rejectPeminjam').textContent = peminjam;
            document.getElementById('rejectForm').action = `/peminjaman/${id}/reject`;
        }
        function closeRejectModal() { document.getElementById('rejectModal').classList.add('hidden'); }

        // Modal Pengembalian
        function openApprovePengembalianModal(id, kode, peminjam, buku, tanggal_aktual = '') {
            document.getElementById('approvePengembalianModal').classList.remove('hidden');
            document.getElementById('approvePengembalianKodeDisplay').textContent = kode;
            document.getElementById('approvePengembalianPeminjam').textContent = peminjam;
            document.getElementById('approvePengembalianBuku').textContent = buku;
            document.getElementById('approvePengembalianForm').action = `/peminjaman/${id}/approve-pengembalian`;

            // Set tanggal aktual
            document.querySelector('#approvePengembalianForm input[name="tanggal_pengembalian_aktual"]').value = tanggal_aktual;
        }
        function closeApprovePengembalianModal() { document.getElementById('approvePengembalianModal').classList.add('hidden'); }

        function openRejectPengembalianModal(id, kode, peminjam) {
            document.getElementById('rejectPengembalianModal').classList.remove('hidden');
            document.getElementById('rejectPengembalianKodeDisplay').textContent = kode;
            document.getElementById('rejectPengembalianPeminjam').textContent = peminjam;
            document.getElementById('rejectPengembalianForm').action = `/peminjaman/${id}/reject-pengembalian`;
        }
        function closeRejectPengembalianModal() { document.getElementById('rejectPengembalianModal').classList.add('hidden'); }

        // Close modal saat klik luar
        ['approveModal','rejectModal','approvePengembalianModal','rejectPengembalianModal'].forEach(id => {
            document.getElementById(id)?.addEventListener('click', function(e) {
                if (e.target === this) this.classList.add('hidden');
            });
        });
    </script>
@endif
