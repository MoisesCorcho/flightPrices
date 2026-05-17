<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Airport;
use Illuminate\Support\Facades\Storage;

class AirportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = database_path('data/airports.txt');
        
        if (!file_exists($filePath)) {
            $this->command->error("File not found: {$filePath}");
            return;
        }

        $content = file_get_contents($filePath);
        $lines = explode("\n", $content);
        $importedCount = 0;

        foreach ($lines as $line) {
            if (empty(trim($line))) {
                continue;
            }

            $data = str_getcsv($line);

            // Mapping: 0:id, 1:name, 2:city, 3:country, 4:iata, 5:icao
            if (count($data) < 6) {
                continue;
            }

            $name = $data[1];
            $city = $data[2];
            $country = $data[3];
            $iata = $data[4];
            $icao = $data[5];

            // Filter: Country must be Colombia and IATA must be present (3 chars, not \N)
            if ($country !== 'Colombia' || empty($iata) || $iata === '\N' || strlen($iata) !== 3) {
                continue;
            }

            Airport::updateOrCreate(
                ['iata' => $iata],
                [
                    'name' => $name,
                    'city' => $city,
                    'country' => $country,
                    'icao' => $icao === '\N' ? null : $icao,
                ]
            );

            $importedCount++;
        }

        $this->command->info("Imported {$importedCount} airports from Colombia.");
    }
}
