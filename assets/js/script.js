jQuery(document).ready(function ($) {

  $('.btnGetOTPZNS').on('click', function () {
    var button = $(this);
    if (button.hasClass('disabled')) {
      return;
    }
    var button_text = button.text();
    var context = button.data('context');
    var phone_field = button.prev('input');
    var phone = phone_field.val();
    if (phone == '') {
      alert("Vui lòng nhập số điện thoại!");
      phone_field.focus();
      return;
    }

    //var fnonce = $('#' + context + '_nonce_field').val();

    $('#verificationId_' + context).val('');

    $.ajax({
      url: gap.ajax_url,
      type: 'POST',
      dataType: "json",
      data: {
        action: 'gap_get_otp_zalo',
        phone: phone,
        context: context,
        nonce: gap.nonce,
        //fnonce: fnonce
      },
      context: this,
      beforeSend: function () {
        button.addClass('disabled').html('Đang gửi...');
      },
      success: function (response) {
        console.log(response);
        button.text(button_text).removeClass('disabled');
        if (response.success === true) {
          $('#verificationId_' + context).val(response.data);
          alert('Đã gửi OTP, vui lòng kiểm tra điện thoại.');
        } else {
          alert(response.data);
        }
      },
      error: function (error) {
        //console.log(error);
        button.text(button_text).removeClass('disabled');
        alert(error);
      }
    });
  });

  let open_countdown = $('#open_countdown');
  let done = false;
  if (open_countdown.length && !done) {
    $('.ct-cart-actions').hide();
    let product_id = open_countdown.data('product_id');
    open_countdown.each(function () {
      setInterval(function () {
        var xhr = $.ajax({
          type: "post",
          url: gap.ajax_url,
          data: {
            action: 'gap_countdown',
            product_id: product_id,
            nonce: gap.nonce
          },
          success: function (data) {
            if (data == 'finished') {
              xhr.abort();
              done = true;
              open_countdown.remove();
              $('.ct-cart-actions').show();
            }
            console.log(data);
            $('#open_countdown').html(data);
          }
        });
      }, 999); //time in milliseconds 
    });
  }

  $.fn.gap_load_time = function (date) {
    var old_html = $('#gap_time_ajax').html();
    $('#gap_time_ajax').html('<div class="gap_times_loading">Đang tải...</div>');
    $.ajax({
      url: gap.ajax_url,
      type: 'POST',
      data: {
        action: 'gap_click_date',
        date: date,
        nonce: gap.nonce
      },
      success: function (html) {
        //console.log(html);
        $('#gap_time_ajax').html(html);
        $.fn.gap_time_carousel();
      },
      error: function (error) {
        alert("error");
        //code
        console.log(error);
      }
    });
  }

  /**/
  $.fn.gap_time_carousel = function () {
    var gap_time_slide = $(".gap_time_slide");
    $('.gap_time_slide').owlCarousel({
      items: 1,
      dots: false,
      nav: false,
      loop: true,
      margin: 0,
      smartSpeed: 200,
      slideSpeed: 500,
      //autoplay: true,
      //center: true,

      //autoWidth:true,
      //slideBy: slidesPerPage, //alternatively you can slide by 1, this way the active slide will stick to the first item in the second carousel
      responsiveRefreshRate: 100,
      navClass: ['gt-owl-prev', 'gt-owl-next']
    });

    $(".gap_time_nav button.next").click(function () {
      gap_time_slide.trigger('next.owl.carousel');
    })
    $(".gap_time_nav button.prev").click(function () {
      gap_time_slide.trigger('prev.owl.carousel', [300]);
    });
  }

  //Blocking
  $('.BlockingBtn').click(function (e) {
    var button = $(this);
    var date = $('input[name=gap_date]:checked').val();
    var time = $('input[name=gap_time]:checked').val();
    var action = button.data('action');

    if (date === 'undefined' || time === 'undefined') {
      alert("Vui lòng chọn ngày và giờ");
      return;
    }

    button.addClass('disabled');

    $.ajax({
      url: gap.ajax_url,
      type: 'POST',
      data: {
        action: action,
        date: date,
        time: time,
        nonce: gap.nonce
      },
      success: function (html) {
        //console.log(html);
        $('input[name=gap_date]:checked').click();
        //$.fn.gap_load_time('');
        button.removeClass('disabled');
      },
      error: function (error) {
        alert("error");
        //code
        console.log(error);
        button.removeClass('disabled');
      }
    });
  });


  //$.fn.gap_load_time('');
  $.fn.gap_time_carousel();

  /* */
  $(document).on('click', 'input[name=gap_date]', function (e) {

    var date = $(this).val();

    $.fn.gap_load_time(date);
    /* $.ajax({
      url: gap.ajax_url,
      type: 'POST',
      data: {
        action: 'gap_click_date',
        date: date,
        nonce: gap.nonce
      },
      success: function (response) {
        console.log(response.data);
      },
      error: function (error) {
        alert("error");
        //code
        console.log(error);
      }
    }); */
  });

  var billing_phone = $('#current_user_billing_phone').val();
  //alert(billing_phone);
  if ($('.wpcf7-validates-as-tel').length) {
    $('.wpcf7-validates-as-tel').val(billing_phone);
  }

  $('select.wpcf7-select').attr('data-placeholder', 'Chọn thương hiệu');
  $("select.wpcf7-select").chosen({ width: '100%' });

  $('.wpcf7-checkbox input[type=checkbox], .wpcf7-acceptance input[type=checkbox], .wpcf7-radio input[type=radio]').addClass('ct-checkbox');

  $('#verify_zalo_btn').click(function () {
    alert("Gửi mã xác minh Zalo!!!\nTest OPT: 12345");
  });

  var uacf7_prev = $('button.uacf7-prev');
  uacf7_prev.text('Về trước');
  uacf7_prev.click(function () {
    $.fn.gap_load_time('');
  });

  var uacf7_next = $('button.uacf7-next');
  uacf7_next.text('Tiếp theo');


  /* uacf7_next.click(function (e) {
    if (!$('input[name="gap_date"]').is(':checked')) {
      alert("Vui lòng chọn ngày!!!");
      return false;
    }

    $('input[name="gap_date"]:checked').val();
    
    return false;
  }); */

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





  /* $('.gap_time_nav button.next').click(function () {
    $('#scr1').hide();
    $('#scr2').show();
  });
  $('.gap_time_nav button.prev').click(function () {
    $('#scr1').show();
    $('#scr2').hide();
  }); */



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

  //Auto rotate tabs
  /* $('#continue-shopping').click(function (event) {
    var nextLink = $('.tabs-menu').find('li.current').next().find('a');

    if (nextLink.length > 0) {
      nextLink.click();
    } else {
      $('.tabs-menu').find('li:first-of-type a').click();
    }
  }); */

  if ($('.elementor-tabs').length) {
    setInterval(function () {
      var nextLink = $('.elementor-tabs-wrapper').find('div.elementor-active').next('.elementor-tab-title');
      if (nextLink.length > 0) {
        nextLink.click();
      } else {
        $('.elementor-tabs-wrapper').find('.elementor-tab-title:first-of-type').click();
      }
    }, 3000);
  }

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


//Format currency
jQuery(document).ready(function ($) {
  $(document).ready(function () {
    // Attach an event listener to the input
    $('#form-field-pre_amount').attr('maxlength', 14);
    $('#form-field-pre_amount, #online_price').on('keyup', function (e) {
      if (e.which == 8 || e.which == 46) return false;

      // Get the entered value
      var enteredValue = $(this).val();

      // Remove non-numeric characters
      var numericValue = enteredValue.replace(/[^0-9]/g, '');

      // Limit the length to a specified number of characters
      var maxLength = parseInt($(this).attr('maxlength'));
      numericValue = numericValue.substring(0, maxLength);

      // Format as currency without decimals and separate thousands with a period
      var formattedAmount = parseFloat(numericValue).toLocaleString('vi-VN', {
        style: 'currency',
        currency: 'VND',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
      });

      // Update the input value with the formatted amount
      $(this).val(formattedAmount);
    });
  });

});

//
jQuery(function ($) {
  let timeout;
  jQuery('.woocommerce').on('change', 'input.qty', function () {
    if (timeout !== undefined) {
      clearTimeout(timeout);
    }
    timeout = setTimeout(function () {
      jQuery("[name='update_cart']").trigger("click"); // trigger cart update
    }, 1000); // 1 second delay, half a second (500) seems comfortable too
  });

  $('.woocommerce-order-received .woocommerce-order-details').prev('p').html('');
});