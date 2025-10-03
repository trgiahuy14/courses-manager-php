<?php
if(!defined('_HIENUE')){
    die('Truy cập không hợp lệ');
}

// Truy vấn nhiều dòng dữ liệu
function getAll($sql){
    global $conn;
    $stm = $conn -> prepare($sql);

    $stm -> execute();
    
    $result = $stm -> fetchAll(PDO::FETCH_ASSOC); 
    return $result;
}

// Truy vấn 1 dòng dữ liệu
function getOne($sql){
    global $conn;
    $stm = $conn -> prepare($sql);

    $stm -> execute();
    
    $result = $stm -> fetch(PDO::FETCH_ASSOC); 
    return $result;
}

// Insert dữ liệu
function insert($table, $data){
    // $data = ['name'=> 'huy',
    //     'email' => 'huy@gmail.com', 
    //     'phone' => '090143854'];

    $key = array_keys($data);
    echo '<pre>';
    print_r($key);
    echo '</pre>';

    global $conn;
    $sql = "INSERT INTO sinhvien ('name', 'email', 'phone') VALUES (':name', ':email', ':phone')";
    $stm = $conn -> prepare($sql);

    $stm -> execute();

    
}