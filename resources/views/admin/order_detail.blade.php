@extends('admin_layout')
@section('admin_content')
<div class="table-agile-info">
  <div class="panel panel-default">
    <div class="panel-heading">
      Thông tin người đặt
    </div>
    <div class="table-responsive">
      <table class="table table-striped b-t b-light">
        <thead>
          <tr>
            <th>Tên người gửi</th>
            <th>Số ĐT</th>
            <!-- <th>Ngày Thêm</th> -->
            <th style="width:30px;"></th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              {{$getOrderById['name']}}
            </td>
             <td>
              {{$getOrderById['phone']}}
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
   <div class="panel panel-default">
    <div class="panel-heading">
      Thông tin vận chuyển
    </div>
    <div class="table-responsive">
      <table class="table table-striped b-t b-light">
        <thead>
          <tr>
            <th>Người nhận</th>
            <th>Số ĐT</th>
            <th>Địa chỉ</th>
            <!-- <th>Ngày Thêm</th> -->
            <th style="width:30px;"></th>
          </tr>
        </thead>
        <tbody>
           <tr>
            <td>
              {{$getOrderById['shipping_name']}}
            </td>
             <td>
              {{$getOrderById['shipping_phone']}}
            </td>
             <td>
              {{$getOrderById['shipping_address']}}
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <div class="panel panel-default">
      <div class="panel-heading">
        Chi tiết đơn hàng
      </div>
      <div class="table-responsive">
        <table class="table table-striped b-t b-light">
          <thead>
            <tr>
              <th>Tên sản phẩm</th>
              <th>Tổng tiền</th>
              <th>Số lượng</th>
              <!-- <th>Ngày Thêm</th> -->
              <th style="width:30px;"></th>
            </tr>
          </thead>
          <tbody>
            @foreach($getOrderById['product_detail'] as $item)
            <tr>
              <td>{{$item->product_name}}</td>
              <td>{{$item->product_price*$item->product_sales_quantity}}</td>
              <td>{{$item->product_sales_quantity}}</td>
            </tr>
             
          @endforeach
          </tbody>
        </table>
      </div>
    </div>
</div>

@endsection