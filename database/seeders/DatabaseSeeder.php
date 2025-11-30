<?php
// database/seeders/DatabaseSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kategori;
use App\Models\Buku;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Users
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@perpustakaan.com',
            'password' => bcrypt('password'),
            'role' => 'administrator',
            'no_identitas' => 'ADM001',
            'alamat' => 'Jl. Perpustakaan No. 1',
            'no_telepon' => '081234567890'
        ]);

        User::create([
            'name' => 'Petugas Perpustakaan',
            'email' => 'petugas@perpustakaan.com',
            'password' => bcrypt('password'),
            'role' => 'petugas',
            'no_identitas' => 'PTG001',
            'alamat' => 'Jl. Perpustakaan No. 2',
            'no_telepon' => '081234567891'
        ]);

        User::create([
            'name' => 'John Doe',
            'email' => 'peminjam@perpustakaan.com',
            'password' => bcrypt('password'),
            'role' => 'peminjam',
            'no_identitas' => 'PMJ001',
            'alamat' => 'Jl. Peminjam No. 1',
            'no_telepon' => '081234567892'
        ]);

        // Create Kategori
        $kategoris = [
            'Fiksi',
            'Non-Fiksi',
            'Sains',
            'Teknologi',
            'Sejarah',
            'Biografi',
            'Agama',
            'Pendidikan',
            'Komik',
            'Majalah'
        ];

        foreach ($kategoris as $kategori) {
            Kategori::create(['nama_kategori' => $kategori]);
        }

        // Create Sample Books
        $bukus = [
            [
                'kode_buku' => 'BK001',
                'judul' => 'Laskar Pelangi',
                'penulis' => 'Andrea Hirata',
                'penerbit' => 'Bentang Pustaka',
                'tahun_terbit' => 2005,
                'kategori_id' => 1,
                'jumlah_total' => 5,
                'jumlah_tersedia' => 5,
                'deskripsi' => 'Novel tentang kehidupan sepuluh anak dari keluarga miskin yang bersekolah di SD Muhammadiyah.'
            ],
            [
                'kode_buku' => 'BK002',
                'judul' => 'Bumi Manusia',
                'penulis' => 'Pramoedya Ananta Toer',
                'penerbit' => 'Hasta Mitra',
                'tahun_terbit' => 1980,
                'kategori_id' => 1,
                'jumlah_total' => 3,
                'jumlah_tersedia' => 3,
                'deskripsi' => 'Novel pertama dari tetralogi Buru yang menceritakan kisah Minke.'
            ],
            [
                'kode_buku' => 'BK003',
                'judul' => 'Sapiens: A Brief History of Humankind',
                'penulis' => 'Yuval Noah Harari',
                'penerbit' => 'Harper',
                'tahun_terbit' => 2011,
                'kategori_id' => 5,
                'jumlah_total' => 4,
                'jumlah_tersedia' => 4,
                'deskripsi' => 'Buku yang mengeksplorasi sejarah umat manusia dari evolusi hingga modernitas.'
            ],
            [
                'kode_buku' => 'BK004',
                'judul' => 'Clean Code',
                'penulis' => 'Robert C. Martin',
                'penerbit' => 'Prentice Hall',
                'tahun_terbit' => 2008,
                'kategori_id' => 4,
                'jumlah_total' => 6,
                'jumlah_tersedia' => 6,
                'deskripsi' => 'Panduan tentang cara menulis kode yang bersih dan mudah dipelihara.'
            ],
            [
                'kode_buku' => 'BK005',
                'judul' => 'Atomic Habits',
                'penulis' => 'James Clear',
                'penerbit' => 'Avery',
                'tahun_terbit' => 2018,
                'kategori_id' => 8,
                'jumlah_total' => 8,
                'jumlah_tersedia' => 8,
                'deskripsi' => 'Buku tentang cara membangun kebiasaan baik dan menghilangkan kebiasaan buruk.'
            ]
        ];

        foreach ($bukus as $buku) {
            Buku::create($buku);
        }
    }
}

