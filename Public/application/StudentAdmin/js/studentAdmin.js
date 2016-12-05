
function uploadChange(){  //文件上传
    var oldUrl = $(".f-url").val();
    var data = { oldUrl : "" ,type : "pdf" ,"userType":"admin" };
    if(oldUrl.replace(/ /g,"") != ""){
        data = { oldUrl : oldUrl , type : "pdf" ,"userType":"admin" };
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
    $(".h-title").text($(".t-active").text()); //页面标题设置
    $(".a-tips-date").text(clockObject.date + " " + clockObject.time); //更新时间设置
    $(".t-file").click(function(){ //设置PDF文件预览按钮
        myPDF.restart($(this).data("url"));
    });
    $(".f-file").change(uploadChange); //绑定上传控件改变时的上传方法
    $(".tag-item").click(function(){ //标签菜单切换
        $(".tag-content").hide();
        $(".tag-item").removeClass("tag-active");
        $($(this).data("tag")).show();
        $(this).addClass("tag-active");
    });
    $("#u-xls").change(function(){ //获取上传文件名
        var file = $(this).val();
        $(".u-tips").text(file.replace(/^.+?\\([^\\]+?)(\.[^\.\\]*?)?$/gi,"$1") + "." + file.replace(/.+\./,""));
    });
    $("#btn-close").click(function(){
        $('#m-tips').modal('hide')
    }); //点击关闭模态框
    $('#m-tips').on('hide.bs.modal', function (e) { //关闭模态框时刷新界面
        //window.location.reload(true);
        window.location.href = subjectUrl;
    });
    $(".t-delete").each(function(){ //设置删除按钮
        var t_val = $(this).find(".t-ico").data("value");
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
    });
    $(".select-all:checkbox").change(function(){  //全选或反选
        $(".select-item:checkbox").prop("checked",!!$(this).prop("checked"));
    });
    $(".select-item:checkbox").change(function(){  //取消全选
        if(!$(this).attr("checked")){
            $(".select-all:checkbox").attr("checked",false);
        }
    });
})(jQuery,document,window);