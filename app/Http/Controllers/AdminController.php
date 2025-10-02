<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengguna;
use App\Models\TemplateSurat;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function usersIndex()
    {
        $users = Pengguna::all();
        return view('admin.users.index', compact('users'));
    }

    public function usersStore(Request $req)
    {
        $req->validate([
            'username' => 'required|unique:pengguna',
            'nama'     => 'required',
            'password' => 'required|min:6',
            'role'     => 'required|in:admin,guru,kepsek,tu,ktu'
        ]);

        Pengguna::create([
            'username' => $req->username,
            'no_telp'  => $req->no_telp,
            'nip'      => $req->nip,
            'nama'     => $req->nama,
            'password' => Hash::make($req->password),
            'role'     => $req->role
        ]);

        return back()->with('success_message', 'User berhasil dibuat');
    }

    public function usersUpdate(Request $req, $id)
    {
        $user = Pengguna::findOrFail($id);

        $data = $req->only(['username','no_telp','nip','nama','role']);
        if($req->filled('password')){
            $data['password'] = Hash::make($req->password);
        }

        $user->update($data);

        return back()->with('success_message','User berhasil diupdate');
    }

    public function usersDelete($id)
    {
        $user = Pengguna::findOrFail($id);
        $user->delete();
        return back()->with('success_message','User berhasil dihapus');
    }

    public function templatesIndex()
    {
        $templates = TemplateSurat::all();
        return view('admin.templates.index', compact('templates'));
    }

    public function templatesStore(Request $req)
    {
        $req->validate([
            'nama' => 'required',
            'slug' => 'required|unique:template_surat',
            'html_content' => 'required'
        ]);

        TemplateSurat::create($req->all());
        return back()->with('success_message', 'Template berhasil dibuat');
    }

    public function templatesUpdate(Request $req, $id)
    {
        $tpl = TemplateSurat::findOrFail($id);
        $tpl->update($req->all());
        return back()->with('success_message', 'Template berhasil diupdate');
    }

    public function templatesDelete($id)
    {
        TemplateSurat::destroy($id);
        return back()->with('success_message', 'Template berhasil dihapus');
    }
}
