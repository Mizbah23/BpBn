<div class="row">
    <div class="col-md-6">

        <div class="form-group">
            <input type="hidden" name="user_id" value="{{$edit ?$user->id :''}}">
            <label for="first_name">@lang('First Name')</label>
            <input type="text" class="form-control input-solid" id="first_name"
                   name="first_name" placeholder="@lang('First Name')" value="{{ $edit ? $user->first_name : '' }}" >
        </div>
        <div class="form-group">
            <label for="last_name">@lang('Regular Phone')</label>
            <input type="text" class="form-control input-solid" id="phone"
                   name="phone" placeholder="@lang('Last Name')" value="{{ $edit ? $user->barber->phone_1 : '' }}">
        </div>
        <div class="form-group">
            <label for="address">@lang('Working Address')</label>
            <input type="text" class="form-control input-solid" id="address"
                   name="address" placeholder="@lang('Address')" value="{{ $edit ? $user->barber->work_address : '' }}">
        </div>
        <div class="form-group">
            <label>@lang('Experience')</label>
            <input type="text" class="form-control input-solid" id="experience"
            name="experience" placeholder="@lang('Experience')" value="{{ $user->barber->experience }}">

        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="birthday">@lang('Gender')</label>
            <div class="form-group">

                       <select class="form-control input-solid" id="gender" name="gender">

                        <option value="male" {{ ($user->barber->gender == "male") ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ ($user->barber->gender== "female") ? 'selected' : '' }}>Female</option>
                    </select>
            </div>
        </div>
        <div class="form-group">
            <label for="phone">@lang('Alternative Phone')</label>
            <input type="text" class="form-control input-solid" id="phone_2"
                   name="phone_2" placeholder="@lang('Phone')" value="{{ $edit ? $user->barber->phone_2 : '' }}">
        </div>
        <div class="form-group">
            <label for="address">@lang('Address')</label>
            <input type="text" class="form-control input-solid" id="address"
                   name="address" placeholder="@lang('Address')" value="{{ $edit ? $user->address : '' }}">
        </div>
        <div class="form-group">
            <label for="address">@lang('Work Status')</label>
            <select class="form-control input-solid" id="work_status" name="work_status">

                <option value="yes" {{ ($user->barber->work_status == "yes") ? 'selected' : '' }}>Yes</option>
                <option value="no" {{ ($user->barber->work_status== "no") ? 'selected' : '' }}>No</option>
            </select>

        </div>
    </div>

    <div class="col-md-12">

        <div class="form-group">
            <label for="phone">@lang('BKash Number')</label>
            <input type="text" class="form-control input-solid" id="bkash"
                   name="bkash" placeholder="@lang('BKash')" value="{{ $edit ? $user->barber->bkash_number : '' }}">
        </div>
    </div>

    @if($user->input_referral_code)
    @php
        $referred_user=Vanguard\User::where('promo_code',$user->input_referral_code)->first();

        $referred_bkash=Vanguard\Barber::where('user_id',$referred_user->id)->first();

        $referel_number=$referred_bkash->bkash_number;
    @endphp
    <div class="col-md-12">

        <div class="form-group">
            <label for="phone">@lang('Refered BKash Number')</label>
            <input type="text" class="form-control input-solid" id="bkash"
                   name="rbkash" placeholder="@lang('BKash')" value="{{ $referel_number }}" readonly>
        </div>
    </div>

    @endif

    <div class="col-md-6">
        <label>@lang('NID Front Image')</label>
        <div class="row" id="uploadedNidFrontContainer">
            @if($edit && !empty($user->barber->nid_front))
                <div class="col-md-12 mb-3">
                    <img src="{{ asset('app/public/upload/work/' .$user->barber->nid_front) }}"
                         class="img-thumbnail"
                         style="width: 100%; max-width: 500px; height: 250px; object-fit: cover;">
                </div>
            @else
                <p>@lang('No images uploaded yet.')</p>
            @endif
        </div>
    </div>

    <div class="col-md-6">
        <label>@lang('NID Back Image')</label>
        <div class="row" id="uploadedNidBackContainer">
            @if($edit && !empty($user->barber->nid_back))
                <div class="col-md-12 mb-3">
                    <img src="{{ asset('app/public/upload/work/' .$user->barber->nid_back) }}"
                         class="img-thumbnail"
                         style="width: 100%; max-width: 500px; height: 250px; object-fit: cover;">
                </div>
            @else
                <p>@lang('No images uploaded yet.')</p>
            @endif
        </div>
    </div>


    <div class="col-md-12">
        <label>@lang('Uploaded Work Images')</label>
        <form action="{{ route('barber.uploadWorkImages') }}" class="dropzone" id="workImagesDropzone">
            @csrf
        </form>
        <div class="row mt-3" id="uploadedWorkImagesContainer">
            @if($edit && !empty($user->barber->work_images))
                @foreach(json_decode($user->barber->work_images, true) as $image)
                    <div class="col-md-3 mb-3">
                        <img src="{{ asset('app/public/'.$image) }}" class="img-thumbnail"
                             style="width: 100%; max-width: 300px; height: 200px; object-fit: cover;">
                    </div>
                @endforeach
            @else
                <p>@lang('No images uploaded yet.')</p>
            @endif
        </div>
    </div>

    @if (Auth::user()->role_id == 1)
    <div class="col-md-12">
        <div class="form-group">
            <label for="verify">@lang('Verify User')</label>
            <select class="form-control input-solid" id="is_verified" name="is_verified" onchange="toggleMessageField()">
                <option value="">Pending</option>
                <option value="1" {{ ($user->barber->is_verified == 1) ? 'selected' : '' }}>Success</option>
                <option value="2" {{ ($user->barber->is_verified == 2) ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
    </div>

    <div class="col-md-12" id="messageField" style="display: none;">
        <div class="form-group">
            <label for="message">@lang('Rejection Message')</label>
            <textarea class="form-control" id="message" name="message">{{ $user->barber->message ?? '' }}</textarea>
        </div>
    </div>

    <script>
        function toggleMessageField() {
            var selectElement = document.getElementById('is_verified');
            var messageField = document.getElementById('messageField');

            if (selectElement.value == "2") {
                messageField.style.display = "block";
            } else {
                messageField.style.display = "none";
            }
        }

        // Run on page load in case "Rejected" is already selected
        document.addEventListener("DOMContentLoaded", function() {
            toggleMessageField();
        });
    </script>
    @endif

    @if (Auth::user()->role_id==2)
        <div class="col-md-12 mt-2">
            @if($user->barber->is_verified==0)
            <button type="submit" class="btn btn-primary" id="update-details-btn">
                <i class="fa fa-refresh"></i>
                @lang('Review On Process...')
            </button>
            @elseif ($user->barber->is_verified==1)
            <button type="submit" class="btn btn-success" id="update-details-btn">
                <i class="fa fa-refresh"></i>
                @lang('Review Completed Successfully...')
            </button>
            @else
            <button type="submit" class="btn btn-danger" id="update-details-btn">
                <i class="fa fa-refresh"></i>
                @lang('Sorry! Your Application is Rejected...')
            </button>
            @endif
        </div>
    @elseif (Auth::user()->role_id==1)
    <div class="col-md-12 mt-2">
        @if($user->barber->is_verified==0)
        <button type="submit" class="btn btn-primary" id="update-details-btn">
            <i class="fa fa-refresh"></i>
            @lang('Submit')
        </button>
        @endif
    </div>
    @endif
</div>
