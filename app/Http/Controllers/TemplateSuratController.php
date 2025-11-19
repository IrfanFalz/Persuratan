<?php

namespace App\Http\Controllers;

use App\Models\TemplateSurat;
use Illuminate\Http\Request;

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
            'nama_template' => 'required',
            'deskripsi' => 'required',
            'tipe_surat' => 'required',
            'isi_template' => 'required',
            'kop_surat' => 'nullable|image',
        ]);

        if ($request->hasFile('kop_surat')) {
            $data['kop_surat'] = $request->file('kop_surat')->store('kop', 'public');
        }

        TemplateSurat::create($data);

        return back()->with('success', 'Template berhasil ditambahkan!');
    }

    public function update(Request $request, TemplateSurat $template)
    {
        $data = $request->validate([
            'nama_template' => 'required',
            'deskripsi' => 'required',
            'tipe_surat' => 'required',
            'isi_template' => 'required',
            'kop_surat' => 'nullable|image',
        ]);

        if ($request->hasFile('kop_surat')) {
            $data['kop_surat'] = $request->file('kop_surat')->store('kop', 'public');
        }

        $template->update($data);

        return back()->with('success', 'Template berhasil diupdate!');
    }

    public function destroy(TemplateSurat $template)
    {
        $template->delete();
        return back()->with('success', 'Template berhasil dihapus!');
    }

    public function preview(TemplateSurat $template)
    {
        return view('admin.preview-template', compact('template'));
    }
}

