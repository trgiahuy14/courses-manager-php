<?php
if (!defined('_TRGIAHUY')) {
    die('Truy cập không hợp lệ');
}
$data = ['title' => 'Chỉnh sửa khóa học'];
layout('header', $data);
layout('sidebar');

$getData = filterData('get');

$course_id = $getData['id'];
$courseData = getOne("SELECT * FROM course WHERE id = $course_id");

if (isPost()) {

    $filter = filterData();
    $errors = [];

    // Validate name
    if (empty(trim($filter['name']))) {
        $errors['name']['required'] = 'Tên khóa học bắt buộc phải nhập';
    } else {
        if (strlen(trim($filter['name'])) < 5) {
            $errors['name']['length'] = 'Tên khóa học phải lớn hơn 5 ký tự';
        }
    }

    // Validate slug
    if (empty(trim($filter['slug']))) {
        $errors['slug']['required'] = 'Slug bắt buộc phải nhập';
    }


    // Validate price
    if (empty($filter['price'])) {
        $errors['price']['required'] = 'Giá bắt buộc phải nhập';
    }

    // Validate description
    if (empty($filter['description'])) {
        $errors['description']['required'] = 'Mô tả bắt buộc phải nhập';
    }


    if (empty($errors)) {



        $dataUpdate = [
            'name' => $filter['name'],
            'slug' => $filter['slug'],
            'price' => $filter['price'],
            'description' => $filter['description'],

            'category_id' => $filter['category_id'],
            'updated_at' => date('Y:m:d H:i:s')
        ];

        // Nếu có nhập thumbnail mới
        if (!empty($_FILES['thumbnail']['name'])) {

            // Xử lý thumbnail upload
            $uploadDir = './templates/uploads/';

            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true); // Create new upload folder if it doesn't exist
            }

            $fileName = basename($_FILES['thumbnail']['name']);

            $targetFile = $uploadDir . time() . '-' . $fileName;

            $thumb = '';
            $checkMove = move_uploaded_file($_FILES['thumbnail']['tmp_name'], $targetFile);

            if ($checkMove) {
                $thumb = $targetFile;
            }

            $dataUpdate['thumbnail'] = $thumb;
        }

        $condition = "id=" . $course_id;
        $updateStatus = update('course', $dataUpdate, $condition);

        if ($updateStatus) {
            setSessionFlash('msg', 'Chỉnh sửa khóa học thành công.');
            setSessionFlash('msg_type', 'success');
            redirect(('?module=course&action=list'));
        } else {
            setSessionFlash('msg', 'Chỉnh sửa khóa học thất bại');
            setSessionFlash('msg_type', 'danger');
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
if (!empty($courseData)) {
    $oldData = $courseData;
}
$errorsArr = getSessionFlash('errors');

?>

<div class="container add-user">
    <h2>Chỉnh sửa khóa học</h2>
    <hr>
    <?php
    if (!empty($msg) && !empty($msg_type)) {
        getMsg($msg, $msg_type);
    }
    ?>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="row">

            <!-- Course name -->
            <div class="col-6 pb-3">
                <label for="name">Tên khóa học</label>
                <input id="name" name="name" type="text" class="form-control"
                    value="<?php if (!empty($oldData)) {
                                echo oldData($oldData, 'name');
                            }  ?>"
                    placeholder="Tên khóa học">
                <?php
                if (!empty($errorsArr)) {
                    echo formError($errorsArr, 'name');
                } ?>
            </div>

            <!-- slug -->
            <div class="col-6 pb-3">
                <label for="Slug">Đường dẫn</label>
                <input id="slug" name="slug" type="text" class="form-control"
                    value="<?php if (!empty($oldData)) {
                                echo oldData($oldData, 'slug');
                            }  ?>"
                    placeholder="slug">
                <?php
                if (!empty($errorsArr)) {
                    echo formError($errorsArr, 'slug');
                } ?>
            </div>

            <!-- Description -->
            <div class="col-6 pb-3">
                <label for="description">Mô tả khóa học</label>
                <input id="description" name="description" type="text" class="form-control"
                    value="<?php if (!empty($oldData)) {
                                echo oldData($oldData, 'description');
                            }  ?>"
                    placeholder="Mô tả">
                <?php
                if (!empty($errorsArr)) {
                    echo formError($errorsArr, 'description');
                } ?>
            </div>

            <!-- Price -->
            <div class="col-6 pb-3">
                <label for="price">Giá</label>
                <input id="price" name="price" type="text" class="form-control"
                    value="<?php if (!empty($oldData)) {
                                echo oldData($oldData, 'price');
                            }  ?>"
                    placeholder="Giá">
                <?php
                if (!empty($errorsArr)) {
                    echo formError($errorsArr, 'price');
                } ?>
            </div>

            <!-- Thumbnail -->
            <div class="col-6 pb-3">
                <label for="thumbnail">Thumbnail</label>
                <input id="thumbnail" name="thumbnail" type="file" class="form-control">
                <img width="200px" id="previewImage" class="preview-image p-3" src="<?php echo !empty($oldData['thumbnail']) ? $oldData['thumbnail'] : false; ?>" alt="">
            </div>

            <!-- Group -->
            <div class=" col-3 pb-3">
                <label for="category">Lĩnh vực</label>
                <select name="category_id" id="category" class="form-select form-control">
                    <?php
                    $getGroup = getAll("SELECT * FROM `course_category`");
                    foreach ($getGroup as $item):
                    ?>
                        <option value="<?php echo $item['id']; ?>" <?php echo ($oldData['category_id'] == $item['id']) ? 'selected' : false; ?>><?php echo $item['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

        </div>
        <button type="submit" class="btn btn-success">Xác nhận</button>
    </form>
</div>

<script>
    const thumbInput = document.getElementById('thumbnail');
    const previewImage = document.getElementById('previewImage');

    thumbInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.setAttribute('src', e.target.result);
                previewImage.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            previewImage.style.display = 'none';
        }
    });
</script>

<script>
    // Hàm giúp chuyển text thành slug
    function createSlug(strig) {
        return strig.toLowerCase()
            .normalize('NFD') // Chuyển ký tự có dấu thành tổ hợp: é -> e + ' | lap trinh -> l+a+p+
            .replace(/[\u0300-\u036f]/g, '') // Xóa dấu
            .replace(/đ/g, 'd') // Thay đ -> d
            .replace(/[^a-z0-9\s-]/g, '') // Xóa ký tự đặc biệt
            .trim() // Xóa khoảng trắng
            .replace(/\s+/g, '-') // Thay khoảng trắng -> -
            .replace(/-+/g, '-') // Bỏ trùng dấu -
    }


    document.getElementById('name').addEventListener('input', function() {
        const getValue = this.value;
        document.getElementById('slug').value = createSlug(getValue);
    })
</script>

<?php layout('footer') ?>