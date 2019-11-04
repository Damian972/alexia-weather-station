<?php

define('ROOT', dirname(__FILE__, 2));

require(ROOT.'/vendor/autoload.php');

$is_install = false;
if (file_exists(VARF.'/'.DB_NAME.'.db')) $is_install = true;

try {
	if (!$is_install) {
		echo 'You need to install first, use "php alexia.php --install"'.PHP_EOL;
		echo 'If not work, check you pdo driver or if you`\re using mysql create the database first'.PHP_EOL;
		die();
	}
	$db = Utils::getDatabase();
	$handle = fopen(ROOT.'/tests/temperature.txt', 'r'); ///dev/ttyUSB0
	while (($line = fgets($handle)) !== false) {
		
		preg_match('/[-+]?[0-9]+[.]?[0-9]+/', $line, $matches);
		if (!empty($matches)) {
			$options = Utils::getOptionsFromDb($db);
			if (empty($options)) throw new Exception('Impossible d\'obtenir les informations nécessaires');

			$data = ['temperature' => floatval($matches[0]), 'created_at' => date("Y-m-d H:i:s")];

			if (isset($options['refresh_time_cli']) && isset($options['alert_threshold_min']) 
				&& isset($options['alert_threshold_max']) && isset($options['alert_method'])) {

					if ()
					
			}
			$db->insert('data', $data);

			echo '['.$data['created_at'].'] => '.$data['temperature'].'°C'.PHP_EOL;
			$interval_to_refresh = (int) $options['refresh_time_cli'];
			if (!$interval_to_refresh) throw new Exception('Interval de rafraichissement invalide');

		}
		sleep(5 ?? $interval_to_refresh ?? 120);
	}
	
	fclose($handle); 
} catch(Exception $e) {
	die('Une erreur s`\est produite : '.$e->getMessage());
}