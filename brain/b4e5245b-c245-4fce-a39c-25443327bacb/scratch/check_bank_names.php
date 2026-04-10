<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Workdo\Account\Entities\BankAccount;

$accounts = BankAccount::where('workspace', 18)->where('holder_name', 'LIKE', '%cash%')->get();
foreach($accounts as $a){
    echo "ID: " . $a->id . " | Bank Name: '" . $a->bank_name . "' | Holder Name: '" . $a->holder_name . "'\n";
}
