<form action="/konfigurasi/users/{{ $user->id }}/update" method="POST" id="frmUser">
    @csrf
    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-plus">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                        <path d="M16 19h6" />
                        <path d="M19 16v6" />
                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
                    </svg>
                </span>
                <input type="text" id="nama_user" value="{{$user->name}}" name="nama_user" class="form-control"
                    placeholder="Nama Users" required>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-password">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 10v4" />
                        <path d="M10 13l4 -2" />
                        <path d="M10 11l4 2" />
                        <path d="M5 10v4" />
                        <path d="M3 13l4 -2" />
                        <path d="M3 11l4 2" />
                        <path d="M19 10v4" />
                        <path d="M17 13l4 -2" />
                        <path d="M17 11l4 2" />
                    </svg>
                </span>
                <input type="password" id="password" value="" name="password"
                    class="form-control" placeholder="Password">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-mail">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" />
                        <path d="M3 7l9 6l9 -6" />
                    </svg>
                </span>
                <input type="text" id="email" value="{{ $user->email }}" name="email" class="form-control"
                    placeholder="Email" required>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <select name="role" id="role" class="form-select" required>
                    <option value="">Role</option>
                    @foreach ($role as $d)
                        <option {{ $user->role_id == $d->id ? 'selected' : '' }} value="{{ $d->id }}">
                            {{ ucwords($d->name) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="form-group">
                <select name="kode_cabang" id="kode_cabang" class="form-select" required>
                    <option value="">Cabang</option>
                    @foreach ($cabang as $d)
                        <option {{ $user->kode_cabang == $d->kode_cabang ? 'selected' : '' }}
                            value="{{ $d->kode_cabang }}">{{ strtoupper($d->nama_cabang) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="form-group">
                <button class="btn btn-primary w-100">
                    Update
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-send">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M10 14l11 -11" />
                        <path d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</form>
