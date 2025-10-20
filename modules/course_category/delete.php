<?php
if (!defined('_TRGIAHUY')) {
    die('Truy cập không hợp lệ');
}

$filter = filterData('get');

if (!empty($filter)) {
    $cateID = $filter['id'];

    $checkCate = getOne("SELECT * FROM course_category WHERE id = $cateID");

    if (!empty($checkCate)) {
        // check course table
        $checkCourse = getRows("SELECT * FROM course WHERE category_id = $cateID");

        if ($checkCourse > 0) {
            // If course is exist on this category
            setSessionFlash('msg', 'Lĩnh vực đang còn khóa học.');
            setSessionFlash('msg_type', 'danger');
            redirect('?module=course_category&action=list');
        } else {
            // Delete category
            $condition = "id=" . $cateID;
            $deleteStatus = delete('course_category', $condition);

            if ($deleteStatus) {
                setSessionFlash('msg', 'Xóa thành công.');
                setSessionFlash('msg_type', 'success');
                redirect('?module=course_category&action=list');
            }
        }
    } else {
        setSessionFlash('msg', 'Danh mục không tồn tại.');
        setSessionFlash('msg_type', 'danger');
        redirect('?module=course_category&action=list');
    }
} else {
    setSessionFlash('msg', 'Danh mục không tồn tại.');
    setSessionFlash('msg_type', 'danger');
}
