<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-white py-2">
    <div class="container d-flex flex-wrap justify-content-between">
      <a class="navbar-brand d-flex justify-content-between align-items-center order-lg-0" href="{{ route('product.index') }}">
        <span class="text-uppercase">Gang Store</span>
      </a>

      <div class="order-lg-2 nav-btns d-flex flex-wrap align-items-center">
        <button id="toggleThis" type="button" class="btn position-relative">
          <i class="fa fa-search fs-4"></i>
        </button>

        @if(Auth::check())
        @php
        $countOrder = session('count_order', 0);
        $countCart = session('count_cart', 0);
        @endphp
        <a href="{{route('shipping_process')}}">
          <button type="button" class="btn position-relative">
            <i class="fa-solid fa-truck-fast fs-4"></i>
            <span id="orderCount" class="position-absolute top-0 start-100 translate-middle badge bg-primary">{{ $countOrder }}</span>
          </button>
        </a>
        <a href="{{ route('cart.index') }}">
          <button type="button" class="btn position-relative">
            <i class="fa fa-shopping-cart fs-4"></i>
            <span id="cartCount" class="position-absolute top-0 start-100 translate-middle badge bg-primary">{{ $countCart }}</span>
          </button>
        </a>
        @else
        <a href="">
          <button type="button" class="btn position-relative">
            <i class="fa-solid fa-truck-fast fs-4"></i>
            <span id="orderCount" class="position-absolute top-0 start-100 translate-middle badge bg-primary">0</span>
          </button>
        </a>
        <a href="{{ route('cart.index') }}">
          <button type="button" class="btn position-relative">
            <i class="fa fa-shopping-cart fs-4"></i>
            <span id="cartCount" class="position-absolute top-0 start-100 translate-middle badge bg-primary">0</span>
          </button>
        </a>
        @endif

        <a href="{{ route('dashboard') }}">
          <button type="button" class="btn position-relative">
            <i class="fa-solid fa-circle-user fs-4"></i>
          </button>
        </a>
      </div>

      <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse order-lg-1" id="navMenu">
        <ul class="navbar-nav mx-auto text-center">
          <li class="nav-item px-2 py-2">
            <a class="nav-link text-uppercase text-dark" href="{{ route('product.list') }}">danh mục sản phẩm</a>
          </li>
          <li class="nav-item px-2 py-2">
            <a class="nav-link text-uppercase text-dark" href="{{ route('about') }}">giới thiệu</a>
          </li>
          @if(Auth::check())
          <li class="nav-item px-2 py-2 border-0">
            <a class="nav-link text-uppercase text-dark" href="#">Voucher</a>
          </li>
          @endif
          <li class="nav-item px-2 py-2 border-0">
            <a class="nav-link text-uppercase text-dark" href="{{ route('payment_guide') }}">hướng dẫn thanh toán</a>
          </li>
          <li class="nav-item px-2 py-2 border-0">
            <a class="nav-link text-uppercase text-dark" href="#">tra cứu bảo hành</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <form action="{{ route('product.search') }}" method="GET">
    <div class="input-group">
        <input type="search" name="kyw" class="form-control" placeholder="Tìm kiếm sản phẩm..." value="{{ request('kyw') }}">
        <input type="hidden" name="brand" value="{{ request('brand') }}">
        <input type="hidden" name="cate" value="{{ request('cate') }}">
        <input type="hidden" name="load_type" value="{{ request('load_type') }}">
        <button class="btn btn-primary" type="submit">Tìm</button>
    </div>
</form>


</body>

</html>