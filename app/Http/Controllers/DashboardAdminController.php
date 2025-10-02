<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengguna;
use App\Models\Surat;
use App\Models\TemplateSurat;
use Illuminate\Support\Facades\Hash;

class DashboardAdminController extends Controller
{
    public function index()
    {
        $stats = [
            'total_surat'   => Surat::count(),
            'surat_selesai' => Surat::where('status_berkas', 'selesai')->count(),
            'jumlah_guru'   => Pengguna::where('role', 'GURU')->count(),
            'jumlah_tu'     => Pengguna::where('role', 'TU')->count(),
            'jumlah_kepsek' => Pengguna::where('role', 'KEPSEK')->count(),
        ];

        $suratTerbaru = Surat::with('pengguna')->orderBy('dibuat_pada','desc')->take(5)->get();
        $suratSelesai = Surat::with('pengguna')->where('status_berkas','selesai')->orderBy('dibuat_pada','desc')->take(5)->get();

        $chartData = [
            'labels'   => ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'],
            'diajukan' => [],
            'selesai'  => [],
        ];

        for ($i = 1; $i <= 12; $i++) {
            $chartData['diajukan'][] = Surat::whereMonth('dibuat_pada', $i)->count();
            $chartData['selesai'][]  = Surat::whereMonth('dibuat_pada', $i)->where('status_berkas','selesai')->count();
        }

        return view('admin.dashboard', compact('stats','suratTerbaru','suratSelesai','chartData'));
    }

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
            'role'     => 'required|in:ADMIN,GURU,KEPSEK,TU,KTU'
        ]);

        Pengguna::create([
            'username' => $req->username,
            'no_telp'  => $req->no_telp,
            'nip'      => $req->nip,
            'nama'     => $req->nama,
            'password' => Hash::make($req->password),
            'role'     => $req->role
        ]);

        return back()->with('success_message','User berhasil dibuat');
    }

    public function usersUpdate(Request $req, $id)
    {
        $user = Pengguna::findOrFail($id);

        $data = $req->only(['username','no_telp','nip','nama','role']);
        if ($req->filled('password')) {
            $data['password'] = Hash::make($req->password);
        }

        $user->update($data);

        return back()->with('success_message','User berhasil diupdate');
    }

    public function usersDelete($id)
    {
        Pengguna::destroy($id);
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

        TemplateSurat::create($req->only(['nama','slug','html_content']));

        return back()->with('success_message','Template berhasil dibuat');
    }

    public function templatesUpdate(Request $req, $id)
    {
        $tpl = TemplateSurat::findOrFail($id);
        $tpl->update($req->only(['nama','slug','html_content']));

        return back()->with('success_message','Template berhasil diupdate');
    }

    public function templatesDelete($id)
    {
        TemplateSurat::destroy($id);
        return back()->with('success_message','Template berhasil dihapus');
    }

    public function viewTemplate($id)
    {
        $template = TemplateSurat::findOrFail($id);
        return view('admin.view-template', compact('template'));
    }

    public function historySurat()
    {
        $historySurat = Surat::with('pengguna')->orderBy('dibuat_pada','desc')->paginate(10);
        return view('admin.history-surat', compact('historySurat'));
    }
    public function kelolaGuru()
    {
        $dataGuru = Pengguna::whereIn('role', ['GURU','KEPSEK','TU'])->get();
        return view('admin.kelola-guru', compact('dataGuru'));
    }
    public function kelolaSurat()
    {
        $daftarSurat = Surat::with('pengguna')->orderBy('dibuat_pada','desc')->get();
        return view('admin.kelola-surat', compact('daftarSurat'));
    }
}
