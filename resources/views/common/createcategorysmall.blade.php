<form class="cat-form small-cat-form" method="POST" action="/api/newcategory">
    <div class="well">By creating this category you will be the Admin!</div>
    {{ csrf_field() }}
    <div class="form-group">
        <input type="text" name="newcat" id="newcat" required placeholder="Category Name"/>
    </div>
    <button type="submit" class="createcat"><i class="fa fa-check" aria-hidden="true"></i></button>
</form>
<div class="alert">

</div>
