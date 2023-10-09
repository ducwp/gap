jQuery(document).ready(function ($) {

  /* */
  var billing_phone = $('#current_user_billing_phone').val();
  //alert(billing_phone);
  if($('.wpcf7-validates-as-tel').length){
    $('.wpcf7-validates-as-tel').val(billing_phone);
  }

  $('select.wpcf7-select').attr('data-placeholder', 'Chọn thương hiệu');
  $("select.wpcf7-select").chosen({ width: '100%' });

  $('.wpcf7-checkbox input[type=checkbox], .wpcf7-acceptance input[type=checkbox], .wpcf7-radio input[type=radio]').addClass('ct-checkbox');

  $('#verify_zalo_btn').click(function () {
    alert("Gửi mã xác minh Zalo!!!\nTest OPT: 12345");
  });

  $('.gap_time_nav button.next').click(function(){
    $('#scr1').hide();
    $('#scr2').show();
  });
  $('.gap_time_nav button.prev').click(function(){
    $('#scr1').show();
    $('#scr2').hide();
  });

  //$('.calendar-wrapper').calendar();

  $('.navigable').contentNavigation({
    offsetIndicator: '-33%',
  });

  var products = $('#gap_carousel .products').addClass('owl-carousel owl-theme');

  /* Product carousel */
  /* products.owlCarousel({
    items: 3,
    margin: 30,
    nav: true,
    dots: false,
    autoplay: true,
    navText: ['<svg width="100%" height="100%" viewBox="0 0 11 20"><path style="fill:none;stroke-width: 1px; " d="M9.554,1.001l-8.607,8.607l8.607,8.606"/></svg>', '<svg width="100%" height="100%" viewBox="0 0 11 20" version="1.1"><path style="fill:none;stroke-width: 1px; " d="M1.054,18.214l8.606,-8.606l-8.606,-8.607"/></svg>'],
  }); */

  products.owlCarousel({
    loop: true,
    margin: 30,
    responsiveClass: true,
    dots: false,
    responsive: {
      0: {
        items: 1,
        nav: false,
        dots: true
      },
      600: {
        items: 2,
        nav: false,
        dots: true
      },
      1000: {
        items: 3,
        nav: true
      }
    },
    autoplay: true,
    navText: ['<svg width="100%" height="100%" viewBox="0 0 11 20"><path style="fill:none;stroke-width: 1px; " d="M9.554,1.001l-8.607,8.607l8.607,8.606"/></svg>', '<svg width="100%" height="100%" viewBox="0 0 11 20" version="1.1"><path style="fill:none;stroke-width: 1px; " d="M1.054,18.214l8.606,-8.606l-8.606,-8.607"/></svg>'],
  })

  /* Hero slider */
  var sync1 = $("#sync1");
  var sync2 = $("#sync2");
  var slidesPerPage = 6; //globaly define number of elements per page
  var syncedSecondary = true;

  sync1.owlCarousel({
    items: 1,
    animateOut: 'fadeOut',
    slideSpeed: 2000,
    nav: false,
    dots: false,
    autoplay: true,
    loop: true,
    responsiveRefreshRate: 200,
    navText: ['<svg width="100%" height="100%" viewBox="0 0 11 20"><path style="fill:none;stroke-width: 1px;stroke: #000;" d="M9.554,1.001l-8.607,8.607l8.607,8.606"/></svg>', '<svg width="100%" height="100%" viewBox="0 0 11 20" version="1.1"><path style="fill:none;stroke-width: 1px;stroke: #000;" d="M1.054,18.214l8.606,-8.606l-8.606,-8.607"/></svg>'],
  }).on('changed.owl.carousel', syncPosition);


  /*Thumbs*/
  sync2
    .on('initialized.owl.carousel', function () {
      sync2.find(".owl-item").eq(0).addClass("current");
    })
    .owlCarousel({
      // items: 6,
      dots: false,
      nav: false,
      responsive: {
        0: {
          items: 3,
        },
        600: {
          items: 4,

        },
        1000: {
          items: 6,
        }
      },
      margin: 20,
      smartSpeed: 200,
      slideSpeed: 500,
      //center: true,

      //autoWidth:true,
      //slideBy: slidesPerPage, //alternatively you can slide by 1, this way the active slide will stick to the first item in the second carousel
      responsiveRefreshRate: 100
    }).on('changed.owl.carousel', syncPosition2);


  $(".cnext").click(function () {
    sync2.trigger('next.owl.carousel');
  })
  $(".cprev").click(function () {
    sync2.trigger('prev.owl.carousel', [300]);
  });

  function syncPosition(el) {
    //if you set loop to false, you have to restore this next line
    //var current = el.item.index;

    //if you disable loop you have to comment this block
    var count = el.item.count - 1;
    var current = Math.round(el.item.index - (el.item.count / 2) - .5);

    if (current < 0) {
      current = count;
    }
    if (current > count) {
      current = 0;
    }

    //end block

    sync2
      .find(".owl-item")
      .removeClass("current")
      .eq(current)
      .addClass("current");
    var onscreen = sync2.find('.owl-item.active').length - 1;
    var start = sync2.find('.owl-item.active').first().index();
    var end = sync2.find('.owl-item.active').last().index();

    if (current > end) {
      sync2.data('owl.carousel').to(current, 100, true);
    }
    if (current < start) {
      sync2.data('owl.carousel').to(current - onscreen, 100, true);
    }
  }

  function syncPosition2(el) {
    if (syncedSecondary) {
      var number = el.item.index;
      sync1.data('owl.carousel').to(number, 100, true);
    }
  }

  sync2.on("click", ".owl-item", function (e) {
    e.preventDefault();
    var number = $(this).index();
    sync1.data('owl.carousel').to(number, 300, true);
  });
});


jQuery(function ($) {
  $(document).on('submit_success', function (e, data) {
    //alert("AA");

    //Phuong thuc ky gui
    if (data.data.post_amount) {
      //console.log(data.data);
      var post_amount = data.data.post_amount;
      $('#cal_result').html(post_amount);
      //$(e.target).append(post_amount);
    }

    //Xem tong ket
    if (data.data.summary_result) {
      //console.log(data.data);
      var summary_result = data.data.summary_result;
      $('#summary_result').html(summary_result);
      //$(e.target).append(summary_result);
    }

  });
});

function gap_html2pdf() {
  var element = document.getElementById('contentToPrint');
  var opt = {
    margin: 0.3,
    filename: 'hoa_don_chi_tiet_ban_hang.pdf',
    image: { type: 'jpeg', quality: 0.98 },
    html2canvas: { scale: 2, scrollY: 0 },
    jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
  };
  html2pdf().set(opt).from(element).save();
  //html2pdf(element, opt);
  //html2pdf(element);
}

//new AutoNumeric('.money_format', { currencySymbol: '₫', currencySymbolPlacement: 's', decimalCharacter: '.' });