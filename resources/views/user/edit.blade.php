@extends('layouts.app')

@section('page-title', __('Edit User'))
@section('page-heading', $user->present()->nameOrEmail)

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('users.index') }}">@lang('Users')</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('users.show', $user->id) }}">
            {{ $user->present()->nameOrEmail }}
        </a>
    </li>
    <li class="breadcrumb-item active">
        @lang('Edit')
    </li>
@stop

@section('content')

@include('partials.messages')

<div class="row">
    <div class="col-8">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs" id="nav-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active"
                           id="details-tab"
                           data-toggle="tab"
                           href="#details"
                           role="tab"
                           aria-controls="home"
                           aria-selected="true">
                            @lang('User Details')
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                           id="authentication-tab"
                           data-toggle="tab"
                           href="#login-details"
                           role="tab"
                           aria-controls="home"
                           aria-selected="true">
                            @lang('Login Details')
                        </a>
                    </li>
                    @if (setting('2fa.enabled'))
                        <li class="nav-item">
                            <a class="nav-link"
                               id="authentication-tab"
                               data-toggle="tab"
                               href="#2fa"
                               role="tab"
                               aria-controls="home"
                               aria-selected="true">
                                @lang('Two-Factor Authentication')
                            </a>
                        </li>
                    @endif
                </ul>

                <div class="tab-content mt-4" id="nav-tabContent">
                    <div class="tab-pane fade show active px-2"
                         id="details"
                         role="tabpanel"
                         aria-labelledby="nav-home-tab">
                        <form action="{{ route('users.update.details', $user) }}" method="POST" id="details-form" enctype="multipart/form-data" >
                            @csrf
                            @method('PUT')
                            @include('user.partials.details', ['profile' => true])
                        </form>
                    </div>

                    <div class="tab-pane fade px-2"
                         id="login-details"
                         role="tabpanel"
                         aria-labelledby="nav-profile-tab">
                        <form action="{{ route('users.update.login-details', $user) }}"
                              method="POST"
                              id="login-details-form">
                            @csrf
                            {{-- @method('PUT') --}}
                            @include('user.partials.auth')
                        </form>

                    </div>

                    @if (setting('2fa.enabled'))
                        <div class="tab-pane fade px-2" id="2fa" role="tabpanel" aria-labelledby="nav-profile-tab">
                            <?php $route = Authy::isEnabled($user) ? 'disable' : 'enable'; ?>

                            <form action="{{ route("two-factor.{$route}") }}" method="POST" id="two-factor-form">
                                @csrf
                                <input type="hidden" name="user" value="{{ $user->id }}">
                                @include('user.partials.two-factor')
                            </form>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <div class="col-4">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('user.update.avatar', $user->id) }}"
                      method="POST"
                      id="avatar-form"
                      enctype="multipart/form-data">
                    @csrf
                    @include('user.partials.avatar', ['updateUrl' => route('user.update.avatar.external', $user->id)])
                </form>
            </div>
        </div>
        @if($user->barber->is_verified==1)
        <div class="card">
            <div class="card-body">
                <div class="spinner-grow text-danger" role="status">
                    <span class="visually-hidden"></span>

                  </div>
                  <h4>Your Request has been successfully verified. </h4>

            </div>
        </div>
        @elseif($user->barber->is_verified==0)
        <div class="card">
            <div class="card-body">

                  <h4>Your Request has been pending. </h4>

            </div>
        </div>
        @else
        <div class="card">
            <div class="card-body">

                  <h4>Your Request has been rejected. </h4>

            </div>
        </div>
        @endif
    </div>
</div>

@stop

@section('scripts')

    {!! HTML::script('assets/js/as/btn.js') !!}
    {!! HTML::script('assets/js/as/profile.js') !!}
    {!! JsValidator::formRequest('Vanguard\Http\Requests\User\UpdateDetailsRequest', '#details-form') !!}
    {!! JsValidator::formRequest('Vanguard\Http\Requests\User\UpdateLoginDetailsRequest', '#login-details-form') !!}

    @if (setting('2fa.enabled'))
        {!! JsValidator::formRequest('Vanguard\Http\Requests\TwoFactor\EnableTwoFactorRequest', '#two-factor-form') !!}
    @endif


@stop
