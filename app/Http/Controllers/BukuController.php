<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $query = Buku::with('kategori');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('penulis', 'like', "%{$search}%")
                  ->orWhere('kode_buku', 'like', "%{$search}%");
            });
        }

        if ($request->has('kategori_id') && $request->kategori_id != '') {
            $query->where('kategori_id', $request->kategori_id);
        }

        $bukus = $query->latest()->paginate(10);
        $kategoris = Kategori::all();

        return view('buku.index', compact('bukus', 'kategoris'));
    }

    public function koleksi(Request $request)
{
    $query = Buku::with('kategori');

    if ($request->has('search')) {
        $search = $request->search;
        $query->where('judul', 'like', "%{$search}%")
              ->orWhere('penulis', 'like', "%{$search}%")
              ->orWhere('kode_buku', 'like', "%{$search}%");
    }

    if ($request->has('kategori_id') && $request->kategori_id != '') {
        $query->where('kategori_id', $request->kategori_id);
    }

    $bukus = $query->latest()->paginate(10); // pagination aman untuk links()
    $kategoris = Kategori::all();

    return view('buku.koleksi-buku', compact('bukus', 'kategoris'));
}


    public function create()
    {
        $kategoris = Kategori::all();
        return view('buku.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_buku' => 'required|unique:bukus',
            'judul' => 'required',
            'penulis' => 'required',
            'penerbit' => 'required',
            'tahun_terbit' => 'required|numeric',
            'kategori_id' => 'required|exists:kategoris,id',
            'jumlah_total' => 'required|numeric|min:1',
            'deskripsi' => 'nullable',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $validated['jumlah_tersedia'] = $validated['jumlah_total'];

        if ($request->hasFile('cover')) {
            $validated['cover'] = $request->file('cover')->store('covers', 'public');
        }

        $buku = Buku::create($validated);

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Menambah Buku',
            'deskripsi' => "Menambah buku: {$buku->judul}"
        ]);

        return redirect()->route('buku.index')
            ->with('success', 'Buku berhasil ditambahkan');
    }

    public function edit(Buku $buku)
    {
        $kategoris = Kategori::all();
        return view('buku.edit', compact('buku', 'kategoris'));
    }

    public function update(Request $request, Buku $buku)
    {
        $validated = $request->validate([
            'kode_buku' => 'required|unique:bukus,kode_buku,' . $buku->id,
            'judul' => 'required',
            'penulis' => 'required',
            'penerbit' => 'required',
            'tahun_terbit' => 'required|numeric',
            'kategori_id' => 'required|exists:kategoris,id',
            'jumlah_total' => 'required|numeric|min:1',
            'deskripsi' => 'nullable',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('cover')) {
            if ($buku->cover) {
                Storage::disk('public')->delete($buku->cover);
            }
            $validated['cover'] = $request->file('cover')->store('covers', 'public');
        }

        $buku->update($validated);

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Mengupdate Buku',
            'deskripsi' => "Mengupdate buku: {$buku->judul}"
        ]);

        return redirect()->route('buku.index')
            ->with('success', 'Buku berhasil diupdate');
    }

    public function destroy(Buku $buku)
    {
        if ($buku->cover) {
            Storage::disk('public')->delete($buku->cover);
        }

        $judul = $buku->judul;
        $buku->delete();

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Menghapus Buku',
            'deskripsi' => "Menghapus buku: {$judul}"
        ]);

        return redirect()->route('buku.index')
            ->with('success', 'Buku berhasil dihapus');
    }
}