/*! Page Content Navigation 1.0.0 | MIT *
 * https://github.com/jpcurrier/page-content-navigation !*/
(function ($) {
  $.fn.contentNavigation = function (options) {

    // default options
    var settings = $.extend({
      includeTopNav: false,
      offsetIndicator: 0
    }, options);

    // create nav
    var contents = this;

    $(document).on('click', '.gap_page_scrolling .ps_prev', function () {
     $('.ps_dots > li.active').prev().children('a')[0].click();
    });

    $(document).on('click', '.gap_page_scrolling .ps_next', function () {
      $('.ps_dots > li.active').next().children('a')[0].click();
     });
    

    // indicators
    function jumpIndex() {
      var scroll = $(window).scrollTop(),
        atBottom = true;

      var offsetIndicator = Number(settings.offsetIndicator);
      if (typeof settings.offsetIndicator === 'string' && settings.offsetIndicator.indexOf('%') > -1) {
        offsetIndicator = settings.offsetIndicator.replace(/%/g, '').trim(); // remove %
        var posDecimal =
          settings.offsetIndicator.indexOf('.') > -1 ?
            settings.offsetIndicator.indexOf('.') - 2 :
            offsetIndicator.length - 2;
        offsetIndicator = offsetIndicator.replace(/\./g, ''); // remove .
        if (posDecimal > -1)
          offsetIndicator = Number(offsetIndicator.slice(0, posDecimal) + '.' + offsetIndicator.slice(posDecimal));
        else {
          var natural = offsetIndicator,
            sign = '';
          if (offsetIndicator.slice(0, 1) == '-') {
            natural = offsetIndicator.slice(1);
            sign = '-';
            posDecimal -= 1;
          }
          offsetIndicator = Number(sign + '0.' + String(Math.pow(10, (0 - posDecimal))).slice(1) + natural);
        }
        offsetIndicator *= $(window).height();
      }

      contents.each(function (i) {
        if (scroll < $(this).offset().top + offsetIndicator) {
          var j =
            !settings.includeTopNav && i - 1 >= 0 ?
              i - 1 :
              i;
          
          $('.ps_dots > li').eq(j).not('.active').addClass('active')
            .siblings('.active').removeClass('active');
          atBottom = false;

          return false;
        }
      });
      if (atBottom) {

        $('.ps_dots > li:last-child').not('.on').addClass('active')
          .siblings('.active').removeClass('active');
      }

    }
    $(window).on('resize', jumpIndex);
    $(window).on('scroll', jumpIndex);
  };
})(jQuery);