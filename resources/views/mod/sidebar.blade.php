
<div class="container-fluid">
    @foreach ($categoryName as $cat)
        <h4>Currently Moderating {{ucfirst(strtolower($cat->name))}}</h4>
    @endforeach
    <h5>Add a Moderator Post</h5>
    <form id="text" class="mod-post-form" action="/mod/post" method="POST">
        {{ csrf_field() }}
        <div class="form-group">
            <input type="text" name="title" id="title" required placeholder="Add a post title"/>
        </div>
        <div class="form-group">
            <textarea name="post" id="post" required placeholder="Add your message"></textarea>
        </div>
        <div class="form-group">
            <input type="hidden" name="cat" id="cat" data-catid="{{$catId}}"/>
        </div>
        <div class="suggestions">

        </div>
        <button type="submit" id="modpost"><i class="fa fa-check" aria-hidden="true"></i></button>
        <a href="#" id="cancelbutton" class="button-round red cancel"><i class="fa fa-times" aria-hidden="true"></i></a>
    </form>
    <h4>Additional Moderators</h4>
    @foreach($mods as $mod)
    <div class="btn-group mods" data-key="{{$mod->userId}}">
        <a href="#" class="btn btn-primary" data-id="{{$mod->userId}}">{{$mod->fname}} {{$mod->lname}}</a>
        <a href="#" class="btn btn-primary messagemod" data-name="{{$mod->fname}} {{$mod->lname}}" data-id="{{$mod->userId}}"><i class="fa fa-envelope" aria-hidden="true"></i> Message</a>
        <a href="#" class="btn btn-danger removemod" data-id="{{$mod->userId}}" data-cat="{{$mod->catId}}"><i class="fa fa-times" aria-hidden="true"></i> Remove</a>
    </div>
    @endforeach
</div>
