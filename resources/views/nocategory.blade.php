@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-sm-3 hidden-xs">
        <!-- left side bar for cat search and follows -->
        @include('common/leftsidebar')
    </div>
    <div class="col-sm-6">
        <div class="row">
            <div class="col-sm-12 feed">
                <h3 class="center">This category doesn't exist... yet. Go ahead... create it now.</h3>
                <!-- Create Category format is created as its own blade template under
                common/postdisplay so it can be consistent in all views -->
                @include('common.createcategory')
            </div>
        </div>

    </div>
    <div class="col-sm-3 trim-padding sidebar hidden-xs">
        @include('common/mainsidebar')

    </div>
</div>

@endsection
