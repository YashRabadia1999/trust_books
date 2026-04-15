<?php
$mysqli = new mysqli('127.0.0.1','root','','trus_tbuuk',3306);
if ($mysqli->connect_error) {
    echo 'CONNECT_ERR: ' . $mysqli->connect_error . PHP_EOL;
    exit(1);
}

$sql = "SELECT id, invoice_id, invoice_module, account_type, user_id, customer_id, workspace, created_by FROM invoices WHERE invoice_id = 4 ORDER BY id DESC LIMIT 5";
$res = $mysqli->query($sql);
if (!$res) {
    echo 'INVOICE_QUERY_ERR: ' . $mysqli->error . PHP_EOL;
    exit(1);
}

while ($inv = $res->fetch_assoc()) {
    echo 'INVOICE=' . json_encode($inv) . PHP_EOL;
    $iid = (int)$inv['id'];

    $itemSql = "SELECT id, invoice_id, product_type, product_id, quantity, price, discount, tax, description FROM invoice_products WHERE invoice_id = {$iid} ORDER BY id";
    $items = $mysqli->query($itemSql);
    if (!$items) {
        echo 'ITEM_QUERY_ERR: ' . $mysqli->error . PHP_EOL;
        continue;
    }

    if ($items->num_rows === 0) {
        echo 'ITEM=NONE' . PHP_EOL;
    }

    while ($it = $items->fetch_assoc()) {
        echo 'ITEM=' . json_encode($it) . PHP_EOL;
    }
}
