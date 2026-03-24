<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function manageBanners()
    {
        $banners = Banner::all();
        return view('admin.manage-banners', compact('banners'));
    }

    public function createBanner()
    {
        $events = Event::where('status', 'active')->get();
        return view('admin.create-banner', compact('events'));
    }

    public function storeBanner(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'status' => 'required'
        ]);

        try {
            $path = $request->file('image')->store('banners', 'public');

            // Link otomatis diarahkan ke halaman beli tiket untuk event tersebut
            $link = route('user.dashboard'); // Nantinya bisa diganti ke detail event: route('events.show', $request->event_id)


            Banner::create([
                'title' => $request->title,
                'image' => $path,
                'link' => $link,
                'status' => $request->status,
                'type' => $request->type,
            ]);

            return redirect()->route('admin.manageBanners')->with('success', 'Banner berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function editBanner(Banner $banner)
    {
        $events = Event::where('status', 'Approved')->get();
        return view('admin.edit-banner', compact('banner', 'events'));
    }

    public function updateBanner(Request $request, Banner $banner)
    {
        $request->validate([
            'title' => 'required',
            'status' => 'required'
        ]);

        try {
            $data = $request->only(['title', 'link', 'status', 'type']);

            if($request->hasFile('image')) {
                // Delete old image from Storage
                if ($banner->image && Storage::disk('public')->exists($banner->image)) {
                    Storage::disk('public')->delete($banner->image);
                }
                
                $path = $request->file('image')->store('banners', 'public');
                $data['image'] = $path;
            }

            $banner->update($data);
            return redirect()->route('admin.manageBanners')->with('success', 'Banner diperbarui!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function deleteBanner(Banner $banner)
    {
        try {
            if ($banner->image && Storage::disk('public')->exists($banner->image)) {
                Storage::disk('public')->delete($banner->image);
            }
            $banner->delete();
            return redirect()->route('admin.manageBanners')->with('success', 'Banner dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.manageBanners')->with('error', 'Gagal!');
        }
    }
}
