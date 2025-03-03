@extends('layouts.app')

@section('title', 'Gang Store - Giỏ Hàng')

@section('content')
<style>
    body {
        overflow-x: hidden;
        height: 100%;
        background-color: whitesmoke;
        background-repeat: no-repeat;
    }

    .card {
        z-index: 0;
        background-color: white;
        padding-bottom: 20px;
        margin-top: 90px;
        margin-bottom: 90px;
        border-radius: 10px;
    }

    #progressbar {
        margin-bottom: 30px;
        overflow: hidden;
        color: #455A64;
        padding-left: 0px;
        margin-top: 30px;
    }

    #progressbar li {
        list-style-type: none;
        font-size: 13px;
        width: 300%;
        float: left;
        position: relative;
        font-weight: 400;
    }

    #progressbar .step0:before {
        font-family: FontAwesome;
        content: "\f10c";
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #progressbar li:before {
        width: 40px;
        height: 40px;
        line-height: 45px;
        display: block;
        font-size: 20px;
        background: #C5CAE9;
        border-radius: 50%;
        margin: auto;
        padding: 0px;
    }

    #progressbar li:after {
        content: '';
        width: 100%;
        height: 12px;
        background: #C5CAE9;
        position: absolute;
        left: 0;
        top: 16px;
        z-index: -1;
    }

    #progressbar li:last-child:after {
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
        position: absolute;
        left: -50%;
    }

    #progressbar li:nth-child(2):after,
    #progressbar li:nth-child(3):after {
        left: -50%;
    }

    #progressbar li:first-child:after {
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
        position: absolute;
        left: 50%;
    }

    #progressbar li:last-child:after {
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
    }

    #progressbar li:first-child:after {
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
    }

    #progressbar li.active:before,
    #progressbar li.active:after {
        background: #651FFF;
    }

    #progressbar li.active:before {
        font-family: FontAwesome;
        content: "\f00c";
    }

    .icon {
        width: 60px;
        height: 60px;
        margin-right: 15px;
    }

    @media screen and (max-width: 992px) {
        .icon-content {
            width: 50%;
        }
    }

    .btn-cancel {
        background-color: limegreen;
        color: white;
        padding: 5px;
        text-align: center;
        border-radius: 0p 5px 5px 0px;
        border: 1px solid limegreen;
        box-shadow: 0px 0px 5px gainsboro;
        font-weight: 600;
        transition: 0.3s;
    }

    .order-qty {
        color: orangered;
    }

    .order-btn {
        background-color: white;
        display: flex;
        justify-content: center;
        text-align: center;
        border: 1px solid black;
        box-shadow: 1px 1px 1px black;
        border-radius: 5px;
        padding: 5px 28px;
        font-weight: 600;
        transition: 0.3s;
    }

    .order-btn:hover {
        color: white;
        background-color: #00b3ff;
    }

    a {
        text-decoration: none;
    }
</style>

<div class="container my-5">
    <div class="row m-0 d-flex justify-content-center">
        <h2 class="col-12 text-center">Bạn Đã Mua <strong class="order-qty">{{ $count }}</strong> Đơn Hàng</h2>
        <a href="{{ route('shipping_process') }}" class="mt-3 mx-2 col-md-2 d-flex justify-content-center">
            <input class="order-btn d-flex justify-content-center" type="submit" value="Đơn Hàng Mới Nhất">
        </a>
        <a href="{{ route('completed_order') }}" class="mt-3 col-md-2 d-flex justify-content-center">
            <input class="order-btn d-flex justify-content-center" type="submit" value="Đơn Hàng Đã Mua">
        </a>
        <a href="{{ route('cancelled_order') }}" class="mt-3 col-md-2 d-flex justify-content-center">
            <input class="order-btn d-flex justify-content-center" type="submit" value="Đơn Hàng Đã Hủy">
        </a>
    </div>
    @foreach($bills as $bill)
    @php
        $total_format = number_format($bill->total, 0, '.', '.');
        $new_date = date("Y-m-d", strtotime($bill->date . ' + 5 day'));
    @endphp
    <div class="card m-0 p-0 mt-3" style="box-shadow: 0px 0px 3px gainsboro;">
        <div class="row d-flex justify-content-between px-3 top pt-3">
            <div class="col-6">
                <h5>Mã Đơn Hàng: <span class="text-primary font-weight-bold">#{{ $bill->id }}</span></h5>
                <p class="mb-0">Dự Kiến Giao Vào Ngày: <span>{{ $new_date }}</span></p>
                <p class="mb-0">Khách Hàng: <span>{{ $bill->name_user }}</span></p>
                <p class="mb-0">Địa Chỉ: <span>{{ $bill->address_user }}</span></p>
            </div>
            <div class="col-6 d-flex flex-column" style="align-items: end;">
                <h5>Sản Phẩm:<br>
                    @foreach($bill->otherBills as $other_bill)
                        <span class="text-primary font-weight-bold">
                            {{ $other_bill->name_pro }} {{ $other_bill->color_product }} X {{ $other_bill->quantity_pro }}
                        </span><br>
                    @endforeach
                </h5>
                <p class="mb-0">Hình Thức Thanh Toán: <span>{{ $bill->payment_name }}</span></p>
                <p class="mb-0">Tổng Thanh Toán: <span>{{ $total_format }}đ</span></p>
                <p class="mb-0">Trạng Thái: <span>{{ $bill->status->name }}</span></p>
            </div>
        </div>
    </div>
@endforeach

</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"></script>
@endsection
