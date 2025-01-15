<form action="/storekoreksipresensi" method="POST" id="formKoreksiPresensi">
    @csrf
    <input type="hidden" name="email" value="{{ $karyawan->email }}">
    <input type="hidden" name="tanggal" value="{{ $tanggal }}">

    <table class="table">
        <tr>
            <td>Email</td>
            <td>{{ $karyawan->email }}</td>
        </tr>
        <tr>
            <td>Nama Lengkap</td>
            <td>{{ $karyawan->nama_lengkap }}</td>
        </tr>
        <tr>
            <td>Tanggal Presensi</td>
            <td>{{ date('d-m-Y', strtotime($tanggal)) }}</td>
        </tr>
    </table>
    <div class="row mb-2">
        <div class="col-12">
            <div class="form-group">
                <select name="status" id="status" class="form-select" required>
                    <option value="">Pilih Status Kehadiran</option>
                    <option
                        @if ($presensi != null) @if ($presensi->status === 'h')
                    selected @endif
                        @endif
                        value="h">Hadir</option>
                    <option 
                    @if ($presensi != null) @if ($presensi->status === 'a')
                    selected @endif
                        @endif
                    value="a">Alfa</option>

                </select>
            </div>
        </div>
    </div>
    <div class="row mb-2" id="frm_jam_in">
        <div class="col-12">
            <div class="input-icon ">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-clock">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                        <path d="M12 7v5l3 3" />
                    </svg>
                </span>
                <input type="text" value="{{ $presensi != null ? $presensi->jam_in : '' }}" id="jam_in"
                    name="jam_in" class="form-control" placeholder="jam masuk">
            </div>
        </div>
    </div>
    <div class="row  mb-2" id="frm_jam_out">
        <div class="col-12">
            <div class="input-icon">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-clock">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                        <path d="M12 7v5l3 3" />
                    </svg>
                </span>
                <input type="text" id="jam_out" value="{{ $presensi != null ? $presensi->jam_out : '' }}"
                    name="jam_out" class="form-control" placeholder="jam keluar">
            </div>
        </div>
    </div>
    <div class="row mb-2" id="frm_kode_jam_kerja">
        <div class="col-12">
            <div class="form-group">
                <select name="kode_jam_kerja" id="kode_jam_kerja" class="form-select" required>
                    <option value="">Pilih Jam Kerja</option>
                    @foreach ($jamkerja as $d)
                        <option
                            @if ($presensi != null) @if ($presensi->kode_jam_kerja === $d->kode_jam_kerja)
                            selected @endif
                            @endif
                            value="{{ $d->kode_jam_kerja }}">{{ $d->nama_jam_kerja }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    
    <div class="row mt-2">
        <div class="col-12">
            <div class="form-group">
                <button class="btn btn-primary w-100">Submit</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(function(){
        function loadkoreksi(){
            var status = $("#status").val();
            if(status == "a"){
                $("#frm_jam_in").hide();
                $("#frm_jam_out").hide();
                $("#frm_kode_jam_kerja").hide();
            }else{
                $("#frm_jam_in").show();
                $("#frm_jam_out").show();
                $("#frm_kode_jam_kerja").show();
            }
        }
        loadkoreksi();
        $('#status').change(function(e){
            loadkoreksi();
        });
    });
</script>