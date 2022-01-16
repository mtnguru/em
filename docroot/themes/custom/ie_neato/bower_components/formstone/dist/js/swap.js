/*! formstone v1.4.22 [swap.js] 2021-10-01 | GPL-3.0 License | formstone.it */
!function(e){"function"==typeof define&&define.amd?define(["jquery","./core","./mediaquery"],e):e(jQuery,Formstone)}(function(s,e){"use strict";function t(e,a){if(e.enabled&&!e.active){e.group&&!a&&s(e.group).not(e.$el).not(e.linked)[c.namespaceClean]("deactivate",!0);var t=e.group?s(e.group).index(e.$el):null;e.$swaps.addClass(e.classes.raw.active),a||e.linked&&s(e.linked).not(e.$el)[c.namespaceClean]("activate",!0),this.trigger(o.activate,[t]),e.active=!0}}function i(e,a){e.enabled&&e.active&&(e.$swaps.removeClass(e.classes.raw.active),a||e.linked&&s(e.linked).not(e.$el)[c.namespaceClean]("deactivate",!0),this.trigger(o.deactivate),e.active=!1)}function a(e,a){e.enabled||(e.enabled=!0,e.$swaps.addClass(e.classes.raw.enabled),a||s(e.linked).not(e.$el)[c.namespaceClean]("enable"),this.trigger(o.enable),e.onEnable?(e.active=!1,t.call(this,e)):(e.active=!0,i.call(this,e)))}function n(e,a){e.enabled&&(e.enabled=!1,e.$swaps.removeClass([e.classes.raw.enabled,e.classes.raw.active].join(" ")),a||s(e.linked).not(e.$el)[c.namespaceClean]("disable"),this.trigger(o.disable))}function l(e){u.killEvent(e);var a=e.data;a.active&&a.collapse?i.call(a.$el,a):t.call(a.$el,a)}var c=e.Plugin("swap",{widget:!0,defaults:{collapse:!0,maxWidth:1/0},classes:["target","enabled","active"],events:{activate:"activate",deactivate:"deactivate",enable:"enable",disable:"disable"},methods:{_construct:function(e){e.enabled=!1,e.active=!1,e.classes=s.extend(!0,{},r,e.classes),e.target=this.data(d+"-target"),e.$target=s(e.target).addClass(e.classes.raw.target),e.mq="(max-width:"+(e.maxWidth===1/0?"100000px":e.maxWidth)+")";var a=this.data(d+"-linked");e.linked=!!a&&"[data-"+d+'-linked="'+a+'"]';var t=this.data(d+"-group");e.group=!!t&&"[data-"+d+'-group="'+t+'"]',e.$swaps=s().add(this).add(e.$target),this.on(o.click+e.dotGuid,e,l)},_postConstruct:function(e){e.collapse||!e.group||s(e.group).filter("[data-"+d+"-active]").length||s(e.group).eq(0).attr("data-"+d+"-active","true"),e.onEnable=this.data(d+"-active")||!1,s.fsMediaquery("bind",e.rawGuid,e.mq,{enter:function(){a.call(e.$el,e,!0)},leave:function(){n.call(e.$el,e,!0)}})},_destruct:function(e){s.fsMediaquery("unbind",e.rawGuid),e.$swaps.removeClass([e.classes.raw.enabled,e.classes.raw.active].join(" ")).off(o.namespace)},activate:t,deactivate:i,enable:a,disable:n}}),d=c.namespace,r=c.classes,o=c.events,u=c.functions});