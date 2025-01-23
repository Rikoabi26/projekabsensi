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
            <form action="{{ route('nonnakes.store') }}" method="POST" class="p-4">
                @csrf

                <div class="form-group mb-4">
                    <label for="nama_lengkap">Nama Lengkap</label>
                    <input type="text" id="nama_lengkap" value="" name="nama_lengkap" class="form-control"
                        required>
                </div>
                <div class="form-group mb-4">
                    <label for="nama_lengkap">Awal Kontrak</label>
                    <input type="date" id="awal_kontrak" value="" name="awal_kontrak" class="form-control"
                        required>
                </div>
                <div class="form-group mb-4">
                    <label for="nama_lengkap">Habis Kontrak</label>
                    <input type="date" id="habis_kontrak" value="" name="habis_kontrak" class="form-control"
                        required>
                </div>
                <div class="form-group mb-4 hidden">
                    {{-- <label for="nama_lengkap">Lama Kerja</label> --}}
                    <input type="hidden" id="lama_kerja" value="" name="lama_kerja" class="form-control" placeholder="Lama Kerja" readonly>
                </div>
                <div class="form-group mb-4">
                    <label for="jen_kel">Jenis Kelamin</label>
                    <select name="jen_kel" id="jen_kel" class="form-select" required>
                        <option value="">Jenis Kelamin</option>
                        <option value="laki-laki">Laki-Laki</option>
                        <option value="perempuan">Perempuan</option>
                    </select>
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
        document.addEventListener('DOMContentLoaded', function() {
            const awalKontrakInput = document.getElementById("awal_kerja");
            const habisKontrakInput = document.getElementById("habis_kerja");
            const lamaKerjaInput = document.getElementById("lama_kerja");

            function calculateLamaKerja() {
                const awal = new Date(awalKontrakInput.value);
                const habis = new Date(habisKontrakInput.value);

                if (awal && habis && habis >= awal) {
                    const timeDiff = Math.abs(habis - awal);
                    const days = Math.floor(timeDiff / (1000 * 60 * 60 * 24));
                    const years = Math.floor(days / 365);
                    const remainingDaysAfterYears = days % 365;
                    const months = Math.floor(remainingDaysAfterYears / 30);
                    const remainingDays = remainingDaysAfterYears % 30;

                    // Gabungkan hasil
                    let lamaKerja = "";
                    if (years > 0) {
                        lamaKerja += `${years} Tahun `;
                    }
                    if (months > 0 || years > 0) { // Tampilkan bulan jika ada bulan atau tahun
                        lamaKerja += `${months} Bulan `;
                    }
                    lamaKerja += `${remainingDays} Hari`;

                    lamaKerjaInput.value = lamaKerja.trim();
                } else {
                    lamaKerjaInput.value = "";
                }
            }

            awalKontrakInput.addEventListener("change", calculateLamaKerja);
            habisKontrakInput.addEventListener("change", calculateLamaKerja);
        });
    </script>
@endpush
