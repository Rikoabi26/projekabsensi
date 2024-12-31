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
            margin-top: 10px;
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
            <form method="GET" action="/presensi/izin">
                <div class="row">
                    <div class="col-8">
                        <div class="form-group">
                            <select name="bulan" id="bulan" class="form-control selectmaterialize">
                                <option value="">Bulan</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option {{ Request('bulan') == $i ? 'selected' : '' }} value="{{ $i }}">
                                        {{ $namabulan[$i] }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <select name="tahun" id="tahun" class="form-control selectmaterialize">
                                <option value="">Tahun</option>
                                @php
                                    $tahun_awal = 2023;
                                    $tahun_sekarang = date('Y');
                                    for ($t = $tahun_awal; $t <= $tahun_sekarang; $t++) {
                                        if (Request('tahun') == $t) {
                                            $selected = 'selected';
                                        } else {
                                            $selected = '';
                                        }
                                        echo "<option $selected value='$t'>$t</option>";
                                    }
                                @endphp
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button class="btn btn-primary form-control mb-2">Cari Data</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row" style="position: fixed; width: 100%; margin: auto; overflow-y:scroll; height: 430px">
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
                <div class="card mt-1 card_izin" kode_izin="{{ $d->kode_izin }}" status_approved = "{{$d->status_approved}}" data-toggle="modal"
                    data-target="#actionSheetIconed">
                    <div class="card-body">
                        <div class="historicontent">
                            <div class="iconpresensi">
                                @if ($d->status == 'i')
                                    <ion-icon name="document-outline"
                                        style="font-size: 48px; color: rgb(33, 33, 199)"></ion-icon>
                                @elseif($d->status == 's')
                                    <ion-icon name="medkit-outline"
                                        style="font-size: 48px; color: rgb(199, 33, 33)"></ion-icon>
                                @elseif ($d->status == 'c')
                                    <ion-icon name="calendar-clear-outline"
                                        style="font-size: 48px; color: rgb(228, 193, 36)"></ion-icon>
                                @endif
                            </div>
                            <div class="datapresensi">
                                <h3 style="line-height: 3px">{{ date('d-m-Y', strtotime($d->tgl_izin_dari)) }}
                                    ({{ $status }})
                                </h3>
                                <small>{{ date('d-m-Y', strtotime($d->tgl_izin_dari)) }} s/d
                                    {{ date('d-m-Y', strtotime($d->tgl_izin_sampai)) }}</small>
                                <p>

                                    {{ $d->keterangan }}
                                    <br>
                                    @if ($d->status == 'c')
                                        <span class="badge bg-warning">{{ $d->nama_cuti }}</span>
                                    @endif
                                    <br>
                                    @if (!empty($d->doc_sid))
                                        <span style="color: blue">
                                            <ion-icon name="document-attach-outline"></ion-icon>SID
                                        </span>
                                    @endif
                                </p>
                            </div>

                            <div class="status">
                                @if ($d->status_approved == 0)
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($d->status_approved == '1')
                                    <span class="badge bg-success">Disetujui</span>
                                @elseif($d->status_approved == '2')
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                                <p style="margin-top: 5px; font-weight: bold">
                                    {{ hitunghari($d->tgl_izin_dari, $d->tgl_izin_sampai) }} Hari</p>
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

    {{-- Modal Pop UP Action --}}
    <div class="modal fade action-sheet" id="actionSheetIconed" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Aksi</h5>
                </div>
                <div class="modal-body" id="showact">

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade dialogbox" id="deleteConfirm" data-backdrop="static" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Yakin Dihapus ?</h5>
                </div>
                <div class="modal-body">
                    Data Pengajuan Izin Akan dihapus
                </div>
                <div class="modal-footer">
                    <div class="btn-inline">
                        <a href="#" class="btn btn-text-secondary" data-dismiss="modal">Batalkan</a>
                        <a href="" class="btn btn-text-primary" id="hapuspengajuan">Hapus</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('myscript')
    <script>
        $(function() {
            $(".card_izin").click(function(e) {
                var kode_izin = $(this).attr("kode_izin");
                var status_approved = $(this).attr("status_approved");

                if(status_approved == 1){
                    Swal.fire({
                        title: 'OOps !',
                        text: "Data Sudah Disetujui, Tidak dapat di ubah",
                        icon: 'warning'
                    })
                }else{
                    $("#showact").load('/izin/' + kode_izin + '/showact');
                }

            });
        });
    </script>
@endpush
