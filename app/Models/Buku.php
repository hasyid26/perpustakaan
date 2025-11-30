<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $fillable = [
        'kode_buku', 'judul', 'penulis', 'penerbit', 'tahun_terbit',
        'kategori_id', 'jumlah_tersedia', 'jumlah_total', 'deskripsi', 'cover'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }

    public function isTersedia(): bool
    {
        return $this->jumlah_tersedia > 0;
    }

    public function kurangiStok(): void
    {
        if ($this->jumlah_tersedia > 0) {
            $this->decrement('jumlah_tersedia');
        }
    }

    public function tambahStok(): void
    {
        if ($this->jumlah_tersedia < $this->jumlah_total) {
            $this->increment('jumlah_tersedia');
        }
    }
}