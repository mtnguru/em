/**
 * @file
 */

(function ($, Drupal) {
  "use strict";

  Drupal.behaviors.linkMediaEntity = {
    attach: function (context) {
      function _init () {
        twttr.widgets.load(context);
      }

      // If the link is being embedded in a CKEditor's iFrame the widgets
      // library might not have been loaded yet.
      if (typeof twttr == 'undefined') {
        $.getScript('//platform.link.com/widgets.js', _init);
      }
      else {
        _init();
      }
    }
  };

})(jQuery, Drupal);
