<div class="row">
    <div class="col-md-6">

        <div class="form-group">
            <label for="first_name">@lang('First Name')</label>
            <input type="text" class="form-control input-solid" id="first_name"
                   name="first_name" placeholder="@lang('First Name')" value="{{ $edit ? $user->first_name : '' }}">
        </div>
        <div class="form-group">
            <label for="last_name">@lang('Regular Phone')</label>
            <input type="text" class="form-control input-solid" id="last_name"
                   name="last_name" placeholder="@lang('Last Name')" value="{{ $edit ? $user->barber->phone_1 : '' }}">
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
                <input type="text"
                       name="birthday"
                       id='birthday'
                       value="{{ $edit ? $user->barber->gender:''  }}"
                       class="form-control input-solid" />
            </div>
        </div>
        <div class="form-group">
            <label for="phone">@lang('Alternative Phone')</label>
            <input type="text" class="form-control input-solid" id="phone"
                   name="phone" placeholder="@lang('Phone')" value="{{ $edit ? $user->barber->phone_2 : '' }}">
        </div>
        <div class="form-group">
            <label for="address">@lang('Address')</label>
            <input type="text" class="form-control input-solid" id="address"
                   name="address" placeholder="@lang('Address')" value="{{ $edit ? $user->address : '' }}">
        </div>
        <div class="form-group">
            <label for="address">@lang('Work Status')</label>
            <input type="text" class="form-control input-solid" id="work_status"
            name="work_status" placeholder="@lang('Work Status')" value="{{ $edit ? $user->barber->work_status : '' }}">
        </div>
    </div>

    <div class="col-md-12">

        <div class="form-group">
            <label for="phone">@lang('BKash Number')</label>
            <input type="text" class="form-control input-solid" id="bkash"
                   name="bkash" placeholder="@lang('BKash')" value="{{ $edit ? $user->barber->bkash_number : '' }}">
        </div>
    </div>

    <div class="col-md-12">
        <label>@lang('Uploaded Work Images')</label>
        <div class="row" id="uploadedImagesContainer">
            @if($edit && !empty($user->barber->work_images))
                @foreach(json_decode($user->barber->work_images, true) as $image)
                    <div class="col-md-2 mb-3">
                        <img src="{{ asset('storage/'.$image) }}" class="img-thumbnail" style="width: 300px; height: 100px; object-fit: cover;">
                    </div>
                @endforeach
            @else
                <p>@lang('No images uploaded yet.')</p>
            @endif
        </div>
    </div>

    @if ($edit)
        <div class="col-md-12 mt-2">
            <button type="submit" class="btn btn-primary" id="update-details-btn">
                <i class="fa fa-refresh"></i>
                @lang('Update Details')
            </button>
        </div>
    @endif
</div>
