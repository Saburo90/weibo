<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="{{ route('home') }}">Weibo App</a>
    <ul class="navbar-nav justify-content-end">
      @if(Auth::check())
        <li class="nav-item"><a href="{{ route('users.index') }}" class="nav-link">用户列表</a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" id="triggerId" data-toggle="dropdown"
             aria-haspopup="true"
             aria-expanded="false">
            {{ Auth::user()->name }}
          </a>
          <div class="dropdown-menu" aria-labelledby="triggerId">
            <a class="dropdown-item" href="{{ route('users.show', Auth::user()) }}">个人中心</a>
            <a class="dropdown-item" href="{{ route('users.edit', Auth::user()) }}">编辑资料</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#" id="logout">
              <form action="{{ route('logout') }}" method="POST">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <button class="btn btn-block btn-danger" name="button" type="submit">退出</button>
              </form>
            </a>
          </div>
        </li>
      @else
        <li class="nav-item">
          <a href="{{ route('help') }}" class="nav-link">帮助</a>
        </li>
        <li class="nav-item">
          <a href="{{ route('login') }}" class="nav-link">登录</a>
        </li>
      @endif
    </ul>
  </div>
</nav>
