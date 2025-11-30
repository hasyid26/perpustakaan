<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    protected $fillable = [
        'kode_peminjaman', 'user_id', 'buku_id', 'tanggal_peminjaman',
        'tanggal_pengembalian_rencana', 'tanggal_pengembalian_aktual',
        'status', 'petugas_pinjam_id', 'petugas_kembali_id', 'denda', 'catatan'
    ];

    protected $casts = [
        'tanggal_peminjaman' => 'date',
        'tanggal_pengembalian_rencana' => 'date',
        'tanggal_pengembalian_aktual' => 'date',
    ];

    /** RELATIONS */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }

    public function petugasPinjam()
    {
        return $this->belongsTo(User::class, 'petugas_pinjam_id');
    }

    public function petugasKembali()
    {
        return $this->belongsTo(User::class, 'petugas_kembali_id');
    }

    /** STATUS HELPERS */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isDipinjam(): bool
    {
        return $this->status === 'dipinjam';
    }

    public function isDitolak(): bool
    {
        return $this->status === 'ditolak';
    }

    public function isDikembalikan(): bool
    {
        return $this->status === 'dikembalikan';
    }

    /** CHECK TERLAMBAT */
    public function isTerlambat(): bool
    {
        if (!$this->isDipinjam()) return false;
        return Carbon::now()->gt($this->tanggal_pengembalian_rencana);
    }

    /** HITUNG DENDA */
    public function hitungDenda(): int
    {
        if (!$this->isDikembalikan() || !$this->tanggal_pengembalian_aktual) {
            return 0;
        }

        $terlambat = $this->tanggal_pengembalian_aktual->diffInDays(
            $this->tanggal_pengembalian_rencana,
            false
        );

        return $terlambat < 0 ? abs($terlambat) * 1000 : 0;
    }

    /** PROSES APPROVE PEMINJAMAN */
    public function approve($petugasId, $lamaPinjamHari = 7)
    {
        $this->update([
            'status' => 'dipinjam',
            'tanggal_peminjaman' => now(),
            'tanggal_pengembalian_rencana' => now()->addDays($lamaPinjamHari),
            'petugas_pinjam_id' => $petugasId,
            'catatan' => null,
        ]);
    }

    /** PROSES TOLAK PEMINJAMAN */
    public function reject($petugasId, $alasan)
    {
        $this->update([
            'status' => 'ditolak',
            'petugas_pinjam_id' => $petugasId,
            'catatan' => $alasan,
        ]);
    }

    /** PROSES PENGEMBALIAN BUKU */
    public function prosesPengembalian($petugasId, $tanggalAktual, $catatan = null)
    {
        $this->update([
            'status' => 'dikembalikan',
            'tanggal_pengembalian_aktual' => $tanggalAktual,
            'petugas_kembali_id' => $petugasId,
            'denda' => $this->hitungDenda(),
            'catatan' => $catatan,
        ]);
    }
}
