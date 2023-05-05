<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>库存</title>
    <link rel="stylesheet" href="../src/layui/css/layui.css">
    <script src="../src/layui/layui.js"></script>
</head>
<body class="layui-layout-body">
    <div class="layui-bg-gray" style="padding:10px">
        <div class="layui-panel" style="padding:10px;text-align:center">
            <form action="" class="layui-form layui-form-pane">
                <div class="layui-form-item layui-inline">
                    <label for="" class="layui-form-label">brand</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" name='brand' id="brand" lay-filter='brand'>
                    </div>
                </div>
                <div class="layui-form-item layui-inline">
                    <label for="" class="layui-form-label">name</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" name='name' id="name" lay-filter='name'>
                    </div>
                </div>
                <div class="layui-form-item layui-inline">
                    <button class="layui-btn layui-btn-md" lay-submit lay-filter="search">筛选</button>
                </div>
            </form>
        </div>
        
        <div class="layui-panel" style="margin:10px auto;text-align:center">
            <table class="layui-table" id="main-table" lay-filter="main-table"></table>
        </div>
        
    </div>
    
</body>
<script>
    layui.use(['form','table'],function(){
        var table = layui.table
        var $ = layui.$
        var form = layui.form

        table.render({
            elem:"#main-table",
            url:"action.php?a=view",
            page:true,
            limit:20,
            size:"sm",
            cols:[[
                {field:"id",title:"ID",align:"center",hide:true},
                {field:"name",title:"名称",align:"center"},
                {field:"brand",title:"品牌",align:"center"},
                {field:"supplier",title:"供应商",align:"center"},
                {field:"num",title:"数量",align:"center"},
            ]]
        })

        $.ajax({
            url:"action.php?a=view",
            success:function(d){

            }
        })

        form.on("submit(search)",function(d){
            var brand = $("#brand").val()
            var name = $("#name").val()
            table.reload("main-table",{
                method:"post",
                url:"action.php?a=search&brand="+brand+"&name="+name,
                page:{curr:1},
                success:function(d){
                    console.log(d)
                }
            })
            return false    
        })
    })
</script>
</html>