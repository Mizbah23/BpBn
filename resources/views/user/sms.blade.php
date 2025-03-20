@extends('layouts.app')

@section('page-title', __('Users'))
@section('page-heading', __('Users'))



@section('content')

@include('partials.messages')

<div class="card">
    <div class="card-body">
        <form action="{{route('send.sms.post')}}" method="post"  class="pb-2 mb-3 border-bottom-light">
            @csrf
            <div class="row my-3 flex-md-row flex-column-reverse">

                <div class="col-md-4">
                    <label for="status" class="form-label">Select User Types:</label>
                    <select id="status" name="status" class="form-control">
                        <option value="">All</option>
                        <option value="0">Pending</option>
                        <option value="2">Reject</option>
                        <option value="1">Accepted</option>
                    </select>
                </div>

                <div class="col-md-12 mt-3">
                    <label for="message" class="form-label">Message:</label>
                    <textarea id="message" name="message" class="form-control" rows="3"></textarea>
                </div>

                <div class="col-md-2 mt-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Submit</button>
                </div>

            </div>
        </form>

    </div>
</div>



@stop

@section('scripts')
    <script>
        $("#status").change(function () {
            $("#users-form").submit();
        });
    </script>
@stop
