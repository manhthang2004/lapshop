@extends('layouts.app')

@section('content')
<div class="container">
    @if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    <div class="card shadow-lg p-4 rounded-4 border-0" style="max-width: 400px; margin: auto;">
        <h4 class="text-center mb-3">Đặt Lại Mật Khẩu</h4>
        <p class="text-muted text-center">Nhập email và mật khẩu mới của bạn</p>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-3">
                <label class="form-label">Email</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-envelope"></i>
                    </span>
                    <input type="email" name="email" class="form-control" placeholder="Nhập email..." required>
                </div>
                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Mật Khẩu Mới</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu mới..." required>
                </div>
                @error('password') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Nhập Lại Mật Khẩu</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-lock-fill"></i>
                    </span>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Nhập lại mật khẩu..." required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-arrow-repeat"></i> Đổi Mật Khẩu
            </button>
        </form>
    </div>
</div>
@endsection