
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title></title>
  <link rel="stylesheet" href="../src/layui/css/layui.css">
</head>
<body class="layui-layout-body layui-bg-gray">
    <div class="layui-bg-gray" style="padding:10px;">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md15">
                <div class="layui-panel" style="padding:10px;text-align:center">
                    <form class="layui-form layui-form-pane" action="">
                        <div class="layui-form-item">
                            <div class="layui-form-item layui-inline">
                                <label class="layui-form-label">供应商</label>
                                <div class="layui-input-inline">
                                    <select name="supplier" id="supplier" lay-verify="required" lay-filter="supplier">
                                    </select>

                                </div>
                            </div>
                            <div class="layui-form-item layui-inline">
                                <label class="layui-form-label">日期</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="date" id="date" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-form-item layui-inline">
                                <label class="layui-form-label">订单号</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="list_num" id="list_num" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item layui-inline">
                                <label class="layui-form-label">收货人</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="receiver" id="receiver" autocomplete="off" class="layui-input">
                                </div>
                            </div>     
                        </div>
                        <hr />
                        <div class="layui-form-item" id="hardwares">
                            <div>
                                <div class="layui-form-item layui-inline">
                                    <label class="layui-form-label">品牌</label>
                                    <div class="layui-input-inline">
                                        <input type="text" id="brand0" name="brand0" autocomplete="off" placeholder="brand" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-form-item layui-inline">
                                    <label class="layui-form-label">名称</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="name0" id="name0" autocomplete="off" placeholder="name" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-form-item layui-inline">
                                    <div class="layui-input-inline">
                                        <input type="number" name="num0" id="num0" placeholder="num" autocomplete="off" class="layui-input">
                                    </div>                               
                                </div>
                                <div class="layui-form-item layui-inline">
                                    <button id="adds" type="button" class="layui-btn layui-btn-warm layui-btn-sm"><i class="layui-icon">&#xe654;</i></button>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <div class="layui-form-item">
                            <div class="layui-form-item layui-inline">
                                <button class="layui-btn layui-btn-md" lay-submit lay-filter="out_submit">提交</button>
                                <button type="reset" class="layui-btn layui-btn-md layui-btn-primary">重置</button>
                            </div> 
                        </div>   
                    </form>
                </div>   
            </div>
        </div>
    </div>
    <script src="../src/layui/layui.js"></script>
    <script>
        layui.use(["layer","table","laydate","form"],function(){
            var layer = layui.layer;
            var table = layui.table;
            var laydate = layui.laydate;
            var form = layui.form;
            var $ = layui.$;
            var x=1;
            var brand=[]

            laydate.render({
                elem:'#date',
                value:new Date(),
            })

            $.ajax({
                type:"post",
                url:"action.php?a=get_supplier",
                success:function(d){
                    var supplier=[]
                    console.log(d)
                    suppliers = JSON.parse(d)
                    console.log(suppliers)
                    for(var i=0;i<suppliers['data'].length;i++){
                        supplier.push(suppliers['data'][i]['supplier'])
                        
                    }

                    console.log(supplier)
                    
                    var str="<option value=''></option>"
                    for(var i=0;i<supplier.length;i++){
                        str+="<option value='"+supplier[i]+"'>"+supplier[i]+"</option>"
                    }
                    $('#supplier').html(str)

                    form.render('select')
                }

            })

            form.on("submit(out_submit)",function(data){
                $.ajax({
                    type:"post",
                    url:"action.php?a=add&x="+x,
                    data:data.field,
                    success:function(d){
                        layer.open({
                            title:"add",
                            type:1,
                            content:d,
                            end:function(){location.reload();}
                        });
                        // console.log(d)
                    }

                });

                console.log(data.field)

                return false;
            });


            $("#adds").on("click",function(){     
                var str = "<div><div class='layui-form-item layui-inline'><label class='layui-form-label'>品牌</label><div class='layui-input-inline'><input name='brand" + x + "' id='brand" + x + "' placeholder='brand' autocomplete='off' class='layui-input'></div></div><div class='layui-form-item layui-inline'><label class='layui-form-label'>名称</label><div class='layui-input-inline'><input type='text' name='name" + x + "' id='name" + x + "' class='layui-input' placeholder='name' autocomplete='off'></div></div><div class='layui-form-item layui-inline'><div class='layui-input-inline'><input type='number' name='num" + x + "' id='num" + x + "' placeholder='num' autocomplete='off' class='layui-input'></div></div>'<div class='layui-form-item layui-inline'><button type='button' class='layui-btn layui-btn-danger layui-btn-sm removeclass'><i class='layui-icon'>&#xe67e;</i></button></div></div>"
                $("#hardwares").append(str);

                

                form.render('select')
                
                x++;
            });
            $("body").on('click', ".removeclass", function () {
                //元素移除前校验是否被引用
                var approvalName = $(this).parent().prev('div.layui-input-inline').children().val();
                var parentEle = $(this).parent().parent();
                parentEle.remove();
            });

        });
    </script>
</body>
</html>