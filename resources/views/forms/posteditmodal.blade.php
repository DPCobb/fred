<div class="modal post-edit">

    <form id="edit-text" action="/update/text" method="POST">
        {{ csrf_field() }}
        <div class="form-group">
            <input type="text" name="title" id="title" required placeholder="Add a post title"/>
        </div>
        <div class="form-group">
            <textarea name="post" id="post" required placeholder="Tell your story"></textarea>
        </div>
        <div class="form-group">
            <input type="text" name="cat" id="cat" class="cat" required placeholder="Where should we put this?"/>
        </div>
        <input type="hidden" name="postid" id="postid" value=""/>
        <div class="suggestions">

        </div>
        <button type="submit" id="edit-text-sub"><i class="fa fa-check" aria-hidden="true"></i></button>
        <a href="#" id="editcancelbutton" class="button-round red cancel"><i class="fa fa-times" aria-hidden="true"></i></a>
    </form>
    <form enctype="multipart/form-data" id="editphoto" action="/update/photo" method="POST">
        {{ csrf_field() }}
        <div class="form-group">
            <input type="text" name="title" id="title" required placeholder="Add a post title"/>
        </div>
        <div class="form-group">
            <input type="text" name="cat" id="cat" class="cat" required placeholder="Where should we put this?"/>
        </div>
        <input type="hidden" name="postid" id="postid" value=""/>
        <div class="suggestions">

        </div>
        <img id='pic' src='' class="thumb"/>
        <button type="submit" id="edit-photo-sub"><i class="fa fa-check" aria-hidden="true"></i></button>
        <a href="#" id="photocancelbutton" class="button-round red cancel"><i class="fa fa-times" aria-hidden="true"></i></a>
    </form>


</div>
