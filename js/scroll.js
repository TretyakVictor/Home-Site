jQuery(document).ready(function() {
  jQuery('#scrollup span').mouseover(function() {
    jQuery(this).animate({
      opacity: 0.65
    }, 100);
  }).mouseout(function() {
    jQuery(this).animate({
      opacity: 1
    }, 100);
  }).click(function() {
    window.scroll(0, 0);
    return false;
  });

  jQuery('#scrolldown span').mouseover(function() {
    jQuery(this).animate({
      opacity: 0.65
    }, 100);
  }).mouseout(function() {
    jQuery(this).animate({
      opacity: 1
    }, 100);
  }).click(function() {
    window.scroll(0, $(document).height());
    return false;
  });

  jQuery(window).scroll(function() {
    if (jQuery(document).scrollTop() > 0) {
      jQuery('#scrollup').fadeIn('fast');
      jQuery('#scrolldown').fadeIn('fast');
    } else {
      jQuery('#scrollup').fadeOut('fast');
      jQuery('#scrolldown').fadeOut('fast');
    }
    if ($(window).scrollTop() + $(window).height() > $(document).height() - 1) {
      jQuery('#scrolldown').fadeOut('fast');
    }
  });

});
