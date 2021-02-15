<?php
	namespace update_stock;
	
	require_once 'model.php';
	
	Class services {

		private $model;
		
		public function __construct($mysqli) {
			$this->model = new model($mysqli);
		}
		
		//create log for service call
		function log_services($message, $result, $type = 'debug') {
			$req_header = json_encode(getallheaders());
			$req_param = json_encode($_REQUEST);
			
			$filepath = 'log/service_'.date('Ymd').'.txt';
			
			if(file_exists($filepath)) {
				$file = fopen($filepath, 'a+');
			} else {
				$file = fopen($filepath, 'a+');
				fwrite($file, "");
			}
			
			$data = strtoupper($type) .' : '. date('Y-m-d H:i:s') ." $message\n\tHeader : $req_header\n\tResult : $result\n";
			
			fwrite($file, $data);
			fclose($file);
		}
		
		//get customer cart data
		function get_cart($data) {
			$response = array(
				'code' => 204,
				'message' => 'no content'
			);
			
			if(property_exists($data, 'kode_pelanggan')) {
				if(isset($data->kode_pelanggan)) {
					$kode_pelanggan = $data->kode_pelanggan;
					
					$result = $this->model->data_cart($kode_pelanggan);
					
					$populate = array();
					if($result->num_rows > 0) {
						while($row = $result->fetch_object()) {
							array_push($populate, $row);
						}
						
						$response = array(
							'code' => 200,
							'message' => 'Success',
							'data' => $populate
						);
					} else {
						$response = array(
							'code' => 210,
							'message' => 'Failed',
							'data' => $populate
						);
					}
					
					$result->close();
				}			
			}
			
			return $response;
		}
		
		//insert customer cart data
		function transact_cart($data) {
			
			$response = array(
				'code' => 204,
				'message' => 'No content'
			);
			
			if(property_exists($data, 'kode_pelanggan') && property_exists($data, 'kode_barang') && property_exists($data, 'qty')) {
				if(isset($data->kode_pelanggan) && isset($data->kode_barang) && isset($data->qty)) {
					$kode_pelanggan = $data->kode_pelanggan;
					$kode_barang = $data->kode_barang;
					$qty = $data->qty;
					
					$result = $this->model->insert_cart($kode_pelanggan, $kode_barang, $qty);
					
					if($result->affected_rows > 0) {
						$response = array(
							'code' => 200,
							'message' => 'Success'
						);
					} else {
						$response = array(
							'code' => 210,
							'message' => 'Failed'
						);
					}
				}
			} else {
				$response = array(
					'code' => 210,
					'message' => 'Parameters required (kode_pelanggan, kode_barang, qty)'
				);
				
			}
			
			return $response;
		}
		
		//get data barang
		function get_barang($data) {
			$response = array(
				'code' => 204,
				'message' => 'no content'
			);
			
			$kode_barang = '';
			if(property_exists($data, 'kode_barang')) {
				$kode_barang = $data->kode_barang;
			}
					
			$result = $this->model->data_barang($kode_barang);
			
			$populate = array();
			if($result->num_rows > 0) {
				while($row = $result->fetch_object()) {
					array_push($populate, $row);
				}
				
				$response = array(
					'code' => 200,
					'message' => 'Success',
					'data' => $populate
				);
			} else {
				$response = array(
					'code' => 210,
					'message' => 'Failed',
					'data' => $populate
				);
			}
			
			$result->close();
			
			return $response;
		}
		
		//get data order
		function get_order($data) {
			$response = array(
				'code' => 204,
				'message' => 'no content'
			);
			
			if(property_exists($data, 'kode_pelanggan')) {
				if(isset($data->kode_pelanggan)) {
					$kode_pelanggan = $data->kode_pelanggan;
					
					$result = $this->model->data_order($kode_pelanggan);
					
					$populate = array();
					if($result->num_rows > 0) {
						while($row = $result->fetch_object()) {
							array_push($populate, $row);
						}
						
						$response = array(
							'code' => 200,
							'message' => 'Success',
							'data' => $populate
						);
					} else {
						$response = array(
							'code' => 210,
							'message' => 'Failed',
							'data' => $populate
						);
					}
					
					$result->close();
				}			
			}
			
			return $response;
		}
		
		//get data order
		function get_order_detail($data) {
			$response = array(
				'code' => 204,
				'message' => 'no content'
			);
			
			if(property_exists($data, 'kode_order')) {
				if(isset($data->kode_order)) {
					$kode_order = $data->kode_order;
					
					$result = $this->model->data_order_detail($kode_order);
					
					$populate = array();
					if($result->num_rows > 0) {
						while($row = $result->fetch_object()) {
							array_push($populate, $row);
						}
						
						$response = array(
							'code' => 200,
							'message' => 'Success',
							'data' => $populate
						);
					} else {
						$response = array(
							'code' => 210,
							'message' => 'Failed',
							'data' => $populate
						);
					}
					
					$result->close();
				}			
			}
			
			return $response;
		}
		
		//check out item
		function transact_order($data) {
			$response = array(
				'code' => 204,
				'message' => 'no content'
			);
			
			if(count($data) > 0) {
				$result = $this->model->proc_order($data);
				
				if($result) {
					$response = array(
						'code' => 200,
						'message' => 'Success'
					);
				} else {
					$response = array(
						'code' => 210,
						'message' => 'Failed'
					);
				}
			} else {
				$response = array(
					'code' => 210,
					'message' => 'Parameters required (data order)'
				);
					
			}
				
			return $response;
		}
		
		// //get data customer cart
		// function get_cart() {
			// $response = array(
				// 'code' => 404,
				// 'message' => ''
			// );
			
			// if(isset($_POST['kode_pelanggan']) {
				// $kode_pelanggan = $_POST['kode_pelanggan'];
				
				// $result = get_cart($kode_pelanggan);
				
				
			// }
			
			// return $response;
		// }
		
		// //get data customer cart
		// function get_cart() {
			// $response = array(
				// 'code' => 404,
				// 'message' => ''
			// );
			
			// if(isset($_POST['kode_pelanggan']) {
				// $kode_pelanggan = $_POST['kode_pelanggan'];
				
				// $result = get_cart($kode_pelanggan);
				
				
			// }
			
			// return $response;
		// }
	}
?>