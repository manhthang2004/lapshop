@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h2 class="text-primary">Hướng Dẫn Thanh Toán</h2>

            <p>Chúng tôi cung cấp nhiều phương thức thanh toán khác nhau để thuận tiện cho bạn:</p>

            <h4>1. Thanh toán bằng tiền mặt khi nhận hàng (COD)</h4>
            <p>Thanh toán trực tiếp khi nhận hàng từ nhân viên giao hàng.</p>

            <h4>2. Thanh toán qua thẻ ngân hàng</h4>
            <p>Hỗ trợ các loại thẻ VISA, MasterCard, ATM nội địa.</p>

            <h4>3. Thanh toán qua ví điện tử</h4>
            <p>Chúng tôi hỗ trợ các ví điện tử như Momo, ZaloPay, ViettelPay.</p>

            <h4>4. Chuyển khoản ngân hàng</h4>
            <p>Thông tin tài khoản ngân hàng sẽ được hiển thị khi bạn chọn phương thức thanh toán này.</p>

            <p class="mt-3">Nếu có bất kỳ thắc mắc nào, vui lòng liên hệ với chúng tôi.</p>
        </div>

        <div class="col-md-6 text-center">
            <img src="{{ asset('/img/user/thanhtoan2.jpg') }}" class="img-fluid rounded shadow" alt="Hướng Dẫn Thanh Toán">
        </div>
    </div>
</div>
@endsection