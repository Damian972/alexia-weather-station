<?php

define('ROOT', dirname(__FILE__, 2));

require(ROOT.'/vendor/autoload.php');

# Start session
session_start();

# Redirect user if is not logged
if (NEED_LOGIN && !Utils::isLogged()) header('Location: '.BASE_URL.'/login.php');
if (!NEED_LOGIN) Utils::redirectToHome();

$db = Utils::getDatabase();

# Change current user informations
if (isset($_POST['change_my_account_info'])) {
    extract($_POST);
    $_SESSION['form_errors'] = [];
    if (!empty($username) && 3 < strlen($username)) {

        if (!empty($email) && 5 < strlen($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) { 

            if ($password_v !== $password) $_SESSION['form_errors']['password'] = 'Vos mots de passes ne correspondent pas !';
            if (empty($_SESSION['form_errors'])) {
                if (empty($password) && empty($password_v)) {
                    // Save user info without password
                    $is_query_ok = $db->update('users', [
                        'username' => htmlspecialchars($username),
                        'email' => htmlspecialchars($email)
                    ], ['email' => $_SESSION['user']['email']]);
                } else {
                    // With password
                    $is_query_ok = $db->update('users', [
                        'username' => htmlspecialchars($username),
                        'email' => htmlspecialchars($email),
                        'password' => Utils::encryptData(htmlspecialchars($password)),
                    ], ['email' => $_SESSION['user']['email']]);
                }
                if ($is_query_ok !== false) {
                    echo 'yessssssss';
                    $_SESSION['user']['email'] = htmlspecialchars($email);
                    Utils::setFlash('Modifications enregistrées !!', 'success');
        
                } else  Utils::setFlash('Un erreur s\'est produite... Merci de contacter l\'administrateur.', 'danger');
            }
        } else $_SESSION['form_errors']['email'] = 'Merci de bien vouloir entrer un email valide.';

    } else $_SESSION['form_errors']['username'] = 'Votre nom d\'utilisateur doit être supérieur à 3 caractères';
    
    if (0 < count($_SESSION['form_errors'])) Utils::setFlash('Merci de corriger les erreurs!', 'danger'); 
    
    Utils::redirectToSame();
}


$user_account = $db->get('users', ['username', 'email'], ['email' => $_SESSION['user']['email']]);
if (!$user_account) {
    // Destroy session
    session_destroy();
    Utils::setFlash('Votre compte n\'exite pas', 'warning');
    Utils::redirectToHome();
};

# Views
$smarty = Utils::getSmartyInstance();
$smarty->assign(compact('user_account'));
$smarty->display('my-account.view.tpl'); 

Utils::clearFlash();
Utils::clearFormLastErrors();