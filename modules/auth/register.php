<?php
if(!defined('_HIENUE')){
    die('Truy cập không hợp lệ');
}
require_once './templates/layout/header-auth.php';

?>

<section class="vh-100">
  <div class="container-fluid h-custom">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-md-9 col-lg-6 col-xl-5">
        <img src="<?php echo _HOST_URL_TEMPLATES; ?>/assets/image/draw2.jpg"
          class="img-fluid" alt="Sample image">
      </div>
      <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
        <form>
          <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start">
            <h2 class="fw-normal mb-5 me-3">Đăng ký tài khoản</h2>
            
          </div>

          <!-- Register form -->
          <div data-mdb-input-init class="form-outline mb-4">
            <input type="Text"  class="form-control form-control-lg"
              placeholder="Họ tên" />
          </div>

          <div data-mdb-input-init class="form-outline mb-4">
            <input type="email"  class="form-control form-control-lg"
              placeholder="Địa chỉ email" />
          </div>

          <div data-mdb-input-init class="form-outline mb-4">
            <input type="text"  class="form-control form-control-lg"
              placeholder="Nhập số điện thoại" />
          </div>

    
          <!-- Password input -->
          <div data-mdb-input-init class="form-outline mb-3">
            <input type="password" id="form3Example4" class="form-control form-control-lg"
              placeholder="Nhập mật khẩu" />
          </div>

          <!-- Nhập lại mật khẩu -->
          <div data-mdb-input-init class="form-outline mb-3">
            <input type="password" id="form3Example4" class="form-control form-control-lg"
              placeholder="Nhập lại mật khẩu" />
          </div>

         

          <div class="text-center text-lg-start mt-4 pt-2">
            <button  type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg"
              style="padding-left: 2.5rem; padding-right: 2.5rem;">Đăng ký</button>
            <p class="small fw-bold mt-2 pt-1 mb-0">Bạn đã có tài khoản? <a href="<?php echo _HOST_URL?>?module=auth&action=login"
                class="link-danger">Đăng nhập</a></p>
          </div>

        </form>
      </div>
    </div>
  </div>

  
</section>

<?php

require_once './templates/layout/footer.php';