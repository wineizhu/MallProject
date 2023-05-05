
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
    <div class="layui-panel" style="margin:10px auto;padding:10px;text-align:center">
        <form action="" class="layui-form layui-form-pane" lay-filter="edit_form">
            <div class="layui-form-item">
                <div class="layui-form-item layui-hide">
                    <label for="" class="layui-form-label">id</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" name="id" id="id" disabled>
                    </div>
                </div>
                <div class="layui-form-item layui-inline">
                    <label for="" class="layui-form-label">box_num</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" name="box_num" id="box_num" disabled>
                    </div>
                </div>
                <div class="layui-form-item layui-inline">
                    <label for="" class="layui-form-label">detail</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" name="detail" id="detail" disabled>
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-form-item layui-inline">
                    <label for="" class="layui-form-label">brand</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" name="brand" id="brand" disabled>
                    </div>
                </div>
                <div class="layui-form-item layui-inline">
                    <label for="" class="layui-form-label">name</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" name="name" id="name" disabled>
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-form-item layui-inline">
                    <label for="" class="layui-form-label">num</label>
                    <div class="layui-input-inline">
                        <input type="number" class="layui-input" name="num" id="num">
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-form-item layui-inline">
                    <button class="layui-btn layui-btn-md" lay-submit lay-filter="out_submit">提交</button>
                </div> 
            </div>
        </form>
    </div>
        
</body>
<script src="../src/layui/layui.js"></script>
<script>
    function child(dataFromFather){
        layui.use(['layer','form','table'],function(){
            var layer = layui.layer
            var form = layui.form
            var table = layui.table
            var $ = layui.$

            form.val('edit_form',{
                "box_num":dataFromFather.box_num,
                "detail":dataFromFather.detail,
                "brand":dataFromFather.brand,
                "name":dataFromFather.name,
                "num":dataFromFather.num,
                "id":dataFromFather.id,
            })

            form.on("submit(out_submit)",function(data){
                $.ajax({
                    type:"post",
                    url:"action.php?a=edit",
                    data:data.field,
                    success:function(data){
                        layer.msg(data)
                    }
                })
                console.log(data.field)
                return false
            })
        
        })
    }
</script>
</html>