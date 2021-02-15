<?php
	namespace update_stock;
	
	require_once 'koneksi.php';
	require_once 'services.php';
	
	if(php_sapi_name() == 'cli') {
		if(isset($argv[1])) {
			$data = json_decode($argv[2]);
			$services = new services($mysqli);
			$response = $services->{$argv[1]}($data);
			$mysqli->close();
			// $services->log_services('Request', json_encode($response));
			
			echo json_encode($response);
		}
	} else { 
		if(isset($_REQUEST['fn'])) {
			$data = json_decode(file_get_contents('php://input'));
			$services = new services($mysqli);
			$response = $services->{$_REQUEST['fn']}($data);
			$mysqli->close();
			$services->log_services('Request', json_encode($response));
			
			echo json_encode($response);
		}
	}
?>