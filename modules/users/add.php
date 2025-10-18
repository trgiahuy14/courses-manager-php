<?php
if (!defined('_TRGIAHUY')) {
    die('Truy cập không hợp lệ');
}
$data = ['title' => 'Thêm mới người dùng'];
layout('header', $data);
layout('sidebar');

if (isPost()) {

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
        if (strlen(trim($filter['password'])) < 6) {
            $errors['password']['length'] = 'Mật khẩu phải dài hơn 6 ký tự';
        }
    }

    if (empty($errors)) {
        $dataInsert = [
            'fullname' => $filter['fullname'],
            'email' => $filter['email'],
            'phone' => $filter['phone'],
            'group_id' => $filter['group_id'],
            'password' => password_hash($filter['password'], PASSWORD_DEFAULT),
            'status' => $filter['status'],
            'avatar' => '/templates/uploads/avatar-default.jpg',
            'address' => (!empty($filter['address']) ? $filter['address'] : null),
            'created_at' => date('Y:m:d H:i:s')
        ];

        $insertStatus = insert('users', $dataInsert);

        if ($insertStatus) {
            setSessionFlash('msg', 'Thêm người dùng thành công.');
            setSessionFlash('msg_type', 'success');
            redirect(('?module=users&action=list'));
        } else {
            setSessionFlash('msg', 'Thêm người dùng thất bại');
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

<div class="container add-user">
    <h2>Thêm mới người dùng</h2>
    <hr>
    <?php
    if (!empty($msg) && !empty($msg_type)) {
        getMsg($msg, $msg_type);
    }
    ?>
    <form action="" method="post">
        <div class="row">

            <!-- Full name -->
            <div class="col-6 pb-3">
                <label for="fullname">Họ và tên</label>
                <input id="fullname" name="fullname" type="text" class="form-control"
                    value="<?php if (!empty($oldData)) {
                                echo oldData($oldData, 'fullname');
                            }  ?>"
                    placeholder="Họ tên">
                <?php
                if (!empty($errorsArr)) {
                    echo formError($errorsArr, 'fullname');
                } ?>
            </div>

            <!-- Email -->
            <div class="col-6 pb-3">
                <label for="fullname">Email</label>
                <input id="email" name="email" type="text" class="form-control"
                    value="<?php if (!empty($oldData)) {
                                echo oldData($oldData, 'email');
                            }  ?>"
                    placeholder="Email">
                <?php
                if (!empty($errorsArr)) {
                    echo formError($errorsArr, 'email');
                } ?>
            </div>

            <!-- Phone number -->
            <div class="col-6 pb-3">
                <label for="phone">Số điện thoại</label>
                <input id="phone" name="phone" type="text" class="form-control"
                    value="<?php if (!empty($oldData)) {
                                echo oldData($oldData, 'phone');
                            }  ?>"
                    placeholder="Số điện thoại">
                <?php
                if (!empty($errorsArr)) {
                    echo formError($errorsArr, 'phone');
                } ?>
            </div>

            <!-- Password -->
            <div class="col-6 pb-3">
                <label for="password">Mật khẩu</label>
                <input id="password" name="password" type="password" class="form-control"
                    value="<?php if (!empty($oldData)) {
                                echo oldData($oldData, 'password');
                            }  ?>"
                    placeholder="Mật khẩu">
                <?php
                if (!empty($errorsArr)) {
                    echo formError($errorsArr, 'password');
                } ?>
            </div>

            <!-- Address -->
            <div class="col-6 pb-3">
                <label for="address">Địa chỉ</label>
                <input id="address" name="address" type="text" class="form-control" placeholder="Địa chỉ">
            </div>

            <!-- Group -->
            <div class="col-3 pb-3">
                <label for="group">Phân cấp người dùng</label>
                <select name="group_id" id="group" class="form-select form-control">
                    <?php
                    $getGroup = getAll("SELECT * FROM `groups`");
                    foreach ($getGroup as $item):
                    ?>
                        <option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Status -->
            <div class="col-3 pb-3">
                <label for="status">Trạng thái tài khoản</label>
                <select name="status" id="status" class="form-select form-control">
                    <option value="0">Chưa kích hoạt</option>
                    <option value="1">Đã kích hoạt</option>
                </select>
            </div>

        </div>
        <button type="submit" class="btn btn-success">Xác nhận</button>
    </form>
</div>

<?php layout('footer') ?>