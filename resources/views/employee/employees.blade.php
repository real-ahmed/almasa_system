@extends("layouts.app")

@section("panel")
    <div class="card shadow">
        <div class="card-body">
            <div class="toolbar row mb-3">
                <div class="col">
                    <form class="form-inline">
                        <div class="form-row">
                            <div class="input-group col-auto">
                                <input type="text" class="form-control" value="{{request()->input('search')}}"
                                       name="search" placeholder="@lang("بحث")" aria-describedby="button-addon2">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit" id="button-addon2"><i
                                            class="fa-solid fa-magnifying-glass"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col ml-auto">
                    <div class="dropdown float-right">
                        <button data-toggle="modal" data-target="#varyModal" class="btn btn-primary float-right ml-3"
                                type="button">@lang("+ موظف جديد")</button>
                    </div>
                </div>
            </div>
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>@lang("رقم")</th>
                    <th>@lang("الاسم")</th>
                    <th>@lang("اسم المستخدم")</th>
                    <th>@lang("رقم الهاتف")</th>
                    <th>@lang("الراتب الثابت")</th>
                    <th>@lang("بونص الشهر")</th>
                    <th>@lang("خصم الشهر")</th>

                    <th>@lang("مجموع الراتب")</th>
                    <th>@lang("تاريخ التعين")</th>
                    <th>@lang("نوع الوظيفة")</th>
                    <th>@lang("الحالة")</th>
                    <th>@lang("العمليات")</th>

                </tr>
                </thead>
                <tbody>
                @foreach($employees as $employee)
                    <tr>
                        <td>{{$employees->firstItem() + $loop->index}}</td>
                        <td>{{$employee->name}}</td>
                        <td>{{$employee->email}}</td>
                        <td>{{$employee->phone}}</td>
                        <td><a href="{{route('receptionist.user.advance.payment.all',$employee->id)}}">{{showAmount($employee->employee->salary)}} {{$general->money_sign}}</a></td>
                        <td><span>{{showAmount($employee->MonthlyBonus)}} {{$general->money_sign}}</span></td>
                        <td><span>{{showAmount($employee->MonthlyDeduction)}} {{$general->money_sign}}</span></td>
                        <td>{{showAmount($employee->MonthlyBonus + $employee->employee->salary - $employee->MonthlyDeduction)}} {{$general->money_sign}}</td>
                        <td>{{ showDateTime($employee->salary_date,'m/d/y') .$employee->shouldDistributeSalaryToday()}} </td>
                        <td>{{$employee->rolename}}</td>
                        <td>{!! $employee->statusname !!}</td>
                        <td>
                            <a href="{{route("receptionist.user.setting",$employee->id)}}" class="btn btn-primary "><i
                                    class="fa-solid fa-pen"></i></a>

                            <a data-toggle="modal" data-target="#bonusModal"
                               data-employee_id="{{$employee->id}}"
                               data-id=""
                               class="btn btn-success edit-bonus"><i class="fa-solid fa-hand-holding-dollar"></i></a>

                            <a data-toggle="modal" data-target="#deductionModal"
                               data-employee_id="{{$employee->id}}"
                               data-id=""
                               class="btn btn-danger edit-deduction"><i class="fa-solid fa-circle-minus"></i></a>


                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <nav aria-label="Table Paging" class="mb-0 text-muted">
                @if ($employees->hasPages())
                    {{ $employees->links() }}
                @endif
            </nav>

        </div>
    </div>

    @include("employee.model")
    @include("models.bonus")
    @include("models.deduction")


@endsection

