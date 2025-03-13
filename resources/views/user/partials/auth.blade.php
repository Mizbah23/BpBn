<div class="form-group">
    <label for="email">@lang('Phone')</label>
    <input type="text"
           class="form-control input-solid"
           id="phone"
           name="phone"
           placeholder="@lang('Phone')"
           value="{{ $edit ? $user->phone : '' }}" readonly>
</div>

<div class="form-group">
    <label for="username">@lang('Username')</label>
    <input type="text"
           class="form-control input-solid"
           id="username"
           placeholder="(@lang('optional'))"
           name="username"
           value="{{ $edit ? $user->username : '' }}" readonly>
</div>

<div class="form-group">
    <label for="password">{{ $edit ? __("New Password") : __('Password') }}</label>
    <input type="password"
           class="form-control input-solid"
           id="password"
           name="password"
           @if ($edit) placeholder="@lang("Leave field blank if you don't want to change it")" @endif>
</div>

<div class="form-group">
    <label for="password_confirmation">{{ $edit ? __("Confirm New Password") : __('Confirm Password') }}</label>
    <input type="password"
           class="form-control input-solid"
           id="password_confirmation"
           name="password_confirmation"
           @if ($edit) placeholder="@lang("Leave field blank if you don't want to change it")" @endif>
</div>

@if ($edit)
    <button type="submit" class="btn btn-primary mt-2" id="update-login-details-btn">
        <i class="fa fa-refresh"></i>
        @lang('Update Details')
    </button>
@endif
