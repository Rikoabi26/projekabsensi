@extends('layouts.presensi')

@section('header')
    {{-- app header --}}
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Data Izin/Sakit</div>
        <div class="right"></div>
    </div>

    <style>
        .historicontent {
            display: flex;
        }

        .datapresensi {
            margin-left: 10px;
        }

        .status {
            position: absolute;
            right: 15px;
        }
    </style>
@endsection

@section('content')
    <div class="row" style="margin-top: 70px">
        <div class="col">
            @php
                $messagesuccess = Session::get('success');
                $messageerror = Session::get('error');
            @endphp
            @if (Session::get('success'))
                <div class="alert alert-success">{{ $messagesuccess }}</div>
            @endif
            @if (Session::get('error'))
                <div class="alert alert-danger">{{ $messageerror }}</div>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col">
            @foreach ($dataizin as $d)
                @php
                    if ($d->status == 'i') {
                        $status = 'Izin';
                    } elseif ($d->status == 's') {
                        $status = 'Sakit';
                    } elseif ($d->status == 'c') {
                        $status = 'Cuti';
                    } else {
                        $status = 'Not Found';
                    }
                @endphp
                <div class="card">
                    <div class="card-body">
                        <div class="historicontent">
                            <div class="iconpresensi">
                                <ion-icon name="document-outline"
                                    style="font-size: 48px; color: rgb(33, 33, 199)"></ion-icon>
                            </div>
                            <div class="datapresensi">
                                <h3 style="line-height: 3px">{{ date('d-m-Y', strtotime($d->tgl_izin_dari)) }}
                                    ({{ $status }})</h3>
                                <small>{{ date('d-m-Y', strtotime($d->tgl_izin_dari)) }} s/d
                                    {{ date('d-m-Y', strtotime($d->tgl_izin_sampai)) }}</small>
                                <p>{{ $d->keterangan }}</p>
                            </div>
                           
                                <div class="status">
                                    @if ($d->status_approved == 0)
                                    <span class="badge bg-warning">Pending</span>
                                    @elseif($d->status_approved == '1')
                                    <span class="badge bg-success">Disetujui</span>
                                    @elseif($d->status_approved == '2')
                                    <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                    <p style="margin-top: 5px; font-weight: bold">{{hitunghari($d->tgl_izin_dari, $d->tgl_izin_sampai)}} Hari</p>
                                </div>
                        
                        </div> 
                    </div>
                </div>
                {{-- <ul class="listview image-listview">
                    <li>
                        <div class="item">
                            {{-- <img src="{{ asset('assets/img/sample/avatar/avatar1.jpg') }}" alt="image" class="image"> --}}
                {{-- <div class="in">
                                <div>
                                    <b>{{ date('d-m-Y', strtotime($d->tgl_izin_dari)) }}
                                        ({{ $d->status == 's' ? 'Sakit' : 'Izin' }})
                                    </b><br>
                                    <small>{{ $d->keterangan }}</small>
                                </div>
                                @if ($d->status_approved == 0)
                                    <span class="badge bg-warning">Diajukan</span>
                                @elseif($d->status_approved == 1)
                                    <span class="badge bg-success">Disetujui</span>
                                @elseif($d->status_approved == 2)
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </div>
                        </div>
                    </li>
                </ul> --}}
            @endforeach
        </div>
    </div>
    <div class="fab-button animate bottom-right dropdown" style="margin-bottom: 70px">
        <a href="#" class="fab bg-primary" data-toggle="dropdown">
            <ion-icon name="add-outline" role="img" class="md hydrated" aria-label="add outline"></ion-icon>
        </a>
        <div class="dropdown-menu">
            <a href="/izinabsen" class="dropdown-item bg-primary">
                <ion-icon name="document-outline" role="img" class="md hydrated" aria-label="image outline"></ion-icon>
                <p>Izin Absen</p>
            </a>

            <a href="/izinsakit" class="dropdown-item bg-primary">
                <ion-icon name="document-outline" role="img" class="md hydrated"
                    aria-label="videocam outline"></ion-icon>
                <p>Sakit</p>
            </a>

            <a href="/izincuti" class="dropdown-item bg-primary">
                <ion-icon name="document-outline" role="img" class="md hydrated"
                    aria-label="videocam outline"></ion-icon>
                <p>Cuti</p>
            </a>
        </div>
    </div>
@endsection
