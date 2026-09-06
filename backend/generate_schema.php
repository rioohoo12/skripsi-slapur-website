<?php

$models = [
    'Role' => 'roles',
    'Student' => 'students',
    'Teacher' => 'teachers',
    'Staff' => 'staff',
    'Room' => 'rooms',
    'RoomAssignment' => 'room_assignments',
    'DiningService' => 'dining_services',
    'AcademicSchedule' => 'academic_schedules',
    'Grade' => 'grades',
    'Attendance' => 'attendances',
    'Payment' => 'payments',
    'ChatLog' => 'chat_logs',
];

$modelsDir = __DIR__ . '/app/Models';
$migrationsDir = __DIR__ . '/database/migrations';

$timestamp = time();

foreach ($models as $model => $table) {
    // Generate Model
    $modelContent = <<<PHP
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class $model extends Model
{
    use HasFactory;
    
    protected \$guarded = [];
}
PHP;
    file_put_contents("$modelsDir/$model.php", $modelContent);

    // Generate Migration
    $ts = date('Y_m_d_His', $timestamp);
    $className = 'Create' . str_replace('_', '', ucwords($table, '_')) . 'Table';
    
    $migrationContent = <<<PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('$table', function (Blueprint \$table) {
            \$table->id();
            // TODO: add columns
            \$table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('$table');
    }
};
PHP;
    
    // Only create migration if it doesn't already exist for this table
    $existing = glob("$migrationsDir/*_create_{$table}_table.php");
    if (empty($existing)) {
        file_put_contents("$migrationsDir/{$ts}_create_{$table}_table.php", $migrationContent);
        $timestamp++;
    }
}

echo "All models and migrations created successfully!\n";
