<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../src/layui/css/layui.css">
</head>
<body class="layui-layout-body layui-bg-gray">
    <div class="layui-panel" style="margin:10px;padding:10px;text-align:center">
        <form action="" class="layui-form layui-form-pane">
            <div class="layui-form-item">
                <div class="layui-form-item layui-inline">
                    <label for="" class="layui-form-label">action</label>
                    <div class="layui-input-inline">
                        <input type="text" id="action" name="action" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item layui-inline">
                    <label for="" class="layui-form-label">date</label>
                    <div class="layui-input-inline">
                        <input type="text" id="date" name="date" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item layui-inline">
                    <button class="layui-btn layui-btn-md" lay-submit lay-filter="search">筛选</button>
                </div>
            </div>
        </form>
    </div>
    <div class="layui-panel" style="margin:10px;padding:10px;text-align:center">
        <table class="layui-table" id="main-table" lay-filter="main-table"></table>
    </div>
</body>
<script src="../src/layui/layui.js"></script>
<script>
    layui.use(['table','layer','laydate','form'],function(){
        var table = layui.table
        var laydate = layui.laydate
        var form = layui.form
        var $ = layui.$

        

        laydate.render({
            elem:"#date",
            // value:new Date()
        })
        
        table.render({
            elem:"#main-table",
            url:"action.php?a=view",
            page:true,
            size:"sm",
            toolbar:true,
            limit:10,
            cols:[[
                {field:"id",title:"id",align:"center",hide:true},
                {field:"dates",title:"dates",align:"center"},
                {field:"action",title:"action",align:"center"},
                {field:"brand",title:"brand",align:"center"},
                {field:"name",title:"name",align:"center"},
                {field:"num",title:"num",align:"center"},
                {field:"detail",title:"detail",align:"center"},
            ]]
        })

        form.on("submit(search)",function(d){
            console.log("dasfd")
            var date = $("#date").val()
            var action = $("#action").val()

            // $.ajax({
            //     url:"action.php?a=search&date="+date+"&action="+action,
            //     success:function(d){
            //         console.log(d)
            //     }
            // })

            table.reload('main-table',{
                method:"post",
                url:"action.php?a=search&date="+date+"&action="+action,
                page:true,
                limit:10,
                success:function(d){
                    console.log(d)
                }
            })
            return false
        })
    })
</script>
</html>