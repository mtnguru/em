/**
 * @file az_maestro.js
 * Controller that loads streams into blocks.
 */
(function ($) {
  'use strict';

  if (!Drupal.az) {
    Drupal.az = {};
  }

  Drupal.az.maestroC = function () {

    function init (context) {
      // Initialize tabs
      $('.az-tabs', context).once('az-attached').each(function () {
        initTabs(this, context);
      });

      // Initialize stream
      $('.maestro-stream', context).once('az-attached').each(function() {
        var set = drupalSettings.azmaestro[this.id.replace('tab-', '')];
        if ($(this).hasClass('block-content')) {
          getStream(set);
        }
      });

    }

    var initTabs = function (tabContainer, context) {
      var $tabs = $(tabContainer).find('.tab');
      $($tabs[0]).addClass('active');

      $tabs.each(function () {
        $(this).click(function (ev) {
          $tabs.removeClass('active');
          $(this).addClass('active');
          var set = drupalSettings.azmaestro[this.id.replace('tab-', '')];
          if (!set['loaded']) {
            getStream(set);
          }
        });
      });

      var set = drupalSettings.azmaestro[$tabs[0].id.replace('tab-', '')];
      getStream(set);
    };

    var doAjax = function doAjax(url, data, successCallback, errorCallback) {
      $.ajax({
        url: url,
        type: 'POST',
        data: JSON.stringify(data),
        contentType: "application/json; charset=utf-8",
        processData: false,
        success: function (response) {
          if (Array.isArray(response) && response.length > 0) {
            if (response[0].data && response[0].data.message) {
              alert(response[0].data.message);
            }
            if (successCallback) successCallback(response);
          } else {
            (errorCallback) ? errorCallback(response) : successCallback(response);
          }
          return false;
        },
        error: function (response) {
          alert('az_content::doAjax: ' + response.responseText);
          (errorCallback) ? errorCallback(response) : successCallback(response);
        }
      });
    };

    var streamLoaded = function (response) {
      for (var i = 0; i < response.length; i++) {
        if (response[i].command == 'GetStreamCommand') {
          var set = response[i].set;
          var $streamContainer = $('#' + set['id']);

          // Remove the old more button
          $streamContainer.find('.more-button').remove();
          // Append the new stream html
          $streamContainer.find('.content-container').append(response[i].stream);
          // Find the new more button
          var $moreButton = $streamContainer.find('.more-button');

          // If we're at the end then remove the more button
          if (set.pageNum * set.pageNumItems + set.numRows >= set.totalRows) {
            $moreButton.remove();
          }
          else {
            // Set click event handler on more button.
            $moreButton.click(function () {
              set.pageNum++;   // Increment the page number.
              getStream(set);
            });
          }
        }
      }
    };

    var getStream = function (set) {
      set['loaded'] = true;
      doAjax('/maestro/getStream', set, streamLoaded);
    };

    return {
      init: init,
    };
  };

  Drupal.behaviors.az_maestro = {
    // Attach functions are executed by Drupal upon page load or ajax loads.
    attach: function (context, settings) {
      if (!Drupal.az.maestro) {  // Ensures we only run this once
        Drupal.az.maestro = Drupal.az.maestroC();
      }
      Drupal.az.maestro.init(context);
    }
  };


}(jQuery));

