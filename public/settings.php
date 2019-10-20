<?php

define('ROOT', dirname(__FILE__, 2));

require(ROOT.'/config/config.php');
require(INC.'/Database.php');
require(INC.'/Smarty.class.php');
require(INC.'/Utils.php');

# Start session
session_start();

# Redirect user if is not logged
if (NEED_LOGIN && !Utils::isLogged()) header('Location: '.BASE_URL.'/login.php');

$pdo = new Database();

# Views
$smarty = Utils::getSmartyInstance();
$smarty->display('settings.view.tpl'); 

Utils::clearFlash();
Utils::clearFormLastErrors();