@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-sm-3 hidden-xs">
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
                <h3>Viewing Your Posts</h3>
                <!-- Post display format is created as its own blade template under
                common/mypostdisplay so it can be consistent in all views -->
                @include('common.mypostdisplay')
            </div>
        </div>

    </div>
    <div class="col-sm-3 trim-padding sidebar hidden-xs">
        @include('common/mainsidebar')

    </div>
</div>
@include('forms.commentreply')
@include('forms/commenteditmodal')
@include('forms.posteditmodal')
@endsection
