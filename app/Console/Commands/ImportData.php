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
        $this->output->writeln('Importing data from CSV file to database table...');

        // Disable the query log to avoid memory issues.
        DB::disableQueryLog();

        $imports = [
            ['path' => 'DimenLookupAge8277.csv', 'table' => 'age_lookup', 'columns' => ['Code', 'Description', 'SortOrder']],
            ['path' => 'DimenLookupArea8277.csv', 'table' => 'area_lookup', 'columns' => ['Code', 'Description', 'SortOrder']],
            ['path' => 'DimenLookupEthnic8277.csv', 'table' => 'ethnic_lookup', 'columns' => ['Code', 'Description', 'SortOrder']],
            ['path' => 'DimenLookupSex8277.csv', 'table' => 'sex_lookup', 'columns' => ['Code', 'Description', 'SortOrder']],
            ['path' => 'DimenLookupYear8277.csv', 'table' => 'year_lookup', 'columns' => ['Code', 'Description', 'SortOrder']],
            ['path' => 'Data8277.csv', 'table' => 'data', 'columns' => ['Year', 'Age', 'Ethnic', 'Sex', 'Area', 'count']]
        ];

        $bar = $this->output->createProgressBar(count($imports));

        $bar->start();

        foreach ($imports as $import) {
            $this->output->writeln('Importing '.$import['path'].' to '.$import['table'].' table...');
            $this->import($import['path'], $import['table'], $import['columns']);
            $bar->advance();
        }

        $bar->finish();

        $this->info('<fg=green>Done!</>');
    }

    private function import($path, $table, $columns) {
        $start_time = hrtime(true);

        // We need to use the storage_path() helper function to get the path to the CSV file that saved inside the storage.
        $csvPath = storage_path('app/'.$path);

        $file = fopen($csvPath, 'r');

        // Takes the first row of the CSV file in order to remove them from the process.
        fgets($file);

        // To reduce the number of queries, we will use the insert() method to insert the data in batches.
        $data = [];
        $count = 0;
        $batch = 1000;

        // Efficiently import the data from the CSV file into the database table, line by line.
        while ($row = fgetcsv($file)) {
            $row_data = [];
            foreach($columns as $key => $column) {
                $row_data[$column] = $row[$key];
            }

            $data[] = $row_data;

            $count++;

            if ($count == $batch) { // Insert every $batchSize rows
                DB::table($table)->insert($data);
                $data = [];
                $count = 0;
            }
        }

        // Insert any remaining rows
        if (!empty($data)) {
            DB::table($table)->insert($data);
        }

        fclose($file);

        // Time measurement
        $end_time = hrtime(true);
        $elapsed = ($end_time - $start_time) / 1e+9; // divide by 1 billion to convert from nanoseconds to seconds

        $minutes = floor($elapsed / 60);
        $seconds = $elapsed % 60;

        $this->output->writeln('<fg=green>All data imported successfully to: <bg=white;fg=black;options=bold>'.$table.'</>!</>');
        $this->output->writeln("Action took <fg=magenta>".$minutes." minutes and <fg=magenta>".$seconds."</> seconds.");
    }
}
