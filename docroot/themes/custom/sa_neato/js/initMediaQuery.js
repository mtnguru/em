!function(n){"use strict";var e=function(){n.mediaquery({minWidth:[320,580,960,1280],maxWidth:[1280,960,580,320]}),n.mediaquery("bind","mq-desktop","(min-width: 960px)",{enter:function(){_.defer(function(){window.dispatchEvent(new CustomEvent("desktop"))})},leave:function(){_.defer(function(){window.dispatchEvent(new CustomEvent("mobile"))})}})};Drupal.behaviors.initMediaQuery={attach:function(i){n(i).find("body").once("initMediaQuery").each(function(){e()})}}}(jQuery);
//# sourceMappingURL=maps/initMediaQuery.js.map
