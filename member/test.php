<html>

<head>

<script type="text/javascript" src="jquery_1_7_2.js"></script>

<script type="text/javascript">

var data={url:"https://www.btc-8.com/API.php?type=ticker&symbol=BTC_CNY"}

$.ajax({

	type: "POST",

    url: "https://www.btc-8.com/member/apipost.php",

	data: data,

    dataType:'json',

    success: function(result){

 $("#images").html("最低价："+result.ticker.low + "最高价："+result.ticker.high);
   /* $.each(result.ticker, function(i, field){

      $("#images").append(field.low + " ");

    });*/

	}

});

</script>

</head>



<body>

<div id="images"></div>
<div id="images2"></div>
</body>

</html>