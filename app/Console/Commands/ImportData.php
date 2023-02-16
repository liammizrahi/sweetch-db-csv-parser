<?php

/*
 * This is a command handler that can be used to import data from a CSV file into a database table.
 * It takes the CSV file and imports it into the database table.
 * To run it, use the command: php artisan import:data
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data from CSV file to database table.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // We need to use the storage_path() helper function to get the path to the CSV file that saved inside the storage.
        $csvPath = storage_path('app/Data8277.csv');
        $file = fopen($csvPath, 'r');

        // Takes the first row of the CSV file in order to remove them from the process.
        $headers = fgetcsv($file);

        // Efficiently import the data from the CSV file into the database table, line by line.
        while ($row = fgetcsv($file)) {
            $data = [
                'Year' => $row[0],
                'Age' => $row[1],
                'Ethnic' => $row[2],
                'Sex' => $row[3],
                'Area' => $row[4],
                'count' => $row[5],
            ];

            DB::table('data')->insert($data);
        }

        fclose($file);
    }
}
