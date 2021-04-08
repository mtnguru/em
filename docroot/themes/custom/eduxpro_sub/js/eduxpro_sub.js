/* Prepage loader
--------------------------*/
jQuery(window).on('load', function() {
  jQuery(".loader").delay(1000).fadeOut( 'slow' );
});

/* Load jQuery.
--------------------------*/
jQuery(document).ready(function ($) {
  // Look for the book navigation menu
  // if found move it to the content section.
  let $navmenu = $("nav.book-navigation");
  if ($navmenu) {
    let $dest = $(".block-content .field--name-body");
    $dest[0].appendChild($navmenu[0])
  }

/* End document
--------------------------*/
});
