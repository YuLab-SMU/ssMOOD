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


// Kimi
//$openaiApiKey = 'sk-iSRrrIFDThVBPSj8HiwjZ5zaxYOyBL3YI1ofEb5OoPT6lCqI'; 
//$url = 'https://api.moonshot.cn/v1/chat/completions';
//$model = 'moonshot-v1-8k';

//DeepSeek-Chat
$openaiApiKey = 'sk-07015df3288d4a88a09b4b8400b297cf'; 
$url = 'https://api.deepseek.com/v1/chat/completions';
$model = 'deepseek-chat';