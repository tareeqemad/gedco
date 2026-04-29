<?php

namespace App\Console\Commands;

use App\Models\EmployeeDirectory;
use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportEmployeeDirectory extends Command
{
    protected $signature = 'staff:import-directory {file : Path to XLS/XLSX/CSV file}';

    protected $description = 'Import employee directory from spreadsheet (columns: NO, NAME, ID)';

    public function handle(): int
    {
        $file = $this->argument('file');

        if (! file_exists($file)) {
            $this->error("File not found: {$file}");
            return self::FAILURE;
        }

        $this->info('Reading file...');
        $spreadsheet = IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        if (count($rows) < 2) {
            $this->error('File is empty or has no data rows.');
            return self::FAILURE;
        }

        // Normalize headers
        $headers = array_map(fn($h) => strtoupper(trim($h)), $rows[0]);
        $noIdx = array_search('NO', $headers);
        $nameIdx = array_search('NAME', $headers);
        $idIdx = array_search('ID', $headers);

        if ($noIdx === false || $nameIdx === false || $idIdx === false) {
            $this->error('Expected columns: NO, NAME, ID. Found: ' . implode(', ', $headers));
            return self::FAILURE;
        }

        $dataRows = array_slice($rows, 1);
        $this->info(sprintf('Found %d rows. Importing...', count($dataRows)));

        // Truncate and re-import
        EmployeeDirectory::truncate();

        $imported = 0;
        $skipped = 0;

        foreach ($dataRows as $i => $row) {
            $empNo = trim($row[$noIdx] ?? '');
            $name = trim($row[$nameIdx] ?? '');
            $nationalId = trim($row[$idIdx] ?? '');

            if (empty($empNo) || empty($name) || empty($nationalId)) {
                $skipped++;
                continue;
            }

            EmployeeDirectory::create([
                'employee_number' => (int) $empNo,
                'full_name' => $name,
                'national_id' => $nationalId,
            ]);

            $imported++;
        }

        $this->info("Done! Imported: {$imported}, Skipped: {$skipped}");

        return self::SUCCESS;
    }
}
