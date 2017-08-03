@extends('layouts.admin.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">管理面板</div>
                <div class="panel-body">
                    <a href="{{ url('admin/article') }}" class="btn btn-lg btn-success col-xs-12">文章管理</a>
                    <p>&nbsp;</p>
                    <a href="{{ url('admin/comment') }}" class="btn btn-lg btn-success col-xs-12">评论管理</a>
                    <p>&nbsp;</p>
                    <a href="{{ url('admin/clearCache') }}" class="btn btn-lg btn-success col-xs-12">清除缓存</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection