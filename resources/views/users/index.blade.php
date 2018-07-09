@extends('layouts.default')
@section('title', '所有用户')

@section('content')
    <div class="col-md-offset-2 col-md-8">
        <h1>所有用户</h1>
        <ul class="users">
            <ul class="users">
                @foreach ($users as $user)
                    @include('users._users')
                @endforeach
            </ul>
        </ul>
        {!! $users->render() !!}
    </div>
@stop