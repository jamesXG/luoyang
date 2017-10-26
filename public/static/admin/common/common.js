$("#keyword").focus(function () {
    $(".container,.mainheader").fadeOut(300);
    $("#SearchMain").slideDown(800, 'linear');
});

$("#content").on('input propertychange', function () {
    str = $(this).val();
    $(".searchContainer").hide();
    url = SCOPE.searchAjax;
    postData = {'str': str};
    if(str != '') {
        $.post(url, postData, function (res) {
            if (res.status == 1) {
                data = res;
                major_html = '';
                $(data).each(function () {
                    $(this.data).each(function () {
                        ID = this.id;
                        hrefUrl = SCOPE.searchIndex + ID;
                        major_html += "<a href='" + hrefUrl + "'><li>" + this.name + "</li></a>";
                    });
                });
                $(".predictionCen").html(major_html);
            } else if (res.status == 0) {
                $(".predictionCen").html('');
            }
        });
    }else{
        $(".predictionCen").html('');
    }
});

//搜索页面的输入框失去焦点时
$("#content").blur(function () {
    $(".searchContainer").fadeIn();
});

//点击指定内容显示指定搜索的框
$(".listP").click(function () {
    //获取元素的class值
    name = $(this).attr("class");
    switch (name){
        case 'main listP name':
            $("#searchCss").removeClass('right-search').attr('class', 'top-search-name');
            $(".predictionCen").css('top','0');
            $("#content").attr('placeholder', '搜索姓名');
            break;
        case 'main listP stu_num':
            $("#searchCss").removeClass('right-search').attr('class', 'top-search-stu_num');
            $(".predictionCen").css('top','0');
            $("#content").attr('placeholder', '搜索学号');
            break;
        case 'main listP room':
            $("#searchCss").removeClass('right-search').attr('class', 'top-search-room');
            $(".predictionCen").css('top','0');
            $("#content").attr('placeholder', '搜索房间:安园16#111');
            break;
        case 'main listP tel':
            $("#searchCss").removeClass('right-search').attr('class', 'top-search-tel');
            $(".predictionCen").css('top','0');
            $("#content").attr('placeholder', '搜索电话');
            break;
        default:
            layer.msg('error');
    }

    $(".searchContainer").fadeOut();
});


function layer_laert() {
    layer.msg('功能暂未开放');
}

//显示弹出层
function edit(title, url) {

    var index = layer.open({
        type: 2,
        closeBtn: 2,
        shadeClose: true,
        title: title,
        content: url,
        closeBtn: 1
    });
    layer.closeAll(index);
}

// 获取学院一下的相关专业
$(".college").change(function () {
    college = $(this).val();

//    抛送请求
    url = SCOPE.college;
    postData = {'college': college};
    $.post(url, postData, function (result) {
        if (result.status == 1) {
            data = result.data;
            major_html = '';
            $(data).each(function (i) {
                major_html += "<option value='" + this.major + "'>" + this.major + "</option>";
            });

            $(".major").html(major_html);

        } else if (result.status == 0) {
            $(".major").html('');
        }
    }, 'json');

});

//验证手机格式
$(".tel").blur(function () {
    myreg = /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
    tel = $(this).val();

    if (!myreg.test(tel)) {
        layer.msg("请输入正确的手机号");
        return false;
    }
});

//验证输入的房间+床位数据是否符合规格
$('#reviseRoom').on("input propertychange", function () {
    // alert(5);
    reg = /^\d{3}-\d{1}$/;
    str = $(this).val();

    if (!reg.test(str)) {
        layer.msg('输入不符合规定');
        $('button:submit').css('display', 'none');
        return false;
    }
    $('button:submit').fadeIn().css('display', '');
// 获取楼座信息
    gallery = $("#galleryOption").val();
    room = gallery + '#' + str;  //安园#101-2
    url = SCOPE.room;

    postData = {'room': room};
    $.post(url, postData, function (res) {
        major_html = '';
        if (res.status == 1) {
            $('button:submit').css('display', '');

        } else if (res.status == 0) {
            layer.msg('此床位已被占');
            $('button:submit').css('display', 'none');

        }
    }, 'json');

    return true;
});

//验证退宿
$("#reason").on("input propertychange", function () {
    reg = /^[\u4E00-\u9FA5\d0-9]{2,}$/;  //匹配中文+数字
    str = $(this).val();
    if (!reg.test(str)) {
        layer.msg('输入不符合规范[中文、数字]');
        $('button:submit').css('display', 'none');
        return false;
    }
    $('button:submit').fadeIn().css('display', '');
    return true;
});

//入住验证输入的学号以及入住刷新
$(document).ready(function () {
    $(".checkroom").on('click', function () {
        var str = $(this).parent().parent().children('td').find('input:text').val();
        var id = $(this).parent().parent().children('td').find('input:hidden').val();
        reg = /^[0-9]{9}$/;
        if (!reg.test(str)) {
            layer.msg('请输入正确的学号');
            return false;
        }
        url = SCOPE.room;
        postData = {'stu_num': str, 'id': id};
        $.post(url, postData, function (res) {
            if (res.status == 0) {
                data = res.data;
                if (data != null) {
                    if (data.room != null)
                        layer.msg('此人已入住');
                    return false;
                } else {
                    layer.msg('查无此人');
                    return false;
                }
            } else if (res.status == 1) {
                data = res.data;
                console.log(data);
                window.location.reload();
                return true;
            }
        }, 'json');
    });
});

