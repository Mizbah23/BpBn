<div class="card widget">
    <div class="card-body">
        <div class="row">
            <div class="p-3 text-info flex-1">
                <i class="fa fa-user fa-3x"></i>
            </div>
            @php
                $unconfirmed=Vanguard\User::leftjoin('barbers','barbers.user_id','=','users.id')->where('is_verified',0)->get();
            @endphp
            <div class="pr-3">
                <h2 class="text-right">{{ count($unconfirmed) }}</h2>
                <div class="text-muted">@lang('Unconfirmed Users')</div>
            </div>
        </div>
    </div>
</div>
