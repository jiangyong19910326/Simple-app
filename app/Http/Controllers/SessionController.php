<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
class SessionController extends Controller
{
    // 未登录用户可以访问登录页面权限设置
    public function __construct()
    {
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    // 登陆页面
    public function create()
    {
        return view('sessions.create');
    }

    // 登陆 创建会话
    public function store(Request $request)
    {
        $credentials = $this->validate($request,[
            'email' => 'required|max:255',
            'password' => 'required',
        ]);
//        var_dump($credentials['email']);die();
        //用户名或者邮箱的判断
        $type = filter_var($request->email,FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if($type == 'email')
        {
//            echo 1;die();
            if(Auth::attempt(['email' => $credentials['email'],'password' => $credentials['password']],$request->has('remember')))
            {
                if(Auth::user()->activated) {
                    session()->flash('success', '欢迎回来！');
                    return redirect()->intended(route('users.show', [Auth::user()]));
                } else {
                    Auth::logout();
                    session()->flash('warning', '你的账号未激活，请检查邮箱中的注册邮件进行激活。');
                    return redirect('/');
                }
            } else {
                //验证失败之后
                session()->flash('danger','很抱歉，你输入的邮箱或者密码不匹配。');
                return redirect()->back();
            }
        } elseif($type == 'username') {
            if(Auth::attempt(['name' => $credentials['email'],'password' => $credentials['password']],$request->has('remember')))
            {
                if(Auth::user()->activated) {
                    session()->flash('success', '欢迎回来！');
                    return redirect()->intended(route('users.show', [Auth::user()]));
                } else {
                    Auth::logout();
                    session()->flash('warning', '你的账号未激活，请检查邮箱中的注册邮件进行激活。');
                    return redirect('/');
                }
            } else {
                session()->flash('danger','很抱歉，你输入的邮箱或者用户名都不匹配。');
                return redirect()->back();
            }
        }

    }
    // 登出 logout 销毁会话
    public function destroy()
    {
        Auth::logout();
        session()->flash('success','你已经成功退出！');
        return redirect('login');
    }
}
