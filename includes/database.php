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

// Đếm số dòng trả về
function getRows($sql){
    global $conn;
    $stm = $conn -> prepare($sql);
    $stm -> execute();
    return $stm -> rowCount();
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
    global $conn;

    $keys = array_keys($data);
    $column = implode(',',$keys); # phân tách keys
    $placeh = ':' . implode(',:',$keys);
    
    $sql = "INSERT INTO $table ($column) VALUES ($placeh)";
 
    $stm = $conn -> prepare($sql);
    $stm -> execute($data);    
}

// Update  dữ liệu
function update($table, $data, $condition =''){
    global $conn;
    $update = '';
    foreach ($data as $key => $value){
        $update .= $key . '=:' . $key . ',';
    }
    $update = trim($update,',');
    
    if(!empty($condition)){
        $sql = "UPDATE $table SET $update WHERE $condition";
    } else {
        $sql = "UPDATE $table SET $update";
    }
    
    $stm = $conn -> prepare($sql);
    $stm -> execute($data);
}

// Delete dữ liệu
function delete($table, $condition=''){
    global $conn;
    
    if(!empty($condition)){
        $sql = "DELETE FROM $table WHERE $condition";
    } else {
        $sql = "DELETE FROM $table";
    }

    $stm = $conn -> prepare($sql);
    $stm -> execute();
}

function lastID(){
    global $conn;
    return $conn -> lastInsertID();
}