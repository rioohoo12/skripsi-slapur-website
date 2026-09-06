<?php

$schemas = [
    'roles' => <<<PHP
            \$table->string('name')->unique();
            \$table->string('description')->nullable();
PHP,
    'students' => <<<PHP
            \$table->foreignId('user_id')->constrained()->cascadeOnDelete();
            \$table->string('nisn')->unique();
            \$table->enum('gender', ['L', 'P']);
            \$table->date('date_of_birth');
            \$table->text('address')->nullable();
            \$table->string('parent_phone')->nullable();
PHP,
    'teachers' => <<<PHP
            \$table->foreignId('user_id')->constrained()->cascadeOnDelete();
            \$table->string('nip')->unique();
            \$table->string('specialization')->nullable();
PHP,
    'staff' => <<<PHP
            \$table->foreignId('user_id')->constrained()->cascadeOnDelete();
            \$table->string('position');
            \$table->string('department');
PHP,
    'rooms' => <<<PHP
            \$table->string('name');
            \$table->integer('capacity');
            \$table->enum('gender_type', ['L', 'P']);
PHP,
    'room_assignments' => <<<PHP
            \$table->foreignId('room_id')->constrained()->cascadeOnDelete();
            \$table->foreignId('student_id')->constrained()->cascadeOnDelete();
            \$table->string('academic_year');
            \$table->string('status')->default('active');
PHP,
    'dining_services' => <<<PHP
            \$table->foreignId('student_id')->constrained()->cascadeOnDelete();
            \$table->string('meal_plan_type');
            \$table->date('valid_until');
            \$table->string('status')->default('active');
PHP,
    'academic_schedules' => <<<PHP
            \$table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            \$table->string('subject_name');
            \$table->string('day_of_week');
            \$table->time('start_time');
            \$table->time('end_time');
            \$table->string('location')->nullable();
PHP,
    'grades' => <<<PHP
            \$table->foreignId('student_id')->constrained()->cascadeOnDelete();
            \$table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            \$table->string('subject_name');
            \$table->string('semester');
            \$table->decimal('score', 5, 2);
PHP,
    'attendances' => <<<PHP
            \$table->foreignId('student_id')->constrained()->cascadeOnDelete();
            \$table->date('date');
            \$table->enum('status', ['present', 'absent', 'sick', 'leave']);
PHP,
    'payments' => <<<PHP
            \$table->foreignId('student_id')->constrained()->cascadeOnDelete();
            \$table->decimal('amount', 10, 2);
            \$table->date('payment_date');
            \$table->string('type'); // tuition, dormitory, dining
            \$table->string('status')->default('pending');
PHP,
    'chat_logs' => <<<PHP
            \$table->foreignId('user_id')->constrained()->cascadeOnDelete();
            \$table->text('message');
            \$table->text('response');
            \$table->string('intent')->nullable();
PHP,
];

$migrationsDir = __DIR__ . '/database/migrations';
$files = glob("$migrationsDir/*.php");

foreach ($files as $file) {
    $content = file_get_contents($file);
    foreach ($schemas as $table => $schemaContent) {
        if (str_contains($file, "create_{$table}_table")) {
            $content = str_replace('// TODO: add columns', $schemaContent, $content);
            file_put_contents($file, $content);
            echo "Updated schema for $table\n";
        }
    }
}
