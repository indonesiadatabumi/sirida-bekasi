<?php

require_once("inc/init.php");
require_once("lib/user_controller.php");
require_once("lib/global_obj.php");
require_once("helpers/mix_helper.php");

$uc = new user_controller($db, $__SESSION_ID_NAME);
$global = new global_obj($db);

$username = $_POST['username'];
$password = md5($_POST['password']);
$ip = get_ip();

$status_login = $uc->login_process($username, $password, $ip, $__SESSION_ID_NAME);

// echo $status_login;

// tambahan oleh wenx on 10.05.2022

if ($status_login == "success") {
    header("location: index.php", true, 301);
    exit();
} else {
    header("location: login.php?f=empty", true, 301);
    exit();
}
