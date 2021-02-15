This is simple API for test.

The purpose is for problem solving misreported inventory quantities in online store.
With limited information and without observing what is going on in environment, script, etc.
thus, I try to recreate API with simple table order and inventory

Cause : flood of orders
Effect (probability):
- query not completed
- transaction running without fail

to use this app :
with url :
give parameter fn at url to call function example : index.php?fn=get_barang
use method post
body json

function :
get_barang
use if need to get just 1 item
{
  "kode_barang" : ""
}

get_cart
parameter needed
{
  "kode_pelanggan" : ""
}

get_order
{
  "kode_pelanggan" : ""
}

get_order_detail
{
  "kode_order" : ""
}

transact_cart
{
    "kode_pelanggan" : "0000000001",
    "kode_barang" : "0000000001",
    "qty" : "2"
}

transact_order
[{
    "tgl_cart" : ""
    "kode_pelanggan" : "0000000001",
    "kode_barang" : "0000000001",
    "qty" : "2"
}]