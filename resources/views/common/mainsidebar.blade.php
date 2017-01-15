<h4>Categories I Follow</h4>
@foreach ($cats as $cat)
    <h5><a href="/c/{{ strtolower($cat->name) }}" title="{{ ucfirst(strtolower($cat->name)) }}">{{ ucfirst(strtolower($cat->name)) }}</a></h5>
@endforeach
<h4>Categories I Moderate</h4>

<h4>Links</h4>
<a href="/activity/myposts">View my Posts</a>
