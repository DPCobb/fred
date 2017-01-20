@foreach($msgs as $reply)
@if($reply->parentId == $msg->msgId)
<div class="well">
    {{$reply->text}}
</div>
@endif
@endforeach
