
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
                                <label class="layui-form-label">位置</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="detail" id="detail" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item layui-inline">
                                <label class="layui-form-label">备注</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="note" id="note" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                        </div>
                        <hr />
                        <div class="layui-form-item" id="hardwares">
                            <div>
                                <div class="layui-form-item layui-inline">
                                    <label class="layui-form-label">brand</label>
                                    <div class="layui-input-inline">
                                        <select name="brand0" id="brand0" class="brand_select" lay-filter="brand0" lay-search>
                                        </select>
                                    </div>
                                </div>
                                <div class="layui-form-item layui-inline">
                                    
                                        <select name="name0" id="name0" lay-search>
                                        </select>                              
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
            var brand_list=[]

            $.ajax({
                    type:"post",
                    url:"action.php?a=get_brand",
                    success:function(d){
                        
                        brand = JSON.parse(d)["data"]
                        for(var i=0;i<brand.length;i++){
                            brand_list.push(brand[i]['brand'])
                            
                        }                  
                        // // console.log(hw_list)
                        var str="<option value=''></option>"
                        for(var i=0;i<brand_list.length;i++){
                            str+="<option value='"+brand_list[i]+"'>"+brand_list[i]+"</option>"
                        }
                        $('#brand0').html(str)
                        // console.log(str)

                        form.render('select')
                    }

            })

            form.on("select(brand0)",function(d){
                // console.log(d.value)
                $.ajax({
                    url:"action.php?a=get_name&brand="+d.value,
                    success:function(data){
                        data = JSON.parse(data)["data"]
                        var str="<option value=''></option>"
                        for(var i=0;i<data.length;i++){
                            str+="<option value='"+data[i][0]+"'>"+data[i][0]+"</option>"
                        }
                        console.log(str)
                        $('#name0').html(str)
                        form.render('select')
                    }
                })
                return false
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
                            end:function(){location.reload();},
                            success:function(layero,index){
                                $(".layui-laypage-btn").click()
                            }
                        });
                        // console.log(d)
                    }

                });
                // console.log(data.field)

                return false;
            });


            
            $("#adds").on("click",function(){ 
                var y = x;
                var str = "<div><div class='layui-form-item layui-inline'><label class='layui-form-label'>brand</label><div class='layui-input-inline'><select name='brand" + x + "' id='brand" + x +"' class='brand_select' lay-filter='brand" + x + "' lay-search></select></div></div>" + "<div class='layui-form-item layui-inline'><select name='name" + x + "' id='name" + x + "' lay-search></select></div>" + "<div class='layui-form-item layui-inline'><div class='layui-input-inline'><input type='number' name='num" + x + "' placeholder='0' autocomplete='off' class='layui-input'></div></div>" + '<div class="layui-form-item layui-inline"><button type="button" class="layui-btn layui-btn-danger layui-btn-sm removeclass"><i class="layui-icon">&#xe67e;</i></button></div></div>';
                $("#hardwares").append(str);
                
                var str="<option value=''></option>"
                for(var i=0;i<brand_list.length;i++){
                    str+="<option value='"+brand_list[i]+"'>"+brand_list[i]+"</option>"
                }
                $('.brand_select').append(str)
                // console.log(str)

                form.render('select')
                
                x++; 

                form.on("select(brand"+y+")",function(d){
                    console.log('y====>'+y)
                    $.ajax({
                        url:"action.php?a=get_name&brand="+d.value,
                        success:function(data){
                            data = JSON.parse(data)["data"]
                            var str="<option value=''></option>"
                            for(var i=0;i<data.length;i++){
                                str+="<option value='"+data[i][0]+"'>"+data[i][0]+"</option>"
                            }
                            console.log(str)
                            $('#name'+y).html(str)
                            form.render('select')
                        }
                    })
                    return false
                }) 
                
            });

            // $('#part').on('change',function(){
            //     var part_va = $("#part").val()
            //     // console.log(part_va+typeof(part_va))
            //     window.area_va = part_va[0].toUpperCase()
            //     $('#area').val(window.area_va)
            // })

            // $('#area').val(window.area_va)

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
