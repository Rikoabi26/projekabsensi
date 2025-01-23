@extends('layouts.admin.tabler')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Kontrak Sewa
                    </h2>
                </div>
                <div class="col">
                    <a href="{{ route('sewa.tambah') }}" class="btn btn-primary float-end">+ Tambah</a>
                </div>
            </div>
        </div>
    </div>
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
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-12">
                    <form action="{{ url('/sewa') }}" method="GET" autocomplete="off">
                        <div class="row">
                            <div class="col-4">
                                <div class="input-icon mb-3">
                                    <span class="input-icon-addon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-users">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                            <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                            <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                                        </svg>
                                    </span>

                                    <input type="text" value="{{ Request('jen_sewa') }}" name="jen_sewa" id="search"
                                        class="form-control" autocomplete="off" placeholder="Search">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <button class="btn btn-primary" type="submit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-search">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                                            <path d="M21 21l-6 -6" />
                                        </svg>
                                        Search</button>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Jenis Sewa</th>
                                <th>Awal Sela</th>
                                <th>Habis Kontrak</th>
                                <th>Unit</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sewa as $d)
                                @php
                                    $isExpiring = \Carbon\Carbon::parse($d->akir_sewa)->lessThanOrEqualTo(
                                        \Carbon\Carbon::now()->addMonth(),
                                    );
                                @endphp
                                <tr class="{{ $isExpiring ? 'table-danger' : '' }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $d->jen_sewa }}</td>
                                    <td>{{ $d->awal_sewa }}</td>
                                    <td>{{ $d->akir_sewa }}</td>
                                    <td>{{ $d->nama_cabang }}</td>
                                    <td class="d-flex align-items-center">
                                        <a href="{{ url('/sewa/edit/' . $d->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ '/sewa/delete/' . $d->id }}" method="POST">
                                            @csrf
                                            <a class="btn btn-sm btn-danger delete">Hapus</a>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                    {{ $sewa->links('vendor.pagination.bootstrap-5') }}
                </div>

            </div>
        </div>
    </div>
@endsection
@push('myscript')
    <script>
        $(function() {
            $(".delete").click(function(e) {
                var form = $(this).closest('form');
                e.preventDefault();
                Swal.fire({
                    title: "Anda yakin menghapus data ini?",
                    text: "Data akan terhapus permanen",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "YA, Hapus"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                        Swal.fire({
                            title: "Deleted!",
                            text: "Data berhasil di hapus",
                            icon: "success"
                        });
                    }
                });
            });
        });
    </script>
@endpush
