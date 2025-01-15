@extends('layouts.admin.tabler')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Approve Pengajuan Izin/Sakit
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

            <div class="row">
                <div class="col-12">
                    <form action="/presensi/izinsakit" method="GET" autocomplete="off">
                        <div class="row">
                            <div class="col-4">
                                <div class="input-icon mb-3">
                                    <span class="input-icon-addon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-month">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" />
                                            <path d="M16 3v4" />
                                            <path d="M8 3v4" />
                                            <path d="M4 11h16" />
                                            <path d="M7 14h.013" />
                                            <path d="M10.01 14h.005" />
                                            <path d="M13.01 14h.005" />
                                            <path d="M16.015 14h.005" />
                                            <path d="M13.015 17h.005" />
                                            <path d="M7.01 17h.005" />
                                            <path d="M10.01 17h.005" />
                                        </svg>
                                    </span>
                                    <input type="text" value="{{ Request('dari') }}" name="dari" id="dari"
                                        class="form-control" autocomplete="off" placeholder="Dari">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="input-icon mb-3">
                                    <span class="input-icon-addon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-month">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" />
                                            <path d="M16 3v4" />
                                            <path d="M8 3v4" />
                                            <path d="M4 11h16" />
                                            <path d="M7 14h.013" />
                                            <path d="M10.01 14h.005" />
                                            <path d="M13.01 14h.005" />
                                            <path d="M16.015 14h.005" />
                                            <path d="M13.015 17h.005" />
                                            <path d="M7.01 17h.005" />
                                            <path d="M10.01 17h.005" />
                                        </svg>
                                    </span>
                                    <input type="text" value="{{ Request('sampai') }}" name="sampai" id="sampai"
                                        class="form-control" autocomplete="off" placeholder="Sampai">
                                </div>
                            </div>
                            @role('administrator', 'user')
                                <div class="col-4">
                                    <div class="form-group">
                                        <select name="kode_cabang" class="form-select" id="kode_cabang" required>
                                            <option value="">Cabang</option>
                                            @foreach ($cabang as $d)
                                                <option {{ Request('kode_cabang') == $d->kode_cabang ? 'selected' : '' }}
                                                    value="{{ $d->kode_cabang }}">{{ strtoupper($d->nama_cabang) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endrole
                        </div>
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
                                    <input type="text" value="{{ Request('nama_lengkap') }}" name="nama_lengkap"
                                        id="nama_lengkap" class="form-control" autocomplete="off"
                                        placeholder="Nama Lengkap">
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="form-group">
                                    <select name="status_approved" id="status_approved" class="form-select">
                                        <option value="">Pilih Status</option>
                                        <option value="0" {{ Request('status_approved') === '0' ? 'selected' : '' }}>
                                            Pending</option>
                                        <option value="1" {{ Request('status_approved') == 1 ? 'selected' : '' }}>
                                            Disetujui</option>
                                        <option value="2" {{ Request('status_approved') == 2 ? 'selected' : '' }}>
                                            Ditolak</option>
                                    </select>
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
                                <th>Kode Izin</th>
                                <th>Tanggal</th>
                                <th>Email</th>
                                <th>Nama Karyawan</th>
                                <th>Jabatan</th>
                                <th>Status</th>
                                <th><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-checklist">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M9.615 20h-2.615a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8" />
                                        <path d="M14 19l2 2l4 -4" />
                                        <path d="M9 8h4" />
                                        <path d="M9 12h2" />
                                    </svg></th>
                                <th>Keterangan</th>
                                <th>Status</th>
                                <th style="min-width:400px">Approval</th>
                                
                                {{-- <th>Aksi</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($izinsakit as $d)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $d->kode_izin }}</td>
                                    <td>
                                        {{ date('d-m-Y', strtotime($d->tgl_izin_dari)) }} s/d
                                        {{ date('d-m-Y', strtotime($d->tgl_izin_sampai)) }}
                                    </td>
                                    <td>{{ $d->email }}</td>
                                    <td>{{ $d->nama_lengkap }}</td>
                                    <td>{{ $d->jabatan }}</td>
                                    <td>
                                        {{ $d->status == 'i' ? 'Izin' : ($d->status == 's' ? 'Sakit' : ($d->status == 'c' ? 'Cuti' : 'Tidak Diketahui')) }}
                                    </td>
                                    <td>
                                        @if (!empty($d->doc_sid))
                                            @php
                                                $path = Storage::url('uploads/sid/' . $d->doc_sid);
                                            @endphp
                                            <a href="{{ url($path) }}" target="_blank">

                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-paperclip">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M15 7l-6.5 6.5a1.5 1.5 0 0 0 3 3l6.5 -6.5a3 3 0 0 0 -6 -6l-6.5 6.5a4.5 4.5 0 0 0 9 9l6.5 -6.5" />
                                                </svg></a>
                                        @endif
                                    </td>
                                    <td>{{ $d->keterangan }}</td>

                                    <td>
                                        @if ($d->status_approved == 1)
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($d->status_approved == 2)
                                            <span class="badge bg-danger">Rejected</span>
                                        @elseif($d->status_approved == 3)
                                            <span class="badge bg-primary">On Approval</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>

                                    <td>
                                        @foreach ($d->izinWorkflow($d->kode_izin) as $index => $item)
                                            <div class="float-start" style="font-weight: bold">
                                                {{$item->role->name ?? '-'}} By :
                                            </div>
                                            <div class="float-end">
                                                @if ($item->status == 'Approve')
                                                    <span class="badge bg-success">Approve</span>
                                                @elseif($item->status == 'Reject')
                                                    <span class="badge bg-danger">Reject</span>
                                                @else
                                                    <span class="badge bg-warning">Waiting Approval</span>
                                                @endif
                                            </div>
                                            <br>
                                            <br>
                                            <div class="float-start">
                                                {{$item->user->name ?? '-'}}
                                            </div>
                                            <div class="float-end">
                                                @php
                                                    $user_id = Illuminate\Support\Facades\Auth::guard('user')->user()->id;
                                                    $user = App\Models\User::find($user_id);

                                                @endphp
                                                @if ($item->active == 1 && $user->hasRole($item->role->name))
                                                    <a href="{{url('/presensi/form-approval-izin/'.$item->id.'/'.$d->kode_izin)}}" class="btn btn-primary btn-sm">
                                                        Form Approval
                                                    </a>
                                                @endif
                                            </div>
                                            <br>
                                            <div class="float-start" style="font-size:12px; color:gray">
                                                Notes : {{$item->notes ?? '-'}}
                                            </div>
                                            <br>
                                            @if ($index < count($d->izinWorkflow($d->kode_izin)) - 1)
                                                <hr>
                                            @endif
                                        @endforeach
                                    </td>

                                    {{-- <td>
                                        @if ($d->status_approved == 0)
                                            <a href="#" class="btn btn-sm btn-primary approve"
                                                kode_izin="{{ $d->kode_izin }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-external-link">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M12 6h-6a2 2 0 0 0 -2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-6" />
                                                    <path d="M11 13l9 -9" />
                                                    <path d="M15 4h5v5" />
                                                </svg>
                                            </a>
                                        @else
                                            <a href="/presensi/{{ $d->kode_izin }}/batalkanizinsakit"
                                                class="btn btn-sm bg-danger">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-square-x">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M3 5a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-14z" />
                                                    <path d="M9 9l6 6m0 -6l-6 6" />
                                                </svg>
                                            </a>
                                        @endif


                                    </td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $izinsakit->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal-blur fade" id="modal-izinsakit" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Approve</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/presensi/approveizinsakit" method="POST">
                        @csrf
                        <input type="hidden" id="kode_izin_form" name="kode_izin_form">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <select name="status_approved" id="status_approved" class="form-select">
                                        <option value="1">Disetujui</option>
                                        <option value="2">Ditolak</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <button class="btn btn-primary w-100" type="submit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-check">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M5 12l5 5l10 -10" />
                                        </svg>
                                        Approve
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('myscript')
    <script>
        $(function() {
            $(".approve").click(function(e) {
                e.preventDefault();
                var kode_izin = $(this).attr('kode_izin');
                // alert(id_izinsakit);
                $("#kode_izin_form").val(kode_izin);
                $("#modal-izinsakit").modal('show');
            });

            $("#dari, #sampai").datepicker({
                autoclose: true,
                todayHighlight: true,
                dateFormat: 'yy-mm-dd'
            });
        });

        document.getElementById('status_approved').addEventListener('change', function() {
            const rejectionDiv = document.querySelector('.rejection-reason');
            if (this.value === '2') {
                rejectionDiv.style.display = 'block';
            } else {
                rejectionDiv.style.display = 'none';
            }
        });
    </script>
@endpush
