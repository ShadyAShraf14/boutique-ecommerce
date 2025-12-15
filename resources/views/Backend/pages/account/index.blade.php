@extends('Backend.inc.master')

@section('content')
<div class="container-fluid mt-4">

    @include('Backend.inc.flash')

    <div class="card shadow mb-4">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">
                Account settings
            </h6>
        </div>

        <div class="card-body">

            <form action="{{ route('admin.account.update') }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>First Name</label>
                        <input type="text" name="first_name" class="form-control"
                               value="{{ old('first_name', $user->first_name) }}" required>
                        @error('first_name')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label>Last Name</label>
                        <input type="text" name="last_name" class="form-control"
                               value="{{ old('last_name', $user->last_name) }}">
                        @error('last_name')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control"
                               value="{{ old('username', $user->username) }}" required>
                        @error('username')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control"
                               value="{{ old('email', $user->email) }}" required>
                        @error('email')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Mobile</label>
                        <input type="text" name="mobile" class="form-control"
                               value="{{ old('mobile', $user->mobile) }}">
                        @error('mobile')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control">
                        <small class="text-muted">اتركه فارغًا لو مش عايزة تغيري الباسورد.</small>
                        @error('password')
                        <small class="text-danger d-block">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label>User Image</label>
                    <div class="mb-2">
                        @php
                            $avatar = $user->avatar
                                ? asset('storage/'.$user->avatar)
                                : url('assets/img/undraw_profile.svg');
                        @endphp
                        <img src="{{ $avatar }}" alt="Avatar"
                             style="height:80px; width:80px; border-radius:50%; object-fit:cover;">
                    </div>
                    <input type="file" name="avatar" class="form-control-file">
                    @error('avatar')
                    <small class="text-danger d-block">{{ $message }}</small>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    Save changes
                </button>
            </form>

        </div>
    </div>

</div>
@endsection
