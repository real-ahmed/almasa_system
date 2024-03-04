@extends("layouts.app")
@section('panel')
    <div class="card shadow">
        <div class="card-body">
            <div class="toolbar row mb-3">
                <div class="col">
                    <form class="form-inline" action="{{route('repair.request.all',['type'=>$type])}}">

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
                        {{--                        <button data-toggle="modal" data-target="#componentModal"--}}
                        {{--                                class="btn btn-primary float-right ml-3"--}}
                        {{--                                type="button">@lang("فاتو")</button>--}}
                    </div>
                </div>
            </div>
            <div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>@lang("رقم")</th>
                        <th>@lang("البراند")</th>
                        <th>@lang("الموديل")</th>
                        <th>@lang("العميل")</th>
                        <th>@lang("المستلم")</th>
                        <th>@lang("المخزن")</th>
                        <th>@lang("الحالة")</th>
                        @if($type == 0 || auth()->user()->isreceptionist )
                            <th>@lang("العمليات")</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($screens as $screen)
                        <tr>
                            <td>{{ $screens->firstItem() + $loop->index}}</td>
                            <td>{{$screen->brand->name}}</td>
                            <td>{{$screen->model}}</td>
                            <td>{{$screen->repairs->first()->customer->name}}</td>
                            <td>{{$screen->repairs->first()->receptionist->name}}</td>
                            <td>{{$screen->warehouse->name}}</td>
                            <td><?php echo $screen->statusName ?></td>
                            @if($type == 0 || auth()->user()->isreceptionist)

                            <td>
                                <a
                                    href="{{route('repair.request.model',$screen->id)}}"
                                    class="btn btn-primary edit">
                                    <i class="fa-solid fa-gears"></i></a>
                            </td>
                            @endif

                        </tr>
                    @endforeach

                    </tbody>
                </table>
                <nav aria-label="Table Paging" class="mb-0 text-muted">
                    @if ($screens->hasPages())
                        {{ $screens->links() }}
                    @endif
                </nav>
            </div>
        </div>
    </div>



@endsection


