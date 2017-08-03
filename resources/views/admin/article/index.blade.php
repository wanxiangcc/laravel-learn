@extends('layouts.admin.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">文章管理</div>
                <div class="panel-body">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            {!! implode('<br>', $errors->all()) !!}
                        </div>
                    @endif
                    <a href="{{ url('admin/article/create') }}" class="btn btn-lg btn-primary">新增</a>
                    <div id="contentDiv">
                    	@include('admin.article.data')
                    </div>
                </div>
                <div>
                	{{ $articles->links('common.ajaxPage') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script type="text/javascript">
function getData(page){
	$.ajax({
		type : 'GET',
	    url: "{{ url('admin/article') }}" ,
	    data : {
	    	'page' : page
	    },
	    dataType: "json",
	    contentType: "application/x-www-form-urlencoded; charset=utf-8",
	    success: function(data) {
	    	if(data.result == 1){
	    		$('#contentDiv').html(data.html);
	    	}else{
	    	}
	    }
	});
}
</script>