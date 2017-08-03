<!-- 
extends('layouts.app')
这表示此视图的基视图是 resources/views/layouts/app.blade.php 。
这个函数还隐含了一个小知识：在使用名称查找视图的时候，可以使用 . 来代替 / 或 \
 -->
@extends('layouts.app')

@section('content')
	<div id="content">
        <ul>
            @foreach ($articles as $article)
            <li style="margin: 50px 0;">
                <div class="title">
                    <a href="{{ url('article/'.$article->id) }}">
                        <h4>{{ $article->title }}</h4>
                    </a>
                </div>
                <div class="body">
                    <p>{{ $article->body }}</p>
                </div>
            </li>
            @endforeach
        </ul>
        {{ $articles->links() }}
    </div>
@endsection