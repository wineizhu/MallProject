<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title></title>
  <link rel="stylesheet" href="../../layui/css/layui.css">
</head>
<body class="layui-layout-body layui-bg-gray">
    <div class="layui-bg-gray" style="padding:10px;">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md15">
                <div class="layui-panel" style="padding:10px;text-align:center">
                    <form class="layui-form layui-form-pane" action="">
                        <div class="layui-form-item layui-inline">
                            <label class="layui-form-label">date</label>
                            <div class="layui-input-inline">
                                <input type="text" name="out_date" id="out_date" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item layui-inline">
                            <button class="layui-btn layui-btn-md layui-btn-primary" lay-submit lay-filter="search">筛选</button>
                            <button class="layui-btn layui-btn-md">添加</button>
                        </div>
                    </form>
                </div>   
            </div>
        </div>
    </div>
    <div class="layui-bg-gray" style="padding:0 10px;">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md15">
                <div class="layui-panel" style="padding:10px;text-align:center">
                    <table id="main-table" class="layui-table"></table>
                </div>   
            </div>
        </div>
    </div>
    <script src="../../layui/layui.js"></script>
    <script>
        layui.use(["layer","table","laydate","form"],function(){
            var layer = layui.layer;
            var table = layui.table;
            var laydate = layui.laydate;
            var form = layui.form;
            var $ = layui.$;


        });
    </script>
</body>
</html>