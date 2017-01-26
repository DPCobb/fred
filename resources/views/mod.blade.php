@extends('layouts.app')
@section('content')
@php
    if(!session('id')){
        header('Location: /');
        exit();
    }
@endphp
<div class="row">
    <div class="col-sm-4">
        @include('mod/sidebar')

    </div>
    <div class="col-sm-8">
        <div class="container-fluid">
            <div class="alert alert-success" id="mod-alert">

            </div>
            @include('mod/main')
        </div>
    </div>
</div>

<!-- Global modals -->
@include('forms/commentreply')
@include('forms/commenteditmodal')
@include('forms/createmessage')
@include('mod/reason')
@endsection
