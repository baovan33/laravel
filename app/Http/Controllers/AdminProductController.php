<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\product;
use App\Models\product_cat;


class AdminProductController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'Product']);

            return $next($request);
        });
    }
    public function add()
    {
        $product_cats = product_cat::all();
        return view('admin.product.add', compact('product_cats'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => ['required'],
                'price' => ['required', 'numeric'],
                'detail' => ['required'],
                'description' => ['required'],
                'product_cats' => ['required'],
            ],
            [
                'required' => ':attribute không được để trống',
                'numeric' => ':attribute phải là chữ số'

            ],
            [
                'name' => 'Tên sản phẩm',
                'price' => 'Giá',
                'description' => 'Mô tả sản phẩm',
                'detail' => 'Chi tiết sản phẩm',
                'product_cats' => 'Danh mục'
            ]
        );

        $product = product::create([
            'name' => $request->name,
            'detail' => $request->detail,
            'description' => $request->description,
            'price' => $request->price,
            'product_cats_id' => $request->product_cats
        ]);

        return redirect()->route('product.list')->with('status', 'Bạn đã thêm thành công!');
    }

    public function list(Request $request)
    {
        $keyword = "";
        $status = $request->input('status');
        $list_act = [
            'sold' => 'Hết hàng',
            'delete' => 'Xoá sản phẩm'
        ];

        if ($status == 'sold') {
            $list_act = [
                'active' => 'Còn hàng',
                'delete' => 'Xoá sản phẩm'
            ];
            $products = product::onlyTrashed()->paginate(10);
            // tim kiem trong thung ra
//           if($request->input('keyword')) {
//               $products = product::onlyTrashed()
//                   ->where('name','LIKE',"%{$keyword}%")
//                   ->paginate(10);
//           }
        } else {
            if ($request->input('keyword')) {
                $keyword = $request->input('keyword');
            }
            $products = product::where('name', 'LIKE', "%{$keyword}%")
                ->orwhere('price', 'LIKE', "%{$keyword}%")
                ->paginate(5);
        }
        $count_product_active = product::count();
        $count_product_sold = product::onlyTrashed()->count();

        $count = [$count_product_active, $count_product_sold];
        return view('admin.product.list', compact('products', 'list_act', 'count'));
    }

    public function delete($id)
    {
        $product = product::find($id)->forceDelete();
        return redirect()->route('product.list')->with('status', 'Bạn đã xoá thành công!');
    }

    public function action(Request $request)
    {
        $list_check = $request->input('list_check');
        $act = $request->input('act');
        $status = $request->input('status');
        if ($act == 'delete' && $status == 'active' && $status == "") {

            product::whereIN('id', $list_check)->forceDelete();

            //   $products = product::find($list_check);
//         foreach($products as $product)
//         {
//            $id = $product->id;
//             product::find($id)->forceDelete();
//         }
            return redirect()->route('product.list')->with('status', 'Bạn đã xoá thành công!');
        }
        if ($act == 'delete' && $status == 'sold') {
            if ($list_check == '') {
                return redirect()->route('product.list')->with('status', 'Vui lòng chọn sản phẩm!');
            }
            product::withoutTrashed()->whereIN('id', $list_check)->forceDelete();
            return redirect()->route('product.list')->with('status', 'Bạn đã xoá thành công!');
        }
        if ($act == 'sold') {
            if ($list_check == '') {
                return redirect()->route('product.list')->with('status', 'Vui lòng chọn sản phẩm!');
            }
            product::destroy([$list_check]);
            return redirect()->route('product.list')->with('status', 'Bạn đã chuyển thành hết hàng thành công!');
        }
        if ($act == 'active') {
            if ($list_check == '') {
                return redirect()->route('product.list')->with('status', 'Vui lòng chọn sản phẩm!');
            }
            product::withTrashed()
                ->whereIn('id', $list_check)
                ->restore();
            return redirect()->route('product.list')->with('status', 'Bạn đã chuyển thành còn hàng thành công!');
        } else {
            if ($list_check == '') {
                return redirect()->route('product.list')->with('status', 'Vui lòng chọn sản phẩm!');
            }
            return redirect()->route('product.list')->with('status', 'Vui lòng chọn tác vụ');
        }
    }

    public function edit($id)
    {
        $product_cats = product_cat::all();
        $product = product::find($id);
        return view('admin.product.edit',compact('product_cats', 'product'));
    }

    public function update(Request $request, $id) {
       $request->validate(
            [
                'name' => ['required'],
                'price' => ['required', 'numeric'],
                'detail' => ['required'],
                'description' => ['required'],
                'product_cats' => ['required'],
            ],
            [
                'required' => ':attribute không được để trống',
                'numeric' => ':attribute phải là chữ số'

            ],
            [
                'name' => 'Tên sản phẩm',
                'price' => 'Giá',
                'description' => 'Mô tả sản phẩm',
                'detail' => 'Chi tiết sản phẩm',
                'product_cats' => 'Danh mục'
            ]
        );
        product::where('id',$id)->update([
            'name'=>$request->input('name'),
            'price'=>$request->input('price'),
            'description'=>$request->input('description'),
            'detail'=>$request->input('detail'),
            'product_cats_id' => $request->input('product_cats')
        ]);
        return redirect()->route('product.list')->with('status', 'Đã cập nhập thành công !');

    }
    public function cat_list(){
        return view('admin.product.cat_list');
    }
}
