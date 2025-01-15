@extends('layouts.admin.tabler')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Workflow
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
            <form action="{{url('/workflow/update/'.$workflow->id)}}" class="p-4" method="POST">
                @method('PUT')
                @csrf
                <div class="form-group mb-4">
                    <label for="name">Nama Workflow</label>
                    <input type="text" id="name" value="{{old('name', $workflow->name)}}" name="name" class="form-control"  required>
                </div>
                <div class="form-group mb-4">
                    <label for="role_id">Role</label>
                    <select name="role_id" id="role_id" class="form-select" required>
                        <option value="">Pilih Role</option>
                        @foreach ($roles as $role)
                            @if (old('role_id', $workflow->role_id) == $role->id)
                                <option value="{{$role->id}}" selected>{{$role->name}}</option>
                            @else
                                <option value="{{$role->id}}">{{$role->name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-4">
                    <label for="ordinal">Urutan</label>
                    <input type="text" id="ordinal" value="{{old('ordinal', $workflow->ordinal)}}" name="ordinal" class="form-control"  required>
                </div>
                <div class="form-group mb-4">
                    <label for="status">Status</label>
                    <textarea name="status" id="status" cols="20" rows="10" class="form-control">{{old('status', $workflow->status)}}</textarea>
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
