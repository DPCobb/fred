<div class="container-fluid">
    <h4>Find Categories to Follow</h4>
    <form id="cat-search" method="GET" action="/api/category/search">
        {{ csrf_field() }}
        <input type="text" name="catsearch" id="catsearch" required placeholder="Search for a category"/>
    </form>
    <div class="cat-list">

    </div>
    
</div>
