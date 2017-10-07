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
      var more = document.getElementById("js-centered-more");

      if ($(more).length > 0) {
        var windowWidth = $(window).width();
        var moreLeftSideToPageLeftSide = $(more).offset().left;
        var moreLeftSideToPageRightSide = windowWidth - moreLeftSideToPageLeftSide;

        if (moreLeftSideToPageRightSide < 330) {
          $("#js-centered-more .submenu .submenu").removeClass("fly-out-right");
          $("#js-centered-more .submenu .submenu").addClass("fly-out-left");
        }

        if (moreLeftSideToPageRightSide > 330) {
          $("#js-centered-more .submenu .submenu").removeClass("fly-out-left");
          $("#js-centered-more .submenu .submenu").addClass("fly-out-right");
        }
      }
      var menuToggle = $("#js-centered-navigation-mobile-menu").unbind();
      $("#js-centered-navigation-menu").removeClass("show");

      menuToggle.on("click", function(e) {
        e.preventDefault();
        $("#js-centered-navigation-menu").slideToggle(function(){
          if($("#js-centered-navigation-menu").is(":hidden")) {
            $("#js-centered-navigation-menu").removeAttr("style");
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
      var $fart = $(context).find('#site-header');
      $(context).find('#site-header').once('menuTopAttached').each(function () {
        if (!Drupal.menuTop) {
          Drupal.menuTop = Drupal.menuTopC();
        }
        Drupal.menuTop.init(this);
      });
    }
  };

}(jQuery));

