@php
    function selisih($jam_masuk, $jam_keluar)
    {
        [$h, $m, $s] = explode(':', $jam_masuk);
        $dtAwal = mktime($h, $m, $s, '1', '1', '1');
        [$h, $m, $s] = explode(':', $jam_keluar);
        $dtAkhir = mktime($h, $m, $s, '1', '1', '1');
        $dtSelisih = $dtAkhir - $dtAwal;
        $totalmenit = $dtSelisih / 60;
        $jam = explode('.', $totalmenit / 60);
        $sisamenit = $totalmenit / 60 - $jam[0];
        $sisamenit2 = $sisamenit * 60;
        $jml_jam = $jam[0];
        return $jml_jam . ':' . round($sisamenit2);
    }
@endphp
@if ($presensi->isEmpty())
    <tr>
        <td colspan="4">Data tidak ditemukan</td>
    </tr>
@else
    @foreach ($presensi as $d)
        @php
            $foto_in = Storage::url('uploads/absensi/' . $d->foto_in);
            $foto_out = Storage::url('uploads/absensi/' . $d->foto_out);

        @endphp
        
        @if ($d->status == 'h')
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $d->email }}</td>
                <td>{{ $d->nama_lengkap }}</td>
                <td>{{ $d->kode_dept }}</td>
                <td>{{ $d->nama_jam_kerja }} ({{ $d->jam_masuk }})</td>
                <td>{{ $d->jam_in }}</td>
                <td>
                    @if ($d->foto_in != null)
                        {{-- <img src="{{ url($foto_in) }}" class="avatar" alt="">   --}}
                        <img src="{{ asset('assets/new-uploads/absensi/') . '/' . $d->foto_in }}"
                            class="avatar clickable-gambar" alt=""
                            data-src="{{ asset('assets/new-uploads/absensi/') . '/' . $d->foto_in }}">
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-hourglass-low">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M6.5 17h11" />
                            <path d="M6 20v-2a6 6 0 1 1 12 0v2a1 1 0 0 1 -1 1h-10a1 1 0 0 1 -1 -1z" />
                            <path d="M6 4v2a6 6 0 1 0 12 0v-2a1 1 0 0 0 -1 -1h-10a1 1 0 0 0 -1 1z" />
                        </svg>
                    @endif
                </td>
                <td>{!! $d->jam_out != null ? $d->jam_out : '<span class="badge bg-info"> Belum absen</span> ' !!}</td>
                <td>
                    @if ($d->foto_out != null)
                        {{-- <img src="{{ url($foto_out) }}" class="avatar" alt=""> --}}
                        <img src="{{ asset('assets/new-uploads/absensi/') . '/' . $d->foto_out }}" class="avatar clickable-gambar"
                            alt=""
                            data-src="{{ asset('assets/new-uploads/absensi/') . '/' . $d->foto_out }}">
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-hourglass-low">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M6.5 17h11" />
                            <path d="M6 20v-2a6 6 0 1 1 12 0v2a1 1 0 0 1 -1 1h-10a1 1 0 0 1 -1 -1z" />
                            <path d="M6 4v2a6 6 0 1 0 12 0v-2a1 1 0 0 0 -1 -1h-10a1 1 0 0 0 -1 1z" />
                        </svg>
                    @endif

                </td>
                <td>{{ $d->status }}</td>

                <td>

                    @if ($d->jam_in >= $d->jam_masuk)
                        @php
                            $jamterlambat = selisih($d->jam_masuk, $d->jam_in);
                        @endphp
                        <span class="badge bg-danger">Terlambat {{ $jamterlambat }}</span>
                    @else
                        <span class="badge bg-primary">In Time</span>
                    @endif
                </td>
                <td>
                    @if ($d->lokasi_in != null)
                        <a href="#" class="btn btn-primary tampilkanpeta" id="{{ $d->id }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-map-2">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 18.5l-3 -1.5l-6 3v-13l6 -3l6 3l6 -3v7.5" />
                                <path d="M9 4v13" />
                                <path d="M15 7v5.5" />
                                <path
                                    d="M21.121 20.121a3 3 0 1 0 -4.242 0c.418 .419 1.125 1.045 2.121 1.879c1.051 -.89 1.759 -1.516 2.121 -1.879z" />
                                <path d="M19 18v.01" />
                            </svg>
                        </a>
                    @endif

                    <a href="#" class="btn btn-success btn-sm mt-1 koreksipresensi"
                        email="{{ $d->email }}">Koreksi</a>
                </td>
            </tr>
        @else
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $d->email }}</td>
                <td>{{ $d->nama_lengkap }}</td>
                <td>{{ $d->kode_dept }}</td>
                <td>
                    <span class="badge bg-danger">Belum Absen</span>
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    @if ($d->status == 'i')
                        <span class="badge bg-warning">I</span>
                    @elseif ($d->status == 's')
                        <span class="badge bg-info">S</span>
                    @elseif ($d->status == 'a')
                        <span class="badge bg-danger">A</span>
                    @elseif ($d->status == 'c')
                        <span class="badge" style="background-color: #a600ff">C</span>
                    @endif
                </td>
                <td>{{ $d->keterangan }}</td>
                <td><a href="#" class="btn btn-success btn-sm mt-1 koreksipresensi"
                        email="{{ $d->email }}">Koreksi</a></td>
            </tr>
        @endif
    @endforeach
@endif



<script>
    $(function() {
        $(".tampilkanpeta").click(function(e) {
            var id = $(this).attr("id");
            $.ajax({
                type: "POST",
                url: '/tampilkanpeta',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                cache: false,
                success: function(respond) {
                    console.log("Respon Server:", respond);
                    $("#loadmap").html(respond);
                }
            });
            $('#modal-tampilkanpeta').modal("show");
        });

        $(".koreksipresensi").click(function(e) {
            var email = $(this).attr("email");
            var tanggal = "{{ $tanggal }}";
            $.ajax({
                type: "POST",
                url: '/koreksipresensi',
                data: {
                    _token: "{{ csrf_token() }}",
                    email: email,
                    tanggal: tanggal
                },
                cache: false,
                success: function(respond) {
                    $("#loadkoreksipresensi").html(respond);
                }
            });
            $('#modal-koreksipresensi').modal("show");
        });

        $(".clickable-gambar").on("click", function() {
            var src = $(this).data("src");
            $("#gambar-besar").attr("src", src); // Set gambar ke modal
            $("#modal-gambar").modal("show"); // Tampilkan modal
        });

    });
</script>
