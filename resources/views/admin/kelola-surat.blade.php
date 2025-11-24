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

<!-- Title + Button -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-4 sm:mb-0">Template Surat</h1>

    <button onclick="openModal('addModal')"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Tambah Template
    </button>
</div>

<!-- Table -->
<div class="bg-white rounded-lg shadow-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full table-auto">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Template</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe Surat</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($templates as $template)
                <tr class="hover:bg-gray-50">

                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $template->nama }}</div>
                    </td>

                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-500">{{ $template->deskripsi }}</div>
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ $template->tipe }}
                        </span>
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="flex justify-center space-x-2">

                            <!-- PREVIEW (eye) -->
                            <button onclick="previewTemplateById({{ $template->id }})"
                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm inline-flex items-center justify-center"
                                title="Preview">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>

                            <!-- EDIT -->
                            <button onclick='editTemplate(@json($template))'
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">
                                Edit
                            </button>

                            <!-- DELETE -->
                            <form action="{{ route('templates.destroy', $template->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                    Delete
                                </button>
                            </form>

                        </div>
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</div>

<!-- ============================= -->
<!--          ADD MODAL           -->
<!-- ============================= -->
<div id="addModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 overflow-y-auto h-full w-full">
    <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">

        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">Tambah Template Surat</h3>
            <button onclick="closeModal('addModal')" class="text-gray-400 hover:text-gray-600">✕</button>
        </div>

        <form method="POST" action="{{ route('templates.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <label class="block text-sm">Nama Template</label>
            <input type="text" name="nama" class="w-full px-3 py-2 border rounded">

            <label class="block text-sm">Deskripsi</label>
            <textarea name="deskripsi" rows="3" class="w-full px-3 py-2 border rounded"></textarea>

            <label class="block text-sm">Tipe Surat</label>
            <input type="text" name="tipe" class="w-full px-3 py-2 border rounded">

            <label class="block text-sm">Kop Surat</label>
            <input type="file" name="kop_path" class="w-full">

            <label class="block text-sm">Isi Template (HTML)</label>
            <textarea name="isi_template" rows="10" class="w-full px-3 py-2 border rounded font-mono text-sm"></textarea>

            <div class="flex justify-end space-x-3">
                <button onclick="closeModal('addModal')" type="button" class="px-4 py-2">Batal</button>
                <button class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
            </div>
        </form>

    </div>
</div>

<!-- ============================= -->
<!--          EDIT MODAL          -->
<!-- ============================= -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 overflow-y-auto h-full w-full">
    <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">

        <div class="flex justify-between mb-4">
            <h3 class="text-lg font-medium">Edit Template Surat</h3>
            <button onclick="closeModal('editModal')" class="text-gray-400">✕</button>
        </div>

        <form id="editForm" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <label class="block text-sm">Nama Template</label>
            <input type="text" id="editNama" name="nama" class="w-full px-3 py-2 border rounded">

            <label class="block text-sm">Deskripsi</label>
            <textarea id="editDeskripsi" name="deskripsi" rows="3" class="w-full px-3 py-2 border rounded"></textarea>

            <label class="block text-sm">Tipe Surat</label>
            <input type="text" id="editTipe" name="tipe" class="w-full px-3 py-2 border rounded">

            <label class="block text-sm">Kop Surat</label>
            <input type="file" name="kop_path" class="w-full">

            <img id="editKopPreview" class="mt-2 w-32 hidden border rounded">

            <label class="block text-sm">Isi Template</label>
            <textarea id="editIsi" name="isi_template" rows="10" class="w-full px-3 py-2 border rounded font-mono text-sm"></textarea>

            <div class="flex justify-end space-x-3">
                <button onclick="closeModal('editModal')" type="button" class="px-4 py-2">Batal</button>
                <button class="px-4 py-2 bg-blue-600 text-white rounded">Update</button>
            </div>
        </form>

    </div>
</div>

<!-- ============================= -->
<!--        PREVIEW MODAL         -->
<!-- ============================= -->
<div id="previewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-4xl bg-white rounded-md shadow-lg">

        <div class="flex justify-between mb-4">
            <h3 class="text-lg font-medium">Preview Template</h3>
            <button onclick="closeModal('previewModal')" class="text-gray-400">✕</button>
        </div>

        <div id="previewContent" class="border rounded-lg p-6 max-h-96 overflow-y-auto bg-white"></div>

        <div class="flex justify-end mt-4">
            <button onclick="closeModal('previewModal')" class="px-4 py-2 bg-gray-600 text-white rounded">
                Tutup
            </button>
        </div>

    </div>
</div>


<!-- ============================= -->
<!--       JAVASCRIPT SECTION     -->
<!-- ============================= -->

<script>
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
}
function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
}

// ================= PREVIEW ==================
function previewTemplate(template) {
    let kop = template.kop_path
        ? `<img src="/storage/${template.kop_path}" style="width:100%; height: auto; margin-bottom:20px;">`
        : '';

    let html = `
        <div style="text-align:center;">${kop}</div>
        <div class="prose max-w-none">${template.isi_template}</div>
    `;

    document.getElementById('previewContent').innerHTML = html;
    openModal('previewModal');
}

// Fetch preview via server (useful when not embedding full template JSON in page)
function previewTemplateById(id) {
    const previewBase = "{{ url('/admin/template-surat') }}";
    const url = previewBase + '/' + id + '/preview';

    fetch(url, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => {
        if (!res.ok) throw new Error('Failed to fetch template preview');
        return res.json();
    })
    .then(template => {
        // reuse existing renderer
        previewTemplate(template);
    })
    .catch(err => {
        console.error(err);
        alert('Gagal memuat preview template.');
    });
}

// ================= EDIT ==================
function editTemplate(template) {
    // use the correct route base: /admin/template-surat/{id}
    document.getElementById('editForm').action = "/admin/template-surat/" + template.id;

    document.getElementById('editNama').value = template.nama;
    document.getElementById('editDeskripsi').value = template.deskripsi;
    document.getElementById('editTipe').value = template.tipe;
    document.getElementById('editIsi').value = template.isi_template;

    if (template.kop_path) {
        document.getElementById('editKopPreview').src = "/storage/" + template.kop_path;
        document.getElementById('editKopPreview').classList.remove('hidden');
    }

    openModal('editModal');
}
</script>


<!-- ============================= -->
<!--          TINYMCE             -->
<!-- ============================= -->

<script src="https://cdn.tiny.cloud/1/el473fs8jwf59wr358d4a09pnx9mbpg0ql7iid90ig9p9vwk/tinymce/6/tinymce.min.js"></script>

<script>
tinymce.init({
    selector: 'textarea[name=isi_template], #editIsi',
    height: 350,
    plugins: 'image link table code lists media',
    toolbar:
        'undo redo | bold italic underline | alignleft aligncenter alignright | ' +
        'bullist numlist | image media link table | code',

    images_upload_url: '{{ route("templates.upload-image") }}',
    automatic_uploads: true,

    images_upload_handler: function (blobInfo, success, failure) {
        let xhr = new XMLHttpRequest();
        xhr.open('POST', '{{ route("templates.upload-image") }}');
        xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

        xhr.onload = function () {
            if (xhr.status !== 200) {
                failure('HTTP Error: ' + xhr.status);
                return;
            }
            let json = JSON.parse(xhr.responseText);
            success(json.location);
        };

        let formData = new FormData();
        formData.append('file', blobInfo.blob());
        xhr.send(formData);
    }
});
</script>

@endsection
