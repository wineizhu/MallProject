<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>详情</title>
    <link rel="stylesheet" href="../src/layui/css/layui.css">
</head>
<body class="layui-layout-body layui-bg-gray">
    <form action="" class="layui-form layui-form-pane" lay-filter="edit_form">
        <div class="layui-panel" style="padding:10px;text-align:center">
            <div class="layui-form-item">
                <div class="layui-form-item layui-inline">
                    <label for="" class="layui-form-label">SN</label>
                    <div class="layui-input-inline">
                        <input type="text" id="sn" name="sn" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item layui-inline">
                    <label for="" class="layui-form-label">supplier</label>
                    <div class="layui-input-inline">
                        <input type="text" id="supplier" name="supplier" class="layui-input">
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-form-item layui-inline">
                    <label for="" class="layui-form-label">订单号</label>
                    <div class="layui-input-inline">
                        <input type="text" id="list_num" name="list_num" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item layui-inline">
                    <label for="" class="layui-form-label">收货人</label>
                    <div class="layui-input-inline">
                        <input type="text" id="receiver" name="receiver" class="layui-input">
                    </div>
                </div>
            </div>
        </div>

        <div class="layui-panel" style="margin:10px auto">
            <table class="layui-table" id="main-table" lay-filter="test"></table>
        </div>
    </form>
</body>
<script src="../src/layui/layui.js"></script>
<script>
    function child(dataFromFather){
        layui.use(['layer','form','table'],function(){
            var layer = layui.layer;
            var form = layui.form;
            var $ = layui.$;
            var table = layui.table;

            form.val('edit_form',{
                "sn":dataFromFather.sn,     
            })
            var sn = $("#sn").val();
            // console.log('sn是'+sn)

            $.ajax({
                url:"action.php?a=get_info&sn="+sn,
                success:function(data){
                    // console.log(data)
                    var datas = JSON.parse(data)["data"][0]
                    console.log(datas["supplier"])
                    $("#supplier").val(datas["supplier"])
                    $("#list_num").val(datas["list_num"])
                    $("#receiver").val(datas["receiver"])
                }
            })

            table.render({
                elem:"#main-table",
                url:"action.php?a=get_detail&sn="+sn,
                page:true,
                limit:20,
                toolbar:true,
                size:"sm",
                cols:[[
                    {field:'brand',title:"brand",align:'center'},
                    {field:'name',title:"name",align:'center'},
                    {field:'num',title:"num",align:'center'},
                ]]

            })


            

        })
    }
</script>
</html>