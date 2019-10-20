<?php

define('ROOT', dirname(__FILE__, 2));
//die(var_dump(ROOT));
require(ROOT.'/config/config.php');
require(INC.'/Database.php');
require(INC.'/Smarty.class.php');
require(INC.'/Utils.php');

# Start session
session_start();

# Redirect user if already logged
if (NEED_LOGIN && Utils::isLogged()) header('Location: '.BASE_URL);
if (!NEED_LOGIN) Utils::redirectToHome();

# Treatments
if (isset($_POST['login_form'])) {
    extract($_POST);
    $_SESSION['form_errors'] = [];
    if (!empty($email) && 5 < strlen($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {

        if (5 > strlen($password)) $_SESSION['form_errors']['password'] = 'Vos identifiants sont incorrects !';
        if (empty($_SESSION['form_errors'])) {
            $pdo = new Database();
            $user_account = $pdo->row('SELECT * FROM users WHERE email = :email', ['email' => htmlspecialchars($email)]);
            if (!empty($user_account)) {
                if ($user_account['password'] === Utils::encryptData(htmlspecialchars($password))) {
                    // Set user's session
                    $_SESSION['user'] = [
                        'name' => $user_account['username'],
                        'email' => $user_account['email'],
                        //'is_admin' => intval($user_account['is_admin']),
                    ];
                    Utils::redirectToHome();

                } $_SESSION['form_errors']['password'] = 'Votre mot de passe est incorrect !';
            } else Utils::setFlash('Votre compte n\'existe pas !', 'danger');
        }
    }  else $_SESSION['form_errors']['email'] = 'Merci de bien vouloir entrer un email valide.';
    
    Utils::redirectToSame();
}

Utils::debug($_SESSION);
# Views
$smarty = Utils::getSmartyInstance();
$smarty->display('login.view.tpl');

Utils::clearFlash();
Utils::clearFormLastErrors();