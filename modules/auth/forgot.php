<?php
if (!defined('_TRGIAHUY')) {
  die('Truy cập không hợp lệ');
}

$data = [
  'title' => 'Quên mật khẩu'
];
layout('header-auth', $data);

if (isPost()) {
  $filter = filterData();
  $errors = [];
  // Validate Email
  if (empty(trim($filter['email']))) {
    $errors['email']['required'] = 'Email bắt buộc phải nhập';
  } else {
    if (! validateEmail(trim($filter['email']))) {
      $errors['email']['isEmail'] = 'Email không đúng định dạng';
    }
  }

  if (empty($errors)) {
    // Xử lý và gửi mail
    if (!empty($filter['email'])) {
      $email = $filter['email'];

      $checkEmail = getOne("SELECT * FROM users WHERE email ='$email'");
      if (!empty($checkEmail)) {
        // Update forget_token into user table
        $forget_token = sha1(uniqid() . time());
        $data = [
          'forget_token' => $forget_token
        ];
        $condition = "id=" . $checkEmail['id'];
        $updateStatus = update('users', $data, $condition);
        if ($updateStatus) {
          // Send forgot mail
          $emailTo = $email;
          $subject   = 'Yêu cầu đặt lại mật khẩu – Courses Manager';
          $resetLink = _HOST_URL . '/?module=auth&action=reset&token=' . $forget_token;

          $content  = '<div style="font-family: Arial, sans-serif; background:#f6f9fc; padding:24px;">';
          $content .= '  <div style="max-width:600px; margin:auto; background:#ffffff; border-radius:10px;';
          $content .= '              padding:28px; box-shadow:0 2px 8px rgba(0,0,0,0.05); border:1px solid #e5e7eb;">';

          $content .= '    <h2 style="text-align:center; color:#2563eb; margin-bottom:20px;">Yêu cầu đặt lại mật khẩu</h2>';
          $content .= '    <p style="color:#374151;">Xin chào <b>' . $checkEmail['fullname'] . '</b>,</p>';
          $content .= '    <p style="color:#374151;">Bạn vừa gửi yêu cầu đặt lại mật khẩu cho tài khoản trên hệ thống <b>Courses Manager</b>.</p>';
          $content .= '    <p style="color:#374151;">Để đặt lại mật khẩu, vui lòng nhấn vào nút bên dưới:</p>';

          $content .= '    <div style="text-align:center; margin:30px 0;">';
          $content .= '      <a href="' . $resetLink . '" style="background-color:#2563eb; color:#fff; text-decoration:none;';
          $content .= '         padding:12px 24px; border-radius:8px; font-weight:bold; display:inline-block;">Đặt lại mật khẩu</a>';
          $content .= '    </div>';

          $content .= '    <p style="color:#374151;">Nếu bạn không yêu cầu thay đổi mật khẩu, hãy bỏ qua email này.';
          $content .= ' Liên kết sẽ tự động hết hạn sau một khoảng thời gian ngắn để đảm bảo an toàn.</p>';

          $content .= '    <br><p>Trân trọng,</p>';
          $content .= '    <p><b>Đội ngũ Courses Manager</b></p>';
          $content .= '  </div>';

          $content .= '  <div style="text-align:center; color:#6b7280; font-size:12px; margin-top:18px;">';
          $content .= '    <p style="margin:0;">Email này được gửi tự động, vui lòng không trả lời.</p>';
          $content .= '  </div>';
          $content .= '</div>';


          sendMail($emailTo, $subject, $content);

          setSessionFlash('msg', 'Gửi yêu cầu thành công, vui lòng kiểm tra email.');
          setSessionFlash('msg_type', 'success');
        } else {
          setSessionFlash('msg', 'Đã có lỗi xảy ra, vui lòng thử lạo sau.');
          setSessionFlash('msg_type', 'danger');
        }
      }
    }
  } else {
    setSessionFlash('msg', 'Vui lòng kiểm tra lại dữ liệu nhập vào');
    setSessionFlash('msg_type', 'danger');
    setSessionFlash('oldData', $filter);
    setSessionFlash('errors', $errors);
  }
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
            <h2 class="fw-normal mb-5 me-3">Quên mật khẩu</h2>

          </div>

          <!-- Email input -->
          <div data-mdb-input-init class="form-outline mb-4">
            <input type="text" name="email" value="<?php
                                                    if (!empty($oldData)) {
                                                      echo oldData($oldData, 'email');
                                                    }  ?>"
              id="form3Example3" class="form-control form-control-lg"
              placeholder="Địa chỉ email" />
            <?php if (!empty($errorsArr)) {
              echo formError($errorsArr, 'email');
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
