@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-md-4 col-lg-3">
            <div class="bg-light rounded p-4 shadow-sm">
                <div class="text-center mb-4">
                    <img src="{{ asset('img/user/IMG_2396.jpg') }}" alt="Avatar"
                        class="img-fluid rounded-circle border border-3 border-primary" style="width: 120px; height: 120px; object-fit: cover;">
                </div>
                <ul class="nav flex-column">
                    @if ($role == 1)
                        <li class="nav-item">
                            <a href="index.php?act=admin" class="nav-link link-dark ms-5">
                                Truy Cập Trang Admin
                            </a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a href="index.php?act=account_details" class="nav-link link-dark ms-5">
                            Thông Tin Tài Khoản
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="index.php?act=update_account" class="nav-link link-dark ms-5">
                            Chỉnh Sửa Thông Tin
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="index.php?act=change_password" class="nav-link link-dark ms-5">
                            Đổi Mật Khẩu
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link link-dark ms-5">
                            Sản Phẩm Đã Thích
                        </a>
                    </li>
                    <hr class="m-0">
                    <li class="nav-item">
                        <a href="index.php?act=log_out" class="nav-link link-dark ms-5">
                            Đăng Xuất
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-md-8 col-lg-9 col-xl-8">
            <form method="POST" action="{{ route('change_password') }}">
                @csrf
                <div class="mb-3 mt-3">
                    <label for="current_password" class="form-label">Mật Khẩu Cũ</label>
                    <input type="password" class="form-control" name="current_password" required>
                </div>
                <div class="mb-3">
                    <label for="new_password" class="form-label">Mật Khẩu Mới</label>
                    <input type="password" class="form-control" name="new_password" required>
                </div>
                <div class="mb-3">
                    <label for="new_password_confirmation" class="form-label">Xác Nhận Mật Khẩu Mới</label>
                    <input type="password" class="form-control" name="new_password_confirmation" required>
                </div>

                <button type="submit" class="btn btn-primary">Đổi</button>
            </form>
        </div>
    </div>
</div>
@endsection
