<tr>
    <td style="width: 40px;">
        <a href="{{ route('users.show', $user) }}">
            <img
                class="rounded-circle img-responsive"
                width="40"
                src="{{asset('storage/upload/users/' . $user->avatar) }}"
                alt="{{ $user->present()->name }}">
        </a>
    </td>
    <td class="align-middle">
        <a href="{{ route('users.show', $user) }}">
            {{ $user->username ?: __('N/A') }}
        </a>
    </td>
    <td class="align-middle">{{ $user->first_name . ' ' . $user->last_name }}</td>
    <td class="align-middle">{{ $user->phone }}</td>
    <td class="align-middle">{{ $user->created_at->format(config('app.date_format')) }}</td>
    @if (isset($user->barber))
    <td class="align-middle">
        @if($user->barber->is_verified==0)
        <span class="badge badge-lg badge-warning">
            {{ 'Not Verified' }}
        </span>
        @elseif ($user->barber->is_verified==1)
        <span class="badge badge-lg badge-success">
            {{ 'Verification Completed' }}
        </span>
        @else
        <span class="badge badge-lg badge-danger">
            {{ 'Rejected' }}
        </span>
        @endif
    </td>
    @else
    <td>{{'Not Valid'}}</td>
    @endif

    <td class="text-center align-middle">
        <div class="dropdown show d-inline-block">
            <a class="btn btn-icon"
               href="#" role="button" id="dropdownMenuLink"
               data-toggle="dropdown"
               aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-ellipsis-h"></i>
            </a>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                @if (config('session.driver') == 'database')
                    <a href="{{ route('user.sessions', $user) }}" class="dropdown-item text-gray-500">
                        <i class="fas fa-list mr-2"></i>
                        @lang('User Sessions')
                    </a>
                @endif
                <a href="{{ route('users.show', $user) }}" class="dropdown-item text-gray-500">
                    <i class="fas fa-eye mr-2"></i>
                    @lang('View User')
                </a>

                @canBeImpersonated($user)
                    <a href="{{ route('impersonate', $user) }}" class="dropdown-item text-gray-500 impersonate">
                        <i class="fas fa-user-secret mr-2"></i>
                        @lang('Impersonate')
                    </a>
                @endCanBeImpersonated
            </div>
        </div>

        <a href="{{ route('users.edit', $user) }}"
           class="btn btn-icon edit"
           title="@lang('Edit User')"
           data-toggle="tooltip" data-placement="top">
            <i class="fas fa-eye"></i>
        </a>


        @if($user->role_id==2)


        <a href="{{ route('users.destroy', $user) }}"
           class="btn btn-icon"
           title="@lang('Delete User')"
           data-toggle="tooltip"
           data-placement="top"
           data-method="DELETE"
           data-confirm-title="@lang('Please Confirm')"
           data-confirm-text="@lang('Are you sure that you want to delete this user?')"
           data-confirm-delete="@lang('Yes, delete him!')">
            <i class="fas fa-trash"></i>
        </a>
        @endif
    </td>
</tr>
