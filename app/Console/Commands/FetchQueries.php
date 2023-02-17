<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

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
        $start_time = hrtime(true);

        // we will output a table in the console, this is the header of the table
        $headers = ['Description', 'Rows Count'];

        // This is the query with all the conditions met.
        $data = [[
            'Description' => 'Result',
            'Rows Count' =>
                DB::table('data')
                    ->leftJoin('area_lookup', 'area_lookup.Code', '=', 'data.Area')
                    ->leftJoin('age_lookup', 'age_lookup.Code', '=', 'data.Age')
                    ->leftJoin('sex_lookup', 'sex_lookup.Code', '=', 'data.Sex')
                    ->leftJoin('ethnic_lookup', 'ethnic_lookup.Code', '=', 'data.Ethnic')
                    ->where('area_lookup.Description', "Hampstead")
                    ->where(function ($query) {
                        $query->where('age_lookup.SortOrder', '>=', 73)
                            ->orWhere(function ($subQuery) {
                                $subQuery->where('age_lookup.SortOrder', '>=', 16)
                                    ->where('age_lookup.SortOrder', '<=', 27);
                            });
                    })
                    ->where('sex_lookup.Description', 'Female')
                    ->where('Year', 2018)
                    ->where('ethnic_lookup.Description', "Asian")
                    ->count()
        ]];

        // Output the table in the console
        $this->table($headers, $data);

        // Time measurement
        $end_time = hrtime(true);
        $elapsed = ($end_time - $start_time) / 1e+9; // divide by 1 billion to convert from nanoseconds to seconds

        $minutes = floor($elapsed / 60);
        $seconds = $elapsed % 60;

        $this->output->writeln('');
        $this->output->writeln("<fg=magenta>Action took <bg=white;fg=black;options=bold>".$minutes." minutes and <bg=white;fg=black;options=bold>".$seconds."</> seconds.</>");
    }
}
