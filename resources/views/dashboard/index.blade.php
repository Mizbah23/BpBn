@extends('layouts.app')

@section('page-title', __('Dashboard'))
@section('page-heading', __('Dashboard'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('Dashboard')
    </li>
@stop

@section('content')
    @include('partials.messages')
    @if(auth()->user()->role_id==2)
    @if(auth()->user()->barber->is_verified==0)
    <div class="alert alert-warning d-flex align-items-center">
        <div class="spinner-grow spinner-border-sm me-2" role="status">
            <span class="visually-hidden"></span>
        </div>
        <span>
            আপনার পূরণকৃত ফর্মটি বর্তমানে পর্যালোচনা করা হচ্ছে। পর্যালোচনা করার পর আপনার ফরমে পূরণকৃত বিকাশ নম্বরে রিওয়ার্ড পেয়ে যাবেন।
        </span>
    </div>
    @elseif(auth()->user()->barber->is_verified==2)
    <div class="alert alert-warning d-flex align-items-center">
        <div class="spinner-grow spinner-border-sm me-2" role="status">
            <span class="visually-hidden"></span>
        </div>
        <span>
            দুঃখিত! আপনার ফর্মটি পর্যালোচনা করার পরে কিছু সমস্যা পাওয়া গেছে। অনুগ্রহ করে ফর্মটি পুনরায় জমা দিন।  <a href="{{route('profile')}}"><button class="btn btn-primary mt-2">এখানে ক্লিক করুন</button></a>
        </span>
    </div>
    @endif
    @endif
{{-- @dd( auth()->user()->role_id) --}}
@if(auth()->user()->role_id==2)
<div class="row">

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <a href="{{ route('profile') }}" class="text-center no-decoration">
                    <div class="icon my-3 display-3 fw-bold">
                       {{ auth()->user()->bonus->total_earnings}} ৳
                    </div>
                    <p class="lead mb-0">@lang('মোট জমা')</p>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <a href="{{ route('profile') }}" class="text-center no-decoration">
                    <div class="icon my-3 display-3 fw-bold">
                        {{ auth()->user()->bonus->total_withdrawals}} ৳
                    </div>
                    <p class="lead mb-0">@lang('মোট উত্তোলন')</p>
                </a>
            </div>
        </div>
    </div>


    @php
        $refer_code= Vanguard\User::where('id',auth()->user()->id)->first();
        $count_referal=Vanguard\User::where('input_referral_code',$refer_code->promo_code)->count();

    @endphp

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <a href="{{ route('profile') }}" class="text-center no-decoration">
                    <div class="icon my-3 display-3 fw-bold">
                        {{ $count_referal}}
                    </div>
                    <p class="lead mb-0">@lang('রেফার কোড ব্যবহার হয়েছে')</p>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card text-center shadow-lg p-4" style="background: linear-gradient(135deg, #ff7eb3, #ff758c); border-radius: 12px; color: white;">
            <div class="card-body">

                    <div class="icon my-3 display-3 fw-bold" style="font-size: 5.5rem; text-shadow: 2px 2px 8px rgba(0,0,0,0.2);">
                        {{ auth()->user()->promo_code }}
                    </div>
                    <p class="lead mb-0" style="font-weight: bold;">
                        @lang('আপনার রেফারেল কোড | শেয়ার করুন এবং রেফারের মাধ্যমে আয় করুন |')
                    </p>
                    <button class="btn btn-primary mt-2" onclick="copyReferralCode()">Copy Code</button>

            </div>
        </div>
    </div>




</div>

{{-- <!-- Referral Message -->
<div class="mt-4 text-center">
    <div class="alert alert-info p-3 rounded shadow-sm">
        <h5 class="mb-2">🚀 রেফার করে আরও বেশি আয় করুন! 🎉</h5>
        <p class="mb-1">
            আপনার রেফারেল কোড <strong>{{ auth()->user()->promo_code }}</strong> অন্যদের সাথে শেয়ার করুন এবং রেফারের মাধ্যমে আয় করুন!
        </p>
        <button class="btn btn-primary mt-2" onclick="copyReferralCode()">Copy Code</button>
    </div>
</div> --}}


@else
<div class="row">
    <div class="col-md-6">
        <div class="card widget">
            <div class="card-body">
                <div class="row">
                    <div class="p-3 text-primary flex-1">
                        <i class="fa fa-cart-arrow-down fa-3x"></i>
                    </div>
                    @php
                    $total_deposit=Vanguard\User::leftjoin('bonuses','bonuses.user_id','=','users.id')->sum('total_earnings');
                    @endphp
                    <div class="pr-3">
                        <h2 class="text-right">{{ number_format($total_deposit) }}</h2>
                        <div class="text-muted">@lang('Total Deposit')</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card widget">
            <div class="card-body">
                <div class="row">
                    <div class="p-3 text-primary flex-1">
                        <i class="fa fa-share fa-3x"></i>
                    </div>
                    @php
                    $total_withdraws=Vanguard\User::leftjoin('bonuses','bonuses.user_id','=','users.id')->sum('total_withdrawals');
                    @endphp
                    <div class="pr-3">
                        <h2 class="text-right">{{ number_format($total_withdraws) }}</h2>
                        <div class="text-muted">@lang('Total Withdraw')</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @foreach (\Vanguard\Plugins\Vanguard::availableWidgets(auth()->user()) as $widget)
        @if ($widget->width)

            <div class="col-md-{{ $widget->width }}">
        @endif
            {!! app()->call([$widget, 'render']) !!}
        @if($widget->width)
            </div>
        @endif
    @endforeach

</div>
@endif

@stop

@section('scripts')
    @foreach (\Vanguard\Plugins\Vanguard::availableWidgets(auth()->user()) as $widget)
        {{-- @dd($widget) --}}
        @if (method_exists($widget, 'scripts'))
            {!! app()->call([$widget, 'scripts']) !!}
        @endif
    @endforeach

    <script>
        function copyReferralCode() {
            const promoCode = "{{ auth()->user()->promo_code }}";
            navigator.clipboard.writeText(promoCode).then(() => {
                alert("Referral code copied!");
            });
        }
    </script>
@stop
