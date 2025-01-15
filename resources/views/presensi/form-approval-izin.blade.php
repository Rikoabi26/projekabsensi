@extends('layouts.admin.tabler')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Form Approval {{$izin->kode_izin ?? '-'}} 
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
            <form action="{{url('/presensi/form-approval-izin/store/'.$izin_workflow->id.'/'.$izin->kode_izin)}}" class="p-4" method="POST">
                @csrf
                <div class="form-group mb-4">
                    <label for="notes">Status</label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="">Pilih Status</option>
                        @foreach ($status_flows as $status_flow)
                            @if (old('status', $izin_workflow->status) == $status_flow)
                                <option value="{{ $status_flow }}" selected>
                                    {{ $status_flow }}
                                </option>
                            @else
                                <option value="{{ $status_flow }}">
                                    {{ $status_flow }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-4">
                    <label for="notes">Notes</label>
                    <textarea name="notes" id="notes" cols="20" rows="10" class="form-control">{{old('notes', $izin_workflow->notes)}}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>

@endsection
@push('myscript')
    <script>
           
    </script>
@endpush
