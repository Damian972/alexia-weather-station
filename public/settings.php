<?php

define('ROOT', dirname(__FILE__, 2));

require(ROOT.'/vendor/autoload.php');

# Start session
session_start();

# Redirect user if is not logged
if (NEED_LOGIN && !Utils::isLogged()) header('Location: '.BASE_URL.'/login.php');

$db = Utils::getDatabase();

if (isset($_POST['settings_alert_threshold_form'])) {

    if (empty($_POST['alert_threshold_min_temperature']) || !filter_var($_POST['alert_threshold_min_temperature'], FILTER_VALIDATE_INT)) 
        $_SESSION['form_errors']['alert_threshold_min_temperature'] = 'Merci d\'entrer des données valides (nombres).';

    if (empty($_POST['alert_threshold_max_temperature']) || !filter_var($_POST['alert_threshold_max_temperature'], FILTER_VALIDATE_INT))
        $_SESSION['form_errors']['alert_threshold_max_temperature'] = 'Merci d\'entrer des données valides (nombres > 0).';
    
    if (empty($_SESSION['form_errors'])) {
        if (intval($_POST['alert_threshold_max_temperature']) < intval($_POST['alert_threshold_min_temperature'])) {
            $_SESSION['form_errors']['alert_threshold_min_temperature'] = 'Doit être inférieur à la valeur maxiamale';
        } elseif (intval($_POST['alert_threshold_min_temperature']) > intval($_POST['alert_threshold_max_temperature'])) {
            $_SESSION['form_errors']['alert_threshold_max_temperature'] = 'Doit être supérieur à la valeur minimale.';
        }
    }

    if (empty($_SESSION['form_errors'])) {
        $db->update('options', ['value' => htmlspecialchars($_POST['alert_threshold_min_temperature'])], ['name' => 'alert_threshold_min_temperature']);
        $db->update('options', ['value' => htmlspecialchars($_POST['alert_threshold_max_temperature'])], ['name' => 'alert_threshold_max_temperature']);
        
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

    if (!isset($_POST['alert_method']) || !in_array(htmlspecialchars($_POST['alert_method']), ['0', '1'])) {
        $_SESSION['form_errors']['alert_method'] = 'Merci de selectionner une des valeurs ci-dessus.';
    }
    $push_notif = false;
    if (empty($_SESSION['form_errors']['alert_method']) && 0 === intval(htmlspecialchars($_POST['alert_method']))) {

        if (empty($_POST['alert_method_pushbullet_api_key']) || 30 > strlen($_POST['alert_method_pushbullet_api_key'])) {
            $_SESSION['form_errors']['alert_method_pushbullet_api_key'] = 'Merci d\'entrer une clé valide';
        } else $push_notif = true;
    }

    if (empty($_SESSION['form_errors'])) {

        if ($push_notif) {

            $db->update('options', ['value' => htmlspecialchars($_POST['alert_method'])], ['name' => 'alert_method']);
            $db->update('options', ['value' => htmlspecialchars($_POST['alert_method_pushbullet_api_key'])], ['name' => 'alert_method_pushbullet_api_key']);

        } else $db->update('options', ['value' => htmlspecialchars($_POST['alert_method'])], ['name' => 'alert_method']);
        
    } else Utils::redirectToSame();
}

if (!empty($_SESSION['form_errors'])) {
    Utils::setFlash('Merci de corriger les erreurs !', 'danger');
}

# Views
$smarty = Utils::getSmartyInstance();
$smarty->display('settings.view.tpl', [
    'options' => Utils::getOptionsFromDb($db)
]); 

Utils::clearFlash();
Utils::clearFormLastErrors();