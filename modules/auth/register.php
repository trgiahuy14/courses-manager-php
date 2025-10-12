<?php
if (!defined('_HIENUE')) {
  die('Truy cập không hợp lệ');
}
$data = [
  'title' => 'Đăng ký tài khoản'
];
layout('header-auth', $data);

// Validate

if (!empty(isPost())) {
  $filter = filterData();
  $errors = [];

  // Validate fullname
  if (empty(trim($filter['fullname']))) {
    $errors['fullname']['required'] = 'Họ tên bắt buộc phải nhập';
  } else {
    if (strlen(trim($filter['fullname'])) < 5) {
      $errors['fullname']['length'] = 'Họ tên phải lớn hơn 5 ký tự';
    }
  }

  // Validate Email
  if (empty(trim($filter['email']))) {
    $errors['email']['required'] = 'Email bắt buộc phải nhập';
  } else {
    if (! validateEmail(trim($filter['email']))) {
      $errors['email']['isEmail'] = 'Email không đúng định dạng';
    } else {
      $email = $filter['email'];
      $checkEmail = getRows("SELECT * FROM users WHERE email = '$email'");
      if ($checkEmail > 0) {
        $errors['email']['check'] = 'Email đã tồn tại';
      }
    }
  }

  // Validate phone
  if (empty($filter['phone'])) {
    $errors['phone']['required'] = 'Số điện thoại bắt buộc phải nhập';
  } else {
    if (! isPhone($filter['phone'])) {
      $errors['phone']['isPhone'] = 'Số điện thoại không đúng định dạng';
    }
  }

  // Validate password
  if (empty($filter['password'])) {
    $errors['password']['required'] = 'Mật khẩu bắt buộc phải nhập';
  } else {
    if (strlen(trim($filter['password']) < 6)) {
      $errors['password']['length'] = 'Mật khẩu phải dài hơn 6 ký tự';
    }
  }

  // Validate confirm pass
  if (empty($filter['confirm_pass'])) {
    $errors['confirm_pass']['required'] = 'Vui lòng nhập lại mật khẩu';
  } else {
    if (trim($filter['confirm_pass']) !== trim($filter['password'])) {
      $errors['confirm_pass']['like'] = 'Mật khẩu nhập lại không khớp';
    }
  }

  if (empty($errors)) {
    $msg = 'Đăng ký thành công';
    $msg_type = 'success';

    $activeToken = sha1(uniqid() . time()); // Tạo mã token

    $data = [
      'fullname'      => $filter['fullname'],
      'address'       => $filter['address'],
      'phone'         => $filter['phone'],
      'password'      => password_hash($filter['password'], PASSWORD_DEFAULT),
      'email'         => $filter['email'],
      'active_token'  => $activeToken,
      'group_id'      => 1,
      'created_at'    => date('Y:m:d H:i:s')
    ];

    // For debug
    // try {
    //   $insertStatus = insert('users', $data);
    // } catch (Throwable $e) {
    //   echo '<pre>SQL ERROR: ' . $e->getMessage() . '</pre>';
    //   // nếu cần: echo $e->getTraceAsString();
    //   exit;
    // }


    $insertStatus = insert('users', $data);
    if ($insertStatus) {
      $msg = 'Đăng ký thành công';
      $msg_type = 'success';
    } else {
      $msg = 'Đăng ký không thành công';
      $msg_type = 'danger';
    }
  } else {
    $msg = 'Dữ liệu không hợp lệ, hãy kiểm tra lại';
    $msg_type = 'danger';

    setSessionFlash('oldData', $filter);
    setSessionFlash('errors', $errors);
  }
  $oldData = getSessionFlash('oldData');
  $errorsArr = getSessionFlash('errors');
}

?>
<section class="vh-100">
  <div class="container-fluid h-custom">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-md-9 col-lg-6 col-xl-5">
        <img src="<?php echo _HOST_URL_TEMPLATES; ?>/assets/image/draw2.jpg"
          class="img-fluid" alt="Sample image">
      </div>
      <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">

        <?php getMsg($msg, $msg_type) ?>

        <form method="POST" action="" enctype="multipart/form-data">
          <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start">
            <h2 class="fw-normal mb-5 me-3">Đăng ký tài khoản</h2>

          </div>


          <!-- Register form -->
          <!-- Full name -->
          <div data-mdb-input-init class="form-outline mb-4">
            <input name="fullname" type="Text" value="<?php echo oldData($oldData, 'fullname')  ?>" class="form-control form-control-lg"
              placeholder="Họ tên" />
            <?php echo formError($errorsArr, 'fullname') ?>
          </div>

          <!-- Email -->
          <div data-mdb-input-init class="form-outline mb-4">
            <input name="email" type="text" value="<?php echo oldData($oldData, 'email')  ?>" class="form-control form-control-lg"
              placeholder="Địa chỉ email" />
            <div class="error"><?php echo !empty($errorsArr['email']) ? reset($errorsArr['email']) : false; ?> </div>

          </div>

          <!-- Phone number -->
          <div data-mdb-input-init class="form-outline mb-4">
            <input name="phone" type="text" value="<?php echo oldData($oldData, 'phone')  ?>" class="form-control form-control-lg"
              placeholder="Nhập số điện thoại" />
            <div class="error"><?php echo !empty($errorsArr['phone']) ? reset($errorsArr['phone']) : false; ?> </div>

          </div>


          <!-- Password input -->
          <div data-mdb-input-init class="form-outline mb-3">
            <input name="password" type="password" id="form3Example4" class="form-control form-control-lg"
              placeholder="Nhập mật khẩu" />
            <div class="error"><?php echo !empty($errorsArr['password']) ? reset($errorsArr['password']) : false; ?> </div>

          </div>

          <!-- Nhập lại mật khẩu -->
          <div data-mdb-input-init class="form-outline mb-3">
            <input name="confirm_pass" type="password" id="form3Example4" class="form-control form-control-lg"
              placeholder="Nhập lại mật khẩu" />
            <div class="error"><?php echo !empty($errorsArr['confirm_pass']) ? reset($errorsArr['confirm_pass']) : false; ?> </div>

          </div>



          <div class="text-center text-lg-start mt-4 pt-2">
            <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg"
              style="padding-left: 2.5rem; padding-right: 2.5rem;">Đăng ký</button>
            <p class="small fw-bold mt-2 pt-1 mb-0">Bạn đã có tài khoản? <a href="<?php echo _HOST_URL ?>?module=auth&action=login"
                class="link-danger">Đăng nhập</a></p>
          </div>

        </form>
      </div>
    </div>
  </div>


</section>

<?php

layout('footer');
