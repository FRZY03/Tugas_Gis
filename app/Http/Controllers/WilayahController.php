<?php

namespace App\Http\Controllers;

use App\Models\Wilayah;
use Illuminate\Http\Request;
use App\Http\Controllers\WilayahController;

class WilayahController extends Controller
{
    public function index()
    {
        $wilayah = Wilayah::all();
        return view('wilayah.index', compact('wilayah'));
    }

    public function manage()
    {
        $wilayah = Wilayah::all();
        return view('wilayah.manage', compact('wilayah'));
    }

    // Simpan data wilayah baru
    public function store(Request $request)
{
    $validated = $request->validate([
        'nama' => 'required|string|max:255',
        'deskripsi' => 'nullable|string',
        'latitude' => 'required|numeric',
        'longitude' => 'required|numeric',
    ]);

    $wilayah = Wilayah::create($validated);

    // Jika request AJAX, kembalikan data JSON
    if ($request->ajax()) {
        return response()->json($wilayah);
    }

    return redirect()->route('wilayah.index')->with('success', 'Wilayah berhasil ditambahkan');
}

    public function update(Request $request, $id)
    {
        $wilayah = Wilayah::findOrFail($id);
        $wilayah->update($request->all());
        return redirect()->route('wilayah.index')->with('success', 'Wilayah berhasil diupdate');
    }

    public function destroy($id)
    {
        $wilayah = Wilayah::findOrFail($id);
        $wilayah->delete();
        return redirect()->route('wilayah.index')->with('success', 'Wilayah berhasil dihapus');
    }

    public function hapusSemua()
{
    Wilayah::truncate(); // Menghapus semua data
    return response()->json(['message' => 'Semua data wilayah berhasil dihapus']);
}
}