@extends('layouts.master')

@section('content')
    <!-- page-wrapper start -->
    <div class="wrapper">
        @include('partials.sidenav')
        @include('partials.topnav')

        <main role="main" class="main-content">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <h2 class="h3 mb-4 page-title">{{__($pageTitle) ?? ''}}</h2>

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if(session('successMessages'))
                            @foreach(session('successMessages') as $message)
                                <div class="alert alert-success">
                                    {{ $message }}
                                </div>
                            @endforeach
                        @endif
                @yield('panel')


                    </div><!-- bodywrapper__inner end -->
                </div><!-- body-wrapper end -->
            </div>
        </main>
    </div>




@endsection
