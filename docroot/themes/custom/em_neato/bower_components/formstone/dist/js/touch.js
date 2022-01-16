/*! formstone v1.4.22 [touch.js] 2021-10-01 | GPL-3.0 License | formstone.it */
!function(e){"function"==typeof define&&define.amd?define(["jquery","./core"],e):e(jQuery,Formstone)}(function(r,s){"use strict";function o(e){e.preventManipulation&&e.preventManipulation();var t=e.data,a=e.originalEvent;if(a.type.match(/(up|end|cancel)$/i))d(e);else{if(a.pointerId){var n=!1;for(var i in t.touches)t.touches[i].id===a.pointerId&&(n=!0,t.touches[i].pageX=a.pageX,t.touches[i].pageY=a.pageY);n||t.touches.push({id:a.pointerId,pageX:a.pageX,pageY:a.pageY})}else t.touches=a.touches;a.type.match(/(down|start)$/i)?v(e):a.type.match(/move$/i)&&c(e)}}function v(e){var t=e.data,a=void 0!==t.touches&&t.touches.length?t.touches[0]:null;a&&t.$el.off(m.mouseDown),t.touching||(t.startE=e.originalEvent,t.startX=a?a.pageX:e.pageX,t.startY=a?a.pageY:e.pageY,t.startT=(new Date).getTime(),t.scaleD=1,t.passedAxis=!1),t.$links&&t.$links.off(m.click);var n=Y(t.scale?m.scaleStart:m.panStart,e,t.startX,t.startY,t.scaleD,0,0,"","");if(t.scale&&t.touches&&2<=t.touches.length){var i=t.touches;t.pinch={startX:f(i[0].pageX,i[1].pageX),startY:f(i[0].pageY,i[1].pageY),startD:x(i[1].pageX-i[0].pageX,i[1].pageY-i[0].pageY)},n.pageX=t.startX=t.pinch.startX,n.pageY=t.startY=t.pinch.startY}t.touching||(t.touching=!0,t.pan&&!a&&M.on(m.mouseMove,t,c).on(m.mouseUp,t,d),s.support.pointer?M.on([m.pointerMove,m.pointerUp,m.pointerCancel].join(" "),t,o):M.on([m.touchMove,m.touchEnd,m.touchCancel].join(" "),t,o),t.$el.trigger(n))}function c(e){var t=e.data,a=void 0!==t.touches&&t.touches.length?t.touches[0]:null,n=a?a.pageX:e.pageX,i=a?a.pageY:e.pageY,s=n-t.startX,o=i-t.startY,c=0<s?"right":"left",p=0<o?"down":"up",r=Math.abs(s)>t.threshold,l=Math.abs(o)>t.threshold;if(!t.passedAxis&&t.axis&&(t.axisX&&l||t.axisY&&r))d(e);else{!t.passedAxis&&(!t.axis||t.axis&&t.axisX&&r||t.axisY&&l)&&(t.passedAxis=!0),t.passedAxis&&(w.killEvent(e),w.killEvent(t.startE));var h=!0,u=Y(t.scale?m.scale:m.pan,e,n,i,t.scaleD,s,o,c,p);if(t.scale)if(t.touches&&2<=t.touches.length){var g=t.touches;t.pinch.endX=f(g[0].pageX,g[1].pageX),t.pinch.endY=f(g[0].pageY,g[1].pageY),t.pinch.endD=x(g[1].pageX-g[0].pageX,g[1].pageY-g[0].pageY),t.scaleD=t.pinch.endD/t.pinch.startD,u.pageX=t.pinch.endX,u.pageY=t.pinch.endY,u.scale=t.scaleD,u.deltaX=t.pinch.endX-t.pinch.startX,u.deltaY=t.pinch.endY-t.pinch.startY}else t.pan||(h=!1);h&&t.$el.trigger(u)}}function d(e){var t=e.data,a=void 0!==t.touches&&t.touches.length?t.touches[0]:null,n=a?a.pageX:e.pageX,i=a?a.pageY:e.pageY,s=n-t.startX,o=i-t.startY,c=(new Date).getTime(),p=t.scale?m.scaleEnd:m.panEnd,r=0<s?"right":"left",l=0<o?"down":"up",h=1<Math.abs(s),u=1<Math.abs(o);if(t.swipe&&c-t.startT<t.time&&Math.abs(s)>t.threshold&&(p=m.swipe),t.axis&&(t.axisX&&u||t.axisY&&h)||h||u){t.$links=t.$el.find("a");for(var g=0,d=t.$links.length;g<d;g++)X(t.$links.eq(g),t)}var f=Y(p,e,n,i,t.scaleD,s,o,r,l);M.off([m.touchMove,m.touchEnd,m.touchCancel,m.mouseMove,m.mouseUp,m.pointerMove,m.pointerUp,m.pointerCancel].join(" ")),t.$el.trigger(f),t.touches=[],t.scale,a&&(t.touchTimer=w.startTimer(t.touchTimer,5,function(){t.$el.on(m.mouseDown,t,v)})),t.touching=!1}function X(e,t){e.on(m.click,t,n);var a=r._data(e[0],"events").click;a.unshift(a.pop())}function n(e){w.killEvent(e,!0),e.data.$links.off(m.click)}function Y(e,t,a,n,i,s,o,c,p){return r.Event(e,{originalEvent:t,bubbles:!0,pageX:a,pageY:n,scale:i,deltaX:s,deltaY:o,directionX:c,directionY:p})}function f(e,t){return(e+t)/2}function x(e,t){return Math.sqrt(e*e+t*t)}function a(e,t){e.css({"-ms-touch-action":t,"touch-action":t})}var e=!s.window.PointerEvent,t=s.Plugin("touch",{widget:!0,defaults:{axis:!1,pan:!1,scale:!1,swipe:!1,threshold:10,time:50},methods:{_construct:function(e){if(e.touches=[],e.touching=!1,this.on(m.dragStart,w.killEvent),e.swipe&&(e.pan=!0),e.scale&&(e.axis=!1),e.axisX="x"===e.axis,e.axisY="y"===e.axis,s.support.pointer){var t="";!e.axis||e.axisX&&e.axisY?t="none":(e.axisX&&(t+=" pan-y"),e.axisY&&(t+=" pan-x")),a(this,t),this.on(m.pointerDown,e,o)}else this.on(m.touchStart,e,o).on(m.mouseDown,e,v)},_destruct:function(e){this.off(m.namespace),a(this,"")}},events:{pointerDown:e?"MSPointerDown":"pointerdown",pointerUp:e?"MSPointerUp":"pointerup",pointerMove:e?"MSPointerMove":"pointermove",pointerCancel:e?"MSPointerCancel":"pointercancel"}}),m=t.events,w=t.functions,M=s.$window;m.pan="pan",m.panStart="panstart",m.panEnd="panend",m.scale="scale",m.scaleStart="scalestart",m.scaleEnd="scaleend",m.swipe="swipe"});