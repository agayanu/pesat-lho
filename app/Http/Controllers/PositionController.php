<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $positions = Position::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%");
        })
        ->orderBy('id', 'desc')
        ->paginate(10)
        ->withQueryString();

        return view('positions.index', compact('positions', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:positions,name',
        ], [
            'name.required' => 'Nama jabatan wajib diisi',
            'name.unique'   => 'Nama jabatan sudah ada',
        ]);

        Position::create([
            'name' => $request->name,
        ]);

        return redirect()->route('positions.index')->with('success', 'Data Jabatan berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $position = Position::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:positions,name,' . $id,
        ], [
            'name.required' => 'Nama jabatan wajib diisi',
            'name.unique'   => 'Nama jabatan sudah ada',
        ]);

        $position->update([
            'name' => $request->name,
        ]);

        return redirect()->route('positions.index')->with('success', 'Data Jabatan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $position = Position::findOrFail($id);
        $position->delete();

        return redirect()->route('positions.index')->with('success', 'Data Jabatan berhasil dihapus');
    }
}
