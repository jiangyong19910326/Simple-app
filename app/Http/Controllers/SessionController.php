<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
class SessionController extends Controller
{
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
        var_dump($credentials['email']);
        //用户名或者邮箱的判断
        $type = filter_var($request->email,FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if($type == 'email')
        {
            if(Auth::attempt(['email' => $credentials['email'],'password' => $credentials['password']],$request->has('remember')))
            {
                //验证成功之后
                session()->flash('success','欢迎回来！');
                return redirect()->route('users.show',[Auth::user()]);
            } else {
                //验证失败之后
                session()->flash('danger','很抱歉，你输入的邮箱或者密码不匹配。');
                return redirect()->back();
            }
        } elseif($type == 'username') {
            if(Auth::attempt(['name' => $credentials['email'],'password' => $credentials['password']],$request->has('remember')))
            {
                //验证成功之后
                session()->flash('success','欢迎回来！');
                return redirect()->route('users.show',[Auth::user()]);
            } else {
                //验证失败之后
                session()->flash('danger','很抱歉，你输入的邮箱或者密码不匹配。');
                return redirect()->back();
            }
        } else {
            session()->flash('danger','很抱歉，你输入的邮箱或者用户名都不匹配。');
            return redirect()->back();
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
