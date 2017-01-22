@extends('layouts.app')
@section('content')
@php
    if(!session('id')){
        header('Location: /');
        exit();
    }
@endphp
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
            <div class="col-sm-12 feed" id="feed">
                <!-- Post display format is created as its own blade template under
                common/postdisplay so it can be consistent in all views -->
                @include('common.postdisplay')
            </div>
        </div>

    </div>
    <div class="col-sm-3 trim-padding sidebar hidden-xs">
        <!-- Side bar information -->
        @include('common/mainsidebar')

    </div>
</div>
<!-- Global modals -->
@include('forms.commentreply')
@include('forms/commenteditmodal')
@include('forms/createmessage')
@endsection
