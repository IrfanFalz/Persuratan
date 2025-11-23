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
        $today = now()->toDateString();

        $stats = [
            'total_surat'   => Surat::count(),
            'surat_selesai' => Surat::where('status_berkas', 'selesai')->count(),
            'jumlah_guru'   => Pengguna::where('role', 'GURU')->count(),
            'jumlah_tu'     => Pengguna::where('role', 'TU')->count(),
            'jumlah_kepsek' => Pengguna::where('role', 'KEPSEK')->count(),
        ];

        $suratTerbaru = Surat::with(['pengguna', 'template'])
            ->orderBy('dibuat_pada', 'desc')
            ->take(5)
            ->get();

        $suratSelesai = Surat::with(['pengguna', 'template'])
            ->where('status_berkas', 'selesai')
            ->orderBy('dibuat_pada', 'desc')
            ->take(5)
            ->get();

        $chartData = [
            'labels'   => ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'],
            'diajukan' => [],
            'selesai'  => [],
        ];

        for ($i = 1; $i <= 12; $i++) {
            $chartData['diajukan'][] = Surat::whereMonth('dibuat_pada', $i)->count();
            $chartData['selesai'][]  = Surat::whereMonth('dibuat_pada', $i)
                ->where('status_berkas', 'selesai')
                ->count();
        }

        $ringkasan = [
            'baru'         => Surat::whereDate('dibuat_pada', $today)->count(),
            'menunggu'     => Surat::where('status_berkas', 'pending')->whereDate('dibuat_pada', $today)->count(),
            'selesai_hari' => Surat::where('status_berkas', 'selesai')->whereDate('dibuat_pada', $today)->count(),
        ];

        return view('admin.dashboard', compact(
            'stats',
            'suratTerbaru',
            'suratSelesai',
            'chartData',
            'ringkasan'
        ));
    }

    public function usersIndex()
    {
        $users = Pengguna::all();
        return view('admin.users.index', compact('users'));
    }

    public function usersStore(Request $req)
    {
        $req->validate([
            'nama' => 'required',
            'nip'  => 'required|unique:pengguna,nip',
            'password' => 'required|min:6',
            'role' => 'required|in:ADMIN,GURU,KEPSEK,TU,KTU',
            'no_telp' => 'nullable|string|max:20',
        ]);

        Pengguna::create([
            'username' => $req->nip,
            'no_telp'  => $req->no_telp,
            'nip'      => $req->nip,
            'nama'     => $req->nama,
            'password' => Hash::make($req->password),
            'role'     => $req->role,
        ]);

        return redirect()->route('admin.kelola-guru')
            ->with('success_message', 'Data guru berhasil ditambahkan!');
    }

    public function usersUpdate(Request $req, $id)
    {
        $user = Pengguna::findOrFail($id);

        $data = $req->only(['username','no_telp','nip','nama','role']);
        $data['username'] = $req->nip;

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

    // ================================
    // TEMPLATE SURAT (CRUD)
    // ================================
    public function kelolaSurat()
    {
        $templates = TemplateSurat::orderBy('id','desc')->get();

        // TIDAK mengirim $daftarSurat karena view tidak butuh
        return view('admin.kelola-surat', compact('templates'));
    }

    public function templatesStore(Request $req)
    {
        $req->validate([
            'nama'         => 'required',
            'deskripsi'    => 'required',
            'tipe'         => 'required',
            'isi_template' => 'required',
            'kop_path'     => 'nullable|mimes:jpg,jpeg,png,svg,pdf|max:5120',
        ]);

        $data = $req->only(['nama','deskripsi','tipe','isi_template']);

        if ($req->hasFile('kop_path')) {
            $data['kop_path'] =
                $req->file('kop_path')->store('kop', 'public');
        }

        TemplateSurat::create($data);

        return back()->with('success','Template berhasil ditambahkan!');
    }

    public function templatesUpdate(Request $req, $id)
    {
        $template = TemplateSurat::findOrFail($id);

        $req->validate([
            'nama'         => 'required',
            'deskripsi'    => 'required',
            'tipe'         => 'required',
            'isi_template' => 'required',
            // allow common image/document formats for kop surat
            'kop_path'     => 'nullable|mimes:jpg,jpeg,png,svg,pdf|max:5120',
        ]);

        $data = $req->only(['nama','deskripsi','tipe','isi_template']);

        if ($req->hasFile('kop_path')) {
            $data['kop_path'] =
                $req->file('kop_path')->store('kop','public');
        }

        $template->update($data);

        return back()->with('success','Template berhasil diupdate!');
    }

    public function templatesDelete($id)
    {
        TemplateSurat::destroy($id);
        return back()->with('success','Template berhasil dihapus!');
    }

    public function viewTemplate($id)
    {
        $template = TemplateSurat::findOrFail($id);
        return response()->json($template);
    }

    public function historySurat()
    {
        $historySuratPaginated = Surat::with(['pengguna', 'template'])
            ->orderBy('dibuat_pada', 'desc')
            ->paginate(10);

        $pagination = [
            'current_page' => $historySuratPaginated->currentPage(),
            'last_page'    => $historySuratPaginated->lastPage(),
            'per_page'     => $historySuratPaginated->perPage(),
            'total'        => $historySuratPaginated->total(),
        ];

        $daftarSurat = Surat::with(['pengguna', 'template'])
            ->orderBy('id_surat', 'DESC')
            ->get();

        return view('admin.history-surat', compact('historySuratPaginated', 'pagination', 'daftarSurat'));
    }

    public function kelolaGuru()
    {
        $users = Pengguna::all();
        return view('admin.kelola-guru', compact('users'));
    }
}
