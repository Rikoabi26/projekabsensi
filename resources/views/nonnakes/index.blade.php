@extends('layouts.admin.tabler')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Kontrak Kerja Karyawan
                    </h2>
                </div>
                <div class="col">
                    <a href="{{ route('nonnakes.tambah') }}" class="btn btn-primary float-end">+ Tambah</a>
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
                    <form action="{{ url('/nonnakes') }}" method="GET" autocomplete="off">
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

                                    <input type="text" value="{{ Request('nama_karyawan') }}" name="nama_karyawan"
                                        id="search" class="form-control" autocomplete="off" placeholder="Search">
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
                                <th>Nama Lengkap</th>
                                <th>Awal Kontrak</th>
                                <th>Habis Kontrak</th>
                                <th>Lama Kerja</th>
                                <th>Jenis Kelamin</th>
                                <th>Unit</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($nonnakes as $d)
                                @php
                                    $isExpiring = \Carbon\Carbon::parse($d->habis_kontrak)->lessThanOrEqualTo(
                                        \Carbon\Carbon::now()->addMonth(),
                                    );
                                @endphp
                                <tr class="{{ $isExpiring ? 'table-danger' : '' }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $d->nama_lengkap }}</td>
                                    <td>{{ $d->awal_kontrak }}</td>
                                    <td>{{ $d->habis_kontrak }}</td>
                                    <td>{{ $d->lama_kerja }}</td>
                                    <td>{{ $d->jen_kel }}</td>
                                    <td>{{ $d->nama_cabang }}</td>
                                    <td>
                                        <a href="{{ url('/nonnakes/edit/' . $d->id) }}"
                                            class="btn btn-sm btn-warning">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                    {{ $nonnakes->links('vendor.pagination.bootstrap-5') }}
                </div>

            </div>
        </div>
    </div>
@endsection
