<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardAdminController extends Controller
{
    public function index()
    {
        // Data dummy untuk statistik
        $stats = [
            'total_surat' => 45,
            'surat_selesai' => 32,
            'jumlah_guru' => 15,
            'jumlah_tu' => 3,
            'jumlah_kepsek' => 1
        ];

        // Data dummy untuk surat diajukan terakhir
        $suratTerbaru = [
            [
                'pengaju' => 'Maya Sari',
                'nip' => '198501012009012001',
                'telp' => '081234567890',
                'tanggal' => '2024-12-15',
                'jenis' => 'Surat Dispensasi'
            ],
            [
                'pengaju' => 'Andi Pratama',
                'nip' => '198707152010011002',
                'telp' => '081234567891',
                'tanggal' => '2024-12-14',
                'jenis' => 'Surat Panggilan Tugas'
            ],
            [
                'pengaju' => 'Siti Rahayu',
                'nip' => '199002282012012003',
                'telp' => '081234567892',
                'tanggal' => '2024-12-13',
                'jenis' => 'Surat Dispensasi'
            ],
            [
                'pengaju' => 'Budi Santoso',
                'nip' => '198812102008011004',
                'telp' => '081234567893',
                'tanggal' => '2024-12-12',
                'jenis' => 'Surat Panggilan Tugas'
            ],
            [
                'pengaju' => 'Dewi Lestari',
                'nip' => '199105202013012005',
                'telp' => '081234567894',
                'tanggal' => '2024-12-11',
                'jenis' => 'Surat Dispensasi'
            ]
        ];

        // Data dummy untuk surat selesai terakhir
        $suratSelesai = [
            [
                'pengaju' => 'Rahman Hakim',
                'nip' => '198403152007011006',
                'telp' => '081234567895',
                'tanggal' => '2024-12-10',
                'jenis' => 'Surat Dispensasi'
            ],
            [
                'pengaju' => 'Fitri Handayani',
                'nip' => '199208182014012007',
                'telp' => '081234567896',
                'tanggal' => '2024-12-09',
                'jenis' => 'Surat Panggilan Tugas'
            ],
            [
                'pengaju' => 'Agus Wibowo',
                'nip' => '198906252009011008',
                'telp' => '081234567897',
                'tanggal' => '2024-12-08',
                'jenis' => 'Surat Dispensasi'
            ],
            [
                'pengaju' => 'Nina Marlina',
                'nip' => '199304102015012009',
                'telp' => '081234567898',
                'tanggal' => '2024-12-07',
                'jenis' => 'Surat Panggilan Tugas'
            ],
            [
                'pengaju' => 'Hendra Gunawan',
                'nip' => '198511282010011010',
                'telp' => '081234567899',
                'tanggal' => '2024-12-06',
                'jenis' => 'Surat Dispensasi'
            ]
        ];

        // Data dummy untuk chart (per bulan)
        $chartData = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
            'diajukan' => [8, 12, 6, 9, 15, 11, 7, 13, 10, 8, 14, 9],
            'selesai' => [6, 10, 5, 8, 12, 9, 6, 11, 8, 7, 12, 8]
        ];

        return view('admin.dashboard', compact('stats', 'suratTerbaru', 'suratSelesai', 'chartData'));
    }

    public function kelolaGuru()
    {
        // Data dummy guru
        $dataGuru = [
            [
                'id' => 1,
                'nama' => 'Dr. Ahmad Wijaya',
                'nip' => '196505101990031001',
                'role' => 'KEPSEK',
                'telp' => '081234560001',
                'pass' => 'kepsek123'
            ],
            [
                'id' => 2,
                'nama' => 'Maya Sari',
                'nip' => '198501012009012001',
                'role' => 'GURU',
                'telp' => '081234560002',
                'pass' => 'maya1'
                
            ],
            [
                'id' => 3,
                'nama' => 'Siti Aminah',
                'nip' => '197808152005012002',
                'role' => 'TU',
                'telp' => '081234560003',
                'pass' => 'siti1'
            ],
            [
                'id' => 4,
                'nama' => 'Andi Pratama',
                'nip' => '198707152010011002',
                'role' => 'GURU',
                'telp' => '081234560004',
                'pass' => 'andi1'
            ],
            [
                'id' => 5,
                'nama' => 'Siti Rahayu',
                'nip' => '199002282012012003',
                'role' => 'GURU',
                'telp' => '081234560005',
                'pass' => 'siti2'
            ],
            [
                'id' => 6,
                'nama' => 'Budi Santoso',
                'nip' => '198812102008011004',
                'role' => 'GURU',
                'telp' => '081234560006',
                'pass' => 'budi1'
            ],
            [
                'id' => 7,
                'nama' => 'Dewi Lestari',
                'nip' => '199105202013012005',
                'role' => 'GURU',
                'telp' => '081234560007',
                'pass' => 'dewi1'
            ],
            [
                'id' => 8,
                'nama' => 'Rahman Hakim',
                'nip' => '198403152007011006',
                'role' => 'GURU',
                'telp' => '081234560008',
                'pass' => 'rahman1'
            ]
        ];

        return view('admin.kelola-guru', compact('dataGuru'));
    }

    public function kelolaSurat()
    {
        // Data dummy template surat
        $templateSurat = [
            [
                'id' => 1,
                'jenis' => 'Surat Dispensasi',
                'deskripsi' => 'Template untuk surat dispensasi siswa'
            ],
            [
                'id' => 2,
                'jenis' => 'Surat Panggilan Tugas',
                'deskripsi' => 'Template untuk surat panggilan tugas guru'
            ]
        ];

        return view('admin.kelola-surat', compact('templateSurat'));
    }

    public function historySurat()
    {
        // Data dummy history surat dengan paginasi
        $historySurat = collect([
            [
                'id' => 1,
                'pengaju' => 'Maya Sari (198501012009012001)',
                'jenis' => 'Surat Dispensasi',
                'status' => 'Selesai',
                'tanggal' => '2024-12-15'
            ],
            [
                'id' => 2,
                'pengaju' => 'Andi Pratama (198707152010011002)',
                'jenis' => 'Surat Panggilan Tugas',
                'status' => 'Diproses',
                'tanggal' => '2024-12-14'
            ],
            [
                'id' => 3,
                'pengaju' => 'Siti Rahayu (199002282012012003)',
                'jenis' => 'Surat Dispensasi',
                'status' => 'Selesai',
                'tanggal' => '2024-12-13'
            ],
            [
                'id' => 4,
                'pengaju' => 'Budi Santoso (198812102008011004)',
                'jenis' => 'Surat Panggilan Tugas',
                'status' => 'Diajukan',
                'tanggal' => '2024-12-12'
            ],
            [
                'id' => 5,
                'pengaju' => 'Dewi Lestari (199105202013012005)',
                'jenis' => 'Surat Dispensasi',
                'status' => 'Selesai',
                'tanggal' => '2024-12-11'
            ],
            [
                'id' => 6,
                'pengaju' => 'Rahman Hakim (198403152007011006)',
                'jenis' => 'Surat Dispensasi',
                'status' => 'Selesai',
                'tanggal' => '2024-12-10'
            ],
            [
                'id' => 7,
                'pengaju' => 'Fitri Handayani (199208182014012007)',
                'jenis' => 'Surat Panggilan Tugas',
                'status' => 'Selesai',
                'tanggal' => '2024-12-09'
            ],
            [
                'id' => 8,
                'pengaju' => 'Agus Wibowo (198906252009011008)',
                'jenis' => 'Surat Dispensasi',
                'status' => 'Selesai',
                'tanggal' => '2024-12-08'
            ],
            [
                'id' => 9,
                'pengaju' => 'Nina Marlina (199304102015012009)',
                'jenis' => 'Surat Panggilan Tugas',
                'status' => 'Diproses',
                'tanggal' => '2024-12-07'
            ],
            [
                'id' => 10,
                'pengaju' => 'Hendra Gunawan (198511282010011010)',
                'jenis' => 'Surat Dispensasi',
                'status' => 'Selesai',
                'tanggal' => '2024-12-06'
            ],
            [
                'id' => 11,
                'pengaju' => 'Lisa Permata (199501052016012011)',
                'jenis' => 'Surat Panggilan Tugas',
                'status' => 'Diajukan',
                'tanggal' => '2024-12-05'
            ],
            [
                'id' => 12,
                'pengaju' => 'Rizki Fadilah (198709122009011012)',
                'jenis' => 'Surat Dispensasi',
                'status' => 'Selesai',
                'tanggal' => '2024-12-04'
            ]
        ]);

        // Simulasi paginasi - ambil 10 item pertama
        $currentPage = request()->get('page', 1);
        $perPage = 10;
        $total = $historySurat->count();
        $historySuratPaginated = $historySurat->forPage($currentPage, $perPage);
        
        $pagination = [
            'current_page' => $currentPage,
            'per_page' => $perPage,
            'total' => $total,
            'last_page' => ceil($total / $perPage)
        ];

        return view('admin.history-surat', compact('historySuratPaginated', 'pagination'));
    }

    public function viewTemplate($id)
    {
        $templates = [
            1 => [
                'jenis' => 'Surat Dispensasi',
                'content' => 'SURAT DISPENSASI

Kepada Yth.
Bapak/Ibu Guru
Di tempat

Dengan hormat,
Kami memberitahukan bahwa siswa/siswi berikut:
- Nama: [NAMA_SISWA]
- Kelas: [KELAS]
- NIS: [NIS]

Tidak dapat mengikuti kegiatan pembelajaran pada:
- Hari/Tanggal: [TANGGAL]
- Jam: [JAM]
- Mata Pelajaran: [MAPEL]

Dengan alasan: [ALASAN]

Demikian surat dispensasi ini dibuat untuk dipergunakan sebagaimana mestinya.

Hormat kami,

[NAMA_GURU]
NIP. [NIP_GURU]
Telp: [TELP_GURU]'
            ],
            2 => [
                'jenis' => 'Surat Panggilan Tugas',
                'content' => 'SURAT PANGGILAN TUGAS

Nomor: [NOMOR_SURAT]
Hal: Panggilan Tugas

Kepada Yth.
Bapak/Ibu [NAMA_GURU]
NIP. [NIP_GURU]
Di tempat

Dengan hormat,
Sehubungan dengan kegiatan [NAMA_KEGIATAN], maka dengan ini kami panggil Bapak/Ibu untuk:

- Hari/Tanggal: [TANGGAL]
- Waktu: [WAKTU]
- Tempat: [TEMPAT]
- Acara: [ACARA]

Demikian surat panggilan ini disampaikan, atas perhatian dan kehadiran Bapak/Ibu kami ucapkan terima kasih.

Hormat kami,

[NAMA_PENGIRIM]
NIP. [NIP_PENGIRIM]
Telp: [TELP_PENGIRIM]'
            ]
        ];

        $template = $templates[$id] ?? null;
        
        if (!$template) {
            abort(404);
        }

        return view('admin.view-template', compact('template', 'id'));
    }
}