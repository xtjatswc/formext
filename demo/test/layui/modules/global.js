/**

 layui官网

*/

layui.define(['code', 'element', 'table', 'util'], function(exports){
  var $ = layui.jquery
  ,element = layui.element
  ,layer = layui.layer
  ,form = layui.form
  ,util = layui.util
  ,device = layui.device()

  ,$win = $(window), $body = $('body');


  //阻止IE7以下访问
  if(device.ie && device.ie < 8){
    layer.alert('Layui最低支持ie8，您当前使用的是古老的 IE'+ device.ie + '，你肯定不是程序猿吧！');
  }

  layer.ready(function(){
    var local = layui.data('layui');

    //升级提示
    if(local.version && local.version !== layui.v){
      layer.open({
        type: 1
        ,title: '更新提示' //不显示标题栏
        ,closeBtn: false
        ,area: '300px;'
        ,shade: false
        ,offset: 'b'
        ,id: 'LAY_updateNotice' //设定一个id，防止重复弹出
        ,btn: ['更新日志', '朕不想升']
        ,btnAlign: 'c'
        ,moveType: 1 //拖拽模式，0或者1
        ,content: ['<div class="layui-text">'
          ,'layui 已更新到：<strong style="padding-right: 10px; color: #fff;">v'+ layui.v + '</strong> <br>请注意升级！'
        ,'</div>'].join('')
        ,skin: 'layui-layer-notice'
        ,yes: function(index){
          layer.close(index);
          setTimeout(function(){
            location.href = '/doc/base/changelog.html';
          }, 500);
        }
        ,end: function(){
          layui.data('layui', {
            key: 'version'
            ,value: layui.v
          });
        }
      });
    }
    layui.data('layui', {
      key: 'version'
      ,value: layui.v
    });


    //公告
    layui.data('layui', {
      key: 'notice_20171212'
      ,remove: true
    });
    //return;

    if(local.notice_20171212) return;
    layer.open({
      type: 1
      ,title: '活动提示' //不显示标题栏
      ,closeBtn: false
      ,area: '300px;'
      ,shade: false
      ,offset: 'b'
      ,id: 'LAY_Notice' //设定一个id，防止重复弹出
      ,btn: ['了解详情', '朕不想听']
      ,btnAlign: 'c'
      ,moveType: 1 //拖拽模式，0或者1
      ,content: ['<div class="layui-text">'
        ,'<a href="http://fly.layui.com/jie/20572/" target="_blank" style="color: #fff;"> LayIM 限时特惠来袭，自动授权 </a>'
      ,'</div>'].join('')
      ,skin: 'layui-layer-notice'
      ,success: function(layero){
        var btn = layero.find('.layui-layer-btn');
        btn.find('.layui-layer-btn0').attr({
          href: 'http://fly.layui.com/jie/20572/'
          ,target: '_blank'
        });
      }
      ,end: function(){
        layui.data('layui', {
          key: 'notice_20171212'
          ,value: new Date().getTime()
        });
      }
    });
    
  });


  //点击事件
  var events = {
    //联系方式
    contactInfo: function(){
      layer.alert('<div class="layui-text">微信：<a href="//cdn.layui.com/upload/2018_3/168_1521968603171_77146.jpg" target="_blank">layui-kf</a><br>邮箱：xianxin@layui-inc.com</div>', {
        title:'联系'
        ,btn: false
        ,shadeClose: true
      });
    }
  }

  $body.on('click', '*[site-event]', function(){
    var othis = $(this)
    ,attrEvent = othis.attr('site-event');
    events[attrEvent] && events[attrEvent].call(this, othis);
  });


  //搜索组件
  form.on('select(component)', function(data){
    var value = data.value;
    location.href = '/doc/'+ value;
  });

  //切换版本
  form.on('select(tabVersion)', function(data){
    var value = data.value;
    location.href = value === 'new' ? '/' : ('/' + value + '/doc/');
  });

  //数字前置补零
  var digit = function(num, length, end){
    var str = '';
    num = String(num);
    length = length || 2;
    for(var i = num.length; i < length; i++){
      str += '0';
    }
    return num < Math.pow(10, length) ? str + (num|0) : num;
  };


  //下载倒计时
  var setCountdown = $('#setCountdown');
  if($('#setCountdown')[0]){
    $.get('/api/getTime', function(res){
      util.countdown(new Date(2017,7,21,8,30,0), new Date(res.time), function(date, serverTime, timer){
        var str = digit(date[1]) + ':' + digit(date[2]) + ':' + digit(date[3]);
        setCountdown.children('span').html(str);
      });
    },'jsonp');
  }  

  //让导航在最佳位置
  var thisItem = $('.site-demo-nav').find('dd.layui-this');
  if(thisItem[0]){
    var itemTop = thisItem.offset().top
    ,winHeight = $(window).height()
    ,elemScroll = $('.layui-side-scroll');
    if(itemTop > winHeight - 120){
      elemScroll.animate({'scrollTop': itemTop/2}, 200)
    }
  }

  //点击查看代码选项
  // element.on('tab(demoTitle)', function(obj){
  //   if(obj.index === 1){
  //     if(device.ie && device.ie < 9){
  //       layer.alert('强烈不推荐你通过ie8/9 查看代码！因为，所有的标签都会被格式成大写，且没有换行符，影响阅读');
  //     }
  //   }
  // })

  element.on('nav(lay_nav_left)', function(obj){
    var id = new Date().getTime();
    element.tabAdd('lay_tab_main', {
      title: '新选项'+ (Math.random()*1000|0) //用于演示
      ,content: '内容'+ (Math.random()*1000|0)
      ,id: id //实际使用一般是规定好的id，这里以时间戳模拟下
    });
    element.tabChange('lay_tab_main', id);
  })

  //Hash地址的定位
  var layid = location.hash.replace(/^#lay_tab_main=/, '');
  element.tabChange('lay_tab_main', layid);
  
  element.on('tab(lay_tab_main)', function(elem){
    location.hash = 'lay_tab_main='+ $(this).attr('lay-id');
  });

  exports('global', {});
});