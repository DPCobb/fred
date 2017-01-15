
        @extends('layouts.app')
        @section('content')
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-8" id="val-prop">
                    <h2>Welcome to nmbley.</h2>
                    <h3>A new way to share your stories and follow what interests you.</h3>
                    <p>Just login with Facebook to get started. Nothing special, no new account, just use your
                        existing Facebook login to sign up and login. From there start following the topics you are
                        interested in, or start a new topic.</p>
                        @php
                            if(session('id')){
                                $id = session('id');
                                echo'
                                <a href="/home" class="btn btn-success btn-lg" role="button">Take Me Home</a>
                                ';
                            }
                            else{
                                echo'
                                <a href="/login" class="btn btn-success btn-lg" role="button">Login or Sign Up with Facebook</a>
                                ';
                            }
                        @endphp
                </div>

                <div class="col-sm-4 center push-15">

                </div>
            </div>
        </div>
    @endsection
