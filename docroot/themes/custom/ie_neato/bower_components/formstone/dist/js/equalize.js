/*! formstone v1.4.22 [equalize.js] 2021-10-01 | GPL-3.0 License | formstone.it */
!function(e){"function"==typeof define&&define.amd?define(["jquery","./core","./mediaquery"],e):e(jQuery,Formstone)}(function(t,e){"use strict";function n(){u=t(d.element)}function i(e){if(e.data&&(e=e.data),e.enabled)for(var t,n,i,r=0;r<e.target.length;r++){n=t=0,(i=e.$el.find(e.target[r])).css(e.property,"");for(var a=0;a<i.length;a++)t<(n=i.eq(a)[e.type]())&&(t=n);i.css(e.property,t)}}function r(e){for(var t=0;t<e.target.length;t++)e.$el.find(e.target[t]).css(e.property,"");e.$el.find("img").off(o.namespace)}var a=e.Plugin("equalize",{widget:!0,priority:5,defaults:{maxWidth:1/0,minWidth:"0px",property:"height",target:null},methods:{_construct:function(e){e.maxWidth=e.maxWidth===1/0?"100000px":e.maxWidth,e.mq="(min-width:"+e.minWidth+") and (max-width:"+e.maxWidth+")",e.type="height"===e.property?"outerHeight":"outerWidth",e.target?t.isArray(e.target)||(e.target=[e.target]):e.target=["> *"],n(),t.fsMediaquery("bind",e.rawGuid,e.mq,{enter:function(){(function(e){if(!e.enabled){e.enabled=!0;var t=e.$el.find("img");t.length&&t.on(o.load,e,i),i(e)}}).call(e.$el,e)},leave:function(){(function(e){e.enabled&&(e.enabled=!1,r(e))}).call(e.$el,e)}})},_destruct:function(e){r(e),t.fsMediaquery("unbind",e.rawGuid),n()},_resize:function(e){f.iterate.call(u,i)},resize:i}}),d=a.classes,o=(d.raw,a.events),f=a.functions,u=[]});