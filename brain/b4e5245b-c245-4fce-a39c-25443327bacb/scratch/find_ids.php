<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Workdo\Account\Entities\BankAccount;
use Workdo\ProductService\Entities\Category;
use Workdo\Taskly\Entities\Project;

$project = Project::find(22);
if (!$project) {
    echo "Project 22 not found\n";
    exit;
}

$workspace = $project->workspace;
$creator = $project->created_by;

echo "Workspace: $workspace, Creator: $creator\n";

$account = BankAccount::where('workspace', $workspace)->where('holder_name', 'LIKE', '%Cash%')->first();
if ($account) {
    echo "Account Found: " . $account->holder_name . " (ID: " . $account->id . ")\n";
} else {
    echo "Account NOT Found\n";
}

$category = Category::where('workspace_id', $workspace)->where('name', 'LIKE', '%Project%')->where('type', 2)->first();
if ($category) {
    echo "Category Found: " . $category->name . " (ID: " . $category->id . ")\n";
} else {
    echo "Category NOT Found for Workspace $workspace\n";
    // Let's see what categories exist for this workspace
    $categories = Category::where('workspace_id', $workspace)->where('type', 2)->get();
    foreach($categories as $c){
        echo " - Category: " . $c->name . " (ID: " . $c->id . ")\n";
    }
}
