/**
 * @file az_maestro.js
 * Controller that loads streams into blocks.
 */
(function ($) {
  'use strict';

  var initTabs = function(tabContainer, context) {
    var $tabs = $(tabContainer).find('.tab');
    $($tabs[0]).addClass('active');

    $tabs.each(function() {
      $(this).click(function (ev) {
        $tabs.removeClass('active');
        $(this).addClass('active');
      });
    });
  };


  var initStream = function(maestroStream, context) {
    var $streamContainer = $(maestroStream).find('.content-container');
    var $moreButton;
    var $oldMoreButton;
    var streamId = maestroStream.id;
    var set = drupalSettings.azmaestro[streamId];

    var doAjax = function doAjax (url, data, successCallback, errorCallback) {
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
          if ($moreButton) $moreButton.remove();
          $streamContainer.append(response[i].stream);
          $moreButton = $(maestroStream).find('.more-button');
          set = response[i].set;
          if (set.pageNum * set.pageNumItems + set.numRows >= set.totalRows) {
            $moreButton.remove();
          } else {
            $moreButton.click(function () {
              $oldMoreButton = $moreButton;
              set.pageNum++;
              getStream(set);
            });
          }
        }
      }
    };

    var getStream = function (set) {
      doAjax('/maestro/getStream', set, streamLoaded);
    };

    getStream(set);
  };

  Drupal.behaviors.azMaestro = {
    attach: function(context, set) {
      $('.maestro-stream', context).once('az-attached').each(function() {
        initStream(this, context)
      });

      $('.az-tabs', context).once('az-attached').each(function () {
        initTabs(this, context);
      });
    }
  };
}(jQuery));

