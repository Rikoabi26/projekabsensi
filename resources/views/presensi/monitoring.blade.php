@extends('layouts.admin.tabler')

<style>
    .clickable-gambar {
        cursor: pointer;
        transition: transform 0.2s;
    }

    .clickable-gambar:hover {
        transform: scale(1.1);
    }
    #gambar-besar {
        max-width: 100%; /* Batas lebar sesuai ukuran modal */
        max-height: 80vh; /* Batas tinggi sesuai dengan viewport */
        object-fit: contain; /* Gambar akan disesuaikan dengan proporsional */
        margin: auto; /* Pusatkan gambar secara vertikal dan horizontal */
    }
</style>
@section('content')
    <div class="page-header d-print-none">
        <div class="container-fluid">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Monitoring Presensi
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">

                                    @if (Session::get('success'))
                                        <div class="alert alert-success">
                                            {{ Session::get('success') }}
                                        </div>
                                    @endif

                                    @if (Session::get('warning'))
                                        <div class="alert alert-warning">
                                            {{ Session::get('warning') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <div class="input-icon mb-3">
                                        <input type="date" id="tanggal" name="tanggal" value="{{ date('Y-m-d') }}"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <select name="kode_cabang" class="form-select" id="kode_cabang" required>
                                        <option value="">Cabang</option>
                                        @foreach ($cabang as $d)
                                            <option value="{{ $d->kode_cabang }}">{{ strtoupper($d->nama_cabang) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Email</th>
                                                <th>Nama LEngkap</th>
                                                <th>Dept</th>
                                                <th>Jadwal</th>
                                                <th>Jam Masuk</th>
                                                <th>Foto</th>
                                                <th>Jam Pulang</th>
                                                <th>Foto</th>
                                                <th>Status</th>
                                                <th>Keterangan</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="loadpresensi"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal-blur fade" id="modal-tampilkanpeta" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Lokasi Presensi Karyawan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="loadmap">

                </div>
            </div>
        </div>
    </div>
    <div class="modal modal-blur fade" id="modal-koreksipresensi" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Presensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="loadkoreksipresensi">

                </div>
            </div>
        </div>
    </div>
    <!-- Modal untuk menampilkan gambar besar -->
    <div class="modal fade" id="modal-gambar" tabindex="-1" aria-labelledby="modalGambarLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalGambarLabel">Detail Gambar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="gambar-besar" src="" class="img-fluid" alt="Gambar Besar">
                </div>
            </div>
        </div>
    </div>
@endsection
@push('myscript')
    <script>
        $(function() {
            function loadpresensi() {
                var tanggal = $("#tanggal").val();
                var kode_cabang = $("#kode_cabang").val();
                $.ajax({
                    type: 'POST',
                    url: '/getpresensi',
                    data: {
                        _token: "{{ csrf_token() }}",
                        tanggal: tanggal,
                        kode_cabang: kode_cabang
                    },
                    cache: false,
                    success: function(respond) {
                        $('#loadpresensi').html(respond);
                    }
                });
            }
            $('#tanggal').change(function(e) {
                loadpresensi();
            });
            $("#kode_cabang").change(function(e) {
                loadpresensi();
            });
            loadpresensi();


        });
    </script>
@endpush
