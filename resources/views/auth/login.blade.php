@extends('layouts.master')

@section('content')
    <div class="wrapper ">
        <div class="">
            <form class="col-lg-3 col-md-4 col-10 mx-auto text-center" method="POST" action="{{ route('login') }}">
                @csrf
                <a class="navbar-brand mx-auto mt-2 flex-fill text-center" href="{{route('home')}}">
                   <img class="w-100 p-4" src="{{getImage(getFilePath('logoIcon') .'/site_logo.png')}}" alt="@lang('image')">
                </a>
                <h1 class="h6 mb-3">@lang("تسجيل الدخول")</h1>
                <div class="form-group">
                    <label for="inputEmail" class="sr-only">@lang("اسم المستخدم")</label>
                    <input type="text" id="email" name="email" class="form-control form-control-lg  @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="@lang("اسم المستخدم")" required="" autocomplete="email" autofocus="">
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="inputPassword" class="sr-only">@lang("كلمة المرور")</label>
                    <input type="password" id="password" name="password" autocomplete="current-password" class="form-control form-control-lg @error('password') is-invalid @enderror" placeholder="@lang("كلمة المرور")" required="">
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>

                <div class="checkbox mb-3">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} value="remember-me">

                        <label class="form-check-label" for="remember" > @lang("تذكرني")</label>
                </div>
                <button class="btn btn-lg btn-primary btn-block" type="submit">@lang("تسجيل الدخول")</button>
                <p class="mt-5 mb-3 text-muted">© {{$year}}</p>
            </form>
        </div>
    </div>

@endsection
