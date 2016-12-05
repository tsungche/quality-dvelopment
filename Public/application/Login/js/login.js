/**
 * Created by HYH on 2015-10-15.
 */

$(document).ready(function(){
    //刷新类型
    var type = $(".dropdown-menu").data("value");
    $('.dropdown-menu li a').each(function(){
        $(this).removeClass();
        if($(this).data("value") == type){
            $(this).addClass("active");
            $("#select-text").text($(this).text());
        }
    });
});

//自定义下拉框实现
$('.dropdown-menu li a').click(function(){
    $('.dropdown-menu li a').removeClass();
    $(this).addClass("active");
    $("#select-text").text($(this).text());
    $("#select-type").val($(this).data("value"));
});


//刷新验证码
$(".form-code").click(function(){
    var verifyimg = $("#codeImg").attr("src");
    $("#codeImg").attr("src", verifyimg.replace(/\?.*$/,'')+'?'+Math.random());
});

//非空验证
function is_empty(){
    var flag = true;
    $(".form-text").each(function(){
        if($(this).val().replace(/ /g,"") == ""){
            $(this).parent().find(".form-tips").text('请输入' + $(this).attr('placeholder'));
            flag = false;
        }
    });
    return flag;
}

//清除提示信息
$(".form-text").focus(function(){
    $(this).next().text("");
});