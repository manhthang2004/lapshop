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
                <div class="card shadow-lg p-4 rounded-3">
                    <h4 class="text-center mb-4">🔐 Đổi Mật Khẩu</h4>

                    <div class="mb-3">
                        <label for="current_password" class="form-label fw-semibold">Mật Khẩu Cũ</label>
                        <input type="password" class="form-control" name="current_password" required placeholder="Nhập mật khẩu cũ">
                    </div>

                    <div class="mb-3">
                        <label for="new_password" class="form-label fw-semibold">Mật Khẩu Mới</label>
                        <input type="password" class="form-control" name="new_password" required placeholder="Nhập mật khẩu mới">
                    </div>

                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label fw-semibold">Xác Nhận Mật Khẩu Mới</label>
                        <input type="password" class="form-control" name="new_password_confirmation" required placeholder="Nhập lại mật khẩu mới">
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold">
                        <i class="bi bi-arrow-repeat"></i> Đổi Mật Khẩu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<style>
    /* Card chứa form */
    .card {
        background: #ffffff;
        border: none;
        border-radius: 12px;
        box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
    }

    /* Tiêu đề form */
    h4.text-center {
        font-weight: bold;
        color: #007bff;
    }

    /* Định dạng label */
    .form-label {
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }

    /* Input fields */
    .form-control {
        border: 1px solid #ced4da;
        border-radius: 8px;
        padding: 10px;
        font-size: 16px;
        transition: all 0.3s ease-in-out;
    }

    /* Hiệu ứng khi focus vào input */
    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 8px rgba(0, 123, 255, 0.3);
    }

    /* Nút submit */
    .btn-primary {
        background-color: #007bff;
        border: none;
        padding: 12px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: bold;
        color: white;
        cursor: pointer;
        transition: background 0.3s ease-in-out;
    }

    /* Hiệu ứng hover */
    .btn-primary:hover {
        background-color: #0056b3;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .card {
            padding: 20px;
        }

        .btn-primary {
            font-size: 14px;
        }
    }
</style>

@endsection