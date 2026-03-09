<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Aset</title>

    <!-- Bootstrap 3 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap.min.css">

    <style>
        body { background: #f5f6fa; font-family: Arial, sans-serif; }
        .panel { margin-top: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .panel-heading { background: #1F4E79 !important; color: #fff !important; border-radius: 8px 8px 0 0; font-weight: bold; font-size: 15px; }
        .btn-upload-area { margin-bottom: 12px; }
        .label-success { background-color: #27ae60; }
        .label-danger  { background-color: #e74c3c; }
        .label-warning { background-color: #f39c12; }
        .label-default { background-color: #95a5a6; }
        th { background: #1F4E79 !important; color: #fff !important; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="panel panel-default">
        <div class="panel-heading">
            <span class="glyphicon glyphicon-list-alt"></span> Data Aset
        </div>
        <div class="panel-body">

            <div class="btn-upload-area text-right">
                <button id="downloadFormatAset" class="btn btn-info btn-sm">
                    <span class="glyphicon glyphicon-download-alt"></span> Download Format Upload
                </button>
                <button id="uploadAset" class="btn btn-success btn-sm">
                    <span class="glyphicon glyphicon-upload"></span> Upload Aset
                </button>
            </div>

            <div class="table-responsive">
                <table id="tabelAset" class="table table-bordered table-striped table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kd Aset</th>
                            <th>No Aset</th>
                            <th>Nama Aset</th>
                            <th>Kategori</th>
                            <th>Tgl Peroleh</th>
                            <th>Harga Peroleh</th>
                            <th>Umur Pakai</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                            <th>Petugas</th>
                        </tr>
                    </thead>
                    <tbody id="bodyTabelAset">
                        <tr>
                            <td colspan="12" class="text-center">
                                <i>Memuat data...</i>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 3 JS -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap.min.js"></script>

<script>
$(document).ready(function () {

    var table = null;

    loadDataAset();

    function loadDataAset() {
        if (table !== null) {
            table.destroy();
            table = null;
        }

        $('#bodyTabelAset').html('');

        table = $('#tabelAset').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json',
                emptyTable: 'Belum ada data aset.'
            },
            order: [[0, 'asc']],
            ajax: {
                url: './function/get_data_aset.php',
                type: 'GET',
                dataSrc: function(json) {
                    return json.data || [];
                },
                error: function() {
                    $('#bodyTabelAset').html('<tr><td colspan="12" class="text-center text-danger">Gagal memuat data.</td></tr>');
                }
            },
            columns: [
                { data: 'no' },
                { data: 'kd_aset' },
                { data: 'no_aset' },
                { data: 'nama' },
                { data: 'kategori' },
                { data: 'tgl_peroleh' },
                { data: 'harga_peroleh' },
                { data: 'umur_pakai' },
                { data: 'lokasi' },
                { data: 'status' },
                { data: 'keterangan' },
                { data: 'petugas' }
            ]
        });
    }

    $('#downloadFormatAset').on('click', function () {
        Swal.fire({
            title: 'Konfirmasi Unduh',
            text: "Apakah Anda yakin ingin mengunduh format aset?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Unduh',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#17a2b8'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('./function/dwn_format_aset.php')
                    .then(response => response.blob())
                    .then(blob => {
                        const url = window.URL.createObjectURL(blob);
                        const a   = document.createElement('a');
                        a.href     = url;
                        a.download = 'Format Upload Aset.xlsx';
                        document.body.appendChild(a);
                        a.click();
                        a.remove();
                        window.URL.revokeObjectURL(url);
                    })
                    .catch(() => {
                        Swal.fire('Error!', 'Terjadi kesalahan saat mengunduh file.', 'error');
                    });
            }
        });
    });

    $('#uploadAset').on('click', function () {
        Swal.fire({
            title: '<h3>Upload Data Aset</h3>',
            html: `
                <table>
                <tr>
                    <td>
                        <input type="file" id="fileInputAset" accept=".xls,.xlsx"
                            style="padding:5px;border:solid 1px grey;border-radius:5px;width:450px;">
                    </td>
                </tr>
                </table>
            `,
            showCancelButton: true,
            confirmButtonText: 'Upload',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#28a745',
            preConfirm: () => {
                const fileInput = Swal.getPopup().querySelector('#fileInputAset');
                const file      = fileInput.files[0];
                if (!file) {
                    Swal.showValidationMessage('File belum dimasukkan');
                    return false;
                }
                const ext = file.name.split('.').pop().toLowerCase();
                if (!['xls', 'xlsx'].includes(ext)) {
                    Swal.showValidationMessage('Hanya file Excel (.xls, .xlsx) yang diperbolehkan');
                    return false;
                }
                return file;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('file', result.value);

                Swal.fire({
                    title: 'Uploading...',
                    text: 'Silakan tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                fetch('./function/upload_aset.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => {
                    if (!res.ok) throw new Error('Respon jaringan tidak bagus.');
                    return res.text();
                })
                .then(data => {
                    if (data.startsWith('error')) {
                        Swal.fire('Gagal!', data.replace('error: ', ''), 'error');
                    } else {
                        Swal.fire('Berhasil!', data.replace('success: ', ''), 'success')
                            .then(() => loadDataAset());
                    }
                })
                .catch(err => {
                    Swal.fire('Error!', 'Terjadi kesalahan: ' + err.message, 'error');
                });
            }
        });
    });

});
</script>

</body>
</html>