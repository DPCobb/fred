@foreach($comments as $com)
    @if($com->postId == $post->postId && $com->parent == null)
        <div class="well">
            {{$com->msg}}
            <h6>{{$com->first}} {{$com->last}} at {{$com->updated_at}}</h6>
            @if($com->userId == session('id'))
            <a href="#" class="small-link com-edit" data-commentid="{{$com->commentId}}">Edit</a>
            <form class="delete-form" action="/home/comment/delete/{{$com->commentId}}" method="POST">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <button class="small-link-button">Delete</button>
            </form>
            <a href="#" class="small-link reply" data-commentid="{{$com->commentId}}" data-postid="{{$com->postId}}">Reply</a>
            @else
            <a href="#" class="small-link reply" data-commentid="{{$com->commentId}}" data-postid="{{$com->postId}}">Reply</a>
            @endif
            @foreach($replies as $reply)
                @if ($reply->childId == $com->commentId)
                <div class="well">
                    {{$reply->msg}}
                    <h6>{{$reply->fname}} {{$reply->lname}} at {{$reply->updated_at}}</h6>
                    @if($reply->userId == session('id'))
                    <a href="#" data-commentid="{{$reply->commentId}}" class="small-link com-edit">Edit</a>
                    <form class="delete-form" action="/home/comment/delete/{{$reply->commentId}}" method="POST">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <button class="small-link-button">Delete</button>
                    </form>
                    @endif
                    <a href="#" class="small-link reply" data-commentid="{{$com->commentId}}" data-postid="{{$com->postId}}">Reply</a>
                </div>
                @endif
            @endforeach
        </div>
    @endif
@endforeach
