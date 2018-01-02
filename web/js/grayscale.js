(function($) {
  "use strict"; // Start of use strict

  // Smooth scrolling using jQuery easing
  $('a.js-scroll-trigger[href*="#"]:not([href="#"])').click(function() {
    if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
      if (target.length) {
        $('html, body').animate({
          scrollTop: (target.offset().top - 48)
        }, 1000, "easeInOutExpo");
        return false;
      }
    }
  });

  // Closes responsive menu when a scroll trigger link is clicked
  $('.js-scroll-trigger').click(function() {
    $('.navbar-collapse').collapse('hide');
  });
  

  // Collapse Navbar
  var navbarCollapse = function() {
    if ($("#mainNav").offset().top > 150) {
      $("#mainNav").addClass("navbar-shrink");
	  $("#return-to-top").addClass("button-show");
    } else {
      $("#mainNav").removeClass("navbar-shrink");
	  $("#return-to-top").removeClass("button-show");
    }
  };
  // Collapse now if page is not at top
  navbarCollapse();
  // Collapse the navbar when page is scrolled
  $(window).scroll(navbarCollapse);

  // Activate scrollspy to add active class to navbar items on scroll
  $('body').scrollspy({
    target: '#mainNav',
    offset: 54
  });

$('#return-to-top').click(function() {      // When arrow is clicked
    $('html,body').animate({scrollTop:0},'slow');
});

})(jQuery); // End of use strict
