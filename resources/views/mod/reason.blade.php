<div class="modal reason-modal">
    <form class="mod-rep"  method="POST">
        <h5>Reason for deleting this post:</h5>
        {{ csrf_field() }}
        <input type="text" name="reasonfor" id="reasonfor" required placeholder="Add a reason"/>
        <input type="hidden" name="postid" id="postid" value="" required/>
        <input type="hidden" name="user" id="user" value="" required/>
        <input type="submit" id="mod-submit-reason" value="Send"/>
    </form>
</div>
