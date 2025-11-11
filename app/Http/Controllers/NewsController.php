<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    public function indexAdmin()
    {
        $news = News::orderBy('created_at', 'desc')->paginate(6);
        return view('admin.landingPage.berita', compact('news'));
    }

    public function showAdmin($id)
    {
        $news = News::findOrFail($id);

        return view('admin.news.detail', compact('news'));
    }

    public function index(Request $request)
    {
        $query = News::query();

        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $news = $query->paginate(6);

        if ($request->ajax()) {
            return view('news._news-list', compact('news'));
        }

        return view('landing-page.news.index', compact('news'));
    }

    public function show($id)
    {
        $news = News::findOrFail($id);

        $otherNews = News::where('id', '!=', $id)
                     ->latest()
                     ->take(6)
                     ->get();
        return view('landing-page.news.show', compact('news', 'otherNews'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'required|string',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('news_images', 'public');
        }

        News::create([
            'title' => $request->title,
            'image' => $imagePath,
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Berita berhasil disimpan'
        ], 201);
    }

    // public function upload(Request $request)
    // {
    //     if ($request->hasFile('image')) {
    //         $file = $request->file('image');
    //         $path = $file->store('news', 'public');
    //         $url = asset('storage/' . $path);

    //         return response()->json([
    //             'uploaded' => true,
    //             'url' => $url
    //         ]);
    //     }

    //     return response()->json(['success' => false], 400);
    // }

    public function edit($id)
    {
        $news = News::findOrFail($id);
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'required|string',
        ]);

        $news = News::findOrFail($id);

        $imagePath = $news->image;
        if ($request->hasFile('image')) {
            if ($news->image && Storage::disk('public')->exists($news->image)) {
                Storage::disk('public')->delete($news->image);
            }
            $imagePath = $request->file('image')->store('news_images', 'public');
        }

        $news->update([
            'title' => $request->title,
            'image' => $imagePath,
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Berita berhasil diperbarui'
        ], 200);
    }

    // Hapus berita
    public function delete($id)
    {
        $news = News::findOrFail($id);

        // Hapus gambar jika ada
        if ($news->image) {
            Storage::disk('public')->delete($news->image);
        }

        $news->delete();

        return redirect()->route('news.berita')->with('success', 'News deleted successfully.');
    }
}
