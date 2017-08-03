@extends('layouts.douban.app')

@section('customCss')
<!-- bootstrap 教程 -->
<!-- http://v3.bootcss.com/components/#thumbnails -->
<style type="text/css">
<!--
-->
</style>
@endsection

@section('content')
<div class="container">
    <ul class="nav nav-pills">
    	@foreach ($indexCategory as $cat)
	  	<li @if($cat->id == $cid) class="active" @endif><a href="{{ url('db/cat/'.$cat->id) }}">{{ $cat->name }}</a></li>
	  	@endforeach
	</ul>
	<div class="well well-sm" style="margin-top: 20px;">here is tips or message</div>
	<div class="row">
		@foreach ($articles as $article)
		<div class="col-xs-6 col-md-3 img_single" >
		    <div class="thumbnail">
		      <a href="{{ url('db/show/'.$article->id)}}" target="_blank">
		      	<img data-original="{{ asset($article->thumb_img) }}" alt="{{ $article->title }}" class="thumbnail_img lazyload">
		      </a>
		      <div class="caption">
		        <a href="{{ url('db/show/'.$article->id)}}" target="_blank"><h4 class="cutstr">{{ $article->title }}</h4></a>
		      </div>
		    </div>
		</div>
        @endforeach
    </div>
    {{ $articles->links() }}
    
</div>
@endsection

@section('customScript')
<script type="text/javascript" src="//cdn.bootcss.com/jquery_lazyload/1.9.7/jquery.lazyload.min.js"></script>
<script type="text/javascript">
$("img.lazyload").lazyload({effect: "fadeIn"});
</script>
@endsection