
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
            <div class="layui-col-md12">
                <div class="layui-panel" style="padding:10px;text-align:center">
                    <form class="layui-form layui-form-pane" action="">
                        <div class="layui-form-item layui-inline">
                            <label class="layui-form-label">brand</label>
                            <div class="layui-input-inline">
                                <input type="text" name="brand" id="brand" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item layui-inline">
                            <label class="layui-form-label">name</label>
                            <div class="layui-input-inline">
                                <input type="text" name="name" id="name" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item layui-inline">
                            <button class="layui-btn layui-btn-md layui-btn-primary" lay-submit lay-filter="search">筛选</button>
                            <button class="layui-btn layui-btn-md" id="add">添加</button>
                        </div>
                    </form>
                </div>   
            </div>
        </div>
    </div>
    <div class="layui-bg-gray" style="padding:0 10px;">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md2" style="overflow-y:scroll;height:500px">
                <div class="layui-panel" style="padding:10px;text-align:center">
                    <div id="trees" style="align:left"></div>
                </div>  
            </div>
            <div class="layui-col-md10">
                <div class="layui-panel" style="padding:10px;text-align:center">
                    <table id="main-table" class="layui-table" lay-filter="test"></table>
                </div>   
            </div>
        </div>
    </div>
    <script src="../src/layui/layui.js"></script>
    <script type="text/html" id="barDemo">
        <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit">编辑</a>
        <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="sell">出售</a>
    </script>
    <script>
        layui.use(["layer","table","laydate","form","tree"],function(){
            var layer = layui.layer;
            var table = layui.table;
            var laydate = layui.laydate;
            var form = layui.form;
            var $ = layui.$;
            var tree = layui.tree;

            laydate.render({
                elem:"#out_date"
            })


            $("#add").on("click",function(data){
                layer.open({
                    type:2,
                    offset:"100px",
                    title:"添加",
                    area:['1080px','600px'],
                    content:"add.php",
                    end:function(){location.reload();},
                    success:function(layero,index){
                        $(".layui-laypage-btn").click()
                    }
                });
                return false;
            });

            table.render({
            elem:"#main-table",
            url:"action.php?a=view",
            page:true,
            size:"sm",
            toolbar:true,
            cols:[[
                    {field:"id",title:"id",align:"center",hide:true},
                    {field:"box_num",title:"box_num",align:"center"},
                    {field:"detail",title:"位置",align:"center",width:65},
                    {field:"brand",title:"brand",align:"center",width:150},
                    {field:"name",title:"name",align:"left",minWidth:260},
                    {field:"num",title:"num",align:"center",width:88},
                    {fixed:"right",title:"操作",toolbar:'#barDemo',width:130,align:'center'}
                    
            ]],
            done:function(res,curr,count){
                    // console.log(res)
                }

            });

            table.on('tool(test)',function(obj){
                var data = obj.data;
                var id = data.id

                console.log(obj.event)
                if(obj.event == 'edit'){
                    layer.open({
                        type:2,
                        offset:'100px',
                        title:"更改",
                        content:"edit.php?id="+id,
                        area:['800px','500px'],
                        end:function(){location.reload();},
                        success:function(layero,index){
                            var iframe = window['layui-layer-iframe'+index];
                            iframe.child(data); 
                        }
                    }) 
                }else{
                    layer.open({
                        type:2,
                        offset:'100px',
                        title:"出售",
                        content:"sell.php?id="+id,
                        area:['800px','500px'],
                        end:function(){location.reload();},
                        success:function(layero,index){
                            var iframe = window['layui-layer-iframe'+index];
                            iframe.child(data); 
                        }
                    }) 
                }
                               
            })


            form.on("submit(search)",function(){
                var brand = $("#brand").val()
                var name = $("#name").val()

            
                table.reload("main-table",{
                    method:"post",
                    url:"action.php?a=search&brand="+brand+"&name="+name,
                    page:{curr:1},
                    limit:10
                });

                return false;
            
            });

            $.ajax({
                url:"action.php?a=tree",
                success:function(data){
                    var datas = JSON.parse(data);
                    // console.log(datas['data'])
                    // console.log(data)
                    tree.render({
                        elem:"#trees",
                        showLine:false,
                        data:datas["data"],
                        click: function(obj){
                            
                            var data = obj.data;
                            var id = data.id;
                            var nodes = document.getElementsByClassName("layui-tree-txt");
                            for(var i = 0; i<nodes.length;i++){
                                if(nodes[i].innerHTML === data.title){
                                    if(data.children == null || data.children.length == 0){
                                        nodes[i].style.color = '#FF0000';
                                        nodes[i].checked = true;
                                    }else{
                                        nodes[i].style.color = '#00FF00';
                                    }
                                }else{
                                    nodes[i].style.color = '#555';
                                }
                            }
                            if(id){
                                console.log(data)
                                box_num = data.title
                                // console.log('box_num==>'+box_num)
                                table.reload("main-table",{
                                    method:"post",
                                    url:"action.php?a=view&box_num="+box_num,
                                    page:{curr:1},
                                    limit:10
                                });
                            }                           
                        }
                       
                    });
                }
            });



    

        });

        

        
    </script>
</body>
</html>
