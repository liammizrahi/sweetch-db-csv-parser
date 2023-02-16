<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FetchQueries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queries:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch the queries from the data table based on the task requirements.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // we will output a table in the console, this is the header of the table
        $headers = ['Condition', 'Rows Count'];
        $data = [];

        /*
         * It was a bit unclear if the task required these 5 queries to be executed or just one query with all the conditions.
         * So in order to be sure, I've implemented both.
         */
        $data[] = ['Condition' => 'Area = Hampstead', 'Rows Count' => DB::table('data')->where('Area', "Hampstead")->count()];
        $data[] = ['Condition' => 'Age > 45', 'Rows Count' => DB::table('data')->where('Age', '>', 45)->count()];
        $data[] = ['Condition' => 'Gender = Female', 'Rows Count' => DB::table('data')->where('Sex', 2)->count()];
        $data[] = ['Condition' => 'Year = 2018', 'Rows Count' => DB::table('data')->where('Year', 2018)->count()];
        $data[] = ['Condition' => 'Ethnic = Asian', 'Rows Count' => DB::table('data')->where('Ethnic', "Asian")->count()];

        // This is the query with all the conditions met.
        $data[] = [
            'Condition' => 'ALL CONDITIONS',
            'Rows Count' =>
                DB::table('data')
                    ->where('Area', "Hampstead")
                    ->where('Age', '>', 45)
                    ->where('Sex', 2)
                    ->where('Year', 2018)
                    ->where('Ethnic', "Asian")
                    ->count()
        ];

        // Output the table in the console
        $this->table($headers, $data);
    }
}
