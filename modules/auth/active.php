<?php
if(!defined('_HIENUE')){
    die('Truy cập không hợp lệ');
}

$data = [
    'title' => 'Kích hoạt tài khoản'
];
layout('header-auth', $data);

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
            <h2 class="fw-normal mb-5 me-3">Kích hoạt tài khoản thành công</h2>
            
          </div>

          <div class="text-center text-lg-start mt-4 pt-2">
        
            <a href="<?php echo _HOST_URL?>?module=auth&action=login"
                class="link-danger" style ="font-size: 20px">Đăng nhập ngay</a>

        </form>
      </div>
    </div>
  </div>

  
</section>

<?php

layout('footer');