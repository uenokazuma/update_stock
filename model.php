<?php
	
	namespace update_stock;
	
	Class model {
		
		private $mysqli;
		
		public function __construct($mysqli) {
			$this->mysqli = $mysqli;
		}
	
		function data_cart($kode_pelanggan) {
			
			$sql = "
				select kode_cart,
					   tgl_cart,
					   kode_barang,
					   qty
				  from cart
				 where kode_pelanggan = '$kode_pelanggan'
			";
			
			$query = $this->mysqli->query($sql);
			
			return $query;
		}
		
		function insert_cart($kode_pelanggan, $kode_barang, $qty, $status_cart = '0') {
			
			$date = date('Y-m-d H:i:s.v');
			$sql = "
				insert into cart(kode_pelanggan,	tgl_cart,	kode_barang,	qty,	status_cart)
						  values('$kode_pelanggan',	'$date',	'$kode_barang', '$qty', '$status_cart')
			";
			
			$query = $this->mysqli->query($sql);
			
			return $this->mysqli;
		}
		
		function data_order($kode_pelanggan) {
			
			$sql = "
				select kode_order,
					   tgl_order,
					   status_order,
					   last_update
				  from order_mst
				 where kode_pelanggan = '$kode_pelanggan'
			";
				 
			$query = $this->mysqli->query($sql);
			
			return $query;
		}
		
		function data_order_detail($kode_order) {
			
			$sql = "
				select kode_order_dtl,
					   kode_barang,
					   qty,
					   status_order_dtl
				  from order_dtl
				 where kode_order = '$kode_order'
			";
				 
			$query = $this->mysqli->query($sql);
			
			return $query;
		}
		
		function data_barang($kode_barang) {
			
			if($kode_barang == '') {
				$kode_barang = 'null';
			} else {
				$kode_barang = "'".$kode_barang."'";
			}
			
			$sql = "
				select kode_barang,
					   nama_barang,
					   stock
				  from barang
				 where kode_barang = ifnull($kode_barang, kode_barang)
			";
				 
			$query = $this->mysqli->query($sql);
			
			return $query;
		}
		
		function proc_order($data) {
			
			$kode_order = time();
			$tgl_order = date('Y-m-d H:i:s.v');
			
			/* Tell mysqli to throw an exception if an error occurs */
			mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
			$this->mysqli->autocommit(false);
			
			try {
				for($i=1; $i <= count($data); $i++) {
					$tgl_cart = $data[$i-1]->tgl_cart;
					$kode_pelanggan = $data[$i-1]->kode_pelanggan;
					$kode_barang = $data[$i-1]->kode_barang;
					$qty = $data[$i-1]->qty;
					
					if($i == 1) {
						$sql = "
							insert into order_mst(kode_order,		tgl_order,		kode_pelanggan,		status_order)
										   values('$kode_order',	'$tgl_order',	'$kode_pelanggan',	'0')
						";
						
						$query = $this->mysqli->query($sql);
					}
					
					$sql = "
						update barang join cart
						   set barang.stock = barang.stock - cart.qty
						 where barang.kode_barang = cart.kode_barang
						   and barang.stock >= cart.qty
						   and cart.kode_pelanggan = '$kode_pelanggan'
						   and cart.kode_barang = '$kode_barang'
						   and cart.tgl_cart = '$tgl_cart'
					";
					$query = $this->mysqli->query($sql);
					
					$status_order_dtl = '0';
					if($this->mysqli->affected_rows <= 0) {
						
						$status_order_dtl = '3';
						$sql = "
							update cart
							   set status_cart = '3'
							 where kode_pelanggan = '$kode_pelanggan'
							   and kode_barang = '$kode_barang'
							   and tgl_cart = '$tgl_cart'
						";
						
						$query = $this->mysqli->query($sql);
					}
					
					
					$kode_order_dtl = $kode_order . str_pad($i, 3, "0", STR_PAD_LEFT);
					
					$sql = "
						insert into order_dtl(kode_order_dtl,		kode_order,		kode_barang,		qty,	status_order_dtl)
									   values('$kode_order_dtl',	'$kode_order',	'$kode_barang',		$qty,	'$status_order_dtl');
					";
					
					$query = $this->mysqli->query($sql);
					
				}
				
				$commit = $this->mysqli->commit();
			} catch (mysqli_sql_exception $e) {
				
			}
			
			return $commit;
		}
	}
?>