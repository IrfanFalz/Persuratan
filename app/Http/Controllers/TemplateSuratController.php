<?php

namespace App\Http\Controllers;

use App\Models\TemplateSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TemplateSuratController extends Controller
{
    public function index()
    {
        $templates = TemplateSurat::all();
        return view('admin.kelola-surat', compact('templates'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string',
            'deskripsi' => 'nullable|string',
            'tipe' => 'required|string',
            'isi_template' => 'required',
            'kop_surat' => 'nullable|image',
        ]);

        // Upload kop surat jika ada
        if ($request->hasFile('kop_surat')) {
            $data['kop_path'] = $request->file('kop_surat')->store('kop', 'public');
        }

        TemplateSurat::create($data);

        return back()->with('success', 'Template berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $template = TemplateSurat::findOrFail($id);
        return view('admin.edit-template', compact('template'));
    }

    public function update(Request $request, $id)
    {
        $template = TemplateSurat::findOrFail($id);

        $data = $request->validate([
            'nama' => 'required|string',
            'deskripsi' => 'nullable|string',
            'tipe' => 'required|string',
            'isi_template' => 'required',
            'kop_surat' => 'nullable|image',
        ]);

        // Jika user upload kop surat baru → replace
        if ($request->hasFile('kop_surat')) {
            // hapus file lama
            if ($template->kop_path && Storage::disk('public')->exists($template->kop_path)) {
                Storage::disk('public')->delete($template->kop_path);
            }

            $data['kop_path'] = $request->file('kop_surat')->store('kop', 'public');
        }

        $template->update($data);

        return back()->with('success', 'Template berhasil diupdate!');
    }

    public function destroy($id)
    {
        $template = TemplateSurat::findOrFail($id);

        // Hapus kop surat juga
        if ($template->kop_path && Storage::disk('public')->exists($template->kop_path)) {
            Storage::disk('public')->delete($template->kop_path);
        }

        $template->delete();

        return back()->with('success', 'Template berhasil dihapus!');
    }

    /***
     * Upload gambar via TinyMCE
     */
    public function uploadImage(Request $request)
    {
        if (!$request->hasFile('file')) {
            return response()->json(['error' => 'No file uploaded'], 400);
        }

        $path = $request->file('file')->store('template_images', 'public');

        return response()->json([
            'location' => asset('storage/' . $path)
        ]);
    }

    public function show($id)
    {
        $template = TemplateSurat::findOrFail($id);
        return view('admin.preview-template', compact('template'));
    }
}
