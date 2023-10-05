<div class="cf-container">
  <div class="cf-col-4"><label>Tên</label>[text* name autocomplete:name default:user_display_name]</div>
  <div class="cf-col-4"><label>Số điện thoại (có Zalo)</label> [tel* phone autocomplete:phone]</div>
  <div class="cf-col-4"><label>Tên Zalo</label> [text* zalo_name]</div>
</div>

<div class="cf-container">
  <div class="cf-col-12"><label>Địa chỉ nhận hàng tồn</label> [text* pickup_address]</div>
</div>
<div class="cf-container">
  <div class="cf-col-12">
    <p style="margin-bottom: 10px">
      <span style="margin-right: 10px;"><b>Bạn đã biết</b>:</span> [radio you_know use_label_element default:1 "Có"
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
    <label>Giá bán mong muống là giá</label> [number* desired_price]
  </div>

</div>

<!-- Quần áo - Start  -->
<div style="margin: 1.5rem 0">
  [checkbox clothes_checkbox use_label_element default:1 "Quần áo"]

  [group clothes_box]
  <div style="background-color: var(--has-classic-forms, var(--form-field-initial-background)); padding: 20px; border-radius: 10px">
    <div style="color: red; margin-bottom: 20px; font-size: 14px">Lưu ý: Tổng số lượng mới + cũ phải từ 5 sản phẩm trở lên</div>
    <div class="cf-container">

      <div class="cf-col-4">
        <fieldset>
          <legend>Mới</legend>
          <label>Sản phẩm local</label> [number clothes_new_local min:1 placeholder "số lượng"]
          <label>Brand</label> [text* clothes_new_local_brand placeholder "Brand #1, Brand #2..."]
          <label>Sản phẩm global</label> [number clothes_new_global min:1 placeholder "số lượng"]
          <label>Brand</label> [text* clothes_new_global_brand placeholder "Brand #1, Brand #2..."]
        </fieldset>
      </div>
      <div class="cf-col-4">
        <fieldset>
          <legend>Qua sử dụng</legend>
          [checkbox clothes_used_80 use_label_element "Sản phẩm còn mới > 80%"]
          <label>Sản phẩm local</label> [number clothes_used_local min:1 placeholder "số lượng"]
          <label>Brand</label> [text* clothes_used_local_brand placeholder "Brand #1, Brand #2..."]
          <label>Sản phẩm global</label> [number clothes_used_global min:1 placeholder "số lượng"]
          <label>Brand</label>[text* clothes_used_global_brand placeholder "Brand #1, Brand #2..."]
        </fieldset>
      </div>

      <div class="cf-col-4">
        <fieldset>
          <legend>Hình ảnh</legend>
          [mfile* clothes_photo filetypes:png|jpg|jpeg min-file:1 max-file:1]
          <p>
            Định dạng: *.PNG, *.JPG, *.JPEG<br>
            Dung lượng tối đa của tập tin: 5MB
          </p>
          <p><a href="/huong-dan-chup-hinh" target="_blank"><i>***Hướng dẫn chụp hình</i></a></p>
        </fieldset>
      </div>

    </div>
  </div>
  [/group]
</div>
<!-- Quần áo - End  -->

<!-- Túi - Start  -->
<div style="margin: 1.5rem 0">
  [checkbox bag_checkbox use_label_element default:1 "Túi"]

  [group bag_box]
  <div style="background-color: var(--has-classic-forms, var(--form-field-initial-background)); padding: 20px; border-radius: 10px">
    <div style="color: red; margin-bottom: 20px; font-size: 14px">Lưu ý: Tổng số lượng mới + cũ phải từ 5 sản phẩm trở lên</div>
    <div class="cf-container">

      <div class="cf-col-4">
        <fieldset>
          <legend>Mới</legend>
          <label>Số lượng</label> [number bag_new min:1 placeholder "số lượng"]
          <label>Brand</label> [text* bag_new_brand placeholder "Brand #1, Brand #2..."]
          <p>***Nếu sản phẩm thuộc dòng thương hiệu high-end thì cần phải có bill hoặc trả mức phí kiểm định Entrupy</p>
        </fieldset>
      </div>
      <div class="cf-col-4">
        <fieldset>
          <legend>Qua sử dụng</legend>
          [checkbox bag_used_90 use_label_element "Sản phẩm còn mới > 90%"]
          <label>Số lượng</label> [number bag_used min:1 placeholder "số lượng"]
          <label>Brand</label> [text* bag_used_brand placeholder "Brand #1, Brand #2..."]
        </fieldset>
      </div>

      <div class="cf-col-4">
        <fieldset>
          <legend>Hình ảnh</legend>
          [mfile* bag_photo filetypes:png|jpg|jpeg min-file:1 max-file:1]
          <p>
            Định dạng: *.PNG, *.JPG, *.JPEG<br>
            Dung lượng tối đa của tập tin: 5MB
          </p>
          <p><a href="/huong-dan-chup-hinh" target="_blank"><i>***Hướng dẫn chụp hình</i></a></p>
        </fieldset>
      </div>

    </div>
  </div>
  [/group]
</div>
<!-- Túi - End  -->

<!-- Giày - Start  -->
<div style="margin: 1.5rem 0">
  [checkbox shoe_checkbox use_label_element "Giày"]

  [group shoe_box]
  <div style="background-color: var(--has-classic-forms, var(--form-field-initial-background)); padding: 20px; border-radius: 10px">
    <div style="color: red; margin-bottom: 20px; font-size: 14px">Lưu ý: Tổng số lượng mới + cũ phải từ 5 sản phẩm trở lên</div>
    <div class="cf-container">

      <div class="cf-col-4">
        <fieldset>
          <legend>Mới</legend>
          <label>Số lượng</label> [number shoe_new min:1 placeholder "số lượng"]
          <label>Brand</label> [select shoe_new_brand multiple include_blank "Adidas" "Nike" "Reebox"]
          <p>***Nếu sản phẩm thuộc dòng thương hiệu high-end thì cần phải có bill hoặc trả mức phí kiểm định Entrupy</p>
        </fieldset>
      </div>
      <div class="cf-col-4">
        <fieldset>
          <legend>Qua sử dụng</legend>
          [checkbox shoe_used_90 use_label_element "Sản phẩm còn mới > 90%"]
          <label>Số lượng</label> [number shoe_used min:1 placeholder "số lượng"]
          <label>Brand</label> [select shoe_used_brand multiple include_blank "Adidas" "Nike" "Reebox"]
          <p>***Sản phẩm đã qua sử dụng, vui lòng vệ sinh trước khi gửi đến cửa hàng</p>
        </fieldset>
      </div>

      <div class="cf-col-4">
        <fieldset>
          <legend>Hình ảnh</legend>
          [mfile* show_photo filetypes:png|jpg|jpeg min-file:1 max-file:1]
          <p>
            Định dạng: *.PNG, *.JPG, *.JPEG<br>
            Dung lượng tối đa của tập tin: 5MB
          </p>
          <p><a href="/huong-dan-chup-hinh" target="_blank"><i>***Hướng dẫn chụp hình</i></a></p>
        </fieldset>
      </div>

    </div>
  </div>
  [/group]
</div>
<!-- Giày - End  -->

<!-- Mỹ phẩm - Start  -->
<div style="margin: 1.5rem 0">
  [checkbox cosmetic_checkbox use_label_element "Mỹ phẩm"]

  [group cosmetic_box]
  <div style="background-color: var(--has-classic-forms, var(--form-field-initial-background)); padding: 20px; border-radius: 10px">
    <div style="color: red; margin-bottom: 20px; font-size: 14px">Lưu ý: Tổng số lượng mới + cũ phải từ 5 sản phẩm trở lên</div>
    <div class="cf-container">

      <div class="cf-col-4">
        <fieldset>
          <legend>Mới</legend>
          [checkbox cosmetic_new_6 use_label_element "Còn date > 6 tháng"]
          <label>Số lượng</label> [number cosmetic_new min:1 max:30 placeholder "1-30"]
          <label>Brand</label> [select cosmetic_new_brand multiple include_blank "L'Oréal" "Estee Lauder" "MAC"]
          <p>***Tem nhãn và thương hiệu phải còn nguyên không được mờ, rách. Sản phẩm cần được đóng gói để đảm bảo</p>
        </fieldset>
      </div>
      <div class="cf-col-4">
        <fieldset>
          <legend>Qua sử dụng</legend>
          [checkbox cosmetic_used_6 use_label_element "Còn date > 6 tháng"]
          [checkbox cosmetic_used_capacity_80 use_label_element "Dung tích > 80%"]
          <label>Số lượng</label> [number cosmetic_used min:1 max:30 placeholder "1-30"]
          <label>Brand</label> [select cosmetic_used_brand multiple include_blank "L'Oréal" "Estee Lauder" "MAC"]
          <p>***Tem nhãn và thương hiệu phải còn nguyên không được mờ, rách. Sản phẩm cần được đóng gói để đảm bảo</p>
        </fieldset>
      </div>

      <div class="cf-col-4">
        <fieldset>
          <legend>Hình ảnh</legend>
          [mfile* cosmetic_photo filetypes:png|jpg|jpeg min-file:1 max-file:1]
          <p>
            Định dạng: *.PNG, *.JPG, *.JPEG<br>
            Dung lượng tối đa của tập tin: 5MB
          </p>
          <p><a href="/huong-dan-chup-hinh" target="_blank"><i>***Hướng dẫn chụp hình</i></a></p>
        </fieldset>
      </div>

    </div>
  </div>
  [/group]
</div>
<!-- Mỹ phẩm - End  -->

<!-- Nước hoa - Start  -->
<div style="margin: 1.5rem 0">
  [checkbox perfume_checkbox use_label_element "Nước hoa"]

  [group perfume_box]
  <div style="background-color: var(--has-classic-forms, var(--form-field-initial-background)); padding: 20px; border-radius: 10px">
    <div style="color: red; margin-bottom: 20px; font-size: 14px">Lưu ý: Tổng số lượng mới + cũ phải từ 5 sản phẩm trở lên</div>
    <div class="cf-container">

      <div class="cf-col-4">
        <fieldset>
          <legend>Mới</legend>
          <label>Số lượng</label> [number perfume_new min:1 max:30 placeholder "1-30"]
          <label>Brand</label> [select perfume_new_brand multiple include_blank "Dior" "Armani" "Bvlgari"]
        </fieldset>
      </div>
      <div class="cf-col-4">
        <fieldset>
          <legend>Qua sử dụng</legend>
          [checkbox perfume_used_capacity_80 use_label_element "Dung tích > 80%"]
          <label>Số lượng</label> [number perfume_used min:1 max:30 placeholder "1-30"]
          <label>Brand</label> [select perfume_used_brand multiple include_blank "Dior" "Armani" "Bvlgari"]
        </fieldset>
      </div>

      <div class="cf-col-4">
        <fieldset>
          <legend>Hình ảnh</legend>
          [mfile* perfume_photo filetypes:png|jpg|jpeg min-file:1 max-file:1]
          <p>
            Định dạng: *.PNG, *.JPG, *.JPEG<br>
            Dung lượng tối đa của tập tin: 5MB
          </p>
          <p><a href="/huong-dan-chup-hinh" target="_blank"><i>***Hướng dẫn chụp hình</i></a></p>
        </fieldset>
      </div>

    </div>
  </div>
  [/group]
</div>
<!-- Nước hoa - End  -->


<div class="cf-container">
  <div class="cf-col-8 cf-push-2" style="text-align: center">
    [acceptance agree_checkbox]Tôi cam kết sản phẩm trên là sản phẩm chính hãng. Bảng tổng kết sẽ được
    gửi qua Zalo, thanh toán chuyển khoản, phí shop hai đầu do khách chịu.[/acceptance]
    <div style="margin-top: 10px">
    <label style="display: inline-block">[submit "Ký gửi"]</label>
    </div>
  </div>
</div>

