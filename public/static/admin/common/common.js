function searchproduct() {
    var keyword = $('#keyword').val();
    if (keyword == undefined || keyword == null || keyword == "") {
        layer.open({
            title: '警告',
            content: '搜索内容不能为空',
            shade: [0.3, '#888'],
            time: 2000,

        });
        return false;
    }

    $('#searchform').submit();
}


function layer_laert() {
    layer.msg('功能暂未开放');
}

//关闭模态框
function sure_layer() {

}

function go(url) {
    window.location(url);
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
$(".college").change(function(){
    college =$(this).val();

//    抛送请求
    url = SCOPE.college;
    postData = {'college': college};
    $.post(url, postData, function (result) {
        if(result.status == 1){
            data = result.data;
            major_html = '';
            $(data).each(function (i) {
                major_html += "<option value='"+this.major+"'>"+this.major+"</option>";
            });

            $(".major").html(major_html);

        } else if(result.status == 0){
            $(".major").html('');
        }
    },'json');

});

//验证手机格式
$(".tel").blur(function () {
    myreg = /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
    tel = $(this).val();

    if(!myreg.test(tel)){
        layer.msg("请输入正确的手机号");
        return false;
    }
});

//加载更多
