<div class='modal comment-modal'>
    <form class="comment-form comment-form-reply" action="/api/reply" method="POST">
        <h5>Reply:</h5>
        {{ csrf_field() }}
        <input type="text" name="newreply" id="newreply" required placeholder="Add your reply"/>
        <input type="hidden" name="replypostid" id="replypostid" value="" required/>
        <input type="hidden" name="replycomid" id="replycomid" value="" required/>
        <input type="submit" id="reply-submit" value="Reply"/>
    </form>
</div>
