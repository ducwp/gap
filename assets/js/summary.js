jQuery(document).ready(function ($) {
  'use strict';

  $(document).on('click', '#btnImportXLXSFile', function () {
    let button = $(this);
    if (button.hasClass('disabled')) return;

    let file_id = button.data('id');
    let btnCancel = $('#btnCancelImport');
    let load_text = $(this).text();
    let loading_text = 'Running...';
    button.html('<span class="spinner" role="status" aria-hidden="true" style="visibility: visible"></span> ' + loading_text);
    //select_box.hide();
    btnCancel.removeClass('hidden');

    //XHR - START
    button.addClass('disabled');

    $('.attachment-actions').empty().append('<div id="summary_import_res" style="background: #eee; text-align: left; max-height: 500px; overflow-y: auto; padding: 10px 20px "></div>');


    var last_response_len = false;
    var res = '';
    var xhr = wp.ajax.send({
      data: {
        action: 'summary_import_xlxs',
        file_id: file_id
      },
      xhrFields: {
        onprogress: function (e) {
          var this_response, response = e.currentTarget.response;
          if (last_response_len === false) {
            this_response = response;
            last_response_len = response.length;
          }
          else {
            this_response = response.substring(last_response_len);
            last_response_len = response.length;
          }
          console.log(this_response);
          res += this_response;
          //$('.attachment-actions').html(res);
          $('#summary_import_res').prepend(this_response);

        }
      },
      success: function (response) {
        //console.log(response);
        button.removeClass('disabled').html(load_text);
        //select_box.show();
        btnCancel.addClass('hidden');

      },
      error: function (error) {
        //console.log(error);
        button.removeClass('disabled').html(load_text);
        //select_box.show();
        btnCancel.addClass('hidden');

      }
    });

    btnCancel.on('click', function () {
      xhr.abort();
    });

    //XHR - END

    /* $.ajax({
      url: ajaxurl,
      data: {
        action: 'batch_translate',
        security: button.data('nonce'),
        lang: lang,
        post_ids: post_ids
      },
      type: "POST",
      dataType: "json",
      success: function (data) {

        $.each(data['data'], function (id, title) {
          $('input#cb-select-' + id).parent().next('td.title').find('a.row-title').css('background', '#a8eea8').text(decodeURIComponent(title));

          //alert( id + ": " + title );
        });

        console.log(data);
        button.html(load_text);
        select_box.show();
      },
      error: function (error) {
        console.log(error);
        select_box.show();
        button.html(load_text);
      }
    });  *///ajax

  });

});
