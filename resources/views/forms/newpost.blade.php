<form id="text" action="/home/text" method="POST">
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
    <div class="suggestions">

    </div>
    <button type="submit"><i class="fa fa-check" aria-hidden="true"></i></button>
    <a href="#" id="imgPost" class="button-round blue"><i class="fa fa-camera" aria-hidden="true"></i></a>
    <a href="#" id="cancelbutton" class="button-round red cancel"><i class="fa fa-times" aria-hidden="true"></i></a>
</form>
<form enctype="multipart/form-data" id="photo" action="/home/image" method="POST">
    {{ csrf_field() }}
    <div class="form-group">
        <input type="text" name="title" id="title" required placeholder="Add a post title"/>
    </div>
    <div class="form-group">
        <input type="file" name="pic" id="pic" accept="image/*"/>
    </div>
    <div class="form-group">
        <input type="text" name="cat" id="cat" class="cat" required placeholder="Where should we put this?"/>
    </div>
    <div class="suggestions">

    </div>
    <button type="submit"><i class="fa fa-check" aria-hidden="true"></i></button>
    <a href="#" id="textPost" class="button-round blue"><i class="fa fa-pencil" aria-hidden="true"></i></a>
    <a href="#" id="cancelbutton" class="button-round red cancel"><i class="fa fa-times" aria-hidden="true"></i></a>
</form>
