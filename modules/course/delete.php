<?php
if (!defined('_TRGIAHUY')) {
    die('Truy cập không hợp lệ');
}
$data = ['title' => 'Xóa khóa học'];

$filter = filterData('get');

if (!empty($filter)) {
    $course_id = $filter['id'];
    $checkCourse = getOne("SELECT * FROM course WHERE id= $course_id");
    if (!empty($checkCourse)) {

        $condition = 'id=' . $course_id;
        $deleteStatus = delete('course', $condition);

        if ($deleteStatus) {
            setSessionFlash('msg', 'Xóa khóa học thành công.');
            setSessionFlash('msg_type', 'success');
            redirect(('?module=course&action=list'));
        }
    } else {
        setSessionFlash('msg', 'Khóa không tồn tại.');
        setSessionFlash('msg_type', 'danger');
    }
} else {
    setSessionFlash('msg', 'Đã có lỗi xảy ra, vui lòng thử lại sau.');
    setSessionFlash('msg_type', 'danger');
}
