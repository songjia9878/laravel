<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class UsersController extends Controller
{
    public function __construct()
    {
        //权限校验
        $this->middleware('auth', [
            //show,create,store以外的方法都要进行权限校验
            'except' => ['show', 'create', 'store','index','confirmEmail']
        ]);

        //只允许未登录情况操作的方法
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }


    /**
     * 用户列表显示
     * @author 宋佳
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(){
        $users = User::paginate(6);
        return view('users.index', compact('users'));
    }


    /**
     * 用户注册显示
     * @author 宋佳
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create(){
        return view('users.create');
    }

    /**
     * 用户显示
     * @author 宋佳
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }


    /**
     * 用户注册请求
     * @author 宋佳
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request){
        $this->validate($request,[
            'name1' => 'required|unique:users|max:50',
            'email22' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);

        $user=User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password),
        ]);

        $this->sendEmailConfirmationTo($user);
        session()->flash('success', '验证邮件已发送到你的注册邮箱上，请注意查收。');

//        Auth::login($user);
//        session()->flash('success','欢迎，您将在这里开启一段新的旅程~');
//        return redirect()->route('users.show',[$user]);
    }


    /**
     * 用户编辑显示
     * @author 宋佳
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(User $user){
        //权限校验
        $this->authorize('update',$user);
        return view('users.edit',compact('user'));
    }



    /***
     * 用户编辑请求
     * @author 宋佳
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(User $user,Request $request){
        //权限校验
        $this->authorize('update',$user);
        $this->validate($request, [
            'name' => 'required|max:50',
            'password' => 'nullable|confirmed|min:6'
        ]);

        $data = [];
        $data['name'] = $request->name;
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);

        session()->flash('success', '个人资料更新成功！');

        return redirect()->route('users.show', $user);
    }


    /**
     * 管理员删除用户
     * @author 宋佳
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(User $user){
        $this->authorize('destroy',$user);
        $user->delete();
        session()->flash('success','删除成功！');
        return back();
    }


    /**
     * 邮件发送功能
     * @author 宋佳
     * @param $user
     */
    protected function sendEmailConfirmationTo($user){
        $view = 'emails.confirm';
        $data = compact('user');
        $from = 'summer@example.com';
        $name = 'Summer';
        $to = $user->email;
        $subject = "感谢注册 Weibo 应用！请确认你的邮箱。";
        Mail::send($view, $data, function ($message) use ($from, $name, $to, $subject) {
            $message->from($from, $name)->to($to)->subject($subject);
        });
    }


    /**
     * 邮箱激活
     * @author 宋佳
     * @param $token
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirmEmail($token)
    {
        $user = User::where('activation_token', $token)->firstOrFail();

        $user->activated = true;
        $user->activation_token = null;
        $user->save();

        Auth::login($user);
        session()->flash('success', '恭喜你，激活成功！');
        return redirect()->route('users.show', [$user]);
    }

}
