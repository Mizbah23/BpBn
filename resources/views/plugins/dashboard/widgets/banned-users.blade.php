<div class="card widget">
    <div class="card-body">
        <div class="row">
            <div class="p-3 text-danger flex-1">
                <i class="fa fa-user-slash fa-3x"></i>
            </div>
            @php
            $rejected=Vanguard\User::leftjoin('barbers','barbers.user_id','=','users.id')->where('is_verified',2)->get();
            @endphp
            <div class="pr-3">
                <h2 class="text-right">{{ count($rejected) }}</h2>
                <div class="text-muted">@lang('Rejected Users')</div>
            </div>
        </div>
    </div>
</div>
