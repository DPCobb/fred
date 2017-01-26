@foreach($comments as $com)
    @if($com->postId == $post->postId && $com->parent == null)
        <div class="well">
            {{$com->msg}}
            <h6>{{$com->first}} {{$com->last}} at {{$com->updated_at}}</h6>
            <form class="mod-delete" action="" method="delete">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <button class="small-link-button mod-del-com" data-id="{{$com->commentId}}">Delete</button>
            </form>
            @foreach($replies as $reply)
                @if ($reply->childId == $com->commentId)
                <div class="well">
                    {{$reply->msg}}
                    <h6>{{$reply->fname}} {{$reply->lname}} at {{$reply->updated_at}}</h6>
                    <form class="mod-delete" action="/mod/comment/delete/{{$reply->commentId}}" method="delete">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <button class="small-link-button mod-del-com" data-id="{{$reply->commentId}}">Delete</button>
                    </form>
                </div>
                @endif
            @endforeach
        </div>
    @endif
@endforeach
