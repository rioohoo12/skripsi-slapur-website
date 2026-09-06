<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$models = ["Teacher", "Staff", "Room", "RoomAssignment", "DiningService", "AcademicSchedule", "Grade", "Attendance", "Payment", "ChatLog"];

foreach ($models as $model) {
    $kernel->call('make:model', ['name' => $model, '-m' => true]);
    echo "Created $model\n";
}
