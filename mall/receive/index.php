
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
                        <div class="layui-form-item layui-inline">
                            <label class="layui-form-label">日期</label>
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
                    area:['1000px','600px'],
                    content:"add.php"
                });
                return false;
            });

            table.render({
                elem:"#main-table",
                url:"action.php?a=view",
                page:true,
                size:"sm",
                toolbar:true,
                limit:20,
                cols:[[
                    {field:"id",title:"id",align:"center",hide:true},
                    {field:"sn",title:"SN",align:"center"},
                    {field:"supplier",title:"供应商",align:"center"},
                    {field:"dates",title:"日期",align:"center"},
                    {field:"receiver",title:"收货人",align:"center"},
                    {field:"list_num",title:"订单号",align:"center"},
                ]],
                done:function(res,curr,count){
                    // console.log(res)
                }

            });

            table.on("rowDouble(test)",function(obj){
                var data = obj.data
                console.log(data)
                layer.open({
                    type:2,
                    content:"detail.php",
                    offset:"100px",
                    title:"详情",
                    area:['800px','600px'],
                    success:function(layero,index){
                        var iframe = window['layui-layer-iframe'+index];
                        iframe.child(data); 
                    }
                })
            })

            form.on("submit(search)",function(){
                var date = $("#out_date").val()
                console.log(date+typeof(date))

                table.reload("main-table",{
                    method:"post",
                    url:"action.php?a=search&date="+date,
                    page:{curr:1},
                    success:function(d){
                        // console.log(d)
                    }
                })

                return false;
            
            });

            $.ajax({
                url:"action.php?a=tree",
                success:function(data){
                    var datas = JSON.parse(data);
                    console.log(data)
                    tree.render({
                        elem:"#trees",
                        showLine:false,
                        data:datas["data"],
                        click: function(obj){
                            var data = obj.data;
                            console.log(data)
                            var id = data.id;
                            var sn = data.title;
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
                                table.reload("main-table",{
                                    method:"post",
                                    url:"action.php?a=view&sn="+sn,
                                    page:{curr:1}
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
