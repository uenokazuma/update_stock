CREATE TABLE `barang` (
  `kode_barang` char(10) NOT NULL,
  `nama_barang` varchar(100) DEFAULT NULL,
  `stock` int DEFAULT NULL,
  PRIMARY KEY (`kode_barang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
insert into barang
values
('0000000001', 'baju anak laki', '3'),
('0000000002', 'baju anak perempuan', '3'),
('0000000003', 'celana anak laki', '3'),
('0000000004', 'celana anak perempuan', '3'),
('0000000005', 'baju dewasa laki', '3'),
('0000000006', 'baju dewasa perempuan', '3'),
('0000000007', 'celana dewasa laki', '3'),
('0000000008', 'celana dewasa perempuan', '3');

/*
	status_cart : 0 = on cart, 1 = check out, 2 = cancel, 3= out of stock
*/
CREATE TABLE `cart` (
	`kode_cart` int NOT NULL AUTO_INCREMENT,
    `tgl_cart` datetime(3) NOT NULL,
    `kode_pelanggan` char(10) NOT NULL,
	`kode_barang` char(10) NOT NULL,
    `qty` int NOT NULL,
	`status_cart` char(1) NOT NULL,
  PRIMARY KEY (`kode_cart`),
  KEY `idx_fk_cart_pelanggan` (`kode_pelanggan`),
  KEY `idx_fk_cart_barang` (`kode_barang`),
	CONSTRAINT `fk_order_barang` FOREIGN KEY (`kode_barang`) REFERENCES `barang` (`kode_barang`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*
	status_order : 0 = check out, 1 = paid, 2 = cancel
*/
CREATE TABLE `order_mst` (
  `kode_order` char(10) NOT NULL,
  `tgl_order` datetime(3) NOT NULL,
  `kode_pelanggan` char(10) NOT NULL,
  `status_order` char(1) NOT NULL,
  `last_update` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`kode_order`),
  KEY `idx_fk_order_mst_pelanggan` (`kode_pelanggan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*
	status_order_dtl : 0 = check out, 1 = paid, 2 = cancel, 3 = out of stock
*/
CREATE TABLE `order_dtl` (
	`kode_order_dtl` char(13) NOT NULL,
    `kode_order` char(10) NOT NULL,
	`kode_barang` char(10) NOT NULL,
    `qty` int NOT NULL,
    `status_order_dtl` char(1) NOT NULL,
    `last_update` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
	PRIMARY KEY (`kode_order_dtl`),
    KEY `idx_fk_order_dtl_order` (`kode_order`),
	CONSTRAINT `fk_order_dtl_order` FOREIGN KEY (`kode_order`) REFERENCES `order_mst` (`kode_order`) ON DELETE RESTRICT ON UPDATE CASCADE,
	CONSTRAINT `fk_order_dtl_barang` FOREIGN KEY (`kode_barang`) REFERENCES `barang` (`kode_barang`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;