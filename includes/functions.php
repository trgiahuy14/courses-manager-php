<?php
if(!defined('_HIENUE')){
    die('Truy cập không hợp lệ');
}

function layout($layoutName, $data = []){
    if (file_exists(_PATH_URL_TEMPLATES . '/layouts/' . $layoutName . '.php')){
        require_once _PATH_URL_TEMPLATES . '/layouts/' . $layoutName . '.php';
    }
}