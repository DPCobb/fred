<div class="modal comment-edit">
    <form class="comment-form-edit" action="/update/comment" method="POST">
        {{ csrf_field() }}
        <input type="text" name="editcom" id="editcom" required placeholder="Add your comment"/>
        <input type="hidden" name="commentid" id="commentid" value="" required/>
        <input type="submit" value="Update"/>
    </form>
</div>
