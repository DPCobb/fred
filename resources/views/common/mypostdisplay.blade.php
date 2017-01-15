@foreach ($posts as $post)
    @if ($post->type == 1)
    <article class="text-post">
        <h5>{{ $post->title }}</h5>
        <h6>Posted in {{ ucfirst($post->name)}} by {{$post->fname}} {{$post->lname}}</h6>
        <p>{{$post->text}}</p>
        <div class="buttons">
            <div class="button-action" title="Points">{{$post->likes}}</div>
            <div class="button-action blue-action" title="Add Comment"><i class="fa fa-plus" aria-hidden="true"></i></div>
            @foreach ($likes as $like)
                @if ($like->likedPost == $post->postId )
                    <div class="button-action orange-action liked" title="You liked this!"><i class="fa fa-heart" aria-hidden="true"></i></div>
                @else
                    <div class="button-action orange-action" title="Favorite"><i class="fa fa-heart-o" aria-hidden="true"></i></div>
                @endif
            @endforeach
            <a href="#" title="View Comments" class="small-link comment">View Comments</a>
            <form class="delete-form small-link-right" action="/activity/myposts/delete/{{$post->postId}}" method="POST">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <button class="small-link-right small-link-button deletebutton">Delete</button>
            </form>
            <a href="#" title="Edit Post" class="small-link edit" data-postid="{{$post->postId}}">Edit Post</a>
        </div>
        <div class="add-comment well">
            <form class="comment-form" action="/home/comment" method="POST">
                {{ csrf_field() }}
                <input type="text" name="newcom" id="newcom" required placeholder="Add your comment"/>
                <input type="hidden" name="postid" id="postid" value="{{$post->postId}}" required/>
                <input type="submit" value="Comment"/>
            </form>
        </div>
        <div class="comments">
            @include('common/comments')
        </div>
    </article>
    @else
    <article class="text-post">
        <h5>{{ $post->title }}</h5>
        <h6>Posted in {{ ucfirst($post->name)}} by {{$post->fname}} {{$post->lname}}</h6>
        <img src="../{{$post->image}}" alt="{{$post->title}}"/>
        <div class="buttons">
            <div class="button-action" title="Points">{{$post->likes}}</div>
            <div class="button-action blue-action" title="Add Comment"><i class="fa fa-plus" aria-hidden="true"></i></div>
            @foreach ($likes as $like)
                @if ($like->likedPost == $post->postId )
                    <div class="button-action orange-action liked" title="You liked this!"><i class="fa fa-heart" aria-hidden="true"></i></div>
                @else
                    <div class="button-action orange-action" title="Favorite"><i class="fa fa-heart-o" aria-hidden="true"></i></div>
                    @endif
            @endforeach
            <a href="#" title="View Comments" class="small-link comment">View Comments</a>
            <form class="delete-form small-link-right" action="/activity/myposts/delete/{{$post->postId}}" method="POST">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <button class="small-link-right small-link-button">Delete</button>
            </form>
            <a href="#" title="Edit Post" class="small-link edit" data-postid="{{$post->postId}}">Edit Post</a>
        </div>
        <div class="add-comment well">
            <form class="comment-form" action="/home/comment" method="POST">
                {{ csrf_field() }}
                <input type="text" name="newcom" id="newcom" required placeholder="Add your comment"/>
                <input type="hidden" name="postid" id="postid" value="{{$post->postId}}" required/>
                <input type="submit" value="Comment"/>
            </form>
        </div>
        <div class="comments">
            @include('common/comments')
        </div>
    </article>

    @endif
@endforeach
