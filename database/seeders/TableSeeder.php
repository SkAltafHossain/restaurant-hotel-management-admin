<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tables = [
            ['table_number' => '1', 'capacity' => 4, 'status' => 'available'],
            ['table_number' => '2', 'capacity' => 4, 'status' => 'available'],
            ['table_number' => '3', 'capacity' => 6, 'status' => 'available'],
            ['table_number' => '4', 'capacity' => 6, 'status' => 'available'],
            ['table_number' => '5', 'capacity' => 8, 'status' => 'available'],
            ['table_number' => '6', 'capacity' => 2, 'status' => 'available'],
        ];

        foreach ($tables as $table) {
            Table::updateOrCreate(
                ['table_number' => $table['table_number']],
                $table
            );
        }

        $this->command->info('Tables seeded successfully.');
    }
}
