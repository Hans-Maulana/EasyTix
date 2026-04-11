<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Performer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PerformerController extends Controller
{
    public function managePerformers()
    {
        $performers = Performer::with('genres')->get();
        return view('admin.manage-performers', compact('performers'));
    }

    public function createPerformer()
    {
        $genres = Genre::all();
        return view('admin.create-performer', compact('genres'));
    }

    public function storePerformer(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'genre_ids' => 'required|array',
            'image' => 'nullable|image|max:2048'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('performers', 'public');
        }

        $performer = Performer::create([
            'name' => $request->name,
            'image' => $imagePath
        ]);

        $performer->genres()->attach($request->genre_ids);

        return redirect()->route('admin.managePerformers')->with('success', 'Performer berhasil ditambahkan!');
    }

    public function editPerformer(Performer $performer)
    {
        $genres = Genre::all();
        $performer->load('genres');
        return view('admin.edit-performer', compact('performer', 'genres'));
    }

    public function updatePerformer(Request $request, Performer $performer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'genre_ids' => 'required|array',
            'image' => 'nullable|image|max:2048'
        ]);

        $data = ['name' => $request->name];
        if ($request->hasFile('image')) {
            if ($performer->image) {
                Storage::disk('public')->delete($performer->image);
            }
            $data['image'] = $request->file('image')->store('performers', 'public');
        }

        $performer->update($data);
        $performer->genres()->sync($request->genre_ids);

        return redirect()->route('admin.managePerformers')->with('success', 'Performer berhasil diperbarui!');
    }

    public function deletePerformer(Performer $performer)
    {
        if ($performer->events()->exists()) {
            return redirect()->route('admin.managePerformers')->with('error', 'Performer tidak dapat dihapus karena sedang digunakan dalam event.');
        }

        if ($performer->image) {
            Storage::disk('public')->delete($performer->image);
        }
        $performer->delete();
        return redirect()->route('admin.managePerformers')->with('success', 'Performer berhasil dihapus!');
    }
}
