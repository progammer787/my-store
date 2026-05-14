<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerAdminController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('position')->orderBy('sort_order')->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.form');
    }

    public function store(Request $request)
    {
        $data = $this->validate($request);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('banners', 'public');
        }
        Banner::create($data);
        return redirect()->route('admin.banners.index')->with('success', 'Banner created.');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.form', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $data = $this->validate($request);
        if ($request->hasFile('image')) {
            if ($banner->image) Storage::disk('public')->delete($banner->image);
            $data['image'] = $request->file('image')->store('banners', 'public');
        }
        $banner->update($data);
        return redirect()->route('admin.banners.index')->with('success', 'Banner updated.');
    }

    public function destroy(Banner $banner)
    {
        if ($banner->image) Storage::disk('public')->delete($banner->image);
        $banner->delete();
        return back()->with('success', 'Banner deleted.');
    }

    public function reorder(Request $request)
    {
        foreach ($request->order as $id => $sort) {
            Banner::where('id', $id)->update(['sort_order' => $sort]);
        }
        return response()->json(['success' => true]);
    }

    private function validate(Request $request): array
    {
        return $request->validate([
            'title'       => 'nullable|string|max:200',
            'subtitle'    => 'nullable|string|max:300',
            'button_text' => 'nullable|string|max:50',
            'button_link' => 'nullable|string|max:500',
            'image'       => 'nullable|image|max:4096',
            'badge_text'  => 'nullable|string|max:30',
            'position'    => 'required|in:hero,promo_left,promo_right,mid_banner',
            'text_color'  => 'nullable|string|max:20',
            'bg_color'    => 'nullable|string|max:20',
            'sort_order'  => 'integer|min:0',
            'is_active'   => 'boolean',
        ]);
    }
}
