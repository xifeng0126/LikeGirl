<?php
/*
 * @Version: Like Girl 5.2.1-Stable
 * @Copyright (c) 2023 - 2025 by Ki All Rights Reserved.
 */
error_reporting(0);
header("Content-Type:text/html; charset=utf8");
include_once __DIR__.'/Config_DB.php';
$conn = new mysqli($db_address,$db_username,$db_password,$db_name);

if ($conn->connect_error) {
    die("<script>location.href = '../admin/install/connectDie.php';</script>");
}
$conn->set_charset("utf8mb4");