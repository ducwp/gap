<div class="cf-container">
  <div class="cf-col-4"><label> <span>Tên</span> [text* your-name autocomplete:name default:user_display_name]</label></div>
  <div class="cf-col-4"><label><span>Số điện thoại (có Zalo)</span> [tel* your-phone autocomplete:tel]</label></div>
  <div class="cf-col-4"><label><span>Tên Zalo</span>: [text* zalo-name]</label></div>
</div>

<div class="cf-container">
  <div class="cf-col-12"><label><span>Địa chỉ nhận hàng tồn [text* your-address]</span></label></div>
</div>
<div class="cf-container">
  <div class="cf-col-12">
    <p style="margin-bottom: 10px">
      <span style="margin-right: 10px;"><b>Bạn đã biết</b>:</span> [radio radio-160 use_label_element default:1 "Có"
      "Không"]
    </p>

    GAP là trung gian nhận ký gửi cho người cần bán và bán cho người cần mua, thay mặt người cần
    mua mà thương lượng mức giá, giá cả thuận mua vừa bán. Vậy nên tùy theo tình trạng mức giá thanh lý sẽ được định lại
    cho phù hợp thường 50%-70% giá gốc.
  </div>
</div>
<div class="cf-container">
  <div class="cf-col-6">
    <h5>Mức phí tại GAP</h5>
    <label><span>Giá bán mong muống là giá [number* desire_price]</span></label>
  </div>

</div>

<div style="margin: 1.5rem 0">
  [checkbox checkbox_quan_ao checked:checked use_label_element default:1 "Quần áo"]

  [group box_quan_ao]
  <div style="background-color: var(--has-classic-forms, var(--form-field-initial-background)); padding: 20px; border-radius: 10px">
    <div style="color: red; margin-bottom: 20px; font-size: 14px">Lưu ý: Tổng số lượng mới + cũ phải từ 5 sản phẩm trở lên</div>
    <div class="cf-container">

      <div class="cf-col-4">
        <fieldset>
          <legend>Mới</legend>
          <label> <span>Sản phẩm local</span> [number clothes_new_local_number min:1 placeholder "Số lượng"]</label><label>
            <span>Brand</span> [text* clothes_new_local_brand placeholder "Brand #1, Brand #2..."]</label><label> <span>Sản phẩm
              global</span> [number clothes_new_global_number min:1 placeholder "số lượng"]</label><label> <span>Brand</span> [text* clothes_new_global_brand placeholder "Brand #1, Brand #2..."]</label>
        </fieldset>
      </div>
      <div class="cf-col-4">
        <fieldset>
          <legend>Qua sử dụng</legend>
          <label>[checkbox new_80 "Sản phẩm của bạn còn mới trên 80%|yes"]</label>
          <label><span>Sản phẩm local</span> [number clothes_old_local_number min:1 placeholder "Số lượng"]</label><label>
            <span>Brand</span> [text* clothes_old_local_brand placeholder "Brand #1, Brand #2..."]</label>
          <label><span>Sản phẩm global</span> [number clothes_old_global_number min:1 placeholder "số lượng"]</label>
          <label> <span>Brand</span> [text* clothes_old_global_brand placeholder "Brand #1, Brand #2..."]</label>
        </fieldset>
      </div>

      <div class="cf-col-4">
        <fieldset>
          <legend>Hình ảnh</legend>
          [mfile* upload-file-386 filetypes:png|jpg|jpeg min-file:1 max-file:1]
          <p>
            Định dạng: *.PNG, *.JPG, *.JPEG<br>
            Dung lượng tối đa của tập tin: 5MB
          </p>
        </fieldset>
      </div>

    </div>
  </div>

  [/group]

</div>

<div class="cf-container">
  <div class="cf-col-8 cf-push-2" style="text-align: center">
    [acceptance checkbox_agree]Tôi cam kết sản phẩm trên là sản phẩm chính hãng. Bảng tổng kết sẽ được
    gửi qua Zalo, thanh toán chuyển khoản, phí shop hai đầu do khách chịu.[/acceptance]
    <div style="margin-top: 10px">
    <label style="display: inline-block">[submit "Ký gửi"]</label>
    </div>
  </div>
</div>

