<?php
if (!defined('_TRGIAHUY')) {
    die('Truy cập không hợp lệ');
}
$data = [
    'title' => 'Danh sách người dùng'
];
layout('header', $data);
layout('sidebar');
?>
<div class="container grid-user">
    <div class="container-fluid">
        <a href="?module=users&action=add" class="btn btn-success mb-3"><i class="fa-solid fa-plus"></i>Thêm mới người dùng</a>
        <form class="mb-3" action=" " method="get">
            <div class="row">
                <div class="col-3">
                    <select class="form-select form-control" name="" id="">
                        <option value="">Nhóm người dùng</option>
                        <option value="">1</option>
                    </select>
                </div>
                <div class="col-7">
                    <input class="form-control" type="text" placeholder="Nhập thông tin tìm kiếm...">
                </div>

                <div class="col-2"><button class="btn btn-primary" type="submit">Tìm kiếm</button></div>
            </div>

        </form>
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th scope="col">STT</th>
                    <th scope="col">Họ tên</th>
                    <th scope="col">Email</th>
                    <th scope="col">Ngày đăng ký</th>
                    <th scope="col">Nhóm</th>
                    <th scope="col">Phân quyền</th>
                    <th scope="col">Sửa</th>
                    <th scope="col">Xóa</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">1</th>
                    <td>Mark</td>
                    <td>Otto</td>
                    <td>@mdo</td>
                    <td></td>
                    <td><a href="#" class="btn btn-primary">Phân quyền</a></td>
                    <td><a href="#" class="btn btn-warning"><i class="fa-solid fa-pencil"></i></a></td>
                    <td><a href="#" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a></td>
                </tr>
            </tbody>
        </table>

        <!-- Pagination -->
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">Next</a></li>
            </ul>
        </nav>
    </div>
</div>


<?php layout('footer') ?>