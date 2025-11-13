<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Models\SuratDispensasi;
use App\Models\SuratPerintahTugas;
use App\Models\Notifikasi;
use App\Models\Pengguna;
use App\Helpers\NotifikasiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardTuController extends Controller
{
    public function index()
    {
        // SURAT YANG SUDAH DISETUJUI KEPSEK, TAPI BELUM SELESAI (status: disetujui)
        $approved_letters = Surat::with(['pengguna', 'suratDispensasi', 'suratPerintahTugas'])
            ->where('status_berkas', 'disetujui')
            ->latest()
            ->get()
            ->map(function ($surat) {
                $detail = $surat->suratDispensasi ?? $surat->suratPerintahTugas;

                return [
                    'id' => $surat->id_surat,
                    'teacher' => $surat->pengguna->nama,
                    'nip' => $surat->pengguna->nip,
                    'phone' => $surat->pengguna->no_telp,
                    'full_name' => $surat->pengguna->nama,
                    'type' => $surat->suratDispensasi ? 'Surat Dispensasi' : 'Surat Perintah Tugas',
                    'approved_date' => $surat->updated_at->format('Y-m-d'),
                    'status' => 'approved',
                    'keperluan' => $detail->keperluan ?? '-',
                    'tempat' => $detail->tempat ?? '-',
                    'tanggal_tugas' => $detail->tanggal ?? '-',
                    'hari' => $detail->hari ?? '-',
                    'jam' => $detail->jam ?? '-',
                    'waktu' => $detail->jam ?? '-',
                    'guru_list' => $this->getGuruList($surat),
                ];
            });

        // SURAT YANG SUDAH SELESAI (status: selesai)
        $completed_letters = Surat::with(['pengguna', 'suratDispensasi', 'suratPerintahTugas'])
            ->where('status_berkas', 'selesai')
            ->latest()
            ->get()
            ->map(function ($surat) {
                $detail = $surat->suratDispensasi ?? $surat->suratPerintahTugas;

                return [
                    'id' => $surat->id_surat,
                    'teacher' => $surat->pengguna->nama,
                    'nip' => $surat->pengguna->nip,
                    'phone' => $surat->pengguna->no_telp,
                    'full_name' => $surat->pengguna->nama,
                    'type' => $surat->suratDispensasi ? 'Surat Dispensasi' : 'Surat Perintah Tugas',
                    'completed_date' => $surat->updated_at->format('Y-m-d H:i:s'),
                    'status' => 'completed',
                    'pickup_notified' => true, // Otomatis true karena sudah diberitahu saat selesai
                    'keperluan' => $detail->keperluan ?? '-',
                    'tempat' => $detail->tempat ?? '-',
                    'tanggal_tugas' => $detail->tanggal ?? '-',
                    'hari' => $detail->hari ?? '-',
                    'jam' => $detail->jam ?? '-',
                    'guru_list' => $this->getGuruList($surat),
                ];
            });

        $letters_indexed = $approved_letters->keyBy('id')->toArray();
        $completed_letters_indexed = $completed_letters->keyBy('id')->toArray();

        return view('dashboard-tu', compact(
            'approved_letters',
            'completed_letters',
            'letters_indexed',
            'completed_letters_indexed'
        ))->with('message', session('message'));
    }

    // Helper function untuk ambil list guru
    private function getGuruList($surat)
    {
        $list = [];

        if ($surat->suratPerintahTugas) {
            $details = $surat->suratPerintahTugas->detailSpt ?? [];
            foreach ($details as $detail) {
                $list[] = [
                    'nama' => $detail->nama_guru,
                    'nip' => $detail->nip,
                    'keterangan' => $detail->keterangan ?? '-',
                ];
            }
        } else {
            // Untuk dispensasi, tampilkan guru yang mengajukan
            $list[] = [
                'nama' => $surat->pengguna->nama,
                'nip' => $surat->pengguna->nip,
                'keterangan' => 'Yang mengajukan',
            ];
        }

        return $list;
    }

    // Proses surat (selesaikan surat)
    public function process(Request $request)
    {
        $action = $request->input('action');
        $letterId = $request->input('letter_id');

        $surat = Surat::with('pengguna')->findOrFail($letterId);
        $teacher = $surat->pengguna->nama;

        if ($action === 'complete') {
            // Update status jadi selesai
            $surat->update(['status_berkas' => 'selesai']);

            // Kirim notifikasi otomatis ke guru
            NotifikasiHelper::insert(
                $surat->id_surat,
                $surat->id_pengguna,
                "Surat Anda sudah selesai dan siap diambil di TU",
                null
            );

            $message = "Surat {$teacher} berhasil diselesaikan dan siap diambil. Notifikasi otomatis sudah dikirim ke guru yang bersangkutan.";
        } else {
            $message = "Aksi tidak dikenali untuk surat {$teacher}.";
        }

        return redirect()->route('dashboard.tu')->with('message', $message);
    }

    // Kirim ulang notifikasi
    public function resendNotification(Request $request)
    {
        $letterId = $request->input('letter_id');

        $surat = Surat::with('pengguna')->findOrFail($letterId);
        $teacher = $surat->pengguna->nama;

        // Kirim notifikasi ulang
        NotifikasiHelper::insert(
            $surat->id_surat,
            $surat->id_pengguna,
            "Reminder: Surat Anda sudah selesai dan menunggu pengambilan di TU",
            null
        );

        $message = "Notifikasi berhasil dikirim ulang ke {$teacher}. Reminder pengambilan surat sudah terkirim.";

        return redirect()->route('dashboard.tu')->with('message', $message);
    }

    // Get detail surat (untuk modal/preview)
    public function getLetterDetail($id)
    {
        $surat = Surat::with(['pengguna', 'suratDispensasi.detailDispensasi', 'suratPerintahTugas.detailSpt'])
            ->findOrFail($id);

        $detail = $surat->suratDispensasi ?? $surat->suratPerintahTugas;

        $data = [
            'id' => $surat->id_surat,
            'teacher' => $surat->pengguna->nama,
            'nip' => $surat->pengguna->nip,
            'phone' => $surat->pengguna->no_telp,
            'full_name' => $surat->pengguna->nama,
            'type' => $surat->suratDispensasi ? 'Surat Dispensasi' : 'Surat Perintah Tugas',
            'status' => $surat->status_berkas,
            'keperluan' => $detail->keperluan ?? '-',
            'tempat' => $detail->tempat ?? '-',
            'tanggal_tugas' => $detail->tanggal ?? '-',
            'hari' => $detail->hari ?? '-',
            'jam' => $detail->jam ?? '-',
            'guru_list' => $this->getGuruList($surat),
        ];

        return response()->json($data);
    }
}