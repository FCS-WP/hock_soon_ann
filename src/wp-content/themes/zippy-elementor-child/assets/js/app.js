// import "../lib/slick/slick.min.js";

$(document).ready(function ($) {

  // ============================================================
  // Product Carousel — Slick-powered
  //
  // Main slider:  3 visible slides, centre is active/larger.
  // Thumb strip:  linked via asNavFor, shows all products.
  // Infinite loop + rapid-click safe (handled by Slick).
  // ============================================================

  function initProductCarousels() {

    $('.product-carousel').not('.is-initialised').each(function () {
      var $carousel  = $(this);
      $carousel.addClass('is-initialised');

      var autoplay = $carousel.data('autoplay') === true ||
                     $carousel.data('autoplay') === 'true';
      var interval = parseInt($carousel.data('autoplay-interval'), 10) || 4000;

      var $main   = $carousel.find('.product-carousel__main');
      var $thumbs = $carousel.find('.product-carousel__thumbs');

      // ----------------------------------------------------------
      // Thumbnail strip (init first so asNavFor can reference it)
      // ----------------------------------------------------------
      $thumbs.slick({
        slidesToShow:   5,
        slidesToScroll: 1,
        arrows:         false,
        dots:           false,
        infinite:       true,
        centerMode:     true,
        centerPadding:  '0px',
        focusOnSelect:  true,
        asNavFor:       $main[0],
        responsive: [
          {
            breakpoint: 600,
            settings: { slidesToShow: 3 }
          }
        ]
      });

      // ----------------------------------------------------------
      // Main slider
      // ----------------------------------------------------------
      $main.slick({
        slidesToShow:   3,
        slidesToScroll: 1,
        centerMode:     true,
        centerPadding:  '0px',
        arrows:         false,
        dots:           false,
        infinite:       true,
        speed:          450,
        cssEase:        'cubic-bezier(0.4, 0, 0.2, 1)',
        autoplay:       autoplay,
        autoplaySpeed:  interval,
        pauseOnHover:   true,
        focusOnSelect:  true,
        asNavFor:       $thumbs[0],   // sync thumbnail strip
        responsive: [
          {
            breakpoint: 600,
            settings: {
              slidesToShow:  1,
              centerMode:    true,
              centerPadding: '60px'
            }
          }
        ]
      });
    });
  }

  // Run on page load
  initProductCarousels();

  // Re-run after Elementor frontend renders dynamic widgets
  if (typeof elementorFrontend !== 'undefined') {
    elementorFrontend.hooks.addAction('frontend/element_ready/global', function () {
      initProductCarousels();
    });
  }
});
