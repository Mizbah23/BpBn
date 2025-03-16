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
    @if(auth()->user()->role_id==2 && auth()->user()->barber->is_verified==0)
    <div class="alert alert-warning">

        {{ 'আপনার পূরণকৃত ফর্মটি বর্তমানে পর্যালোচনা করা হচ্ছে। পর্যালোচনা করার পর আপনার ফরমে পূরণকৃত বিকাশ নম্বরে রিওয়ার্ড পেয়ে যাবেন।' }}
    </div>
    @endif
{{-- @dd( auth()->user()->role_id) --}}
@if(auth()->user()->role_id==2)
<div class="row">

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <a href="{{ route('profile') }}" class="text-center no-decoration">
                    <div class="icon my-3 display-3 fw-bold">
                       {{ auth()->user()->bonus->total_earnings}} ৳
                    </div>
                    <p class="lead mb-0">@lang('Deposit Amount')</p>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <a href="{{ route('profile') }}" class="text-center no-decoration">
                    <div class="icon my-3 display-3 fw-bold">
                        {{ auth()->user()->bonus->total_withdrawals}} ৳
                    </div>
                    <p class="lead mb-0">@lang('Withdraw Amount')</p>
                </a>
            </div>
        </div>
    </div>




</div>

<!-- Referral Message -->
<div class="mt-4 text-center">
    <div class="alert alert-info p-3 rounded shadow-sm">
        <h5 class="mb-2">🚀 রেফার করে আরও বেশি আয় করুন! 🎉</h5>
        <p class="mb-1">
            আপনার রেফারেল কোড <strong>{{ auth()->user()->promo_code }}</strong> অন্যদের সাথে শেয়ার করুন এবং পুরষ্কার জিতে নিন!
        </p>
        <button class="btn btn-primary mt-2" onclick="copyReferralCode()">Copy Code</button>
    </div>
</div>


@else
<div class="row">
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
