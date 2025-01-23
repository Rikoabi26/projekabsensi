@extends('layouts.admin.tabler')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Tambah Data
                    </h2>
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
            <form action="{{ route('sewa.store') }}" method="POST" class="p-4">
                @csrf

                <div class="form-group mb-4">
                    <label for="jen_sewa">Jenis Sewa</label>
                    <input type="text" id="jen_sewa" value="" name="jen_sewa" class="form-control"
                        required>
                </div>
                <div class="form-group mb-4">
                    <label for="awal_sewa">Awal Kontrak</label>
                    <input type="date" id="awal_sewa" value="" name="awal_sewa" class="form-control"
                        required>
                </div>
                <div class="form-group mb-4">
                    <label for="akir_sewa">Habis Kontrak</label>
                    <input type="date" id="akir_sewa" value="" name="akir_sewa" class="form-control"
                        required>
                </div>
                
                <div class="form-group mb-4">
                    <label for="cabang">Unit</label>
                    <select name="kode_cabang" id="kode_cabang" class="form-select" required>
                        <option value="">Unit</option>
                        @foreach ($cabang as $d)
                            <option value="{{ $d->kode_cabang }}">{{ strtoupper($d->nama_cabang) }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
@endsection
@push('myscript')
    <script>
       
    </script>
@endpush
