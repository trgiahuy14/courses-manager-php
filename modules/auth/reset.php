<?php
if (!defined('_TRGIAHUY')) {
  die('Truy cập không hợp lệ');
}

$data = [
  'title' => 'Đặt lại mật khẩu'
];
layout('header-auth', $data);

$filterGet = filterData('get');
if (!empty($filterGet['token'])) {
  $tokenReset = $filterGet['token'];
}

if (!empty($tokenReset)) {
  // Check token
  $checkToken = getOne("SELECT * FROM users WHERE forget_token = '$tokenReset'");
  if (!empty($checkToken)) {
    if (isPost()) {
      $filter = filterData();
      $errors = [];

      // Validate password
      if (empty($filter['password'])) {
        $errors['password']['required'] = 'Mật khẩu bắt buộc phải nhập';
      } else {
        if (strlen(trim($filter['password'])) < 6) {
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
        $password = password_hash($filter['password'], PASSWORD_DEFAULT);
        $data = [
          'password' => $password,
          'forget_token' => null,
          'updated_at' => date('Y:m;d H:i:s')
        ];

        $condition = "id=" . $checkToken['id'];

        $updateStatus = update('users', $data, $condition);

        if ($updateStatus) {
          // Send mail
          $emailTo = $checkToken['email'];
          $subject = 'Mật khẩu của bạn đã được thay đổi – Courses Manager';

          $content  = '<div style="font-family: Arial, sans-serif; background:#f6f9fc; padding:24px;">';
          $content .= '  <div style="max-width:600px; margin:auto; background:#ffffff; border-radius:10px;';
          $content .= '              padding:28px; box-shadow:0 2px 8px rgba(0,0,0,0.05); border:1px solid #e5e7eb;">';

          $content .= '    <h2 style="text-align:center; color:#2563eb; margin-bottom:20px;">Đổi mật khẩu thành công</h2>';
          $content .= '    <p style="color:#374151;">Xin chào <b>' . $checkToken['fullname'] . '</b>,</p>';
          $content .= '    <p style="color:#374151;">Mật khẩu tài khoản của bạn trên hệ thống <b>Courses Manager</b> đã được thay đổi thành công.</p>';
          $content .= '    <p style="color:#374151;">Nếu bạn không thực hiện thay đổi này, vui lòng đặt lại mật khẩu ngay bằng cách chọn “Quên mật khẩu” tại trang đăng nhập để đảm bảo an toàn tài khoản.</p>';

          $content .= '    <div style="text-align:center; margin:30px 0;">';
          $content .= '      <a href="' . _HOST_URL . '/?module=auth&action=login" style="background-color:#2563eb; color:#fff; text-decoration:none;';
          $content .= '         padding:12px 24px; border-radius:8px; font-weight:bold; display:inline-block;">Đăng nhập ngay</a>';
          $content .= '    </div>';

          $content .= '    <p style="color:#374151;">Cảm ơn bạn đã sử dụng hệ thống <b>Courses Manager</b>.</p>';
          $content .= '    <br><p>Trân trọng,</p>';
          $content .= '    <p><b>Đội ngũ Courses Manager</b></p>';
          $content .= '  </div>';

          $content .= '  <div style="text-align:center; color:#6b7280; font-size:12px; margin-top:18px;">';
          $content .= '    <p style="margin:0;">Email này được gửi tự động, vui lòng không trả lời.</p>';
          $content .= '  </div>';
          $content .= '</div>';

          // Gửi mail
          sendMail($emailTo, $subject, $content);

          setSessionFlash('msg', 'Đổi mật khẩu thành công.');
          setSessionFlash('msg_type', 'success');
        } else {
          setSessionFlash('msg', 'Đã có lỗi xảy ra, vui lòng thử lại sau');
          setSessionFlash('msg_type', 'danger');
        }
      } else {
        setSessionFlash('msg', 'Vui lòng kiểm tra lại dữ liệu nhập vào');
        setSessionFlash('msg_type', 'danger');
        setSessionFlash('oldData', $filter);
        setSessionFlash('errors', $errors);
      }
    }
  } else {
    getMsg('Liên kết đã hết hạn hoặc không tồn tại', 'danger');
  }
} else {
  getMsg('Liên kết đã hết hạn hoặc không tồn tại', 'danger');
}

$msg = getSessionFlash('msg');
$msg_type = getSessionFlash('msg_type');
$oldData = getSessionFlash('oldData');
$errorsArr = getSessionFlash('errors');

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
            <h2 class="fw-normal mb-5 me-3">Đặt lại mật khẩu</h2>

          </div>

          <!-- Nhập lại mật khẩu mới -->
          <div data-mdb-input-init class="form-outline mb-3">
            <input type="password" name="password" id="form3Example4" class="form-control form-control-lg"
              placeholder="Nhập mật khẩu mới" />
            <?php
            if (!empty($errorsArr)) {
              echo formError($errorsArr, 'password');
            } ?>
          </div>

          <!-- Nhập lại mật khẩu mới -->
          <div data-mdb-input-init class="form-outline mb-3">
            <input type="password" name="confirm_pass" id="form3Example4" class="form-control form-control-lg"
              placeholder="Nhập lại mật khẩu mới" />
            <?php
            if (!empty($errorsArr)) {
              echo formError($errorsArr, 'confirm_pass');
            } ?>
          </div>

          <div class="text-center text-lg-start mt-4 pt-2">
            <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg"
              style="padding-left: 2.5rem; padding-right: 2.5rem;">Gửi</button>
          </div>

        </form>
      </div>
    </div>
  </div>


</section>

<?php

layout('footer');
