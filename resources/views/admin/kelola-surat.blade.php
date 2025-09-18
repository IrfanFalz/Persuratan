

@extends('admin.layout')

@section('title', 'Kelola Surat')
@section('page-title', 'Kelola Surat')

@section('content')
<div class="container mx-auto px-4 py-6">

<!-- Header Section -->
    <div class="card-shadow rounded-2xl p-6 bg-white mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Kelola Surat</h1>
                <p class="text-gray-600 mt-1">Kelola template surat dan form pengajuan surat</p>
            </div>
        </div>
    </div>
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-4 sm:mb-0">Template Surat</h1>
        <button onclick="openModal('addModal')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Template
        </button>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Template</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe Surat</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                    $templateDispensasi = '
                        <div style="font-family: Times New Roman, serif; line-height: 1.6; font-size: 14px;">
                            <!-- Judul -->
                            <p style="text-align: center; font-weight: bold; text-decoration: underline;">SURAT DISPENSASI</p>
                            <p style="text-align: center;">Nomor : ........</p>

                            <!-- Isi Surat -->
                            <p>Yang bertanda tangan di bawah ini, menerangkan bahwa:</p>
                            <table style="margin-left: 20px;">
                                <tr><td>Nama</td><td>: @{{ nama_siswa }}</td></tr>
                                <tr><td>NISN</td><td>: @{{ nisn }}</td></tr>
                                <tr><td>Kelas</td><td>: @{{ kelas }}</td></tr>
                                <tr><td>Jurusan</td><td>: @{{ jurusan }}</td></tr>
                            </table>

                            <p>Dengan ini diberikan dispensasi untuk @{{ alasan }} pada tanggal @{{ tanggal }}.</p>
                            <p>Demikian surat ini dibuat agar dipergunakan sebagaimana mestinya.</p>

                            <!-- Tanda Tangan -->
                            <div style="text-align: right; margin-top: 40px;">
                                <p>Malang, @{{ tanggal_surat }}</p>
                                <p>Kepala Sekolah</p>
                                <br><br><br>
                                <p><strong>@{{ nama_kepala_sekolah }}</strong></p>
                                <p>NIP. @{{ nip }}</p>
                            </div>
                        </div>
                        ';

                        $templatePerintahTugas = '
                        <div style="font-family: Times New Roman, serif; line-height: 1.6; font-size: 14px;">
                            <!-- Judul -->
                            <p style="text-align: center; font-weight: bold; text-decoration: underline;">SURAT PERINTAH TUGAS</p>
                            <p style="text-align: center;">Nomor : ........</p>

                            <!-- Isi Surat -->
                            <p>Kepala SMK Negeri 4 Malang dengan ini menugaskan:</p>
                            <table style="margin-left: 20px;">
                                <tr><td>Nama</td><td>: @{{ nama_guru }}</td></tr>
                                <tr><td>NIP</td><td>: @{{ nip }}</td></tr>
                                <tr><td>Jabatan</td><td>: @{{ jabatan }}</td></tr>
                                <tr><td>Unit Kerja</td><td>: @{{ unit_kerja }}</td></tr>
                            </table>

                            <p>Untuk melaksanakan tugas @{{ jenis_tugas }} pada:</p>
                            <p>Hari/Tanggal : @{{ tanggal_tugas }}</p>
                            <p>Tempat       : @{{ tempat_tugas }}</p>
                            <p>Waktu        : @{{ waktu_tugas }}</p>

                            <p>Demikian surat perintah tugas ini dibuat untuk dilaksanakan dengan penuh tanggung jawab.</p>

                            <!-- Tanda Tangan -->
                            <div style="text-align: right; margin-top: 40px;">
                                <p>Malang, @{{ tanggal_surat }}</p>
                                <p>Kepala Sekolah</p>
                                <br><br><br>
                                <p><strong>@{{ nama_kepala_sekolah }}</strong></p>
                                <p>NIP. @{{ nip }}</p>
                            </div>
                        </div>
                        ';

                        $templateKeterangan = '
                        <div style="font-family: Times New Roman, serif; line-height: 1.6; font-size: 14px;">
                            <!-- Judul -->
                            <p style="text-align: center; font-weight: bold; text-decoration: underline;">SURAT KETERANGAN</p>
                            <p style="text-align: center;">Nomor : ........</p>

                            <!-- Isi Surat -->
                            <p>Yang bertanda tangan di bawah ini, Kepala SMK Negeri 4 Malang menerangkan bahwa:</p>
                            <table style="margin-left: 20px;">
                                <tr><td>Nama</td><td>: @{{ nama }}</td></tr>
                                <tr><td>Tempat/Tgl Lahir</td><td>: @{{ tempat_lahir }}, @{{ tanggal_lahir }}</td></tr>
                                <tr><td>Jabatan/Status</td><td>: @{{ status }}</td></tr>
                                <tr><td>Alamat</td><td>: @{{ alamat }}</td></tr>
                            </table>

                            <p>@{{ keterangan_isi }}</p>

                            <p>Demikian surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>

                            <!-- Tanda Tangan -->
                            <div style="text-align: right; margin-top: 40px;">
                                <p>Malang, @{{ tanggal_surat }}</p>
                                <p>Kepala Sekolah</p>
                                <br><br><br>
                                <p><strong>@{{ nama_kepala_sekolah }}</strong></p>
                                <p>NIP. @{{ nip }}</p>
                            </div>
                        </div>
                        ';
                    
                    $templates = [
                        [
                            'id' => 1,
                            'nama_template' => 'Surat Dispensasi',
                            'deskripsi' => 'Template surat dispensasi untuk siswa yang tidak dapat mengikuti kegiatan sekolah',
                            'tipe_surat' => 'Dispensasi',
                            'isi_template' => $templateDispensasi
                        ],
                        [
                            'id' => 2,
                            'nama_template' => 'Surat Perintah Tugas',
                            'deskripsi' => 'Template surat perintah tugas untuk guru dan staf sekolah',
                            'tipe_surat' => 'Perintah Tugas',
                            'isi_template' => $templatePerintahTugas
                        ],
                        [
                            'id' => 3,
                            'nama_template' => 'Surat Keterangan',
                            'deskripsi' => 'Template surat keterangan untuk keperluan siswa atau guru',
                            'tipe_surat' => 'Keterangan',
                            'isi_template' => $templateKeterangan
                        ]
                    ];
                    @endphp

                    @foreach($templates as $template)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $template['nama_template'] }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-500">{{ $template['deskripsi'] }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $template['tipe_surat'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex justify-center space-x-2">
                                <button onclick="previewTemplate({{ json_encode($template) }})" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm transition-colors">
                                    Preview
                                </button>
                                <button onclick="editTemplate({{ json_encode($template) }})" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm transition-colors">
                                    Edit
                                </button>
                                <button onclick="deleteTemplate({{ $template['id'] }})" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition-colors">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Template -->
<div id="addModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">Tambah Template Surat</h3>
            <button onclick="closeModal('addModal')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Template</label>
                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan nama template">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan deskripsi template"></textarea>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Surat</label>
                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan tipe surat">
            </div>

            <!-- Tambahan: Upload Kop Surat -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kop Surat</label>
                <input type="file" accept="image/*" class="w-full text-sm text-gray-500
                       file:mr-4 file:py-2 file:px-4
                       file:rounded-md file:border-0
                       file:text-sm file:font-semibold
                       file:bg-blue-50 file:text-blue-700
                       hover:file:bg-blue-100">
                <p class="text-xs text-gray-400 mt-1">Pilih gambar untuk kop surat (jpg, jpeg, png).</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Isi Template (HTML)</label>
                <textarea rows="10" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm" placeholder="<div>
  <p>Contoh template surat dengan placeholder:</p>
  <p>Nama: @{{nama}}</p>
  <p>Tanggal: @{{tanggal}}</p>
</div>"></textarea>
            </div>
            
            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" onclick="closeModal('addModal')" class="px-4 py-2 text-gray-500 hover:text-gray-700 transition-colors">
                    Batal
                </button>
                <button type="button" onclick="saveTemplate()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Template -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">Edit Template Surat</h3>
            <button onclick="closeModal('editModal')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Template</label>
                <input type="text" id="editNama" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea rows="3" id="editDeskripsi" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Surat</label>
                <input type="text" id="editTipe" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Edit Kop Surat -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kop Surat</label>
                <input type="file" id="editKop" accept="image/*" class="w-full text-sm text-gray-500
                       file:mr-4 file:py-2 file:px-4
                       file:rounded-md file:border-0
                       file:text-sm file:font-semibold
                       file:bg-blue-50 file:text-blue-700
                       hover:file:bg-blue-100">
                <p class="text-xs text-gray-400 mt-1">Pilih gambar baru jika ingin mengganti kop surat.</p>
                <img id="editKopPreview" src="" alt="Preview Kop Surat" class="mt-2 w-32 h-auto hidden border rounded">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Isi Template (HTML)</label>
                <textarea rows="15" id="editIsi" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm"></textarea>
            </div>
            
            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" onclick="closeModal('editModal')" class="px-4 py-2 text-gray-500 hover:text-gray-700 transition-colors">
                    Batal
                </button>
                <button type="button" onclick="updateTemplate()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Preview Template -->
<div id="previewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">Preview Template</h3>
            <button onclick="closeModal('previewModal')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div id="previewContent" class="border rounded-lg p-6 bg-white max-h-96 overflow-y-auto">
            <!-- Content will be loaded here -->
        </div>
        
        <div class="flex justify-end pt-4">
            <button onclick="closeModal('previewModal')" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

function previewTemplate(template) {
    const previewContent = document.getElementById('previewContent');
    
    // Header dengan kop surat
    const headerHtml = `
        <div class="border-b-2 border-gray-300 pb-4 mb-6">
            <div class="flex items-start space-x-4">
                <img src="/images/logo grf.png" alt="Logo" class="w-16 h-16 object-contain">
                <div class="flex-1">
                    <h1 class="text-lg font-bold text-gray-800">SMK NEGERI 4 MALANG</h1>
                    <p class="text-sm text-gray-600">Jl. Tanimbar No. 22 Malang 65117</p>
                    <p class="text-sm text-gray-600">Telp. (0341) 353798 | Email: smkn4mlg@gmail.com</p>
                    <p class="text-sm text-gray-600">Website: www.smkn4malang.sch.id</p>
                </div>
            </div>
        </div>
    `;
    
    previewContent.innerHTML = headerHtml + '<div class="prose max-w-none">' + template.isi_template + '</div>';
    openModal('previewModal');
}

function editTemplate(template) {
    document.getElementById('editNama').value = template.nama_template;
    document.getElementById('editDeskripsi').value = template.deskripsi;
    document.getElementById('editTipe').value = template.tipe_surat;
    document.getElementById('editIsi').value = template.isi_template;

    // contoh dummy path kop surat (statis)
    document.getElementById('editKopPreview').src = "/images/logo grf.png";
    document.getElementById('editKopPreview').classList.remove('hidden');

    openModal('editModal');
}


function deleteTemplate(id) {
    if (confirm('Apakah Anda yakin ingin menghapus template ini?')) {
        alert('Template berhasil dihapus! (Demo)');
    }
}

function saveTemplate() {
    alert('Template berhasil disimpan! (Demo)');
    closeModal('addModal');
}

function updateTemplate() {
    alert('Template berhasil diupdate! (Demo)');
    closeModal('editModal');
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('bg-gray-600')) {
        const modals = ['addModal', 'editModal', 'previewModal'];
        modals.forEach(modalId => {
            if (!document.getElementById(modalId).classList.contains('hidden')) {
                closeModal(modalId);
            }
        });
    }
}
</script>
@endsection 