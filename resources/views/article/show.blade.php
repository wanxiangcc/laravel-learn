<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Learn Laravel 5</title>

    <link href="//cdn.bootcss.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="//cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script src="{{ URL::asset('js/jquery/jquery.cookie.js') }}"></script>
</head>

    <div id="content" style="padding: 50px;">

        <h4>
            <a href="/"><< 返回首页</a>
        </h4>

        <h1 style="text-align: center; margin-top: 50px;">{{ $article->title }}</h1>
        <hr>
        <div id="date" style="text-align: right;">
            {{ $article->updated_at }}
        </div>
        <div id="content" style="margin: 20px;">
            <p>
                {{ $article->body }}
            </p>
        </div>

        <div id="comments" style="margin-top: 50px;">

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>操作失败</strong> 输入不符合要求<br><br>
                    {!! implode('<br>', $errors->all()) !!}
                </div>
            @endif

            <div id="new">
                <form id="commentForm" action="{{ url('comment') }}" method="POST" onsubmit="return submitComment()">
                    <input type="hidden" name="article_id" value="{{ $article->id }}">
                    <div class="form-group">
                        <label>昵称</label>
                        <input type="text" id="nickname" name="nickname" class="form-control" style="width: 300px;" required="required">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" id="email" name="email" class="form-control" style="width: 300px;">
                    </div>
                    <div class="form-group">
                        <label>主页</label>
                        <input type="text" id="website" name="website" class="form-control" style="width: 300px;">
                    </div>
                    <div class="form-group">
                        <label>内容</label>
                        <textarea name="content" id="newFormContent" class="form-control" rows="10" required="required"></textarea>
                    </div>
                    <button type="submit" class="btn btn-lg btn-success col-lg-12">提交</button>
                </form>
            </div>

            <script>
            function reply(a) {
              var nickname = a.parentNode.parentNode.firstChild.nextSibling.getAttribute('data');
              var textArea = document.getElementById('newFormContent');
              textArea.innerHTML = '@'+nickname+' ';
            }
            // 异步提交评论
            function submitComment(){
                // laravel 默认在cookie中加入了XSRF-TOKEN
                // ajax 全局默认设置
            	$.ajaxSetup({
	           	     headers: {
	           	         'X-XSRF-TOKEN': $.cookie('XSRF-TOKEN')
	           	     }
	           	});
            	$.ajax( {
            	    url: "{{ url('comment') }}" ,
            	    type: "POST" ,
            	    data:{
                	    "nickname" : $('#nickname').val(),
                	    "email" : $('#email').val(),
                	    "website" : $('#website').val(),
                	    "content" : $('#newFormContent').val(),
                	    "article_id":"{{ $article->id }}"
                	},
            	    dataType:"json",
            	    success: function( data, textStatus, jqXHR ){
                	    if(data.result == 1){
                	    	$('#commentForm')[0].reset();
                    	    $('.conmments').append(data.html);
                	    }
            	    } ,
            	    error: function(jqXHR, textStatus, errorMsg){
            	        alert(errorMsg);        
            	    }
            	});
            	return false;
            }
            </script>

            <div class="conmments" style="margin-top: 100px;">
                @foreach ($article->hasManyComments as $comment)

                    <div class="one" style="border-top: solid 20px #efefef; padding: 5px 20px;">
                        <div class="nickname" data="{{ $comment->nickname }}">
                            @if ($comment->website)
                                <a href="{{ $comment->website }}">
                                    <h3>{{ $comment->nickname }}</h3>
                                </a>
                            @else
                                <h3>{{ $comment->nickname }}</h3>
                            @endif
                            <h6>{{ $comment->created_at }}</h6>
                        </div>
                        <div class="content">
                            <p style="padding: 20px;">
                                {{ $comment->content }}
                            </p>
                        </div>
                        <div class="reply" style="text-align: right; padding: 5px;">
                            <a href="#new" onclick="reply(this);">回复</a>
                        </div>
                    </div>

                @endforeach
            </div>
        </div>

    </div>

</body>
</html>