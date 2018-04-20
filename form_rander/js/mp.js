/**
 * iframe间消息通信
 * @returns {undefined}
 */
if(typeof MP=="undefined"){
    MP = {loaded: true};
    (function(jQuery){
        "use strict";
        var MPBUS = {
            /**
             * 路由总线
             */
            routerBus: {},
            /**
             * 消息管理的窗口列表
             */
            allWin: [],
            /**
             *  消息总线的触发器
             * @param {type} event 事件类型
             * @param {type} data 事件数据
             * @param {type} type 数据类型
             * @returns {unresolved}
             */
            _fire: function(event, data, type){
                var me = this;
                for(var q in this.allWin){
                    var v = this.allWin[q];
                    var retOwn = false;
                    if(!v){
                        return;
                    }else {
                        try{
                            if(v.closed){
                                v = null;
                                delete this.allWin[q];
                                return;
                            }
                        }catch(e){
                            try{
                                v = null;
                                delete this.allWin[q];
                                return;
                            }catch(e){
                                return;
                            }
                        }
                    }
                    try{
                        retOwn = v.hasOwnProperty('MP'); //IE 6以下该行出错
                    }catch(err){
                        try{
                            retOwn = Object.prototype.hasOwnProperty.call(v, 'MP'); //firefox跨窗口是该行返回值错误
                        }catch(err1){
                            //ie8时没有.closed属性，所以catch异常删除过期window
                            delete this.allWin[q];
                            retOwn = false;
                        }
                    }
                    //if (Object.prototype.hasOwnProperty.call(v, 'MP') /*v.hasOwnProperty('MP')*/ && v.MP !== null && v.MP !== undefined) {
                    if(retOwn&&v.MP!==null&&v.MP!==undefined){
                        try{
                            v.MP._fire.call(v.MP, event, data, type);
                        }catch(e){
                            try{
                                delete this.allWin[q];
                                return;
                            }catch(e){
                                return;
                            }
                        }
                    }
                }
                //处理路由
                jQuery.each(this.routerBus, function(k, v){
                    if(k===event){
                        var target = v.target;
                        var func = v.func;
                        var newData = null;
                        try{
                            newData = func.call(this, data, type);
                        }catch(err){

                        }
                        if(newData!=undefined&&newData!=null){
                            var targetType;
                            var targetData;
                            if(Object.prototype.hasOwnProperty.call(newData, 'type') /*newData.hasOwnProperty('type')*/
                                    &&newData.type!=undefined){
                                targetType = newData.type;
                            }else {
                                targetType = type;
                            }
                            if(Object.prototype.hasOwnProperty.call(newData, 'data')/*newData.hasOwnProperty('data')*/
                                    &&newData.data!=undefined)
                            {
                                targetData = newData.data;
                            }else {
                                targetData = newData;
                            }
                            me._fire(target, targetData, targetType);
                        }
                    }
                });
            }
        };
        var MP = {
            /**
             * 消息总线所在窗口
             */
            BUSWINDOW: undefined,
            /**
             * 本窗口消息总线
             */
            eventBus: {},
            /**
             * 获得窗口的父窗口
             * @param {type} win
             * @returns {unresolved}
             */
            parentWindow: function(win){
                if(win===undefined||win===null)
                    return null;
                var parent = win.opener;
                if(parent&&parent!==win)
                    return parent;
                parent = win.parent;
                return parent!==win?parent:null;
            },
            /**
             *  获得消息总线窗口
             * @param {type} win
             * @returns {@exp;win@pro;MP_Windows}
             */
            _getBusWindow: function(win){
                if(win===undefined||win===null)
                    return null;
                if(jQuery.isPlainObject(win.MPBUS)){
                    return win;
                }else {
                    var pwin = this.parentWindow(win);
                    return this._getBusWindow(pwin);
                }
            },
            /**
             * 将自身窗口注册到消息总线中
             * @param {type} win
             * @returns {undefined}
             */
            register: function(win){
                var busWindow = this._getBusWindow(win);
                if(busWindow===undefined||busWindow===null){
                    window.MPBUS = MPBUS;
                    busWindow = window;
                }
                var haswindow = false;
                var mpbus = busWindow.MPBUS;
                for(var p in mpbus["allWin"]){
                    var a;
                    try{
                        a = mpbus["allWin"][p];
                        //这两句不能省，这句是为了ie8及以下判断window是否失效的
                        if(a.closed){
                            a = null;
                            delete mpbus["allWin"][p];
                        }
                        if(a===win)
                            haswindow = true;
                    }catch(e){
                        try{
                            delete mpbus["allWin"][p];
                        }catch(e){
//                            mpbus["allWin"].splice(p, 1);
//                            p--;
                        }

                    }
                }
                if(!haswindow)
                    busWindow.MPBUS.allWin.push(win);
                this.BUSWINDOW = busWindow;
            },
            /**
             *  向消息门户发送事件
             * @param {type} event 事件类型
             * @param {type} data 事件数据
             * @param {type} type 数据类型
             * @returns {unresolved}
             */
            send: function(event, data, type){
                if(jQuery.isWindow(this.BUSWINDOW)){
                    this.BUSWINDOW.MPBUS._fire(event, data, type);
                }
            },
            /**
             * 消息注册方法
             * @param {type} event
             * @param {type} func
             * @returns {undefined}
             */
            on: function(event, func){
                this.eventBus[event] = func;
            },
            /**
             * 消息路由方法
             *
             * @param {type} event 源消息名称
             * @param {type} target 目标消息名称
             * @param {type} func 数据转换方法，返回的数据使用 {data:newData,type:newType }形封装
             * @returns {undefined}
             */
            route: function(event, target, func){
                if(jQuery.isWindow(this.BUSWINDOW)){
                    this.BUSWINDOW.MPBUS.routerBus[event] = {target: target, func: func};
                }
            },
            /**
             * 本窗口事件触发器
             * @param {type} event
             * @param {type} data
             * @param {type} type
             */
            _fire: function(event, data, type){
                var me = this;
                jQuery.each(this.eventBus, function(k, v){
                    if(k===event){
                        v.call(me, data, type);
                    }
                });
            },
            /**
             * 反注册窗口
             * @param {type} win
             */
            unregister: function(win){
                var busWindow = this.BUSWINDOW;
                if(jQuery.isWindow(busWindow)){
                    var deleteIndex = -1;
                    for(var i = 0;i<busWindow.MPBUS.allWin.length;i++){
                        if(busWindow.MPBUS.allWin[i]===win){
                            deleteIndex = i;
                            break;
                        }
                    }
                }
                if(deleteIndex>=0){
                    busWindow.MPBUS.allWin.splice(deleteIndex, 1);
                }
            }
        };
        window.MP = MP;
        MP.register(window);

//        //window.onunload = MP.unregister(window);
    })(jQuery);
}