<?php
if (!defined('_TRGIAHUY')) {
    die('Truy cập không hợp lệ');
}
$data = ['title' => 'Danh sách khóa học'];
layout('header', $data);
layout('sidebar');

// Get data from GET
$filter = filterData();
$chuoiWhere = '';
$cate = '0';
$keyword = '';

if (isGet()) {
    if (isset($filter['keyword'])) {
        $keyword = $filter['keyword'];
    }
    if (isset($filter['cate'])) {
        $cate = $filter['cate'];
    }

    if (!empty($keyword)) {
        if (strpos($chuoiWhere, 'WHERE') === false) { //  Phải so sánh chặt (===) vì strpos trả về vị trí đầu tiên 
            $chuoiWhere .= ' WHERE ';                  //    tìm thấy chữ Where, tức là vị trí 0, mà 0 thì là false trong PHP
        } else {
            $chuoiWhere .= ' AND ';
        }
        $chuoiWhere .= "(a.name LIKE '%$keyword%' OR a.description LIKE '%$keyword%')";
    }
    if (!empty($cate)) {
        if (strpos($chuoiWhere, 'WHERE') === false) {
            $chuoiWhere .= ' WHERE ';
        } else {
            $chuoiWhere .= ' AND ';
        }

        $chuoiWhere .= " a.category_id = $cate";
    }
}

// Pagination
$maxData = getRows("SELECT id FROM course"); // Total of data
$perPage = 3; // Row per page 
$maxPage = ceil($maxData / $perPage); // Calculate max page, ceil giúp làm tròn lên
$offset = 0;
$page = 1;

// Get page
if (isset($filter['page'])) {
    $page = $filter['page'];
}

// Over max page or page 0
if ($page > $maxPage || $page < 1) {
    $page = 1;
}

$offset =  ($page - 1) * $perPage;

// Get data from users table
$getDetailUser = getAll("SELECT a.id, a.name, a.price, a.created_at, a.thumbnail, b.name as name_cate
FROM course a
INNER JOIN course_category b
ON a.category_id = b.id $chuoiWhere
ORDER BY a.created_at DESC
LIMIT $offset, $perPage
 ");


// Xử lý querry
if (!empty($_SERVER['QUERY_STRING'])) {
    $queryString = $_SERVER['QUERY_STRING'];
    // Cắt chuỗi để không bị &page=1&page=2
    $queryString = str_replace('&page=' . $page, '', $queryString);
}

// Nếu có thực hiện truy vấn cate hoặc keyword 
if ($cate > 0 || !empty($keyword)) {
    $maxData2 = getRows("SELECT id FROM course a $chuoiWhere");
    $maxPage = ceil($maxData2 / $perPage);
}

$msg = getSessionFlash('msg');
$msg_type = getSessionFlash('msg_type');

?>

<div class="container grid-user">
    <div class="container-fluid">
        <a href="?module=course&action=add" class="btn btn-success mb-3"><i class="fa-solid fa-plus"></i>Thêm mới khóa học</a>
        <?php
        if (!empty($msg) && !empty($msg_type)) {
            getMsg($msg, $msg_type);
        }
        ?>
        <form class="mb-3" action=" " method="get">
            <input type="hidden" name="module" value="course">
            <input type="hidden" name="action" value="list">
            <div class="row">
                <div class="col-3">
                    <select class="form-select form-control" name="cate" id="">
                        <option value="">Lĩnh vực</option>
                        <?php
                        $getCate = getAll("SELECT * FROM course_category");
                        foreach ($getCate as $item):
                        ?>

                            <option value="<?php echo $item['id']; ?>" <?php echo ($cate == $item['id']) ? 'selected' : false; ?>><?php echo $item['name'] ?></option>

                        <?php endforeach; ?>

                    </select>
                </div>
                <div class="col-7">
                    <input class="form-control" type="text" value="<?php echo (!empty($keyword)) ? $keyword : false ?>"
                        name="keyword" placeholder="Nhập thông tin tìm kiếm...">
                </div>

                <div class="col-2"><button class="btn btn-primary" type="submit">Tìm kiếm</button></div>
            </div>

        </form>
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th scope="col">STT</th>
                    <th scope="col">Tên khóa học</th>
                    <th scope="col">Thumbnail</th>
                    <th scope="col">Giá</th>
                    <th scope="col">Lĩnh vực</th>

                    <th scope="col">Sửa</th>
                    <th scope="col">Xóa</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($getDetailUser as $key => $item): ?>
                    <tr>
                        <th scope="row"><?php echo $key + 1 ?></th>
                        <td><?php echo $item['name']; ?></td>
                        <td><img width="150px" src="<?php echo $item['thumbnail']; ?>" alt=""></td>
                        <td><?php echo $item['price']; ?></td>
                        <td><?php echo $item['name_cate']; ?></td>
                        <td><a href="?module=course&action=edit&id=<?php echo $item['id']; ?>" class="btn btn-warning"><i class="fa-solid fa-pencil"></i></a></td>
                        <td><a href="?module=course&action=delete&id=<?php echo $item['id']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa không')" class=" btn btn-danger"><i class="fa-solid fa-trash"></i></a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <nav aria-label="Page navigation example">
            <ul class="pagination">

                <!--  Xử lý nút Trước -->
                <?php if ($page > 1): ?>
                    <li class="page-item"><a class="page-link" href="?<?php echo $queryString; ?>&page=<?php echo $page - 1 ?>">Trước</a></li>
                <?php endif; ?>

                <!--  Xử lý nút ... trước -->
                <?php
                $start = $page - 1; // Tính vị trí bắt đầu 
                if ($start < 1) {
                    $start = 1;
                }
                ?>
                <?php if ($start > 1): ?>
                    <li class="page-item"><a class="page-link" href="?<?php echo $queryString; ?>&page=<?php echo $page - 1 ?>">...</a></li>
                <?php endif;
                $end = $page + 1;
                if ($end > $maxPage) {
                    $end = $maxPage;
                }
                ?>

                <!-- Hiện số trang -->
                <?php for ($i = $start; $i <= $end; $i++): ?>
                    <li class="page-item <?php echo ($page == $i) ? 'active' : false;  ?>"><a class="page-link"
                            href="?<?php echo $queryString; ?>&page=<?php echo $i ?>"><?php echo $i; ?></a></li>
                <?php endfor; ?>

                <!--  Xử lý nút ... sau -->
                <?php if ($end < $maxPage): ?>
                    <li class="page-item"><a class="page-link" href="?<?php echo $queryString; ?>&page=<?php echo $page + 1 ?>">...</a></li>
                <?php endif;
                ?>

                <!-- Xử lý nút sau -->
                <?php if ($page < $maxPage): ?>
                    <li class="page-item"><a class="page-link" href="?<?php echo $queryString; ?>&page=<?php echo $page + 1 ?>">Sau</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</div>


<?php layout('footer') ?>