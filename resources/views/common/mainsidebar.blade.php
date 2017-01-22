<h4>Links</h4>
<a href="/activity/myposts">View my Posts</a>
<h4>Categories I Follow</h4>
@foreach ($cats as $cat)
    <h5><a href="/c/{{ strtolower($cat->name) }}" title="{{ ucfirst(strtolower($cat->name)) }}">{{ ucfirst(strtolower($cat->name)) }}</a></h5>
@endforeach
<h4>Categories I Moderate</h4>
@foreach ($admin as $ad)
    <h5><a href="/c/{{strtolower($ad->name)}}" title ="{{ucfirst(strtolower($ad->name))}}">{{ucfirst(strtolower($ad->name))}}</a></h5>
@endforeach
<h4 class="create">Create a New Category</h4>
<!-- Create new category form -->
@include ('common/createcategorysmall')
