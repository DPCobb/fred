@foreach($posts as $post)
    @if ($post->type == 1)
        @if ($post->flagged == 1)
        <article class="mod-post flagged" data-key="{{$post->postId}}">
        @else
        <article class="mod-post" data-key="{{$post->postId}}">
        @endif
            <h5>{{ $post->title }}</h5>
            <h6>Posted by <a href="#" class="modoptions" data-id="{{$post->user}}" data-name="{{$post->firstname}} {{$post->lastname}}" >{{$post->firstname}} {{$post->lastname}}</a></h6>
            <h6>Current Likes: {{$post->likes}}</h6>
            <p>{{$post->text}}</p>
            <a href="#" title="Comments" class="small-link comment mod-comment">View Comments</a>
            <div class="comments">
                @include('mod/comments')
            </div>
            <div class="mod-actions">
                <div class="btn-group">
                    <a href="#" class="mod-del btn btn-danger delete-post" data-id="{{$post->postId}}"><i class="fa fa-trash" aria-hidden="true"></i> Delete Post</a>
                    <a href="#" class="mod-ban btn btn-danger " data-id="{{$post->user}}"><i class="fa fa-ban" aria-hidden="true"></i> Ban User</a>

                </div>
                <div class="btn-group">
                    <a href="#" class="mod-msg btn btn-primary message" data-id="{{$post->user}}"><i class="fa fa-envelope" aria-hidden="true"></i> Message User</a>
                    <a href="#" class="btn btn-primary addmod" data-id="{{$post->user}}" data-cat="{{$post->categoryId}}"><i class="fa fa-user" aria-hidden="true"></i> Add As A Moderator</a>
                </div>
                @if ($post->flagged == 1)
                <div class="btn-group">
                    <a href="#" class="btn btn-success unflag" data-id="{{$post->postId}}"><i class="fa fa-flag" aria-hidden="true"></i> Remove Flag</a>
                </div>
                @else

                @endif
            </div>
        </article>
        @elseif ($post->type == 2)
        @if ($post->flagged == 1)
        <article class="mod-post admin flagged" data-key="{{$post->postId}}">
        @else
        <article class="mod-post admin" data-key="{{$post->postId}}">
        @endif
            <h5>{{ $post->title }}</h5>
            <h6>Posted by <a href="#" class="modoptions" data-id="{{$post->user}}" data-name="{{$post->firstname}} {{$post->lastname}}" >{{$post->firstname}} {{$post->lastname}}</a></h6>
            <p>{{$post->text}}</p>
            <a href="#" title="View Comments" class="small-link comment">View Comments</a>
            <div class="comments">
                @include('mod/comments')
            </div>
            <div class="mod-actions">
                <div class="btn-group">
                    <a href="#" class="mod-del btn btn-danger delete-post" data-id="{{$post->postId}}"><i class="fa fa-trash" aria-hidden="true"></i> Delete Post</a>
                    <a href="#" class="mod-ban btn btn-danger" data-id="{{$post->user}}"><i class="fa fa-ban" aria-hidden="true"></i> Ban User</a>
                </div>
                <div class="btn-group">
                    <a href="#" class="mod-msg btn btn-primary message" data-id="{{$post->user}}"><i class="fa fa-envelope" aria-hidden="true"></i> Message User</a>
                    <a href="#" class="btn btn-primary addmod" data-id="{{$post->user}}" data-cat="{{$post->categoryId}}"><i class="fa fa-user" aria-hidden="true"></i> Add As A Moderator</a>
                </div>
                @if ($post->flagged == 1)
                <div class="btn-group">
                    <a href="#" class="btn btn-success unflag" data-id="{{$post->postId}}"><i class="fa fa-flag" aria-hidden="true"></i> Remove Flag</a>
                </div>
                @else

                @endif
            </div>
        </article>

    @else
        @if ($post->flagged == 1)
        <article class="mod-post flagged" data-key="{{$post->postId}}">
        @else
        <article class="mod-post" data-key="{{$post->postId}}">
        @endif
            <h5>{{ $post->title }}</h5>
            <h6>Posted by <a href="#" class="modoptions" data-id="{{$post->user}}" data-name="{{$post->firstname}} {{$post->lastname}}" >{{$post->firstname}} {{$post->lastname}}</a></h6>
            <h6>Current Likes: {{$post->likes}}</h6>
            <img src="../{{$post->image}}" alt="{{$post->title}}"/>
            <a href="#" title="Comments" class="small-link comment mod-comment">View Comments</a>
            <div class="comments">
                @include('mod/comments')
            </div>
            <div class="mod-actions">
                <div class="btn-group">
                    <a href="#" class="mod-del btn btn-danger delete-post" data-id="{{$post->postId}}"><i class="fa fa-trash" aria-hidden="true"></i> Delete Post</a>
                    <a href="#" class="mod-ban btn btn-danger" data-id="{{$post->user}}"><i class="fa fa-ban" aria-hidden="true"></i> Ban User</a>
                </div>
                <div class="btn-group">
                    <a href="#" class="mod-msg btn btn-primary message" data-id="{{$post->user}}"><i class="fa fa-envelope" aria-hidden="true"></i> Message User</a>
                    <a href="#" class="btn btn-primary addmod" data-id="{{$post->user}}" data-cat="{{$post->categoryId}}"><i class="fa fa-user" aria-hidden="true"></i> Add As A Moderator</a>
                </div>
                @if ($post->flagged == 1)
                <div class="btn-group">
                    <a href="#" class="btn btn-success unflag" data-id="{{$post->postId}}"><i class="fa fa-flag" aria-hidden="true"></i> Remove Flag</a>
                </div>
                @else

                @endif
            </div>
        </article>


    @endif
@endforeach
<div class="center">
    {{ $posts->links() }}
</div>
<input type="hidden" value="{{$catId}}" id="handle"/>
