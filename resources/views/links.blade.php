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

    </div>
    <div class="col-sm-6 center">
        @include('common/mainsidebar')
        @include('common/leftsidebar')
    </div>
    <div class="col-sm-3 trim-padding sidebar hidden-xs">


    </div>
</div>
<!-- Global modals -->
@include('forms.commentreply')
@include('forms/commenteditmodal')
@include('forms/createmessage')
@endsection
