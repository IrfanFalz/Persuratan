@extends('admin.layout')

@section('title', 'Preview Template - ' . $template['jenis'])
@section('page-title', 'Preview Template')

@section('content')
@php
// Data dummy template yang akan di-preview
$templates = [
    'dispensasi' => [
        'id' => 1,
        'nama_template' => 'Surat Dispensasi',
        'deskripsi' => 'Template surat dispensasi untuk siswa yang tidak dapat mengikuti kegiatan sekolah',
        'tipe_surat' => 'Dispensasi',
        'isi_template' => '<div class="mb-6"><p class="text-center font-bold text-lg">SURAT DISPENSASI</p><p class="text-center">Nomor: ................../SMKN4/2024</p></div><div class="mb-6"><p class="mb-3">Dengan ini menyatakan bahwa:</p><table class="w-full"><tr><td class="py-1 w-32">Nama</td><td class="py-1">: <span class="bg-yellow-100 px-1">{{nama_siswa}}</span></td></tr><tr><td class="py-1">NISN</td><td class="py-1">: <span class="bg-yellow-100 px-1">{{nisn}}</span></td></tr><tr><td class="py-1">Kelas</td><td class="py-1">: <span class="bg-yellow-100 px-1">{{kelas}}</span></td></tr><tr><td class="py-1">Jurusan</td><td class="py-1">: <span class="bg-yellow-100 px-1">{{jurusan}}</span></td></tr></table></div><div class="mb-6"><p>Diberikan dispensasi untuk <span class="bg-yellow-100 px-1">{{alasan}}</span> pada tanggal <span class="bg-yellow-100 px-1">{{tanggal}}</span>.</p></div><div class="mb-6"><p>Demikian surat dispensasi ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p></div><div class="mt-12"><div class="flex justify-end"><div class="text-center"><p>Malang, <span class="bg-yellow-100 px-1">{{tanggal_surat}}</span></p><p class="mt-16"><span class="bg-yellow-100 px-1">{{nama_kepala_sekolah}}</span><br>Kepala Sekolah</p></div></div></div>'
    ],
    'perintah_tugas' => [
        'id' => 2,
        'nama_template' => 'Surat Perintah Tugas',
        'deskripsi' => 'Template surat perintah tugas untuk guru dan staf sekolah',
        'tipe_surat' => 'Perintah Tugas',
        'isi_template' => '<div class="mb-6"><p class="text-center font-bold text-lg">SURAT PERINTAH TUGAS</p><p class="text-center">Nomor: ................../SMKN4/2024</p></div><div class="mb-6"><p class="mb-3">Kepala SMK Negeri 4 Malang dengan ini menugaskan:</p><table class="w-full"><tr><td class="py-1 w-32">Nama</td><td class="py-1">: <span class="bg-yellow-100 px-1">{{nama_guru}}</span></td></tr><tr><td class="py-1">NIP</td><td class="py-1">: <span class="bg-yellow-100 px-1">{{nip}}</span></td></tr><tr><td class="py-1">Jabatan</td><td class="py-1">: <span class="bg-yellow-100 px-1">{{jabatan}}</span></td></tr><tr><td class="py-1">Unit Kerja</td><td class="py-1">: <span class="bg-yellow-100 px-1">{{unit_kerja}}</span></td></tr></table></div><div class="mb-6"><p class="mb-2">Untuk melaksanakan tugas <span class="bg-yellow-100 px-1">{{jenis_tugas}}</span> pada:</p><div class="ml-4"><p>Hari/Tanggal: <span class="bg-yellow-100 px-1">{{tanggal_tugas}}</span></p><p>Tempat: <span class="bg-yellow-100 px-1">{{tempat_tugas}}</span></p><p>Waktu: <span class="bg-yellow-100 px-1">{{waktu_tugas}}</span></p></div></div><div class="mb-6"><p>Demikian surat perintah tugas ini dibuat untuk dilaksanakan dengan penuh tanggung jawab.</p></div><div class="mt-12"><div class="flex justify-end"><div class="text-center"><p>Malang, <span class="bg-yellow-100 px-1">{{tanggal_surat}}</span></p><p class="mt-16"><span class="bg-yellow-100 px-1">{{nama_kepala_sekolah}}</span><br>Kepala Sekolah</p></div></div></div>'
    ],
    'keterangan' => [
        'id' => 3,
        'nama_template' => 'Surat Keterangan',
        'deskripsi' => 'Template surat keterangan untuk keperluan siswa atau guru',
        'tipe_surat' => 'Keterangan',
        'isi_template' => '<div class="mb-6"><p class="text-center font-bold text-lg">SURAT KETERANGAN</p><p class="text-center">Nomor: ................../SMKN4/2024</p></div><div class="mb-6"><p class="mb-3">Yang bertanda tangan di bawah ini, Kepala SMK Negeri 4 Malang menerangkan bahwa:</p><table class="w-full"><tr><td class="py-1 w-40">Nama</td><td class="py-1">: <span class="bg-yellow-100 px-1">{{nama}}</span></td></tr><tr><td class="py-1">Tempat/Tgl Lahir</td><td class="py-1">: <span class="bg-yellow-100 px-1">{{tempat_lahir}}</span>, <span class="bg-yellow-100 px-1">{{tanggal_lahir}}</span></td></tr><tr><td class="py-1">Jabatan/Status</td><td class="py-1">: <span class="bg-yellow-100 px-1">{{status}}</span></td></tr><tr><td class="py-1">Alamat</td><td class="py-1">: <span class="bg-yellow-100 px-1">{{alamat}}</span></td></tr></table></div><div class="mb-6"><p><span class="bg-yellow-100 px-1">{{keterangan_isi}}</span></p></div><div class="mb-6"><p>Demikian surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p></div><div class="mt-12"><div class="flex justify-end"><div class="text-center"><p>Malang, <span class="bg-yellow-100 px-1">{{tanggal_surat}}</span></p><p class="mt-16"><span class="bg-yellow-100 px-1">{{nama_kepala_sekolah}}</span><br>Kepala Sekolah</p></div></div></div>'
    ]
];

// Ambil template berdasarkan parameter (default: dispensasi)
$selectedTemplate = request('template', 'dispensasi');
$currentTemplate = $templates[$selectedTemplate] ?? $templates['dispensasi'];
@endphp

<div class="container mx-auto px-4 py-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Preview Template</h1>
            <p class="text-gray-600 mt-1">{{ $currentTemplate['nama_template'] }}</p>
        </div>
        <div class="flex flex-wrap gap-2 mt-4 sm:mt-0">
            <button onclick="printTemplate()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Print
            </button>
            <button onclick="openVariableModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Lihat Variabel
            </button>
            <button onclick="history.back()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </button>
        </div>
    </div>

    <!-- Template Selection -->
    <div class="mb-6">
        <div class="flex flex-wrap gap-2">
            <button onclick="changeTemplate('dispensasi')" class="px-4 py-2 rounded-lg {{ $selectedTemplate == 'dispensasi' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} transition-colors">
                Surat Dispensasi
            </button>
            <button onclick="changeTemplate('perintah_tugas')" class="px-4 py-2 rounded-lg {{ $selectedTemplate == 'perintah_tugas' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} transition-colors">
                Surat Perintah Tugas
            </button>
            <button onclick="changeTemplate('keterangan')" class="px-4 py-2 rounded-lg {{ $selectedTemplate == 'keterangan' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} transition-colors">
                Surat Keterangan
            </button>
        </div>
    </div>

    <!-- Template Preview -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="p-8" id="templateContent">
            <!-- Kop Surat -->
            <div class="border-b-4 border-gray-800 pb-6 mb-8">
                <div class="flex items-start space-x-6">
                    <div class="flex-shrink-0">
                        <img src="/images/logo grf.png" alt="Logo SMK Negeri 4 Malang" class="w-20 h-20 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <div class="w-20 h-20 bg-gray-300 rounded flex items-center justify-center text-gray-600 text-xs" style="display: none;">
                            LOGO
                        </div>
                    </div>
                    <div class="flex-1 text-center">
                        <h1 class="text-2xl font-bold text-gray-800 mb-1">PEMERINTAH PROVINSI JAWA TIMUR</h1>
                        <h2 class="text-xl font-bold text-gray-800 mb-2">SMK NEGERI 4 MALANG</h2>
                        <div class="text-sm text-gray-700 leading-tight">
                            <p>Bidang Keahlian: Teknologi Informasi dan Komunikasi</p>
                            <p>Jl. Tanimbar No. 22 Malang 65117</p>
                            <p>Telp. (0341) 353798 | Fax. (0341) 368418</p>
                            <p>Email: smkn4mlg@gmail.com | Website: www.smkn4malang.sch.id</p>
                        </div>
                    </div>
                    <div class="w-20"></div> <!-- Spacer untuk balance -->
                </div>
            </div>

            <!-- Isi Template -->
            <div class="prose max-w-none text-gray-800 leading-relaxed">
                {!! $currentTemplate['isi_template'] !!}
            </div>
        </div>
    </div>

    <!-- Template Info -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <h3 class="font-semibold text-blue-800 mb-2">Informasi Template</h3>
        <div class="text-sm text-blue-700">
            <p><strong>Nama:</strong> {{ $currentTemplate['nama_template'] }}</p>
            <p><strong>Tipe:</strong> {{ $currentTemplate['tipe_surat'] }}</p>
            <p><strong>Deskripsi:</strong> {{ $currentTemplate['deskripsi'] }}</p>
        </div>
    </div>
</div>

<!-- Modal Daftar Variabel -->
<div id="variableModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">Daftar Variabel Template</h3>
            <button onclick="closeModal('variableModal')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div class="space-y-4">
            <p class="text-sm text-gray-600">Variabel yang tersedia untuk template <strong id="currentTemplateName">{{ $currentTemplate['nama_template'] }}</strong>:</p>
            
            <div class="bg-gray-50 rounded-lg p-4">
                <div id="variableList" class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                    <!-- Variabel akan dimuat di sini -->
                </div>
            </div>
            
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                <p class="text-sm text-yellow-800">
                    <strong>Catatan:</strong> Variabel ditandai dengan warna kuning pada preview. Saat membuat surat, variabel ini akan diganti dengan data sebenarnya.
                </p>
            </div>
        </div>
        
        <div class="flex justify-end pt-4">
            <button onclick="closeModal('variableModal')" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
// Data variabel untuk setiap template
const templateVariables = {
    'dispensasi': [
        '{{nama_siswa}}', '{{nisn}}', '{{kelas}}', '{{jurusan}}', 
        '{{alasan}}', '{{tanggal}}', '{{tanggal_surat}}', '{{nama_kepala_sekolah}}'
    ],
    'perintah_tugas': [
        '{{nama_guru}}', '{{nip}}', '{{jabatan}}', '{{unit_kerja}}', 
        '{{jenis_tugas}}', '{{tanggal_tugas}}', '{{tempat_tugas}}', 
        '{{waktu_tugas}}', '{{tanggal_surat}}', '{{nama_kepala_sekolah}}'
    ],
    'keterangan': [
        '{{nama}}', '{{tempat_lahir}}', '{{tanggal_lahir}}', '{{status}}', 
        '{{alamat}}', '{{keterangan_isi}}', '{{tanggal_surat}}', '{{nama_kepala_sekolah}}'
    ]
};

function changeTemplate(template) {
    window.location.href = `?template=${template}`;
}

function printTemplate() {
    const printContent = document.getElementById('templateContent').innerHTML;
    const printWindow = window.open('', '', 'height=600,width=800');
    
    printWindow.document.write(`
        <html>
            <head>
                <title>Print Template</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    .bg-yellow-100 { background-color: transparent !important; }
                    @media print {
                        body { margin: 0; }
                        .bg-yellow-100 { background-color: transparent !important; }
                    }
                </style>
            </head>
            <body>
                ${printContent}
            </body>
        </html>
    `);
    
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
}

function openVariableModal() {
    const currentTemplate = '{{ $selectedTemplate }}';
    const variables = templateVariables[currentTemplate] || [];
    
    document.getElementById('currentTemplateName').textContent = '{{ $currentTemplate["nama_template"] }}';
    
    const variableList = document.getElementById('variableList');
    variableList.innerHTML = variables.map(variable => 
        `<div class="bg-white px-3 py-2 rounded border">
            <code class="text-blue-600">${variable}</code>
        </div>`
    ).join('');
    
    openModal('variableModal');
}

function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('bg-gray-600')) {
        const modals = ['variableModal'];
        modals.forEach(modalId => {
            if (!document.getElementById(modalId).classList.contains('hidden')) {
                closeModal(modalId);
            }
        });
    }
}

// Responsive handling untuk mobile
function adjustForMobile() {
    const isMobile = window.innerWidth < 768;
    const templateContent = document.getElementById('templateContent');
    
    if (isMobile) {
        templateContent.style.fontSize = '14px';
    } else {
        templateContent.style.fontSize = '16px';
    }
}

window.addEventListener('resize', adjustForMobile);
window.addEventListener('load', adjustForMobile);
</script>

@endsection