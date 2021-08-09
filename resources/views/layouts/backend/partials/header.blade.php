 <nav class="navbar navbar-expand-lg main-navbar">
  <form class="form-inline mr-auto">
    <ul class="navbar-nav mr-3">
      <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
      <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li>
    </ul>

  </form>
  <ul class="navbar-nav navbar-right">
    {{-- <li class="dropdown">
        <a class="nav-link dropdown-toggle waves-effect waves-light" id="navbarDropdownMenuLink-5" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
          <span class="badge badge-danger ml-2">4</span>
          <i class="fas fa-bell"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg-right dropdown-secondary" aria-labelledby="navbarDropdownMenuLink-5">
          <a class="dropdown-item waves-effect waves-light" href="#">Action <span class="badge badge-danger ml-2">4</span></a>
          <a class="dropdown-item waves-effect waves-light" href="#">Another action <span class="badge badge-danger ml-2">1</span></a>
          <a class="dropdown-item waves-effect waves-light" href="#">Something else here <span class="badge badge-danger ml-2">4</span></a>
        </div>
      </li> --}}

    <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
      @if(empty(Auth::user()->avatar) || !file_exists(Auth::user()->avatar))
      <img src="{{ asset('admin/img/profile/profile.jpg') }}" alt="" class="rounded-circle mr-1">
      @else
      <img src="{{ asset(Auth::user()->avatar) }}" alt="" class="rounded-circle mr-1">
      @endif

      <div class="d-sm-none d-lg-inline-block">{{ __('Hi') }}, {{ Auth::user()->name }}</div></a>
      <div class="dropdown-menu dropdown-menu-right">
        <div class="dropdown-title">{{ __('My Id #').Auth::id() }}</div>

        <a href="{{ route('admin.admin.mysettings') }}" class="dropdown-item has-icon">
          <i class="far fa-user"></i> {{ __('Profile') }}
        </a>

        <div class="dropdown-divider"></div>

        @if(session()->get('back_locale') == 'ar')
        <a href="{{route('back_language', 'en')}}" class="dropdown-item has-icon">
          <i class="fas fa-globe"></i> {{ __('English') }}
        </a>
        @else
        <a href="{{route('back_language', 'ar')}}" class="dropdown-item has-icon">
          <i class="fas fa-globe"></i> {{ __('Arabic') }}
        </a>
        @endif

        <div class="dropdown-divider"></div>

        <a href="{{ route('logout') }}"
          onclick="event.preventDefault();
          document.getElementById('logout-form').submit();" class="dropdown-item has-icon text-danger">
          <i class="fas fa-sign-out-alt"></i>  {{ __('Logout') }}
        </a>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
      </form>
    </div>
    </li>
</ul>
</nav>
