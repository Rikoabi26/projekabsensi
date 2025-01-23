@extends('layouts.admin.tabler')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Edit Data Sewa
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
            <form action="/sewa/{{$sewa->id}}/update" method="POST" class="p-4">
                @csrf
                <div class="form-group mb-4">
                    <label for="jen_sewa">Jenis Sewa</label>
                    <input type="text" id="jen_sewa" value="{{old('jen_sewa', $sewa->jen_sewa)}}" name="jen_sewa" class="form-control"
                        required>
                </div>
                <div class="form-group mb-4">
                    <label for="awal_sewa">Awal Kontrak</label>
                    <input type="date" id="awal_sewa" value="{{old('awal_sewa', $sewa->awal_sewa)}}" name="awal_sewa" class="form-control"
                        required>
                </div>
                <div class="form-group mb-4">
                    <label for="akir_sewa">Habis Kontrak</label>
                    <input type="date" id="akir_sewa" value="{{old('akir_sewa', $sewa->akir_sewa)}}" name="akir_sewa" class="form-control"
                        required>
                </div>
                
                <div class="form-group mb-4">
                    <label for="cabang">Unit</label>
                    <select name="kode_cabang" id="kode_cabang" class="form-select" required>
                        <option value="">Unit</option>
                        @foreach ($cabang as $d)
                            <option {{$sewa->kode_cabang == $d->kode_cabang ? 'selected' : ''}} value="{{ $d->kode_cabang }}">{{ strtoupper($d->nama_cabang) }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
@endsection
