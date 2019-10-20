<?php

define('ROOT', dirname(__FILE__, 2));
require(ROOT.'/config/config.php');
require(INC.'/Database.php');
require(INC.'/Smarty.class.php');
require(INC.'/Utils.php');

# Start session
session_start();

# Redirect user if not logged
if (NEED_LOGIN && !Utils::isLogged()) header('Location: '.BASE_URL.'/login.php');
Utils::debug($_SESSION);

$options = Utils::getOptionsFromDb((new Database()));

# Views
$smarty = Utils::getSmartyInstance();

$smarty->assign(compact('options'));
$smarty->display('index.view.tpl');