@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            @if(session('status'))
                <div class="alert alert-success">{{session('status')}}</div>
            @endif
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                <h5 class="m-0 ">Danh sách sản phẩm</h5>
                <div class="form-search form-inline">
                    <form action="#">
                        <input type="text" name="keyword" value="{{Request()->keyword}}" class="form-control form-search" placeholder="Tìm kiếm">
                        <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary">
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="analytic">
                    <a href="{{request()->fullUrlWithQuery(['status' => 'active'])}}" class="text-primary">Còn hàng<span class="text-muted">({{$count[0]}})</span></a>
                    <a href="{{request()->fullUrlWithQuery(['status' => 'sold'])}}" class="text-primary">Hết hàng<span class="text-muted">({{$count[1]}})</span></a>
                </div>
                <form action="{{route('product.action')}}">
                    <div class="form-action form-inline py-3">
                        <select class="form-control mr-1" id="" name="act">
                            <option value=""></option>
                            @foreach($list_act as $k => $act)
                                <option value="{{$k}}">{{$act}}</option>
                            @endforeach
                        </select>
                        <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary">
                    </div>
                    <table class="table table-striped table-checkall">
                        <thead>
                        <tr>
                            <th scope="col">
                                <input name="checkall" type="checkbox">
                            </th>
                            <th scope="col">#</th>
                            <th scope="col">Ảnh</th>
                            <th scope="col">Tên sản phẩm</th>
                            <th scope="col">Giá</th>
                            <th scope="col">Danh mục</th>
                            <th scope="col">Ngày tạo</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Tác vụ</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $t = 0;
                        @endphp
                        @foreach($products as $product)
                            @php
                                $t++;
                            @endphp
                            <tr class="">
                                <td>
                                    <input type="checkbox" name="list_check[]" value="{{$product->id}}">
                                </td>
                                <td>{{$t}}</td>
                                <td><img src="http://via.placeholder.com/80X80" alt=""></td>
                                <td><a href="#">{{$product->name}}</a></td>
                                <td>{{number_format($product->price, 0, ',' , '.')}}</td>
                                <td>{{$product->product_cat->name}}</td>
                                <td>{{$product->created_at}}</td>
                                @if(Request()->input('status')   == 'sold')
                                    <td><span class="badge badge-secondary">Hết hàng</span></td>
                                @else
                                    <td><span class="badge badge-success">Còn hàng</span></td>
                                @endif
                                <td>
                                    <a href="{{route('product.edit', $product->id)}}" class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                       data-toggle="tooltip" data-placement="top" title="Edit"><i
                                            class="fa fa-edit"></i></a>
                                    <a href="{{route('product.delete', $product->id)}}"
                                       onclick="return confirm('Bạn có muốn xoá sản phẩm này?')"
                                       class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                       data-toggle="tooltip" data-placement="top" title="Delete"><i
                                            class="fa fa-trash"></i></a>
                                </td>
                            </tr>

                        @endforeach
                        </tbody>
                    </table>
                </form>
                {{$products->links("pagination::bootstrap-4")}}
            </div>
        </div>
    </div>
@endsection
