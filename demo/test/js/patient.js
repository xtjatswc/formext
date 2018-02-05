//表单验证
$(function () {
    //让当前表单调用validate方法，实现表单验证功能
    $("#formAnswer").validate({
        debug: true, //调试模式，即使验证成功也不会跳转到目标页面
        rules: {     //配置验证规则，key就是被验证的dom对象，value就是调用验证的方法(也是json格式)
            radio_group_1: {
                required: true,
            },
            check_group_45: {
                required: true,
            },
            ///Content/imgs/1.png
            ///Content/imgs/2.png
            //
            //您所在医院的名称
            text_155:

                {
                    required: true,
                    rangelength: [4, 30],
                },

            //手机
            text_156:

                {
                    required: true,
                    rangelength: [11, 11],
                },

            //E-mail
            text_157:

                {
                    required: true,
                    email: true,
                },


            //第五部分：营养知识来源（此部分可多选）
            //您的营养知识来源为（可多选）
            check_group_45:

                {
                    required: true,
                },

        },
        messages: {
            //您所在医院的名称
            text_155:

                {
                    required: "请输入您所在医院的名称",
                    rangelength: $.validator.format("医院的名称长度必须在：{0}-{1}之间"),

                },
            //手机
            text_156:

                {
                    required: "请输入您的手机号",
                    rangelength: $.validator.format("手机号的长度必须为11位"),

                },

            //E-mail
            text_157:

                {
                    required: "请输入您的E-mail",
                    email: "邮箱格式不正确",

                },

            //第一部分：一般情况
            //您是
            radio_group_1:

                {
                    required: "该项为必填项",

                },

            //第五部分：营养知识来源（此部分可多选）
            //您的营养知识来源为（可多选）
            check_group_45:

                {
                    required: "该项为必填项",

                },

        },
        errorPlacement: function (error, element) { //指定错误信息位置
            if (element.is(':radio') || element.is(':checkbox')) { //如果是radio或checkbox
                var eid = element.attr('name'); //获取元素的name属性
                error.insertBefore(element.parent().parent()); //将错误信息添加当前元素的父结点后面
            } else {
                error.insertAfter(element);
            }
        }
    });

    $('#btnSubmit').on('click', function () {

        if ($("#formAnswer").valid()) {
        } else {
            alert("提交失败，请按页面提示，将问卷填写完整后再提交！");
            $("#formAnswer").submit();
            return;
        }
    
    });
});

