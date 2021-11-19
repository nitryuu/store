@extends('layout.admin')

@section('title')
    User Profile
@endsection

@section('content')
    <div class="breadcrumbs mb-5">
        <div class="breadcrumbs__name">
            User Profile
        </div>
    </div>

    <div class="content__wrapper">
        <div class="content">
            <form action="{{ url('/profile') }}" method="POST">
                @csrf
                <div class="form-group row">
                    <label for="name" class="col-sm-2 col-form-label">Name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="name" value="{{ auth()->user()->name }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="email" class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" name="email" value="{{ auth()->user()->email }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="password" class="col-sm-2 col-form-label">Password</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" name="password"
                            placeholder="kosongkan jika tidak ingin mengubah password">
                    </div>
                </div>
                <button class="btn btn-success">Ubah</button>
            </form>
        </div>
    </div>
@endsection
