@extends('master')

@section('content')
    <div
        style="background-image: url('{{ asset('imgs/login.jpg') }}'); background-size: cover; position: relative"
        class="h-100">
        <div style="position: absolute;background-color: black;height: 100%;width: 100%;opacity: 50%"></div>
        <div class="card shadow-sm"
             style="width: 500px; top: 50%;left: 50%;  transform: translate(-50%, -50%);position: absolute">
            <div class="card-header">
                <h3 class="mb-0">Login</h3>
            </div>
            <div class="card-body">
                <form method="post" action="{{ route('doLogin') }}">
                    @csrf
                    <div class="form-group mb-3">
                            <label>Email</label>
                            <input class="form-control" type="email" name="email">
                    </div>
                    <div class="form-group mb-3">
                        <label>Password</label>
                        <input type="password" class="form-control" name="password">
                    </div>
                    <div class="mb-3">
                        <button id="login-submit" class="btn btn-primary" type="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
