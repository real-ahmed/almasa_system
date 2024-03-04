@extends('layouts.app')


@section("panel")
    <div class="my-4">


        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row mt-5 align-items-center">
                    <div class="col-md-3 text-center mb-5">
                        <div class="avatar avatar-xl">
                            <img src="{{getImage(getFilePath('avatars') .'/'.$user->id.'.png')}}" alt="..."
                                 class="avatar-img rounded-circle">
                        </div>
                    </div>
                    <div class="col">
                        <div class="row align-items-center">
                            <div class="col-md-7">
                                <h4 class="mb-1">{{$user->name}}</h4>
                                <p class="small mb-3"><span class="badge badge-dark">{{$user->roleName}}</span></p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


        </div>

        <div class="card shadow mb-4">
            <div class="card-header">
                <strong class="card-title">@lang("معلومات الحساب")</strong>
            </div>
            <div class="card-body">
                <form method="post" action="{{route('user.update',$user->id)}}">
                    @csrf
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="simpleinput">@lang("الاسم")</label>
                                <input required @readonly(!auth()->user()->isreceptionist) type="text" value="{{$user->name}}"
                                       id="name" name="name" class="form-control">
                            </div>

                            <div class="form-group mb-3">
                                <label for="simpleinput">@lang("رقم الهاتف")</label>
                                <input required type="phone" value="{{$user->phone}}" id="phone" name="phone"
                                       class="form-control input-phoneeg">
                            </div>
                            @if(!$user->isadmin )
                                <div class="form-group mb-3">
                                    <label for="inputState">@lang("الحالة")</label>
                                    <select id="status" name="status"
                                            @readonly($user->role == 0 || !auth()->user()->isreceptionist) class="form-control">
                                        <option @selected($user->status == 1 )  value="1">@lang("فعال") </option>
                                        <option @selected($user->status == 0 ) value="0">@lang("موقف") </option>
                                    </select>
                                </div>
                            @endif

                        </div> <!-- /.col -->
                        <div class="col-md-6">

                            <div class="form-group mb-3">
                                <label for="simpleinput">@lang("اسم المستخدم (لتسجيل الدخول)")</label>
                                <input required type="text" value="{{$user->email}}" id="email" name="email"
                                       class="form-control">
                            </div>

                            <div class="form-group mb-3">
                                <label for="inputState">@lang("الوظيقة")</label>
                                <select id="role" name="role"
                                        @readonly($user->role == 0 || auth()->user()->role != 0) class="form-control">
                                    @if($user->role == 0 )
                                        <option selected value="0">@lang("مسؤول") </option>
                                    @endif
                                    <option @selected($user->role == 1 )  value="1">@lang("مهندس") </option>
                                    <option @selected($user->role == 2 ) value="2">@lang("موظف استقبال") </option>
                                    <option @selected($user->role == 3 ) value="3">@lang("مسول مخزن") </option>
                                </select>
                            </div>


                        </div>

                    </div>
                    <button type="submit" class="btn btn-primary">@lang("حفظ التغير")</button>
                </form>
            </div>


        </div>

        <div class="card shadow mb-4">
            <div class="card-header">
                <strong class="card-title">@lang("الامان")</strong>
            </div>
            <div class="card-body">
                <form method="post" action="{{route('user.update.password',$user->id)}}">
                    @csrf
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="inputPassword4">@lang("كلمة المرور الجديدة")</label>
                                <input type="password" required name="password" class="form-control" id="password">
                            </div>


                            @if(auth()->user()->id == $user->id)

                                <div class="form-group mb-3">
                                    <label for="inputPassword4">@lang("كلمة المرور الحالية")</label>
                                    <input type="password" required name="current_password" class="form-control"
                                           id="current_password">
                                </div>

                            @endif

                        </div> <!-- /.col -->
                        <div class="col-md-6">

                            <div class="form-group mb-3">
                                <label for="inputPassword4">@lang("اعد كلمة المرور الجديدة")</label>
                                <input type="password" required name="password_confirmation" class="form-control"
                                       id="password_confirmation">
                            </div>

                        </div>

                    </div>
                    <button type="submit" class="btn btn-primary">@lang("حفظ التغير")</button>
                </form>
            </div>


        </div>


        @if(auth()->user()->role == 0 && $user->role != 0)
            <div class="card shadow mb-4">
                <div class="card-header">
                    <strong class="card-title">@lang("معلومات الراتب")</strong>
                </div>
                <div class="card-body">
                    <form method="post" action="{{route('receptionist.employee.update.salary',$user->id)}}">
                        @csrf
                        <div class="row">

                            <div class="col-md-6">

                                <div class="form-group mb-3">
                                    <label for="recipient-name" class="col-form-label">@lang("الراتب الشهري")</label>
                                    <div class="input-group">

                                        <input type="text" name="salary" value="{{$user->employee->salary}}"
                                               class="form-control">
                                        <div class="input-group-append">
                                            <span class="input-group-text">{{$general->money_sign}}</span>
                                        </div>
                                    </div>
                                </div>


                            </div> <!-- /.col -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="salary_date" class="col-form-label">@lang("تاريخ التعين ")
                                        <small>(@lang("يوم القبض"))</small></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control drgpicker" id="date-input1"
                                               name="salary_date"
                                               value="{{date('m/d/Y', strtotime($user->employee->salary_date))}}"
                                               aria-describedby="button-addon2">
                                        <div class="input-group-append">
                                            <div class="input-group-text" id="button-addon-date"><span
                                                    class="fa-solid fa-calendar-days"></span></div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <button type="submit" class="btn btn-primary">@lang("حفظ التغير")</button>
                    </form>
                </div>


            </div>
        @endif


        @if( $user->role ==1 )
            <div class="card shadow mb-4">
                <div class="card-header">
                    <strong class="card-title">@lang("معلومات بونص الصيانة")</strong>
                </div>
                <div class="card-body">
                    <form method="post" action="{{route('receptionist.employee.update.repairtypes.bonus',$user->id)}}">
                        @csrf
                        <div class="row">

                            <div class="col-md-6">
                                @for ($i = 0; $i < ceil(count($repairTypes) / 2); $i++)
                                    <input name="id[{{$i}}]" value="{{$repairTypes[$i]->id}}" hidden="">
                                    <div class="form-group mb-3">
                                        <label for="recipient-name"
                                               class="col-form-label">{{ $repairTypes[$i]->name }}</label>
                                        <div class="input-group">
                                            <input @disabled(!auth()->user()->isreceptionist) type="text"
                                                   name="bonus[{{ $i }}]" value="{{ $repairTypes[$i]->default_bonus }}"
                                                   class="form-control" aria-label="Amount (to the nearest dollar)">
                                            <select
                                                @disabled(!auth()->user()->isreceptionist) name="bonus_type[{{ $i }}]"
                                                class=" input-group-append">
                                                <option @selected($repairTypes[$i]->bonus_type == 0) value="0"
                                                        class="input-group-text">{{$general->money_sign}}</option>
                                                <option @selected($repairTypes[$i]->bonus_type == 1) value="1"
                                                        class="input-group-text">%
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                @endfor
                            </div>

                            <div class="col-md-6">
                                @for ($i = ceil(count($repairTypes) / 2); $i < count($repairTypes); $i++)
                                    <input name="id[{{$i}}]" value="{{$repairTypes[$i]->id}}" hidden="">

                                    <div class="form-group mb-3">
                                        <label for="recipient-name"
                                               class="col-form-label">{{ $repairTypes[$i]->name }}</label>
                                        <div class="input-group">
                                            <input @disabled(!auth()->user()->isreceptionist) type="text"
                                                   name="bonus[{{ $i }}]" value="{{ $repairTypes[$i]->default_bonus }}"
                                                   class="form-control" aria-label="Amount (to the nearest dollar)">
                                            <select
                                                @disabled(!auth()->user()->isreceptionist) name="bonus_type[{{ $i }}]"
                                                class=" input-group-append">
                                                <option @selected($repairTypes[$i]->bonus_type == 0) value="0"
                                                        class="input-group-text">{{$general->money_sign}}</option>
                                                <option @selected($repairTypes[$i]->bonus_type == 1) value="1"
                                                        class="input-group-text">%
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                @endfor
                            </div>

                        </div>
                        <button type="submit" class="btn btn-primary">@lang("حفظ التغير")</button>
                    </form>
                </div>


            </div>
        @endif

    </div>
@endsection
