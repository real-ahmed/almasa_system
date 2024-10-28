@extends("layouts.app")

@section('panel')
    <div class="card shadow">
        <div class="card-body">
            <div class="toolbar row mb-3">
                <div class="col">
                    <form class="form-inline" action="{{ route('receptionist.repair.deliver.all', ['type' => $type]) }}" method="GET">
                        <div class="form-row">
                            <div class="input-group col-auto">
                                <input type="text" class="form-control" value="{{ request()->input('search') }}"
                                       name="search" placeholder="@lang('بحث')" aria-describedby="button-addon2">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit" id="button-addon2">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- Add Date Filter Inputs -->
                            <div class="input-group col-auto">
                                <input type="date" class="form-control" name="from_date"
                                       value="{{ request()->input('from_date') }}" placeholder="@lang('من تاريخ')">
                            </div>
                            <div class="input-group col-auto">
                                <input type="date" class="form-control" name="to_date"
                                       value="{{ request()->input('to_date') }}" placeholder="@lang('إلى تاريخ')">
                            </div>
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">@lang('فلتر بتاريخ التسليم')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>@lang("رقم")</th>
                        <th>@lang("الرقم المرجعي")</th>
                        <th>@lang("رقم الاستلام المرجعي")</th>
                        <th>@lang("العميل")</th>
                        <th>@lang("هاتف العميل")</th>
                        <th>@lang("موظف الاستقبال")</th>
                        <th>@lang("تاريخ الاستلام")</th>
                        <th>@lang("تاريخ التسليم المتوقع")</th>
                        <th>@lang("تاريخ التسليم")</th>
                        <th>@lang("العمليات")</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($delivers as $deliver)
                        <tr>
                            <td>{{ $delivers->firstItem() + $loop->index }}</td>
                            <td>{{ $deliver->reference_number }}</td>
                            <td>{{ $deliver->repair->reference_number }}</td>
                            <td>{{ $deliver->repair->customer->name }}</td>
                            <td>{{ $deliver->repair->customer->phone }}</td>
                            <td>{{ $deliver->repair->receptionist->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($deliver->repair->receive_date)->format('m/d/y h:iA') }}</td>
                            <td>{{ \Carbon\Carbon::parse($deliver->repair->expected_delivery_date)->format('m/d/y h:iA') }}</td>
                            <td>{{ $deliver->invoice_print_date ? \Carbon\Carbon::parse($deliver->invoice_print_date)->format('m/d/y h:iA') : ' ' }}</td>
                            <td>
                                <a href="{{ route('receptionist.repair.deliver.details', $deliver->id) }}"
                                   class="btn btn-secondary"><i class="fa-solid fa-display"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <nav aria-label="Table Paging" class="mb-0 text-muted">
                    @if ($delivers->hasPages())
                        {{ $delivers->links() }}
                    @endif
                </nav>
            </div>
        </div>
    </div>
@endsection
