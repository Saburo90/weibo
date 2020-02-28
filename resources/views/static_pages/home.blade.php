@extends('layouts.default')
@section('content')
  <div class="jumbotron">
    <h1>Hello, Laravel!</h1>
    <p class="lead">
      您现在所看到的是 <a href="https://github.com/Saburo90/weibo">Laravel 入门教程 - Weibo App</a> 的示例项目主页
    </p>
    <p>
      一切，将从这里开始。
    </p>
    <p>
      <a class="btn btn-success btn-lg" href="{{ route('signup') }}" role="button">现在注册</a>
    </p>
  </div>
@stop
