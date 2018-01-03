/**
 * @file
 * Ethereal Matters - Top Navigation Menu bar
 *
 * @type {{attach: Drupal.behaviors.menu_top.attach}}
 */

(function ($) {
  'use strict';

  Drupal.menuTopC = function () {

    var init = function init() {
      $(window).resize(function () {

        var more = document.getElementById("js-navigation-more");
        if ($(more).length > 0) {
          var windowWidth = $(window).width();
          var moreLeftSideToPageLeftSide = $(more).offset().left;
          var moreLeftSideToPageRightSide = windowWidth - moreLeftSideToPageLeftSide;

          if (moreLeftSideToPageRightSide < 330) {
            $("#js-navigation-more .submenu .submenu").removeClass("fly-out-right");
            $("#js-navigation-more .submenu .submenu").addClass("fly-out-left");
          }

          if (moreLeftSideToPageRightSide > 330) {
            $("#js-navigation-more .submenu .submenu").removeClass("fly-out-left");
            $("#js-navigation-more .submenu .submenu").addClass("fly-out-right");
          }
        }
      });

      var menuToggle = $("#js-mobile-menu").unbind();
      $("#js-navigation-menu").removeClass("show");

      menuToggle.on("click", function (e) {
        e.preventDefault();
        $("#js-navigation-menu").slideToggle(function () {
          if ($("#js-navigation-menu").is(":hidden")) {
            $("#js-navigation-menu").removeAttr("style");
          }
        });
      });
    };

    return {
      init: init
    };
  }; // function Drupal.menuTopC - function wrapper to make variables local.

  Drupal.behaviors.menuTop = {
    attach: function (context, settings) {
      $(context).find('#site-header').once('menuTopAttached').each(function () {
        if (!Drupal.menuTop) {
          Drupal.menuTop = Drupal.menuTopC();
        }
        Drupal.menuTop.init(this);
      });

      $(context).find('#dyslexia-button').once('dyslexia-attached').each(function () {
        var dyslexia = localStorage.getItem('dyslexia_font');
        if (dyslexia && dyslexia != 'undefined' && dyslexia == 'TRUE') {
          $('body').addClass('dyslexia');
          $(this).prop('checked', true);
        }

        $(this).click(function(ev) {
          if ($(this).is(":checked")) {
            $('body').addClass('dyslexia');
            localStorage.setItem('dyslexia_font', 'TRUE');
          } else {
            $('body').removeClass('dyslexia');
            localStorage.setItem('dyslexia_font', 'FALSE');
          }
        });
      });
    }
  };

}(jQuery));

