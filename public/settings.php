<?php

define('ROOT', dirname(__FILE__, 2));

require(ROOT.'/vendor/autoload.php');

# Start session
session_start();

# Redirect user if is not logged
if (NEED_LOGIN && !Utils::isLogged()) header('Location: '.BASE_URL.'/login.php');

$db = Utils::getDatabase();

if (isset($_POST['settings_alert_threshold_form'])) {

    if (empty($_POST['alert_threshold_min']) || !filter_var($_POST['alert_threshold_min'], FILTER_VALIDATE_INT)) 
        $_SESSION['form_errors']['alert_threshold_min'] = 'Merci d\'entrer des données valides (nombres).';

    if (empty($_POST['alert_threshold_max']) || !filter_var($_POST['alert_threshold_max'], FILTER_VALIDATE_INT))
        $_SESSION['form_errors']['alert_threshold_max'] = 'Merci d\'entrer des données valides (nombres > 0).';
    
    if (empty($_SESSION['form_errors'])) {
        if (intval($_POST['alert_threshold_max']) < intval($_POST['alert_threshold_min'])) {
            $_SESSION['form_errors']['alert_threshold_min'] = 'Doit être inférieur à la valeur maxiamale';
        } elseif (intval($_POST['alert_threshold_min']) > intval($_POST['alert_threshold_max'])) {
            $_SESSION['form_errors']['alert_threshold_max'] = 'Doit être supérieur à la valeur minimale.';
        }
    }

    if (empty($_SESSION['form_errors'])) {
        $db->update('options', ['value' => htmlspecialchars($_POST['alert_threshold_min'])], ['name' => 'alert_threshold_min']);
        $db->update('options', ['value' => htmlspecialchars($_POST['alert_threshold_max'])], ['name' => 'alert_threshold_max']);
        
    } else Utils::redirectToSame();
}

if (isset($_POST['settings_refresh_time_form'])) {

    if (empty($_POST['refresh_time_cli']) || !filter_var($_POST['refresh_time_cli'], FILTER_VALIDATE_INT)) {
        $_SESSION['form_errors']['refresh_time_cli'] = 'Merci d\'entrer des données valides (nombres > 0).';
    }

    if (empty($_POST['refresh_time_gui']) || !filter_var($_POST['refresh_time_gui'], FILTER_VALIDATE_INT)) {
        $_SESSION['form_errors']['refresh_time_gui'] = 'Merci d\'entrer des données valides (nombres > 0).';
    }

    if (empty($_SESSION['form_errors'])) {
        $db->update('options', ['value' => htmlspecialchars($_POST['refresh_time_cli'])], ['name' => 'refresh_time_cli']);
        $db->update('options', ['value' => htmlspecialchars($_POST['refresh_time_gui'])], ['name' => 'refresh_time_gui']);
        
    } else Utils::redirectToSame();
}

if (isset($_POST['alert_method_form'])) {
    
}

if (!empty($_SESSION['form_errors'])) {
    echo 'test';
    Utils::setFlash('Merci de corriger les erreurs !', 'danger');
}

# Views
$smarty = Utils::getSmartyInstance();
$smarty->display('settings.view.tpl'); 

Utils::clearFlash();
Utils::clearFormLastErrors();