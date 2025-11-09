<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surat;
use App\Models\Persetujuan;
use App\Models\Notifikasi;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Events\SuratCreated;
use App\Events\SuratStatusUpdated;
use App\Events\NotifikasiCreated;

class SuratController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        if ($user->role === 'guru') {
            $surat = Surat::with(['persetujuan', 'suratDispensasi', 'suratPerintahTugas'])
                ->where('id_pengguna', $user->id_pengguna)
                ->orderByDesc('dibuat_pada')
                ->get();
        } else {
            $surat = Surat::with(['persetujuan', 'pengguna'])
                ->orderByDesc('dibuat_pada')
                ->get();
        }

        $notifikasi = Notifikasi::where('id_pengguna', $user->id_pengguna)
            ->orderByDesc('dibuat_pada')
            ->get();

        return view('guru.dashboard', compact('surat', 'notifikasi'));
    }

    public function create()
    {
        return view('surat.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'jenis' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $surat = Surat::create([
                'id_pengguna'   => $user->id_pengguna,
                'status_berkas' => 'pending',
                'dibuat_pada'   => now(),
            ]);

            $persetujuan = Persetujuan::create([
                'id_surat'    => $surat->id_surat,
                'id_pengguna' => $user->id_pengguna,
                'disetujui'   => null,
                'timestamp'   => now(),
            ]);

            $surat->update(['id_persetujuan' => $persetujuan->id_persetujuan]);

            $roles = ['TU', 'KEPSEK', 'ADMIN'];
            $targets = Pengguna::whereIn(DB::raw('UPPER(role)'), $roles)->get();

            foreach ($targets as $target) {
                $notif = Notifikasi::create([
                    'id_pengguna' => $target->id_pengguna,
                    'id_surat'    => $surat->id_surat,
                    'pesan'       => "Surat baru dari {$user->nama}.",
                    'status_baca' => 0,
                    'dibuat_pada' => now(),
                ]);

                broadcast(new NotifikasiCreated($notif))->toOthers();
            }

            DB::commit();

            broadcast(new SuratCreated($surat))->toOthers();

            return redirect()
                ->route('dashboard.guru')
                ->with('success', 'Surat berhasil diajukan dan menunggu persetujuan.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Gagal mengajukan surat: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $req, $id)
    {
        $user = Auth::user();

        $req->validate([
            'status_berkas' => 'required|in:pending,approve,decline,selesai',
        ]);

        $surat = Surat::findOrFail($id);

        if (!in_array(strtolower($user->role), ['kepsek', 'tu', 'admin'])) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $surat->update(['status_berkas' => $req->status_berkas]);

        Persetujuan::create([
            'id_surat'    => $surat->id_surat,
            'id_pengguna' => $user->id_pengguna,
            'disetujui'   => $req->status_berkas === 'approve' ? 'ya' : 'tidak',
            'timestamp'   => now(),
        ]);

        $notif = Notifikasi::create([
            'id_pengguna' => $surat->id_pengguna,
            'id_surat'    => $surat->id_surat,
            'pesan'       => "Surat Anda telah diperbarui statusnya menjadi {$req->status_berkas}.",
            'status_baca' => 0,
            'dibuat_pada' => now(),
        ]);

        broadcast(new NotifikasiCreated($notif))->toOthers();
        broadcast(new SuratStatusUpdated($surat))->toOthers();

        return response()->json([
            'success' => true,
            'message' => 'Status surat berhasil diperbarui.',
            'new_status' => $req->status_berkas
        ]);
    }
}
