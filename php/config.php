<?php
// 跨域配置
header("Access-Control-Allow-Origin: *");//发布时去掉此代码，仅用于生产环境。

header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
// 数据库配置
$servername = "localhost";
$username = "scast";
$password = "20030609";
$dbname = "ssmood";