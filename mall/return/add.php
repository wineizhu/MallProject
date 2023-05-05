<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>报废</title>
    <link rel="stylesheet" href="../src/layui/css/layui.css">
    <link rel="stylesheet" href="../src/upload.css" />
</head>
<body class="layui-layout-body layui-bg-gray">
    <div class="layui-panel" style="margin:10px auto;text-align:center">
        <form action="" class="layui-form layui-form-pane" style="padding:10px">
            <div class="layui-form-item">
                <div class="layui-form-item layui-inline">
                    <label for="" class="layui-form-label">退货日期</label>
                    <div class="layui-input-inline">
                        <input type="text" name="dates" id="dates" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item layui-inline">
                    <label for="" class="layui-form-label">退货人</label>
                    <div class="layui-input-inline">
                        <input type="text" name="returner" id="returner" lay-verify="required" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item layui-inline">
                    <label for="" class="layui-form-label">原因</label>
                    <div class="layui-input-inline">
                        <input type="text" name="reason" id="reason" lay-verify="required" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item layui-inline layui-hide">
                    <label for="" class="layui-form-label">图片</label>
                    <div class="layui-input-inline">
                        <input type="text" name="pics" id="pics" class="layui-input">
                    </div>
                </div>
            </div>
            <div class="layui-form-item" id="hardwares">
                <div>
                    <div class="layui-form-item layui-inline">
                        <label class="layui-form-label">供应商</label>
                        <div class="layui-input-inline">
                            <select name="supplier0" id="supplier0" lay-filter="supplier0"></select>
                        </div>
                    </div>
                    <div class="layui-form-item layui-inline">
                        <label class="layui-form-label">品牌</label>
                        <div class="layui-input-inline">
                            <select name="brand0" id="brand0" lay-filter="brand0"></select>
                        </div>
                    </div>
                    <div class="layui-form-item layui-inline">
                        <label class="layui-form-label">名称</label>
                        <div class="layui-input-inline">
                            <select name="name0" id="name0"></select>
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
            <div class="layui-form-item">
                <button class="layui-hide" type="button" id="importModel">导入图片</button>
                <div class="layui-input-inlines-self" id="imgItemInfo">
                    <div class="layui-upload-drag-self" id="importImg0">
                        <div id="imgDivs0">
                            <i class="layui-icon" id="uploadIcon0"> &#xe624; </i>
                        </div>
                        <div class="img layui-hide" id="uploadDemoView0">
                            <img class="layui-upload-img" id="imgs0" src="">
                            <div class="handle layui-hide" id="handle0">
                                <i class="layui-icon icon-myself" id="preImg0">预览</i>
                                <i class="layui-icon icon-myself" id="delImg0">删除</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <button class="layui-btn layui-btn-sm" lay-submit lay-filter="*">submit</button>
            </div>
        </form>
    </div>
</body>

<script src="../src/layui/layui.js"></script>
<script>
    layui.use(["layer","laydate","upload","tree","form","element","table"],function(){
        var form = layui.form;
        var layer = layui.layer;
        var $ = layui.$;
        var table = layui.table;
        var laydate = layui.laydate;
        var element = layui.element;
        var upload = layui.upload;
        var datas = [];
        var x = 1;
        var supplier_list = [];

        laydate.render({
            elem:"#dates",
            type:"datetime",
            format:"yyyy-MM-dd",
            value:new Date()
        })

        $.ajax({
            url:"action.php?a=get_supplier",
            success:function(d){
                var data = JSON.parse(d)["data"]
                for(var i=0;i<data.length;i++){
                    supplier_list.push(data[i]['supplier'])
                }
                var str="<option value=''></option>"
                for(var i=0;i<supplier_list.length;i++){
                    str+="<option value='"+supplier_list[i]+"'>"+supplier_list[i]+"</option>"
                }
                $('#supplier0').html(str)
                // console.log(str)

                form.render('select')
            }
        })

        form.on("select(supplier0)",function(d){
            // console.log(d.value)
            $.ajax({
                url:"action.php?a=get_brand&supplier="+d.value,
                success:function(data){
                    data = JSON.parse(data)["data"]
                    var str="<option value=''></option>"
                    for(var i=0;i<data.length;i++){
                        str+="<option value='"+data[i][0]+"'>"+data[i][0]+"</option>"
                    }
                    console.log(str)
                    $('#brand0').html(str)
                    form.render('select')
                }
            })
            return false
        })

        form.on("select(brand0)",function(d){
            supplier = $("#supplier"+'0').val()
            console.log(supplier)
            $.ajax({
                url:"action.php?a=get_name&supplier="+supplier+"&brand="+d.value,
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

        form.on("submit(*)",function(data){
            var imgs = $('[id^=imgs]');
            var imgArray=[];
            imgs.each(function () {
                var url=$(this).attr('src');
                //滤空
                if (url){
                    imgArray.push(url);
                }
            });
            data.field.pics = imgArray.join(",");
            var base_info = data.field;
            var datas = [];
            datas.push(base_info);

            console.log(base_info)
            $.ajax({
                type:"post",
                data:base_info,
                url:"action.php?a=add&xx="+x,
                success:function(data){
                    layer.open({
                        type:1,
                        title:"添加",
                        content:data,
                        end:function(){
                            location.reload();
                        }
                    });
                }
            });
            return false;
        });

        //删除图片
        $(document).on('click', '[id^=delImg]', function () {
            var importImgF = $('#imgItemInfo').find('div:first');
            var empt = $(this).parent().parent().parent();
            var nextImgSrc = $(this).parent().parent().parent().next().find('img').attr('src');
            if (!nextImgSrc) {
                if (importImgF.attr('id') === empt.attr('id')) {
                    empt.find('img').attr('src','');
                    $(this).parent().parent().addClass('layui-hide');
                    importImgF.find('i:first').removeClass('layui-hide');
                    count--;
                    $('#' + 'importImg' + count).remove();
                } else {
                    empt.remove();
                }
            } else {
                empt.remove();
            }
            return false;
        });

        //图片预览
        $(document).on('click', '[id^=preImg]', function () {
            var iHtml = "<img src='" + $(this).parent().parent().find('img:first').attr('src') + "' style='width: 100%; height: 100%;'/>";
            layer.open({
                type: 1,
                shadeClose:true,
                scrollbar:false,
                content: iHtml
            });
            return false;
        });
        $(document).on("mouseenter", ".img", function () {
            $(this).find('div:first').removeClass('layui-hide');
        }).on("mouseleave", ".img", function () {
            $(this).find('div:first').addClass('layui-hide');
        });
        var imgsId,
            uploadDemoViewId,
            uploadIconId;
        $(document).on('click', '[id^=imgDivs]', function () {
            uploadIconId = $(this).find('i').attr('id');
            uploadDemoViewId = $(this).next().attr('id');
            imgsId = $(this).next().find('img').attr('id');
            $('#importModel').click();
        });
        var count = 1;

        upload.render({
            elem: '#importModel'
            , multiple: true
            , url: 'upload.php'
            , done: function (res) {
                if (res.code !== 0) {
                    return layer.msg('上传失败');
                }
                $('#' + imgsId).attr('src', res.data.src);
                $('#' + uploadDemoViewId).removeClass('layui-hide');
                $('#' + uploadIconId).addClass('layui-hide');
                $('#imgItemInfo').append(
                    '<div class="layui-upload-drag-self" id="importImg' + count + '">' +
                    '<div id="imgDivs' + count + '">' +
                    '<i class="layui-icon" id="uploadIcon' + count + '"> &#xe624; </i>' +
                    '</div>' +
                    '<div class="img layui-hide" id="uploadDemoView' + count + '">' +
                    '<img class="layui-upload-img" id="imgs' + count + '" src="">' +
                    '<div class="handle layui-hide" id="handle' + count + '">' +
                    '<i class="layui-icon icon-myself" id="preImg' + count + '">预览</i>' +
                    '<i class="layui-icon icon-myself" id="delImg' + count + '">删除</i>' +
                    '</div>' + '</div>' + '</div>'
                );
                imgsId = $("#imgDivs" + count).next().find('img').attr('id');
                uploadDemoViewId = $("#imgDivs" + count).next().attr('id');
                uploadIconId =  $("#imgDivs" + count).find('i').attr('id');
                count++;
                
            }
        });

        $("#adds").on("click",function(){   
            var y=x;  
            var str = "<div><div class='layui-form-item layui-inline'><label class='layui-form-label'>供应商</label><div class='layui-input-inline'><select name='supplier" + x + "' id='supplier" + x + "' lay-filter='supplier" + x + "'></select></div></div><div class='layui-form-item layui-inline'><label class='layui-form-label'>品牌</label><div class='layui-input-inline'><select name='brand" + x + "' id='brand" + x + "' lay-filter='brand" + x + "'></select></div></div><div class='layui-form-item layui-inline'><label class='layui-form-label'>名称</label><div class='layui-input-inline'><select name='name" + x + "' id='name" + x + "'></select></div></div><div class='layui-form-item layui-inline'><div class='layui-input-inline'><input type='number' name='num" + x + "' id='num" + x + "' placeholder='num' autocomplete='off' class='layui-input'></div></div>'<div class='layui-form-item layui-inline'><button type='button' class='layui-btn layui-btn-danger layui-btn-sm removeclass'><i class='layui-icon'>&#xe67e;</i></button></div></div>"
            $("#hardwares").append(str);
            // console.log(str)
            x++;

            var str="<option value=''></option>"
            for(var i=0;i<supplier_list.length;i++){
                str+="<option value='"+supplier_list[i]+"'>"+supplier_list[i]+"</option>"
            }
            $('#supplier'+y).append(str)
            // console.log(str)

            form.render('select')

            form.on("select(supplier"+y+")",function(d){
                console.log('y====>'+y)
                $.ajax({
                    url:"action.php?a=get_brand&supplier="+d.value,
                    success:function(data){
                        data = JSON.parse(data)["data"]
                        var str="<option value=''></option>"
                        for(var i=0;i<data.length;i++){
                            str+="<option value='"+data[i][0]+"'>"+data[i][0]+"</option>"
                        }
                        console.log(str)
                        $('#brand'+y).html(str)
                        form.render('select')
                    }
                })
                return false
            }) 

            form.on("select(brand"+y+")",function(d){
                supplier = $("#supplier"+y).val()
                console.log('y====>'+y)
                $.ajax({
                    url:"action.php?a=get_name&supplier="+supplier+"&brand="+d.value,
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


        $("body").on('click', ".removeclass", function () {
            //元素移除前校验是否被引用
            var approvalName = $(this).parent().prev('div.layui-input-inline').children().val();
            var parentEle = $(this).parent().parent();
            parentEle.remove();
        });
})
</script>
<script type="text/html" id="hw_list_toolbar">
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>
</html>