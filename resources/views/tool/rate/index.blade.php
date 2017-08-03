<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>分期费率计算 {{ config('app.name') }}</title>
	<link href="//cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css"
		rel="stylesheet" />
	<script src="//cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
	<script src="//cdn.bootcss.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	<style type="text/css">
	.input-group{
		margin-bottom:10px
	}
	</style>
</head>
<body>
	<nav class="navbar navbar-default navbar-static-top">
		<div class="container">
			<div class="navbar-header">
				<a class="navbar-brand" href="{{ url('/') }}"> {{
					config('app.name') }} </a>
			</div>
		</div>
	</nav>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<!-- content -->
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title">分期费率计算</h3>
					</div>
					<div class="panel-body">
						<div class="alert alert-danger" role="alert" style="display:none">
						</div>
						<div class="input-group">
						  <span class="input-group-addon">￥</span>
						  <input type="text" class="form-control" placeholder="金额" id="amount" aria-label="Amount (to the nearest dollar)">
						</div>
						<div class="input-group">
						  <span class="input-group-addon">期数</span>
						  <input type="text" class="form-control" placeholder="分期月数" id="months" aria-label="Amount (to the nearest dollar)">
						</div>
						<div class="input-group">
						  <input type="text" class="form-control" placeholder="每期手续费率" id="percentPerMonth" aria-describedby="basic-addon2">
						  <span class="input-group-addon" id="basic-addon2">%</span>
						</div>
						<p>如果你有一个理财产品，请填写</p>
						<div class="input-group">
						  <input type="text" class="form-control" placeholder="年化收益率" id="leveragePercent" aria-describedby="basic-addon2">
						  <span class="input-group-addon" id="basic-addon2">%</span>
						</div>
						
						<ul class="list-group" style="display:none">
						  <li class="list-group-item">
						    <span class="badge" id="interestAmount"></span>
						   	 支付手续费
						  </li>
						  <li class="list-group-item">
						    <span class="badge" id="occupyAmountForOneYear"></span>
						   	占用银行一年的钱数
						  </li>
						  <li class="list-group-item">
						    <span class="badge" id="realInterest"></span>
						   	分期实际年化利率
						  </li>
						  <li class="list-group-item">
						    <span class="badge" id="leverageInterest"></span>
						   	 理财收益
						  </li>
						  <li class="list-group-item">
						    <span class="badge" id="lastMoney"></span>
						   	 最终实际盈亏
						  </li>
						</ul>
						<button class="btn btn-lg btn-success col-xs-12" onclick="cacl()">计算</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Scripts -->
	<script type="text/javascript">
	function cacl() {
		$('.alert').hide();
	    var amount = parseFloat($('#amount').val());
	    var months = parseInt($('#months').val());
	    var percentPerMonth = parseFloat($('#percentPerMonth').val());
	    if(isNaN(amount)){
	    	$('.alert').html('请输入正确的金额').show();
		    return false;
	    }
	    if(isNaN(months)){
	    	$('.alert').html('请输入正确的期数').show();
		    return false;
	    }
	    if(isNaN(percentPerMonth)){
	    	$('.alert').html('请输入正确的月费率').show();
		    return false;
	    }
	    var leveragePercent = parseFloat($('#leveragePercent').val());
	    var realInterest = calculateRealInterest(amount, months, percentPerMonth);
	    var leverageInterest = calculateLeverageInterest(amount, months, leveragePercent);
	    display(realInterest, leverageInterest);
	}
	function display(realInterest, leverageInterest) {
		$('#interestAmount').html(realInterest.interestAmount);
		$('#occupyAmountForOneYear').html(parseFloat(realInterest.occupyAmountForOneYear).toFixed(2));
		$('#realInterest').html(parseFloat(realInterest.realInterest * 100).toFixed(2) + '%');
	    if (!isNaN(leverageInterest)) {
	    	$('#leverageInterest').html(leverageInterest);
	    	$('#lastMoney').html( parseFloat(leverageInterest - realInterest.interestAmount).toFixed(2) );
	    	$('#leverageInterest').parent().show();
	    	$('#lastMoney').parent().show();
	    }else{
	    	$('#leverageInterest').parent().hide();
	    	$('#lastMoney').parent().hide();
	    }
	    $('.list-group').show();
	}

	function calculateRealInterest(amount, months, percentPerMonth) {
	    var interestAmount = amount * percentPerMonth / 100 * months; //总手续费
	    var amountPermonth = amount / months; //每期占用银行金额
	    var occupyMonths = (1 + months) * (months / 2); //总占用月数
	    var occupyYears = occupyMonths / 12; //占用年数
	    var occupyAmountForOneYear = amountPermonth * occupyYears; //相当于占用了一年的金额
	    var realInterest = interestAmount / occupyAmountForOneYear; //实际年化利率
	    return {
	        interestAmount: interestAmount,
	        occupyAmountForOneYear: occupyAmountForOneYear,
	        realInterest: realInterest
	    };
	}
	function calculateLeverageInterest(amount, months, leveragePercent) {
	    var perMonthInterest = leveragePercent / 100 / 12;
	    var interestAmount = (1 + months) * months / 2 * perMonthInterest * amount / months;
	    return interestAmount;
	}
	</script>
</body>
</html>
