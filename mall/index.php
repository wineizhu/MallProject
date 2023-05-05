
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title></title>
  <link rel="stylesheet" href="./src/layui/css/layui.css">
</head>
<body class="layui-layout-body">
	<ul class="layui-nav layui-bg-green">
	<div style="text-align:center;padding-right:100px;">
		<li class="layui-nav-item layui-this"><a href="./sum/" target="main-iframe">统计</a></li>
		<li class="layui-nav-item"><a href="./spare/" target="main-iframe">商品</a></li>
        <li class="layui-nav-item"><a href="./receive/" target="main-iframe">进货</a></li>
		<li class="layui-nav-item"><a href="./record/" target="main-iframe">流动记录</a></li>
		<li class="layui-nav-item"><a href="./store/" target="main-iframe">货架</a></li>
		<li class="layui-nav-item"><a href="./return/" target="main-iframe">退货</a></li>
	</div>
	<div style="float:right;margin-top:-50px;">
	<!-- <form method='post' action='./conf/login_check.php'><li  class="layui-nav-item" style="margin-left:20px;"><button type='submit' name='logout' value='logout'>logout</button></li></form> -->
	</div>
	</ul>
	<div>
	<iframe style="width:100%;min-height:850px;" frameboder="0" name="main-iframe" id="main-iframe" src="./sum/"></iframe>
	</div>  
    <script src="./src/layui/layui.js"></script>
</body>
<script>
	layui.use(["layer","table","laydate","form","tree"],function(){
    var layer = layui.layer;
    var table = layui.table;
    var laydate = layui.laydate;
    var form = layui.form;
    var $ = layui.$;
    var tree = layui.tree;

})
</script>
</html>