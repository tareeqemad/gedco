<?php

namespace App\Console\Commands;

use App\Models\EmployeeDirectory;
use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\IOFactory;

class EnrichEmployeeDirectory extends Command
{
    protected $signature = 'staff:enrich-directory {file : Path to merged XLSX file}';

    protected $description = 'Enrich employee directory with data from merged employees file (Arabic headers)';

    /**
     * Header mapping: Arabic header → DB column
     */
    private const HEADER_MAP = [
        'الرقم الوظيفي'   => 'employee_number',
        'اسم الموظف'      => 'full_name',
        'المسمى الوظيفي'  => 'job_title',
        'رقم الجوال'      => 'mobile',
        'الإيميل'         => 'email',
        'رقم الهوية'      => 'national_id',
        'اسم الزوجة'      => 'spouse_name',
        'رقم هوية الزوجة' => 'spouse_national_id',
        'عدد أفراد الأسرة' => 'family_members_count',
        'المحافظة'        => 'governorate',
        'الحالة الوظيفية'  => 'employment_status',
    ];

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

        // Map Arabic headers to column indices
        $headers = $rows[0];
        $colMap = [];
        foreach ($headers as $idx => $header) {
            $h = trim((string) $header);
            if (isset(self::HEADER_MAP[$h])) {
                $colMap[self::HEADER_MAP[$h]] = $idx;
            }
        }

        if (! isset($colMap['employee_number'])) {
            $this->error('Column "الرقم الوظيفي" not found.');
            return self::FAILURE;
        }

        $dataRows = array_slice($rows, 1);
        $this->info(sprintf('Found %d rows. Enriching...', count($dataRows)));

        $updated = 0;
        $notFound = 0;

        foreach ($dataRows as $row) {
            $empNo = trim((string) ($row[$colMap['employee_number']] ?? ''));
            if (empty($empNo)) {
                continue;
            }

            $entry = EmployeeDirectory::where('employee_number', (int) $empNo)->first();
            if (! $entry) {
                $notFound++;
                continue;
            }

            $changes = [];

            // Update full_name if merged file has a longer name
            if (isset($colMap['full_name'])) {
                $newName = trim((string) ($row[$colMap['full_name']] ?? ''));
                if ($newName && mb_strlen($newName) > mb_strlen($entry->full_name)) {
                    $changes['full_name'] = $newName;
                }
            }

            // Update national_id if missing in directory but present in merged
            if (isset($colMap['national_id'])) {
                $nid = trim((string) ($row[$colMap['national_id']] ?? ''));
                if ($nid && (empty($entry->national_id) || $entry->national_id === '0')) {
                    $changes['national_id'] = $nid;
                }
            }

            // Simple string fields
            foreach (['job_title', 'email', 'spouse_name', 'spouse_national_id', 'governorate', 'employment_status'] as $field) {
                if (isset($colMap[$field])) {
                    $val = trim((string) ($row[$colMap[$field]] ?? ''));
                    if ($val !== '') {
                        $changes[$field] = $val;
                    }
                }
            }

            // Mobile - clean to digits only
            if (isset($colMap['mobile'])) {
                $mobile = preg_replace('/\D/', '', (string) ($row[$colMap['mobile']] ?? ''));
                if ($mobile !== '') {
                    $changes['mobile'] = $mobile;
                }
            }

            // Family members count
            if (isset($colMap['family_members_count'])) {
                $count = (int) ($row[$colMap['family_members_count']] ?? 0);
                if ($count > 0) {
                    $changes['family_members_count'] = $count;
                }
            }

            if (! empty($changes)) {
                $entry->update($changes);
                $updated++;
            }
        }

        $this->info("Done! Updated: {$updated}, Not in directory: {$notFound}");

        return self::SUCCESS;
    }
}
