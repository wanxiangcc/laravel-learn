<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('pageTitle') {{ $config['site_name'] }}</title>
    <link href="//cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" />
    <!-- 
    <link href="//cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" rel="stylesheet" />
   	 -->
   	<link href="{{ asset('css/douban.css') }}" rel="stylesheet" />
    <script src="//cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    @yield('customCss')
</head>
<body id="app-layout">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">
                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    YangMao.group
                </a>
            </div>
            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <ul class="nav navbar-nav">
                    <li><a href="{{ url('/db') }}">豆瓣</a></li>
                </ul>
            </div>
        </div>
    </nav>
    @yield('content')
    
    <footer class="footer">
		<div class="container">
			<div class="media">
				<div class="media-body">
					<div class="links">
						@foreach($footerLinks as $key => $link)
						@if($key > 0)
						&nbsp;/&nbsp;
						@endif
						<a href="{{ $link->url }}">{{ $link->name }}</a>
						@endforeach
					</div>
					<div class="copyright">
						{{ $config['description'] }}<br/> 
						Copyright©2017~ {{ $config['domain'] }} {{ $config['icp'] }} <br/> 
						声明：本站所有图片来源于互联网！若有任何版权问题，请联系我们，我们将立即予以纠正注明来源！
					</div>
				</div>
				<div class="media-right" style="width: 200px; text-align: right;">
					&nbsp;
				</div>
			</div>
		</div>
	</footer>
    
    @yield('customScript')
</body>
</html>