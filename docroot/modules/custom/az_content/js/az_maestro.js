/**
 * @file az_maestro.js
 * Controller that loads streams into blocks.
 */
(function ($) {
  'use strict';

  var init = function(maestroStream, context) {
    var $streamContainer = $(maestroStream).find('.content-container');
    var $moreButton;
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
          $streamContainer.append(response[i].stream);
          $moreButton = $(maestroStream).find('.more-button');
          set = response[i].set;
          if (set.pageNum * set.pageNumItems + set.numRows >= set.totalRows) {
            $moreButton.remove();
          } else {
            $moreButton.click(function () {
              var $oldButton = $moreButton;
              set.pageNum++;
              getStream(set);
              $oldButton.remove();
            });
          }
        }
      }
    };

    var getStream = function (set) {
      doAjax(
        '/maestro/getStream',
        set,
        streamLoaded
      );
    };

    getStream(set);
  };

  Drupal.behaviors.azMaestro = {
    attach: function(context, set) {
      $('.maestro-stream', context).once('azActivated').each(function() {
        init(this, context)
      });
    }
  };
}(jQuery));

