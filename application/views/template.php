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

.container .header .options {
    float:right;
}

.container .header .options input[type=textbox] {
    display: inline-block;
    font-size: 10px;
    height:20px;
}

.container .header .options input[type=submit] {
    display: inline-block;
    font-size: 10px;
    height:22px;
}

.container .header .options select {
    font-size: 11px;
    height:20px;
    margin-top:5px;
}

.container .input {
    padding-top: 15px;
    width: 546px;
    clear: both;
}
.container input[type=text],.container input[type=password],.container .input select {
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
.container .captcha {
    clear:both;
    left: 25%;
    position: relative;
}
.container table {
    font-family: "Lucida Sans Unicode","Lucida Grande",sans-serif;
    font-size: 12px;
    background: #fff none repeat scroll 0 0;
}
.container th {
    border-bottom:2px solid #000;
}
.container .actions, .container .actions a {
    text-align: center;
    cursor: pointer;
    color: #2F4F4F;
    font-weight: bold;
}
.container .actions a {
    text-decoration: none;
}
.container .actions a:hover {
    text-decoration: underline;
}
.container .pagination {
    margin-top:10px;
    text-align: center;
}
.container .pagination strong, .container .pagination a, .container .numbers {
    font-family: Tahoma, Geneva, sans-serif;
    font-size: 10px;
    color: #292929;
    text-decoration: none;
    background: #e3e3e3;
    padding: 4px 7px;
}
.container .pagination a {
    border: 1px solid #708090;
}
</style>
</head>
<body>
<div class="container">
    <?php
    if (!isset($message_main)):
        $message_main = $this->session->flashdata('last_action');
    endif;
    if (isset($message_main)): ?>
    <div class="main_error"><?=$message_main;?></div>
    <?php endif;?>

    <?php echo isset($body) ? $body : '';?>
</div>
</body>
</html>