/**
 * @file az_maestro.js
 * Controller that loads content into blocks.
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

      // Initialize content
      $('.maestro-content', context).once('az-attached').each(function() {
        var set = drupalSettings.azmaestro[this.id.replace('tab-', '')];
        if ($(this).hasClass('block-content')) {
          getContent(set);
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
            getContent(set);
          }
        });
      });

      var set = drupalSettings.azmaestro[$tabs[0].id.replace('tab-', '')];
      getContent(set);
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

    var contentLoaded = function (response) {
      for (var i = 0; i < response.length; i++) {
        if (response[i].command == 'GetContentCommand') {
          var set = response[i].set;
          var $contentContainer = $('#' + set['id']); // destination container

          switch (set.type) {
            case 'entity-stream':

              // Remove the old more button
              $contentContainer.find('.more-button').remove();
              // Append the new stream html
              $contentContainer.find('.content-container').append(response[i].content);
              // Find the new more button
              var $moreButton = $contentContainer.find('.more-button');

              // If we're at the end then remove the more button
              if (set.pageNum * set.pageNumItems + set.numRows >= set.totalRows) {
                $moreButton.remove();
              }
              else {
                // Set click event handler on more button.
                $moreButton.click(function () {
                  if (set.type == 'entity-stream') {
                    set.pageNum++;   // Increment the page number.
                  }
                  getContent(set);
                });
              }
              break;
            case 'entity-render':
              // Append the new stream html
              $contentContainer.find('.content-container').append(response[i].content);
              break;
          }
        }
      }
    };

    var getContent = function (set) {
      set['loaded'] = true;
      doAjax('/maestro/getContent', set, contentLoaded);
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

