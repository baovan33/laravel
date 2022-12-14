@extends('layouts.admin');
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Thêm sản phẩm
            </div>
            <div class="card-body">
                <form action="{{route('product.update', $product->id)}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="name">Tên sản phẩm</label>
                                <input class="form-control" type="text" name="name" id="name" value="{{$product->name}}">
                                @error('name')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="price">Giá</label>
                                <input class="form-control" type="text" name="price" id="price" value="{{$product->price}}">
                                @error('price')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="description">Mô tả sản phẩm</label>
                                <textarea name="description" value="{{$product->name}}" class="form-control" id="description" cols="30" rows="5" ></textarea>
                                @error('description')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="detail">Chi tiết sản phẩm</label>
                        <textarea name="detail" class="form-control" id="detail" cols="30" rows="5" value="{{$product->detail}}"></textarea>
                        @error('detail')
                        <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>


                    <div class="form-group">
                        <label for="product-cats">Danh mục</label>
                        <select class="form-control" id="" name="product_cats">
                            @foreach($product_cats as $product_cat)
                                <option  value="{{$product_cat->id}}">{{$product_cat->name}}</option>
                            @endforeach

                        </select>

                    </div>
                    <button type="submit" class="btn btn-primary">Cập nhập</button>
                </form>
            </div>
        </div>
    </div>
@endsection
