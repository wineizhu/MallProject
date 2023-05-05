<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title></title>
  <link rel="stylesheet" href="../src/layui/css/layui.css">
</head>
<body class="layui-layout-body layui-bg-gray">
    
    <div class="layui-bg-gray" style="margin:20px auto;padding:0 10px;">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md15">
                <div class="layui-panel" style="padding:10px;text-align:center">
                    <table id="main-table" class="layui-table"></table>
                </div>   
            </div>
        </div>
    </div>
    <div class="layui-bg-gray" style="padding:0 10px;">
        <div class="layui-panel" style="padding:10px;width: 60%;height:50%" id="container">
            <button class="layui-btn layui-btn-sm layui-btn-normal" id="show">全局展示</button>
            <button class="layui-btn layui-btn-sm layui-btn-normal layui-hide" id="part">局部展示</button>
            <div id="chart">
                <canvas id="myChart"></canvas>
            </div>
            
        </div>   

    </div>
    <script src="../src/layui/layui.js"></script>
    <script src="../src/chart/chart.js"></script>
    <script>
        layui.use(["layer","table","laydate","form"],function(){
            var layer = layui.layer;
            var table = layui.table;
            var laydate = layui.laydate;
            var form = layui.form;
            var $ = layui.$;

            $("#show").on("click",function(){
                $("#show").attr("class","layui-btn layui-btn-sm layui-btn-normal layui-hide")
                $("#part").attr("class","layui-btn layui-btn-sm layui-btn-normal")
                $("#chart").html("<canvas id='myChart'></canvas>")
                $.ajax({
                    url:"action.php?a=chart&type=all",
                    success:function(d){ 
                        console.log(d)
                        datas = JSON.parse(d)["data"]

                        const data = {
                            labels:datas["labels"],
                            datasets: [{
                                label: "全部商品",
                                backgroundColor: 'rgb(255, 99, 132)',
                                borderColor: 'rgb(255, 99, 132)',
                                data: datas["nums"],    
                            }]
                        };

                        const config = {
                            type: 'bar',
                            data: data,
                            options: {}
                        };

                        const myChart = new Chart(
                            document.getElementById('myChart'),
                            config
                        );
                    }
                })
            })

            $("#part").on("click",function(){
                $("#show").attr("class","layui-btn layui-btn-sm layui-btn-normal")
                $("#part").attr("class","layui-btn layui-btn-sm layui-btn-normal layui-hide")
                $("#chart").html("<canvas id='myChart'></canvas>")
                $.ajax({
                    url:"action.php?a=chart",
                    success:function(d){ 
                        datas = JSON.parse(d)["data"]
                        const data = {
                            labels:datas["labels"],
                            datasets: [{
                                label: "需进货商品",
                                backgroundColor: 'rgb(255, 99, 132)',
                                borderColor: 'rgb(255, 99, 132)',
                                data: datas["nums"],    
                            }]
                        };

                        const config = {
                            type: 'bar',
                            data: data,
                            options: {}
                        };

                        const myChart = new Chart(
                            document.getElementById('myChart'),
                            config
                        );
                    }
                })
            })

            table.render({
                elem:"#main-table",
                url:"action.php?a=get_info",
                // page:true,
                size:"sm",
                toolbar:true,
                cols:[[
                    {field:"nums",title:"商品种类",align:"center"},
                    {field:"receive",title:"今日进货数量",align:"center"},
                    {field:"sell",title:"今日出售数量",align:"center"},
                    {field:"back",title:"今日退货数量",align:"center"},
                ]],
                done:function(res,curr,count){
                    // console.log(res)
                }

            });

            $.ajax({
                url:"action.php?a=chart",
                success:function(d){ 
                    datas = JSON.parse(d)["data"]

                    const data = {
                        labels:datas["labels"],
                        datasets: [{
                            label: "需及时进货商品",
                            backgroundColor: 'rgb(255, 99, 132)',
                            borderColor: 'rgb(255, 99, 132)',
                            data: datas["nums"],    
                        }]
                    };

                    const config = {
                        type: 'bar',
                        data: data,
                        options: {}
                    };

                    const myChart = new Chart(
                        document.getElementById('myChart'),
                        config
                    );
                }
            })

        });
    </script>
</body>
</html>