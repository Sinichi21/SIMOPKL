<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::all();
        return view('admin.document.index', compact('documents'));
    }

    public function awardeeIndex()
    {
        $documents = Document::all();
        return view('document.index', compact('documents'));
    }

    public function create()
    {
        return view('admin.document.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,xlsx,xls|max:10240',
        ]);

        $file = $request->file('file');
        $slugName = Str::slug($request->name, '_');
        $fileName = $slugName . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('doc', $fileName);

        Document::create([
            'name' => $request->name,
            'description' => $request->description,
            'file_path' => $filePath,
            'uploaded_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Dokumen berhasil ditambahkan'
        ], 201);
    }

    public function edit($id)
    {
        $document = Document::findOrFail($id);
        return view('admin.document.edit', compact('document'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,xlsx,xls|max:10240',
        ]);

        $document = Document::findOrFail($id);
        $slugName = Str::slug($request->name, '_');

        if ($request->hasFile('file')) {
            Storage::delete($document->file_path);

            $file = $request->file('file');
            $fileName = $slugName . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('doc', $fileName);
        } else {
            $filePath = $document->file_path;
        }

        $document->update([
            'name' => $request->name,
            'description' => $request->description,
            'file_path' => $filePath,
            'uploaded_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Dokumen berhasil diperbarui'
        ], 200);
    }

    public function delete(Request $request)
    {
        $document = Document::findOrFail($request->id);

        Storage::delete($document->file_path);

        $document->delete();

        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        $document = Document::findOrFail($id);

        return view('admin.document.show', compact('document'));
    }

    public function awardeeShow($id)
    {
        $document = Document::findOrFail($id);

        return view('document.show', compact('document'));
    }
}
