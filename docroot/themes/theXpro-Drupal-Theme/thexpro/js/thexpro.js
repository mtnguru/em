/* Load jQuery.
--------------------------*/
jQuery(document).ready(function ($) {
  // Mobile menu.
  $('.mobile-menu').click(function () {
    $(this).next('.primary-menu-wrapper').toggleClass('active-menu');
  });
  $('.close-mobile-menu').click(function () {
    $(this).closest('.primary-menu-wrapper').toggleClass('active-menu');
  });
  // Header search form
  $('.search-icon').on('click', function() {
    $('.search-box').toggleClass('open');
    $('.search-box-content .form-search').focus();
    return false;
  });
  $('.search-box-content input[type="search"]').attr("placeholder", "Type your seearch");

  $('.header-search-close').on('click', function() {
    $('.search-box').removeClass('open');
    return false;
  });
  $(document).on('click', function(event) {
    if(!$(event.target).closest(".search-box-content").length) {
      $('.search-box').removeClass('open');
    }
  })
  // Scroll To Top.
  $(window).scroll(function () {
    if ($(this).scrollTop() > 80) {
      $('.scrolltop').css('display', 'flex');
    } else {
      $('.scrolltop').fadeOut('slow');
    }
  });
  $('.scrolltop').click(function () {
    $('html, body').animate( { scrollTop: 0 }, 'slow');
  });

  // Sliding sidebar
  // Sliding sidebar.
  $('.sliding-sidebar-icon').click(function () {
    $('.sliding-sidebar').toggleClass('animated-panel-is-visible');
  });
  $('.close-sliding-sidebar').click(function () {
    $('.sliding-sidebar').removeClass('animated-panel-is-visible');
  });
/* End document
--------------------------*/
});
