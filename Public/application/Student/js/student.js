//报表学分检验
function tablePointCheck(table){
    var sum = 0; //共修学分
    var i = 0; //未满要求数
    $(table).find("tbody").find("tr").each(function(){
        var minPoint = parseFloat($(this).find(".t-minPoint").text());
        var maxPoint = parseFloat($(this).find(".t-maxPoint").text());
        var nowPoint = parseFloat($(this).find(".t-nowPoint").text());
        var tag = $(this).find(".t-tag").text();
        sum += nowPoint;
        if(tag == "必修"){
            $(this).css("font-weight","bold");
            if(nowPoint < minPoint){
                i++;
                $(this).css("color","#CA5803");
            }else{
                $(this).css("color","#048A23");
            }
        }
    });
    $(".a-sum").text(sum);
    $(".a-count").text(i);
}

//报表状态检验
function tableStatusCheck(table){
    $(table).find("tbody").find("tr").each(function(){
        var status = $(this).find(".t-status");
        if(status.text().indexOf("不通过") >= 0){
            $(this).css("font-weight","bold");
            $(this).css("color","red");
        }else if(status.text().indexOf("待商议") >= 0){
            $(this).css("font-weight","bold");
            $(this).css("color","#e0c31b");
        }else if(status.text().indexOf("已通过") >= 0){
            $(this).css("font-weight","bold");
            $(this).css("color","#0b8e2c");
        }else if(status.text().indexOf("待学院审批") >= 0){
            $(this).css("font-weight","bold");
            $(this).css("color","#00BE87");
        }
    });
}

function uploadChange(){  //文件上传
    var oldUrl = $(".f-url").val();
    var data = { oldUrl : "" ,type : "pdf" };
    if(oldUrl.replace(/ /g,"") != ""){
        data = { oldUrl : oldUrl , type : "pdf" };
    }
    new uploadFiles({
        initialize : function(){
            $(".f-tips").html("<span class='f-loading'></span> 上传中...");
        },
        fileElementId : "f-file",
        data : data,
        callback : function(file,status){
            if(status){
                if(typeof(file) == "string"){
                    $(".f-tips").html("上传失败，请重试");
                    alert(file); //弹出错误报告
                }else{
                    //console.log(file);
                    $(".f-url").val(file.savepath + file.savename); //表单赋值
                    $(".f-tips").html(file.savename); //修改反馈提示
                    myPDF.restart(appUpload + file.savepath + file.savename); //刷新PDF预览
                }
            }else{
                $(".f-tips").html("上传失败，请重试");
            }
            //解决change单次使用
            $("#f-file").replaceWith("<input id='f-file' class='f-file' name='f-file' type='file' accept='.pdf' data-random='"+ Math.random() +"'>"); //覆盖原来的上传控件
            $(".f-file").change(uploadChange); //绑定上传控件改变时的上传方法
        }
    }).startUpload();
}

(function($,document,window){
    tablePointCheck(".table-check-point"); //报表学分检验
    tableStatusCheck(".table-check-status"); //报表状态检验
    $(".h-title").text($(".t-active").text()); //页面标题设置
    $(".a-tips-date").text(clockObject.date + " " + clockObject.time); //更新时间设置
    $(".t-delete").each(function(){ //设置删除按钮
        var t_val = $(this).find(".t-ico").data("value");
        if(t_val){
            $(this).on('click',function(){
                if(confirm("确认删除吗？")){
                    $.ajax({
                        cache: true,
                        type: "POST",
                        url: deleteUrl,
                        data: {"recordId" : t_val},
                        async: false,
                        error: function(data) {
                            alert("操作发生异常");
                        },
                        success: function(data) {
                            if(data.status){
                                alert("操作成功");
                                window.location.reload(true);
                            }else{
                                alert(data.msg);
                            }
                        }
                    });
                }
            });
        }else{
            $(this).css("cursor","not-allowed");
            $(this).find(".t-ico").attr("src",appPublic+"/application/Student/img/notDelete.png")
        }
    });
    $(".t-file").click(function(){ //设置PDF文件预览按钮
        myPDF.restart($(this).data("url"));
    });
    $('#modal-file').on('hidden.bs.modal', function (e) { //恢复默认预览文件
        myPDF.restart(appRoot+"/Uploads/test/default.pdf"); //刷新PDF预览
    });
    $(".f-file").change(uploadChange); //绑定上传控件改变时的上传方法
    $('.dropdown-menu li a').click(function(){ //自定义下拉框实现
        var selectText = $(this).text();
        $('.dropdown-menu li a').removeClass();
        $(this).addClass("active");
        $("#select-text").text(selectText);
        $(".table-check-status").find("tbody").find("tr").each(function(){ //表单筛选
            var status = $(this).find(".t-status");
            if(status.text().indexOf(selectText) >= 0 || selectText.indexOf("全部") >= 0){
                $(this).show();
            }else{
                $(this).hide();
            }
        });
    });
})(jQuery,document,window);