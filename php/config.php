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
$logFile = "./logfile.log";

// Kimi
//$openaiApiKey = 'sk-xxx'; 
//$url = 'https://api.moonshot.cn/v1/chat/completions';
//$model = 'moonshot-v1-8k';

//DeepSeek-Chat
//$openaiApiKey = 'sk-xxx'; 
//$url = 'https://api.deepseek.com/v1/chat/completions';
//$model = 'deepseek-chat';

// 豆包
$openaiApiKey = 'xxx'; 
$url = 'https://ark.cn-beijing.volces.com/api/v3/chat/completions';
$model = 'doubao-1-5-pro-32k-250115';