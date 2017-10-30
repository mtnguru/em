/**
 * @file
 * Open and close hierarchical menus
 *
 * @type {{attach: Drupal.behaviors.menu.attach}}
 */

(function ($) {
  'use strict';

  Drupal.behaviors.menu = {
    attach: function (context, settings) {
      var expandClass = 'menu-item--expanded';

      $(context).find('#book-pages').once('menuAttached').each(function () {
        $('#book-pages .menu-item--children', context).click(function (event) {
          if (event.target.tagName === 'LI') {
            event.stopPropagation();
            if ($(this).hasClass(expandClass)) {
              $(this).removeClass(expandClass);
            }
            else {
              $(this).siblings().removeClass(expandClass)
              $(this).addClass(expandClass);
            }
          }
        });
      });
    }
  };

}(jQuery));

