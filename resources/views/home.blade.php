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
        @if(isset($banned))
            @forelse($banned as $ban)
            <div id="banned" class="col-sm-12 alert alert-danger">
                <h4>It looks like you were banned from posting in {{$ban->name}}.</h4>
            </div>
            @empty

            @endforelse
        @else
        @endif
        <!-- Validator error display template -->
        @include('common.errors')
        <div class="row">
            <div class="col-sm-12 feed" id="feed">
                <!-- Post display format is created as its own blade template under
                common/postdisplay so it can be consistent in all views -->
                <!--Post display is now passed with jQuery for infinite scroll-->

            </div>
        </div>
        <div class="row">
            <div id="loading" class="col-sm-12">
                <i class="fa fa-spinner fa-pulse fa-3x fa-fw load"></i>
            </div>
        </div>
        <div class="row">
            <div id="end" class="col-sm-12 alert alert-warning">
                <h4>It seems like you have reached the end... follow more categories to view more posts!</h4>
            </div>
        </div>

    </div>
    <div class="col-sm-3 trim-padding sidebar hidden-xs">
        <!-- Side bar information -->
        @include('common/mainsidebar')

    </div>
</div>
<!-- Global modals -->
@include('forms/commentreply')
@include('forms/commenteditmodal')
@include('forms/createmessage')
@endsection
