@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-sm-3">
        <!-- left side bar for cat search and follows -->
        @include('common/leftsidebar')
    </div>
    <div class="col-sm-6">
        <!-- Main post form is created as its own blade template under
        forms/newpost so it can be consistent in all views -->
        @include('forms.newpost')
        <!-- Validator error display template -->
        @include('common.errors')
        <div class="row">
            <div class="col-sm-12 feed">
                <h3>Viewing Posts in {{ucfirst(strtolower($categoryname))}}</h3>
                @if(count($follows) === 0)
                <h5><a href="#" data-id="{{$categoryid}}" id="catfollow" title="Follow this Category!">Follow This Category</a></h5>
                @else
                <h5><a href="#" data-id="{{$categoryid}}" id="catunfollow" title="Unfollow this Category">Unfollow This Category</a></h5>
                @endif
                <!-- Post display format is created as its own blade template under
                common/postdisplay so it can be consistent in all views -->
                @include('common.mypostdisplay')
            </div>
        </div>

    </div>
    <div class="col-sm-3 trim-padding sidebar">
        @include('common/mainsidebar')

    </div>
</div>

@endsection
