<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Genre;

class GenreController extends Controller
{
    public function manageGenres()
    {
        $genres = Genre::all();
        return view('admin.manage-genres', compact('genres'));
    }

    public function createGenre()
    {
        return view('admin.create-genre');
    }

    public function storeGenre(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:genres,name|max:255',
        ]);

        try {
            Genre::create($request->all());
            return redirect()->route('admin.manageGenres')->with('success', 'Genre berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan genre: ' . $e->getMessage());
        }
    }

    public function editGenre(Genre $genre)
    {
        return view('admin.edit-genre', compact('genre'));
    }

    public function updateGenre(Request $request, Genre $genre)
    {
        $request->validate([
            'name' => 'required|unique:genres,name,' . $genre->id . '|max:255',
        ]);

        try {
            $genre->update($request->all());
            return redirect()->route('admin.manageGenres')->with('success', 'Genre berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui genre: ' . $e->getMessage());
        }
    }

    public function deleteGenre(Genre $genre)
    {
        try {
            // Detach from performers instead of events
            $genre->performers()->detach();
            $genre->delete();
            return redirect()->route('admin.manageGenres')->with('success', 'Genre berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.manageGenres')->with('error', 'Gagal menghapus genre: ' . $e->getMessage());
        }
    }
}

