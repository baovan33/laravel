<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;




class AdminUserController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'User']);

            return $next($request);
        });
    }
    function list(Request $request){
        $status = $request->input('status');
        $list_act = [
            'delete' => 'Xoá tạm thời'
        ];
        if( $status =='trash'){
            $list_act = [
                'restore' => 'Khôi phục',
                'forceDelete' => 'Xoá vĩnh viễn',
            ];
            $users = user::onlyTrashed()->paginate(10);
        }else {
            $keyword = "";
            if ($request->input('keyword')) {
                $keyword = $request->input('keyword');
            }
            $users = User::where('name', 'LIKE', "%{$keyword}%")->orwhere('email', 'LIKE', "%{$keyword}%")->paginate(10);
        }

        $count_user_active = user::count();
        $count_user_trash = user::onlyTrashed()->count();

        $count =[ $count_user_active, $count_user_trash];

        return view('admin.user.list', compact('users', 'count', 'list_act'));
    }

    function add() {
        return view('admin.user.add');
    }

    function store(Request $request){

        $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ],
            [
                'required' => ':attribute không được để trống',
                'min'   => ':attribute ít nhất :min kí tự',
                'max'   => ':attribute tối đa :max kí tự',
                'confirmed' => 'Xác nhận mật khẩu không thành công',
            ],
            [
                'name' => 'Tên người dùng',
                'email' => 'Email',
                'password' => 'Mật khẩu',
            ]
        );
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);
//        return $user;
       return redirect()->route('user.list')->with('status', 'Đã thêm thành công !');
    }

    function delete($id) {

        if(Auth::id() != $id) {
            $user = user::find($id);
            $user->delete();
            return redirect()->route('user.list')->with('status', 'Đã xoá thành công !');
        }
        else {
            return redirect()->route('user.list')->with('status', 'Bạn không thể xoá mình  !');

        }
    }

    function action(Request $request) {
        $list_check = $request->input('list_check');

        if($list_check ) {
            foreach ($list_check as $k => $id) {
                if(auth::id() == $id ) {
                    unset($list_check[$k]);
                }
                if(!empty($list_check)) {
                    $act = $request->input('act');
                    if ($act == 'delete') {
                        user::destroy([$list_check]);
                        return redirect()->route('user.list')->with('status', 'Bạn đã xoá tạm thời thành công  !');
                    }
                    if ($act == 'restore')
//                    {
//                        if(empty(user::find($id)->delete_at)) {
//                            return redirect()->route('user.list')->with('status', 'Người dùng này chưa bị xoá  !');
//                        }
//                        else {
                         {
                            User::withTrashed()->whereIn('id', $list_check)
                                ->restore();
                            return redirect()->route('user.list')->with('status', 'Bạn đã khôi phục thành công  !');
                        }
                    if ($act == 'forceDelete') {
                        User::withTrashed()->whereIn('id', $list_check)->forceDelete();
                        return redirect()->route('user.list')->with('status', 'Bạn đã xoá thành công  !');
                    }

                }
                else{
                    return redirect()->route('user.list')->with('status', 'Bạn không thể xoá tài khoản mình !');
                }
            }
        }
        else {
            return redirect()->route('user.list')->with('status', 'Bạn cần chọn tài khoản cần thực hiện !');
        }

    }

    function edit($id) {
        $user = user::find($id);
       return view('admin.user.edit', compact('user'));
    }

    function update(Request $request, $id) {
        $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ],
            [
                'required' => ':attribute không được để trống',
                'min'   => ':attribute ít nhất :min kí tự',
                'max'   => ':attribute tối đa :max kí tự',
                'confirmed' => 'Xác nhận mật khẩu không thành công',
            ],
            [
                'name' => 'Tên người dùng',
                'password' => 'Mật khẩu',
            ]
        );
       user::where('id',$id)->update([
           'name'=>$request->input('name'),
           'password' => Hash::make($request->input('password')),
       ]);
        return redirect()->route('user.list')->with('status', 'Đã cập nhập thành công !');

    }

}
