<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengguna;
use App\Models\Surat;
use App\Models\TemplateSurat;
use App\Models\Guru;
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

        $user = Pengguna::create([
            'username' => $req->nip,
            'no_telp'  => $req->no_telp,
            'nip'      => $req->nip,
            'nama'     => $req->nama,
            'password' => Hash::make($req->password),
            'role'     => $req->role,
        ]);

        // If role is GURU, ensure a guru record exists for recommendations and link to pengguna
        if (strtoupper($req->role) === 'GURU') {
            try {
                Guru::updateOrCreate(
                    ['nip' => $req->nip],
                    ['nama' => $req->nama, 'no_telp' => $req->no_telp, 'id_pengguna' => $user->id_pengguna ?? null]
                );
            } catch (\Exception $e) {
                \Log::warning('Failed to create/update guru record: '.$e->getMessage());
            }
        }

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

        // Sync to guru table if role is GURU
        if (strtoupper($data['role'] ?? $user->role) === 'GURU') {
            try {
                Guru::updateOrCreate(
                    ['nip' => $data['nip']],
                    ['nama' => $data['nama'], 'no_telp' => $data['no_telp'], 'id_pengguna' => $user->id_pengguna]
                );
            } catch (\Exception $e) {
                \Log::warning('Failed to sync guru record on user update: '.$e->getMessage());
            }
        }

        return back()->with('success_message','User berhasil diupdate');
    }

    public function usersDelete($id)
    {
        $user = Pengguna::find($id);
        if ($user) {
            // If associated guru record exists, nullify id_pengguna or remove it
            try {
                \App\Models\Guru::where('id_pengguna', $user->id_pengguna)->update(['id_pengguna' => null]);
            } catch (\Exception $e) {
                \Log::warning('Failed to unlink guru record on user delete: '.$e->getMessage());
            }

            Pengguna::destroy($id);
        }
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

        // Compute global counts for status cards, tolerating different status label variants
        $pendingVariants = ['pending', 'diajukan'];
        $approvedVariants = ['approve', 'approved', 'disetujui'];

        $countPending = Surat::whereIn('status_berkas', $pendingVariants)->count();
        $countApproved = Surat::whereIn('status_berkas', $approvedVariants)->count();
        $countSelesai = Surat::where('status_berkas', 'selesai')->count();

        return view('admin.history-surat', compact(
            'historySuratPaginated', 'pagination', 'daftarSurat',
            'countPending', 'countApproved', 'countSelesai'
        ));
    }

    public function exportSurat(\Illuminate\Http\Request $req)
    {
        $exportAll = (bool) $req->input('export_all', false);
        $ids = $req->input('ids', []);

        if ($exportAll) {
            $surats = Surat::with(['pengguna', 'template'])
                ->orderBy('id_surat')
                ->get();

            if ($surats->count() < 5) {
                return response()->json(['message' => 'Terdapat kurang dari 5 surat untuk diekspor.'], 422);
            }
        } else {
            if (!is_array($ids) || count($ids) < 5) {
                return response()->json(['message' => 'Pilih minimal 5 surat untuk diekspor.'], 422);
            }

            $surats = Surat::with(['pengguna', 'template'])
                ->whereIn('id_surat', $ids)
                ->orderBy('id_surat')
                ->get();
        }

        $filename = 'history_surat_export_' . date('Ymd_His') . '.csv';

        $callback = function() use ($surats) {
            $out = fopen('php://output', 'w');
            // Header
            fputcsv($out, ['ID Surat', 'Pengaju', 'NIP Pengaju', 'Jenis Surat', 'Status', 'Tanggal Dibuat', 'Nomor Surat']);

            foreach ($surats as $s) {
                $tgl = $s->dibuat_pada ? \Carbon\Carbon::parse($s->dibuat_pada)->format('Y-m-d H:i:s') : '';
                fputcsv($out, [
                    $s->id_surat,
                    $s->pengguna->nama ?? '',
                    $s->pengguna->nip ?? '',
                    $s->template->nama ?? '',
                    $s->status_berkas ?? '',
                    $tgl,
                    $s->nomor_surat ?? '',
                ]);
            }

            fclose($out);
        };

        return response()->streamDownload($callback, $filename, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function kelolaGuru()
    {
        $users = Pengguna::all();
        return view('admin.kelola-guru', compact('users'));
    }
}
