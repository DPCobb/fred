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
        @include('common/messagedisplay')
    </div>
    <div class="col-sm-3 trim-padding sidebar hidden-xs">
        <!-- Side bar information -->
        @include('common/mainsidebar')

    </div>
</div>
<!-- modals -->
@include('forms/createmessage')
@include('forms/messagereply')

@endsection
