@extends('layouts.admin.tabler')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Edit Data Nakes
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
            <form action="/nakes/{{ $nakes->id }}/update" method="POST" class="p-4" enctype="multipart/form-data">
                
                @csrf
                <div class="form-group mb-4">
                    <label for="sip">SIP</label>
                    <input type="text" id="sip" value="{{old('SIP', $nakes->SIP)}}" name="SIP" class="form-control" required>
                </div>
                <div class="form-group mb-4">
                    <label for="sip">Batas SIP</label>
                    <input type="date" id="sip_expiry_date" value="{{old('sip_expiry_date', $nakes->sip_expiry_date)}}" name="sip_expiry_date" class="form-control" required>
                </div>
                <div class="form-group mb-4">
                    <label for="nama_lengkap">Nama Lengkap</label>
                    <input type="text" id="nama_lengkap" value="{{old('nama_lengkap', $nakes->nama_lengkap)}}" name="nama_lengkap" class="form-control"
                        required>
                </div>
                <div class="form-group mb-4">
                    <label for="jen_kel">Jenis Kelamin</label>
                    <select name="jen_kel" id="jen_kel" class="form-select" required>
                        <option value="">Jenis Kelamin</option>
                        <option value="laki-laki" {{old('jen_kel', $nakes->jen_kel) == 'laki-laki' ? 'selected' : ''}}>Laki-Laki</option>
                        <option value="perempuan" {{old('jen_kel', $nakes->jen_kel) == 'perempuan' ? 'selected' : ''}}>Perempuan</option>
                    </select>
                </div>
                <div class="form-group mb-4">
                    <label for="cabang">Unit</label>
                    <select name="kode_cabang" id="kode_cabang" class="form-select" required>
                        <option value="">Unit</option>
                        @foreach ($cabang as $d)
                        <option {{$nakes->kode_cabang == $d->kode_cabang ? 'selected' : ''}} value="{{$d->kode_cabang}}">{{$d->nama_cabang}}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
@endsection
