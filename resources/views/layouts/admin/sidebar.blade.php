<style>
    /* Mengatur flexbox pada navbar */
    .navbar-nav.flex-row {
        display: flex;
        align-items: center;
        /* Agar logo dan teks tegak lurus di tengah */
    }

    /* Mengurangi margin di sekitar logo dan nama perusahaan */
    .navbar-nav .nav-item {
        margin: 0;
        padding: 0;
    }

    /* Mengurangi jarak antara logo dan teks */
    .nav-item.d-flex.align-items-center {
        margin-bottom: 5px;
        /* Menurunkan jarak antar logo dan teks */
    }

    /* Mengatur ukuran logo agar lebih proporsional */
    .navbar-nav img {
        height: 30px;
        /* Ukuran logo yang lebih kecil */
        margin-right: 8px;
        /* Mengurangi margin ke kanan logo */
    }

    /* Menambahkan padding pada menu di sidebar untuk merapikan */
    .navbar-nav li.nav-item {
        padding: 6px 8px;
    }

    /* Menambahkan efek hover pada menu */
    .navbar-nav li.nav-item a:hover {
        background-color: #495057;
        /* Warna latar belakang hover */
    }

    /* Untuk responsif, memastikan logo dan teks tetap di bawah logo saat tampilan kecil */
    @media (max-width: 992px) {
        .navbar-nav .nav-item.d-flex.align-items-center {
            flex-direction: column;
            /* Membuat logo dan teks ditampilkan secara vertikal di mobile */
            text-align: center;
            /* Menyelaraskan teks di tengah */
        }
    }
</style>
<aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="dark">
    <div class="container-fluid">
        <div class="navbar-nav flex-row d-lg-none">
            <div class="nav-item d-flex align-items-center">
                <!-- Logo -->
                <img src="{{ asset('assets/img/Logo Lisna.png') }}" alt="Company Logo"
                    style="height: 40px; margin-right: 10px;">
                <!-- Nama Perusahaan -->
                <span class="navbar-brand">Absensi Lisna</span>
            </div>
        </div>
        <div class="collapse navbar-collapse" id="sidebar-menu">
            <ul class="navbar-nav pt-lg-3">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('panel/dashboardadmin') ? 'active' : '' }}"
                        href="/panel/dashboardadmin">
                        <span
                            class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M5 12l-2 0l9 -9l9 9l-2 0" />
                                <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                                <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            Home
                        </span>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->is(['karyawan', 'departemen', 'cabang']) ? 'show' : '' }}"
                        href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button"
                        aria-expanded="{{ request()->is(['karyawan', 'departemen', 'cabang']) ? 'true' : '' }}">
                        <span
                            class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/package -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" />
                                <path d="M12 12l8 -4.5" />
                                <path d="M12 12l0 9" />
                                <path d="M12 12l-8 -4.5" />
                                <path d="M16 5.25l-8 4.5" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            Data Master
                        </span>
                    </a>
                    <div class="dropdown-menu {{ request()->is(['karyawan', 'departemen', 'cabang', 'cuti']) ? 'show' : '' }}">
                        <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                                <a class="dropdown-item {{ request()->is(['karyawan']) ? 'active' : '' }}"
                                    href="/karyawan">
                                    Karyawan
                                </a>
                                <a class="dropdown-item {{ request()->is(['departemen']) ? 'active' : '' }}"
                                    href="/departemen">
                                    Departemen
                                </a>
                                <a class="dropdown-item {{ request()->is(['cabang']) ? 'active' : '' }}" href="/cabang">
                                    Tikor Cabang
                                </a>
                                <a class="dropdown-item {{ request()->is(['cuti']) ? 'active' : '' }}" href="/cuti">
                                    Cuti
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('presensi/monitoring') ? 'active' : '' }} "
                        href="/presensi/monitoring">
                        <span
                            class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-heart-rate-monitor">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M3 4m0 1a1 1 0 0 1 1 -1h16a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-16a1 1 0 0 1 -1 -1z" />
                                <path d="M7 20h10" />
                                <path d="M9 16v4" />
                                <path d="M15 16v4" />
                                <path d="M7 10h2l2 3l2 -6l1 3h3" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            Monitoring Absensi
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('presensi/izinsakit') ? 'active' : '' }}"
                        href="/presensi/izinsakit">
                        <span
                            class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-heart-rate-monitor">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M3 4m0 1a1 1 0 0 1 1 -1h16a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-16a1 1 0 0 1 -1 -1z" />
                                <path d="M7 20h10" />
                                <path d="M9 16v4" />
                                <path d="M15 16v4" />
                                <path d="M7 10h2l2 3l2 -6l1 3h3" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            Pengajuan Izin/Sakit
                        </span>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->is(['presensi/laporan', 'presensi/rekap']) ? 'show' : '' }}"
                        href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button"
                        aria-expanded="{{ request()->is(['presensi/laporan', 'presensi/rekap']) ? 'show' : '' }}">
                        <span
                            class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/package -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-notes">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M5 3m0 2a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2z" />
                                <path d="M9 7l6 0" />
                                <path d="M9 11l6 0" />
                                <path d="M9 15l4 0" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            Laporan
                        </span>
                    </a>
                    <div
                        class="dropdown-menu {{ request()->is(['presensi/laporan', 'presensi/rekap']) ? 'show' : '' }}">
                        <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                                <a class="dropdown-item {{ request()->is(['presensi/laporan']) ? 'show' : '' }}"
                                    href="/presensi/laporan">
                                    Laporan Presensi
                                </a>
                                <a class="dropdown-item {{ request()->is(['presensi/rekap']) ? 'show' : '' }}"
                                    href="/presensi/rekap">
                                    Rekap Presensi
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->is(['konfigurasi', 'konfigurasi/*']) ? 'show' : '' }}"
                        href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button"
                        aria-expanded="{{ request()->is(['konfigurasi', 'konfigurasi/*']) ? 'true' : '' }}">
                        <span
                            class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/package -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-settings">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" />
                                <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            Konfigurasi
                        </span>
                    </a>
                    <div class="dropdown-menu {{ request()->is(['konfigurasi', 'konfigurasi/*']) ? 'show' : '' }}">
                        {{-- <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                                <a class="dropdown-item {{ request()->is(['konfigurasi/lokasikantor']) ? 'active' : '' }}"
                                    href="/konfigurasi/lokasikantor">
                                    Lokasi Kantor
                                </a>
                            </div>
                        </div> --}}
                        <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                                <a class="dropdown-item {{ request()->is(['konfigurasi/jamkerja']) ? 'active' : '' }}"
                                    href="/konfigurasi/jamkerja">
                                    Jam Kerja
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</aside>
