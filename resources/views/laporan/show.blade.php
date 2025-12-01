<x-layouts.app title="Detail Laporan">

    <h3 class="mb-3">Detail Laporan</h3>

    <div class="card shadow-sm">
        <div class="card-body">

            <p><strong>Kode Peminjaman:</strong> {{ $laporan->kode_peminjaman }}</p>
            <p><strong>Nama User:</strong> {{ $laporan->user->name }}</p>
            <p><strong>Buku:</strong> {{ $laporan->buku->judul }}</p>
            <p><strong>Kategori:</strong> {{ $laporan->buku->kategori->nama }}</p>
            <p><strong>Tanggal Peminjaman:</strong> {{ $laporan->tanggal_peminjaman }}</p>
            <p><strong>Tanggal Deadline:</strong> {{ $laporan->tanggal_pengembalian_rencana }}</p>
            <p><strong>Tanggal Kembali:</strong> {{ $laporan->tanggal_pengembalian_aktual ?? '-' }}</p>
            <p><strong>Status:</strong>
                <span class="badge 
                    @if($laporan->status=='dipinjam') bg-primary
                    @elseif($laporan->status=='dikembalikan') bg-success
                    @elseif($laporan->status=='pending') bg-warning
                    @else bg-danger
                    @endif">
                    {{ ucfirst($laporan->status) }}
                </span>
            </p>
            <p><strong>Denda:</strong> Rp {{ number_format($laporan->denda, 0, ',', '.') }}</p>
            <p><strong>Petugas Peminjaman:</strong> {{ $laporan->petugasPinjam->name ?? '-' }}</p>
            <p><strong>Petugas Pengembalian:</strong> {{ $laporan->petugasKembali->name ?? '-' }}</p>
            <p><strong>Catatan:</strong><br>{{ $laporan->catatan ?? '-' }}</p>

        </div>
    </div>

    <a href="{{ route('laporan.index') }}" class="btn btn-secondary mt-3">Kembali</a>

</x-layouts.app>
