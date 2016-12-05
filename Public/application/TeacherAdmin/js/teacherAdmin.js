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

//报表结果检验
function tableResultCheck(table){
    $(table).find("tbody").find("tr").each(function(){
        var requiredPoint = parseFloat($(this).find(".t-requiredPoint").text());
        var nowPoint = parseFloat($(this).find(".t-nowPoint").text());
        var count = parseFloat($(this).find(".t-count").text());
        if(nowPoint >= requiredPoint && count <= 0){
            $(this).css("color","#0b8e2c");
        }
    });
}

(function($,document,window){
    tablePointCheck(".table-check-point"); //报表学分检验
    tableStatusCheck(".table-check-status"); //报表状态检验
    tableResultCheck(".table-check-result"); //报表结果检验
    $(".a-tips-date").text(clockObject.date + " " + clockObject.time); //更新时间设置
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
    $(".t-file").click(function(){ //设置PDF文件预览按钮
        myPDF.restart($(this).data("url"));
    });
    $('#modal-file').on('hidden.bs.modal', function (e) { //恢复默认预览文件
        myPDF.restart(appRoot+"/Uploads/test/default.pdf"); //刷新PDF预览
    });
    $('.t-ico').click(function(){ //审批时赋值当前项的标题和ID
        var parent = $(this).parent().parent();
        var title = parent.find(".t-title").find("span").text();
        var record = parent.data("value");
        $(".approval .f-title").text(title);
        $(".approval .f-record").val(record);
    });
    $(".r-select").change(function(){ //综合报告筛选
        $("#r-filter").submit();
    });
})(jQuery,document,window);