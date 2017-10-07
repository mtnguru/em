/**
 * Implement div with max-height which hides content - more button slides down remaining content.
 */
(function ($) {
  'use strict';

  Drupal.behaviors.az_maestro = {
    attach: function(context, settings) {
      var $slider = $('.az-more-wrapper', context);
      $('.az-more-wrapper', context).once('azActivated').each(function() {
      });
    }
  };

}(jQuery));

