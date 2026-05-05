<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    public function index()
    {
        $ruangans = Ruangan::all();
        return view('admin.ruangan.index', compact('ruangans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_ruangan' => 'required|string|max:255',
            'kapasitas' => 'nullable|integer',
            'deskripsi' => 'nullable|string',
        ]);

        Ruangan::create($request->all());

        return back()->with('success', 'Ruangan berhasil ditambahkan.');
    }

    public function update(Request $request, Ruangan $ruangan)
    {
        $request->validate([
            'nama_ruangan' => 'required|string|max:255',
            'kapasitas' => 'nullable|integer',
            'deskripsi' => 'nullable|string',
        ]);

        $ruangan->update($request->all());

        return back()->with('success', 'Data ruangan berhasil diperbarui.');
    }

    public function destroy(Ruangan $ruangan)
    {
        $ruangan->delete();
        return back()->with('success', 'Ruangan berhasil dihapus.');
    }
}
