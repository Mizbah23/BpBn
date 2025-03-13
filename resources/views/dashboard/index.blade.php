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
    <div class="alert alert-warning">
        <i class="fa fa-shield"></i>
        {{ 'আপনার পূরণ করা ফর্মটি বর্তমানে পর্যালোচনা করা হচ্ছে। পর্যালোচনা করার পর আপনার ফরমে পূরণকৃত বিকাশ নম্বরে রিওয়ার্ড পেয়ে যাবেন।' }}
    </div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <a href="{{ route('profile') }}" class="text-center no-decoration">
                    <div class="icon my-3 display-3 fw-bold">
                       50 ৳
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
                       0 ৳
                    </div>
                    <p class="lead mb-0">@lang('Withdraw Amount')</p>
                </a>
            </div>
        </div>
    </div>
</div>

@stop

@section('scripts')
    @foreach (\Vanguard\Plugins\Vanguard::availableWidgets(auth()->user()) as $widget)
        @if (method_exists($widget, 'scripts'))
            {!! app()->call([$widget, 'scripts']) !!}
        @endif
    @endforeach
@stop
