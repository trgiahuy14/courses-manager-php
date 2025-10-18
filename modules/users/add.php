<?php
if (!defined('_TRGIAHUY')) {
    die('Truy cập không hợp lệ');
}
$data = ['title' => 'Thêm mới người dùng'];
layout('header', $data);
layout('sidebar');
?>

<div class="container add-user">
    <h2>Thêm mới người dùng</h2>
    <hr>
    <form action="" method="post">
        <div class="row">

            <!-- Full name -->
            <div class="col-6 pb-3">
                <label for="fullname">Họ và tên</label>
                <input id="fullname" type="text" class="form-control" placeholder="Họ tên">
            </div>

            <!-- Email -->
            <div class="col-6 pb-3">
                <label for="fullname">Email</label>
                <input id="email" type="text" class="form-control" placeholder="Email">
            </div>

            <!-- Phone number -->
            <div class="col-6 pb-3">
                <label for="phone">Số điện thoại</label>
                <input id="phone" type="text" class="form-control" placeholder="Số điện thoại">
            </div>

            <!-- Password -->
            <div class="col-6 pb-3">
                <label for="password">Mật khẩu</label>
                <input id="password" type="password" class="form-control" placeholder="Mật khẩu">
            </div>

            <!-- Address -->
            <div class="col-6 pb-3">
                <label for="address">Địa chỉ</label>
                <input id="address" type="text" class="form-control" placeholder="Địa chỉ">
            </div>

            <!-- Group -->
            <div class="col-3 pb-3">
                <label for="group">Phân cấp người dùng</label>
                <select name="group" id="group" class="form-select form-control">
                    <?php
                    $getGroup = getAll("SELECT * FROM `groups`");
                    foreach ($getGroup as $item):
                    ?>
                        <option value="<?php $item['id']; ?>"><?php echo $item['name']; ?></option>
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