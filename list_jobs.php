<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$all = DB::table('job_postings')->get(['id', 'code', 'title']);
echo "All Job Postings:\n";
foreach($all as $j) {
    echo "ID: {$j->id} | Code: {$j->code} | Title: {$j->title}\n";
}
