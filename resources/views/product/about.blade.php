@extends('layouts.app')

@section('content')

<div class="container mt-3">
    <div class="row">
        <div class="col-md-8 mb-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('product.index') }}" class="text-decoration-none">Trang Chủ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Giới Thiệu</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="container pb-3 mb-5" style="background-color: #f8f9fa; border-radius: 10px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);">
    <div class="row align-items-center">
        <div class="col-12 col-md-6 mt-4" data-aos="fade-right">
            <h2 class="text-primary">Gang Store – Thiên Đường Dành Cho Game Thủ 🎮🔥
            </h2>
            <p class="text-muted">
                MT Store là một sàn thương mại điện tử chuyên cung cấp các sản phẩm công nghệ chất lượng, uy tín.
                Chúng tôi luôn không ngừng cải tiến để mang lại trải nghiệm mua sắm tốt nhất cho khách hàng.
            </p>
            <p class="text-muted">
                Với nhiều năm kinh nghiệm trong ngành, chúng tôi cam kết cung cấp sản phẩm chính hãng với mức giá cạnh tranh,
                cùng với chế độ bảo hành và hỗ trợ khách hàng tận tình.
            </p>
            <p class="text-muted">
            <h2>Về Gang Store</h2>
            <b>Gang Store chuyên cung cấp các sản phẩm gaming như:</b><br>
            ✅ Bàn phím cơ, chuột, tai nghe gaming từ các thương hiệu hàng đầu.<br>
            ✅ Ghế gaming, bàn gaming siêu bền, thoải mái.<br>
            ✅ PC, laptop gaming, linh kiện cao cấp dành cho game thủ và streamer.<br>
            ✅ Phụ kiện RGB, tay cầm chơi game, lót chuột giúp góc gaming thêm ấn tượng.
            </p>
            <p class="text-muted">
            <h2>Tại sao chọn Gang Store?</h2><br>
            💎 Sản phẩm chính hãng – Chất lượng đảm bảo, bảo hành đầy đủ.<br>
            ⚡ Giá cạnh tranh – Nhiều ưu đãi hấp dẫn.<br>
            🚀 Dịch vụ tận tâm – Tư vấn chuyên sâu, giao hàng nhanh chóng.<br>
            </p>
        </div>
        <div class="col-12 col-md-6 mt-4 text-center" data-aos="fade-left">
            <img src="{{ asset('/img/user/gioi-thieu.jpg') }}" class="img-fluid rounded shadow-lg" alt="Giới thiệu Gang Store">
        </div>
    </div>
</div>

@endsection

@section('styles')
<style>
    .about-section {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    h2 {
        font-size: 2rem;
        font-weight: 700;
    }

    p {
        font-size: 1.1rem;
        line-height: 1.6;
    }

    .img-fluid {
        max-width: 100%;
        height: auto;
    }
</style>
@endsection