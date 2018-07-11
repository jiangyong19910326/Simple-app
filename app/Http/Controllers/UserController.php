<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests;
use Auth;
use Mail;
class UserController extends Controller
{
    // 权限设置
    public function __construct()
    {
        $this->middleware('auth', [
            'except' => ['show', 'create', 'store','index','confirmEmail']
        ]);

        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }
    public function index()
    {
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }
    // 注册页面
    public function create()
    {
        return view('users.create');
    }

    //
//    public function show(User $user)
//    {
//        return view('users.show',compact('user'));
//    }

    //用户注册提交
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6|max:18',
            'g-recaptcha-response' => 'required|captcha', //验证码注释
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        $this->sendEmailConfirmationTo($user);
//        Auth::login($user);
        session()->flash('success', '验证邮件已发送到你的注册邮箱上，请注意查收。');
        return redirect()->route('users.show',[$user]);
    }

    // 用户资料修改页面
    public function edit(User $user)
    {
        // 用户修改授权策略
        $this->authorize('update', $user);
        return view('users.edit',compact('user'));
    }

    // 用户资料的修改操作
    public function update(User $user,Request $request)
    {
        $this->validate($request,[
            'name' => 'required|max:50',
            'password' => 'nullable|confirmed|min:6',
        ]);

        $this->authorize('update', $user);

        $data['name'] = $request->name;

        if($request->password)
        {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        session()->flash('success','个人资料更新成功');

        return redirect()->route('users.show',$user->id);
    }

    //删除用户
    public function destroy(User $user)
    {
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success', '成功删除用户！');
        return back();
    }

    //激活成功跳转到用户页面
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

    protected function sendEmailConfirmationTo($user)
    {
        $view = 'emails.confirm';
        $data = compact('user');
        $to = $user->email;
        $subject = "感谢注册 Sample 应用！请确认你的邮箱。";

        Mail::send($view, $data, function ($message) use (  $to, $subject) {
            $message->to($to)->subject($subject);
        });
    }

    //获取用户微博
    public function show(User $user)
    {
        $statuses = $user->statuses()->orderBy('created_at','desc')->paginate('30');

        return view('users.show',compact('user','statuses'));
    }
}
