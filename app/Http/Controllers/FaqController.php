<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::all();

        return view('admin.faq.index')->with('faqs', $faqs);
    }

    public function create()
    {
        return view('admin.faq.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => ['required', 'string', 'min:1', 'max:255'],
            'answer' => ['required', 'string']
        ]);

        Faq::create([
            'question' => $request->question,
            'answer' => $request->answer
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'FAQ berhasil disimpan'
        ], 201);
    }

    public function upload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            $path = $file->store('faq-medias', 'public');
            $url = asset('storage/' . $path);

            return response()->json([
                'uploaded' => true,
                'url' => $url
            ]);
        }

        return response()->json(['success' => false], 400);
    }

    public function deleteUploaded(Request $request)
    {
        $filePath = $request->src;

        if (Storage::exists($filePath)) {
            Storage::delete($filePath);
            return response()->json(['deleted' => true]);
        }

        return response()->json(['deleted' => false], 404);
    }

    public function show(Faq $faq)
    {
        return view('admin.faq.show')->with('faq', $faq);
    }

    public function edit(Faq $faq)
    {
        return view('admin.faq.edit')->with('faq', $faq);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => ['required', 'numeric', 'exists:faqs,id'],
            'question' => ['required', 'string', 'min:1', 'max:255'],
            'answer' => ['required', 'string']
        ]);

        $faq = Faq::findOrFail($request->id);

        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->save();

        return response()->json([
            'success' => true,
            'msg' => 'FAQ berhasil diedit'
        ], 201);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => ['required', 'numeric', 'exists:faqs,id']
        ]);

        Faq::destroy($request->id);

        return response()->json([
            'success' => true,
            'msg' => 'FAQ berhasil dihapus'
        ]);
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => ['required', 'numeric', 'exists:faqs,id']
        ]);

        $faq = Faq::findOrFail($request->id);

        $currStatus = $faq->status;

        if ($currStatus == 'publish') {
            $faq->status = 'unpublish';
        } else {
            $faq->status = 'publish';
        }

        $faq->save();

        return response()->json([
            'success' => true,
            'msg' => 'Status berhasil diupdate'
        ]);
    }

    public function landingPageIndex(Request $request)
    {
        $search = $request->search;

        if ($search) {
            $faqs = Faq::where('status', 'publish')
                ->where(function ($query) use ($search) {
                    $query->where('question', 'like', "%{$search}%")
                        ->orWhere('answer', 'like', "%{$search}%");
                })
                ->paginate(3)
                ->appends(['query' => $search]);


            return view('landing-page.faq.index')->with('faqs', $faqs);
        }

        $faqs = Faq::where('status', 'publish')->paginate(3);

        return view('landing-page.faq.index')->with('faqs', $faqs);
    }

    public function landingPageShow(Faq $faq)
    {
        return view('landing-page.faq.show')->with('faq', $faq);
    }
}
