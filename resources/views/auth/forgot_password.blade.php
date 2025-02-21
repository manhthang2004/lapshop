@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Quên Mật Khẩu</h2>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Nhập Email Đăng Ký</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Gửi Liên Kết Đặt Lại Mật Khẩu</button>
    </form>
</div>
@endsection
