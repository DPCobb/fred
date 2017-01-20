@foreach ($msgs as $msg)
@if($msg->read === 0)
<article class="text-post">
    <h5>{{$msg->subject}}</h5>
    <h6>From: <a href="#" class="useroptionsrep" data-msg="{{$msg->msgId}}" data-id="{{$msg->sender}}" data-name="{{$msg->senderfirst}} {{$msg->senderlast}}">{{$msg->senderfirst}} {{$msg->senderlast}}</a> at {{$msg->created_at}}</h6>
    <p>{{$msg->text}}</p>
    <a href="#" class="small-link hide-rep">Show Replies</a>
    <div class="replies">
        @foreach($replys as $reply)
        @if($reply->parentId === $msg->msgId)
        <div class="well">
            <h6>Reply From <a href="#" class="useroptionsrep" data-msg="{{$msg->msgId}}" data-id="{{$reply->sender}}" data-name="{{$reply->senderfirst}} {{$reply->senderlast}}">{{$reply->senderfirst}} {{$reply->senderlast}}</a> at {{$reply->created_at}}</h6>
            <p>{{$reply->text}}</p>
        </div>
        @endif
        @endforeach
    </div>
    <div class="actions">
        <button class="read" data-msg="{{$msg->msgId}}">Mark As Read</button>
        <button class="del" data-msg="{{$msg->msgId}}">Delete</button>
        <button class="reply-btn" data-msg="{{$msg->msgId}}" data-to="{{$msg->sender}}">Reply</a>
    </div>
</article>
@elseif($msg->read === 1)
<article class="text-post">
    <h5>{{$msg->subject}}</h5>
    <h6>From: <a href="#" class="useroptions" data-msg="{{$msg->msgId}}" data-id="{{$msg->sender}}" data-name="{{$msg->senderfirst}} {{$msg->senderlast}}">{{$msg->senderfirst}} {{$msg->senderlast}}</a></h6>
    <p>{{$msg->text}}</p>
    <a href="#" class="small-link hide-rep">Show Replies</a>
    <div class="replies">
        @foreach($replys as $reply)
        @if($reply->parentId === $msg->msgId)
        <div class="well">
            <h6>Reply From <a href="#" class="useroptionsrep" data-msg="{{$msg->msgId}}" data-id="{{$reply->sender}}" data-name="{{$reply->senderfirst}} {{$reply->senderlast}}">{{$reply->senderfirst}} {{$reply->senderlast}}</a> at {{$reply->created_at}}</h6>
            <p>{{$reply->text}}</p>
        </div>
        @endif
        @endforeach
    </div>
    <div class="actions">
        <button class="del" data-msg="{{$msg->msgId}}">Delete</button>
        <button class="reply-btn" data-msg="{{$msg->msgId}}" data-to="{{$msg->sender}}">Reply</a>
    </div>
</article>
@else
@endif
@endforeach
