<nav class="topnav navbar navbar-light">
    <button type="button" class="navbar-toggler text-muted mt-2 p-0 mr-3 collapseSidebar">
        <i class="fa-solid fa-bars navbar-toggler-icon"></i>
    </button>

    <style>
        /* CSS for centering the paragraph */
        .logo-container {
            max-width: 200px;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .logo-container p {
            font-weight: normal;
            color: #9c4053bf;
            padding: 0;
            margin: 0;
            font-size: 10px;
        }
        /* CSS for hiding the logo on smaller screens */
        @media (max-width: 767px) {
            .logo-container {
                display: none;
            }
        }
    </style>
<div class="logo-container" style="max-width: 200px;padding: 0;">
    <a class="navbar-brand mx-auto mt-2 flex-fill text-center" href="{{route('home')}}">
        <img class="w-100" src="{{getImage(getFilePath('logoIcon') .'/site_logo.png')}}" alt="@lang('image')">
    </a>
    <p >@foreach($general->phone as $index => $phone)
            {{ $phone }}@if($index < count($general->phone) - 1)- @endif
        @endforeach</p>

</div>
{{--    <form class="form-inline mr-auto searchform text-muted">--}}
{{--        <input class="form-control mr-sm-2 bg-transparent border-0 pl-4 text-muted" type="search" placeholder="Type something..." aria-label="Search">--}}
{{--    </form>--}}
    <ul class="nav">
{{--        <li class="nav-item">--}}
{{--            <a class="nav-link text-muted my-2" href="#" id="modeSwitcher" data-mode="dark">--}}
{{--                <i class="fa-solid fa-moon"></i>--}}
{{--            </a>--}}
{{--        </li>--}}
        <li class="nav-item">
            <a class="nav-link text-muted my-2" href="{{ route('logout') }}"
               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                <i class="fa-solid fa-power-off"></i>
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>

        </li>
        <li class="nav-item nav-notif">
            <a class="nav-link text-muted my-2" href="./#" data-toggle="modal" data-target=".modal-notif">
                <span class="fe fe-bell fe-16"></span>
                <span class="dot dot-md bg-success"></span>
            </a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-muted pr-0" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <span class="avatar avatar-sm mt-2">
                <img src="{{getImage(getFilePath('avatars') .'/'.auth()->user()->id.'.png')}}" alt="..." class="avatar-img rounded-circle">
              </span>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                <a class="dropdown-item" href="{{route("user.setting")}}">@lang("اعدادات الحساب")</a>
            </div>
        </li>
    </ul>
</nav>


