
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
                            <label class="layui-form-label">date</label>
                            <div class="layui-input-inline">
                                <input type="text" name="out_date" id="out_date" autocomplete="off" class="layui-input">
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
            <div class="layui-panel" style="padding:10px;text-align:center">
                <table id="main-table" class="layui-table" lay-filter="test"></table>
            </div>   
            <div class="layui-panel" style="margin:10px;padding:10px;text-align:center">
                <table id="show" class="layui-table" lay-filter="show"></table>
            </div>
        </div>
    </div>
    <script src="../src/layui/layui.js"></script>
    <script type="text/html" id="barDemo">
        <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit">编辑</a>
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
                    area:['1260px','600px'],
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
                    {field:"dates",title:"dates",align:"center"},
                    {field:"returner",title:"returner",align:"center"},
                    {field:"reason",title:"reason",align:"center"},
                    
            ]],
            done:function(res,curr,count){
                    // console.log(res)
                }

            });

            table.render({
                elem:"#show",
                url:"action.php?a=show",
                page:true,
                size:"sm",
                toolbar:true,
                cols:[[
                        {field:"id",title:"id",align:"center",hide:true},
                        {field:"supplier",title:"supplier",align:"center"},
                        {field:"brand",title:"brand",align:"center"},
                        {field:"name",title:"name",align:"center"},
                        {field:"num",title:"num",align:"center"},
                        
                ]],
                done:function(res,curr,count){
                        // console.log(res)
                    }

            });

            table.on('tool(test)',function(obj){
                var data = obj.data;
                var id = data.id
                console.log(id)
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
            })

            table.on("rowDouble(test)",function(obj){
                var data = obj.data
                console.log(obj.data.id)
                table.reload("show",{
                    method:"post",
                    url:"action.php?a=show&id="+data.id,
                    page:{curr:1},
                    limit:10
                });
            })


            form.on("submit(search)",function(){
                var date = $("#out_date").val()

            
                table.reload("main-table",{
                    method:"post",
                    url:"action.php?a=search&date="+date,
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
