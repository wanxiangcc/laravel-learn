@extends('layouts.douban.app')

@section('pageTitle' , $article->title)

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-9">
			<div class="topic-detail panel panel-default">
				<!-- 标题begin -->
				<div class="panel-heading media clearfix">
					<div class="media-body">
						<h1 class="media-heading">{{ $article->title }}</h1>
						<div class="info">
							<a data-author="true" data-name="{{ $article->author_name }}" href="javascript:void(0)">{{ $article->author_name }}</a> 
							·&nbsp;于&nbsp;
							<span title="{{ $article->created_at }}">{{ $article->created_at }}</span>&nbsp;发布
						</div>
					</div>
					@if($article->belongAuthor && $article->belongAuthor->avatar)
					<div class="avatar media-right">
						<a href="javascript:;">
							<img class="media-object avatar-48" src="{{ asset($article->belongAuthor->avatar) }}" alt="">
						</a>
					</div>
					@endif
				</div>
				<!-- 标题end -->
				
				<!-- 内容begin -->
				<div class="panel-body markdown" id="content">
					{!! $article->content !!}
				</div>
				<!-- 内容end -->
				<div class="panel-footer clearfix">
					<div class="opts">
						<span class="mobile-hide">转载自：<a href="{{ $article->db_url }}" target="_blank">{{ $article->db_url }}</a></span>
						<span class="pull-right opts">
							<a class="" href="javascript:;"><i class="fa fa-heart-o"></i><span>喜欢</span></a>	
						</span>
					</div>
				</div>
			</div>
			@include('common.login')
		</div>
		<!-- 右侧边栏start -->
		<div class="sidebar col-md-3">
			<div class="user-card">
				@if($article->belongAuthor && $article->belongAuthor->avatar)
				<div class="pic">
					<img width="70px" height="70px" src="{{ asset($article->belongAuthor->avatar) }}" alt="" class="img-circle">
				</div>
				@endif
				<div class="info">
					<ul>
						<li class="name">{{ $article->author_name }}</li>
			      		<li class="home-link">
			      			<a href="javascript:;">主页</a>
			      		</li>
			      		<li class="name">&nbsp;</li>
			      		<li class="name">&nbsp;</li>
			    	</ul>
				</div>
				<!-- 
				<div class="opt">
	                <a href="javascript:;" onclick="alert('您无权限使用此功能...');" class="lnk-doumail">发豆邮</a>
					<a href="javascript:;" onclick="alert('您无权限使用此功能...');" class="add_contact">看相册</a>
				</div>
				-->
			</div>
			<!-- 最新文章start  -->
			<div class="panel panel-default">
	            <div class="panel-heading">最新帖子</div>
	            <ul class="list-group">
	            	@foreach($lastArticles as $article)
	                <li class="list-group-item">
	                    <a href="{{ url('db/show/'.$article->id) }}" target="_blank">{{ $article->title }}</a>
	                </li>
	                @endforeach
	            </ul>
            </div>
            <!-- 最新文章end  -->
		</div>
		<!-- 右侧边栏end -->
	</div>
</div>
@endsection

@section('customScript')
<script type="text/javascript" src="//cdn.bootcss.com/jquery_lazyload/1.9.7/jquery.lazyload.min.js"></script>
<script type="text/javascript">
var assetBase = "{{ asset('') }}";
$(function(){
	$('#content img').each(function(){
		$(this).attr('data-original' , assetBase + $(this).attr('data-original'));
	});
	$("img.lazyload").lazyload({effect: "fadeIn"});
});
</script>
@endsection