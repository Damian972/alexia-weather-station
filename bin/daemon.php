<?php

define('ROOT', dirname(__FILE__, 2));

require(ROOT.'/vendor/autoload.php');

$is_install = false;
if (file_exists(VARF.'/.installed')) $is_install = true;

try {
	if (!$is_install) {
		echo 'Merci de procéder à l\'installation avec "php alexia.php --install"'.PHP_EOL;
		echo 'Si cela ne fonctionne pas, vérifier vos drivers pour mysql (slqlite or mysql) sinon vérifier si votre base de données est créée.'.PHP_EOL;
		die();
	}
	$db = Utils::getDatabase();
	$file = VARF.'/.tmp';
	$handle = fopen('/dev/ttyUSB0', 'r'); ///dev/ttyUSB0
	$options = Utils::getOptionsFromDb($db);

	while (($line = fgets($handle)) !== false) {
		
		preg_match('/[-+]?[0-9]+[.]?[0-9]+/', $line, $matches);
		if (!empty($matches)) {

			$data = ['temperature' => floatval($matches[0]), 'created_at' => date("Y-m-d H:i:s")];
			echo '['.$data['created_at'].'] => '.$data['temperature'].'°C'.PHP_EOL;
			
            if (file_exists($file)) {
				if (empty($options)) throw new Exception('Impossible d\'obtenir les informations nécessaires'.PHP_EOL);

				if (isset($options['refresh_time_cli']) && isset($options['alert_threshold_min_temperature']) && isset($options['alert_threshold_max_temperature']) && isset($options['alert_method'])) {

					$interval_to_refresh = (int) $options['refresh_time_cli'];
					if (!$interval_to_refresh) throw new Exception('Interval de rafraichissement invalide');

					if ($interval_to_refresh < (time() - filemtime($file)))  {
						
						$alert = '';
						if ($data['temperature'] <= intval($options['alert_threshold_min_temperature'])) $alert = 'low';
						if ($data['temperature'] >= intval($options['alert_threshold_max_temperature'])) $alert = 'hight';
						
						if (!empty($alert)) {
							if ('pushbullet' === $options['alert_method'] && !empty($options['alert_method_pushbullet_api_key'])) {
								// send notif via pushbullet
								Utils::sendPushbulletNotif('Temperature alert', 
									('low' === $alert) ? 'Low': 'Hight',
									$options['alert_method_pushbullet_api_key']
								);
							} else {
								// send mail
								$users_emails = $db->select('users', ['email']);
								Utils::sendMail($users_emails, 'Temperature alert', 'emails/temperature-alert.view.tpl', compact('data', 'alert'));
							}
						}
	
						$db->insert('data', $data);
						echo '-------- | refresh: '.$interval_to_refresh.PHP_EOL;
						unlink($file);
						$options = Utils::getOptionsFromDb($db);

					}

				} else echo '['.$data['created_at'].'] => Impossible de verifier la température'.PHP_EOL;
            } else file_put_contents($file, '');
		}
	}
	
	fclose($handle); 
} catch(Exception $e) {
	die('Une erreur s`\est produite : '.$e->getMessage());
}