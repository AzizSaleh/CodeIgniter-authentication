<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<html>
<head>
<title><?php echo isset($page_title) ? $page_title : '';?></title>
<style>
body {
    background-color: #888;
    font-family: sans-serif;
    font-size:.9em;
}
.container {
    position: absolute;
    left: 50%;
    top: 50%;
    width: 546px;
    min-height: 105px;
    height: auto;
    margin-left: -273px;
    margin-top: -132px;
    border: 1px solid #666;
    -moz-border-radius: 10px;
    border-radius: 10px;
    overflow:hidden;
}
.container .main_error {
    padding: 5px;
    margin:5px;
    text-align: center;
    background-color: #7dc4d8;
    color: #401a00;
}
.container .form {
    padding: 10px;
    background-color:#dddddd;
}
.container .danger {
    background-color:#ba1e36;   
}
.container form {
    margin-bottom:0;
}
.container .header {
    font-size: 1.8em;
    border-bottom: 1px solid #666;
}
.container .input {
    padding-top: 15px;
    width: 546px;
    clear: both;
}
.container input[type=text],.container input[type=password] {
    width: 60%;
    float:right;
    margin-right: 20px;
    -moz-border-radius: 5px;
    border-radius: 5px;
    overflow:hidden;
}
.container .action {
    text-align: center;
}
.container .action input[type=submit] {
    width:50%;
    margin-top:10px;
    -moz-border-radius: 5px;
    border-radius: 5px;
    overflow:hidden;
    cursor:pointer;
}
.container input[type=checkbox] {
    cursor:pointer;
}
.container .links a {
    text-decoration: none;
    color: #0b035e;
}
.container .links a:hover {
    text-decoration: underline;
}
.container .error {
    font-style: italic;
    color: #ba1e36;
}
.container .input .notice {
    margin-top:10px;
    font-style: italic;
    color:#ba1e36;
    font-size: .7em;
    width: 100%;
}
</style>
</head>
<body>
<div class="container">
    <?php if (isset($message_main)): ?>
    <div class="main_error"><?=$message_main;?></div>
    <?php endif;?>

    <?php echo isset($body) ? $body : '';?>
</div>
</body>
</html>