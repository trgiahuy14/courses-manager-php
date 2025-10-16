<?php
if (!defined('_TRGIAHUY')) {
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

  // Validate Fullname
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

  // Validate confirm password
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

    $activeToken = sha1(uniqid() . time()); // Tạo mã cho token

    $data = [
      'fullname'      => $filter['fullname'],
      'address'       => $filter['address'],
      'phone'         => $filter['phone'],
      'password'      => password_hash($filter['password'], PASSWORD_DEFAULT),
      'email'         => $filter['email'],
      'active_token'  => $activeToken,
      'group_id'      => 1,   // Mặc định cho tài khoản mới là student
      'created_at'    => date('Y:m:d H:i:s')
    ];

    $insertStatus = insert('users', $data);

    // For debug
    // try {
    //   $insertStatus = insert('users', $data);
    // } catch (Throwable $e) {
    //   echo '<pre>SQL ERROR: ' . $e->getMessage() . '</pre>';
    //   // nếu cần: echo $e->getTraceAsString();
    //   exit;
    // }


    if ($insertStatus) {

      // Send active email
      $emailTo = $filter['email'];
      $subject = 'Kích hoạt tài khoản – Courses Manager';
      $activeLink = _HOST_URL . '/?module=auth&action=active&token=' . $activeToken;

      $content  = '<div style="font-family: Arial, sans-serif; background:#f6f9fc; padding:24px;">';
      $content .= '  <div style="max-width:600px; margin:auto; background:#ffffff; border-radius:10px; ';
      $content .= '              padding:28px; box-shadow:0 2px 8px rgba(0,0,0,0.05); border:1px solid #e5e7eb;">';

      $content .= '    <h2 style="text-align:center; color:#2563eb; margin-bottom:20px;">Kích hoạt tài khoản Courses Manager</h2>';
      $content .= '    <p style="color:#374151;">Xin chào <b>' . $filter['fullname'] . '</b>,</p>';
      $content .= '    <p style="color:#374151;">Cảm ơn bạn đã đăng ký tài khoản trên hệ thống <b>Courses Manager</b>.</p>';
      $content .= '    <p style="color:#374151;">Để hoàn tất việc đăng ký, vui lòng nhấn vào nút bên dưới để kích hoạt tài khoản của bạn:</p>';

      $content .= '    <div style="text-align:center; margin:30px 0;">';
      $content .= '      <a href="' . $activeLink . '" style="background-color:#2563eb; color:#fff; text-decoration:none; ';
      $content .= '         padding:12px 24px; border-radius:8px; font-weight:bold; display:inline-block;">Kích hoạt tài khoản</a>';
      $content .= '    </div>';

      $content .= '    <p style="color:#374151;">Nếu bạn không thực hiện đăng ký này, vui lòng bỏ qua email này. ';
      $content .= 'Liên kết sẽ tự động hết hạn sau một khoảng thời gian ngắn để đảm bảo an toàn.</p>';

      $content .= '    <br><p>Trân trọng,</p>';
      $content .= '    <p><b>Đội ngũ Courses Manager</b></p>';
      $content .= '  </div>';

      $content .= '  <div style="text-align:center; color:#6b7280; font-size:12px; margin-top:18px;">';
      $content .= '    <p style="margin:0;">Email này được gửi tự động, vui lòng không trả lời.</p>';
      $content .= '  </div>';
      $content .= '</div>';

      sendMail($emailTo, $subject, $content);

      setSessionFlash('msg', 'Đăng ký thành công, vui lòng kiểm tra email để kích hoạt tài khoản.');
      setSessionFlash('msg_type', 'success');
    } else {
      setSessionFlash('msg', 'Đăng ký không thành công, vui lòng thử lại sau.');
      setSessionFlash('msg_type', 'danger');
    }
  } else {
    setSessionFlash('msg', 'Vui lòng kiểm tra lại dữ liệu nhập vào');
    setSessionFlash('msg_type', 'danger');

    setSessionFlash('oldData', $filter);
    setSessionFlash('errors', $errors);
  }

  $msg = getSessionFlash('msg');
  $msg_type = getSessionFlash('msg_type');
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

        <?php
        if (!empty($msg) && !empty($msg_type)) {
          getMsg($msg, $msg_type);
        }
        ?>

        <form method="POST" action="" enctype="multipart/form-data">
          <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start">
            <h2 class="fw-normal mb-5 me-3">Đăng ký tài khoản</h2>
          </div>

          <!-- Register form -->
          <!-- Full name -->
          <div data-mdb-input-init class="form-outline mb-4">
            <input name="fullname" type="Text" value="<?php
                                                      if (!empty($oldData)) {
                                                        echo oldData($oldData, 'fullname');
                                                      }  ?>" class="form-control form-control-lg"
              placeholder="Họ tên" />
            <?php
            if (!empty($errorsArr)) {
              echo formError($errorsArr, 'fullname');
            } ?>
          </div>

          <!-- Email -->
          <div data-mdb-input-init class="form-outline mb-4">
            <input name="email" type="text" value="<?php
                                                    if (!empty($oldData)) {
                                                      echo oldData($oldData, 'email');
                                                    }  ?>" class="form-control form-control-lg"
              placeholder="Địa chỉ email" />
            <?php if (!empty($errorsArr)) {
              echo formError($errorsArr, 'email');
            } ?>
          </div>

          <!-- Phone number -->
          <div data-mdb-input-init class="form-outline mb-4">
            <input name="phone" type="text" value="<?php
                                                    if (!empty($oldData)) {
                                                      echo oldData($oldData, 'phone');
                                                    }  ?>" class="form-control form-control-lg"
              placeholder="Nhập số điện thoại" />
            <?php
            if (!empty($errorsArr)) {
              echo formError($errorsArr, 'phone');
            } ?>
          </div>


          <!-- Password input -->
          <div data-mdb-input-init class="form-outline mb-3">
            <input name="password" type="password" id="form3Example4" class="form-control form-control-lg"
              placeholder="Nhập mật khẩu" />
            <?php
            if (!empty($errorsArr)) {
              echo formError($errorsArr, 'password');
            } ?>
          </div>

          <!-- Confirm password -->
          <div data-mdb-input-init class="form-outline mb-3">
            <input name="confirm_pass" type="password" id="form3Example4" class="form-control form-control-lg"
              placeholder="Nhập lại mật khẩu" />
            <?php
            if (!empty($errorsArr)) {
              echo formError($errorsArr, 'confirm_pass');
            } ?>
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
