<?php

define('ROOT', dirname(__FILE__, 2));

require(ROOT.'/config/config.php');
require(INC.'/Utils.php');

# Start session
session_start();

session_destroy();
Utils::redirectToHome();