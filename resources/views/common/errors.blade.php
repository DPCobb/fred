@if (count($errors) > 0)
    <!-- Form Error List -->
    <div class="alert alert-danger">
        <h3>It looks like there was a problem with your post!</h3>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
