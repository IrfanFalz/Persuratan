<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FormSuratController extends Controller
{
    public function index(Request $request)
    {
        $letter_types = [
            'surat-keterangan' => 'Surat Keterangan',
            'surat-izin' => 'Surat Izin',
            'surat-tugas' => 'Surat Tugas',
            'surat-perintah-tugas' => 'Surat Perintah Tugas',
        ];

        $letter_type = $request->query('type', 'surat-keterangan');

        return view('form-surat', [
            'letter_types' => $letter_types,
            'letter_type' => $letter_type,
            'success_message' => session('success_message')
        ]);
    }

    public function submit(Request $request)
    {
        return redirect()->route('form.surat', ['type' => $request->input('letter_type')])
                         ->with('success_message', 'Permintaan surat berhasil diajukan dan akan diproses oleh KTU.');
    }
}
