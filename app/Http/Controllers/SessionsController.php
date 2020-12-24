<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionsController extends Controller
{
    public function __construct()
    {
        //只允许未登录情况操作的方法
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    /**
     * 用户登录显示
     * @author 宋佳
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create(){
        return view('sessions.create');
    }


    /**
     * 用户登录请求
     * @author 宋佳
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request){
        $credentials=$this->validate($request,[
            'email' => 'required|email|max:255',
            'password' => 'required'
        ]);

        //根据邮箱密码查询用户是否存在
        if(Auth::attempt($credentials,$request->has('remember'))){
            session()->flash('success','欢迎回来~');
            //重定向到上次页面，没有的话进入默认首页
            $fallback = route('users.show', Auth::user());
            return redirect()->intended($fallback);
        }else{
            session()->flash('danger','很抱歉，您的邮箱和密码不匹配~');
            return redirect()->back()->withInput();
        }
    }


    /**
     * 注销登录
     * @author 宋佳
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(){
        Auth::logout();
        session()->flash('success','您已成功退出~');
        return redirect('login');
    }

}
