<?php

namespace App\Exports;

use App\Models\Document;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\DB;

class DocumentsExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $query = Document::with(['document_typeb', 'programb']);

        if ($this->startDate) {
            $query->whereDate(
                \DB::raw("DATE_ADD(created_at, INTERVAL 8 HOUR)"),
                '>=',
                $this->startDate
            );
        }

        if ($this->endDate) {
            $query->whereDate(
                \DB::raw("DATE_ADD(created_at, INTERVAL 8 HOUR)"),
                '<=',
                $this->endDate
            );
        }

        return $query->get()->map(function ($doc) {
            return [
                'Tracking Code' => $doc->tracking_code,
                'Document Type' => $doc->document_typeb->name ?? '',
                'Student ID' => $doc->id_number,
                'Name' => $doc->surname . ', ' . $doc->given_name,
                'Program' => $doc->programb->name ?? '',
                'Year Level' => $doc->year_level,
                'Status' => $doc->status,
                'Remarks' => $doc->remarks,
                
                'Created At' => $doc->created_at
                    ? $doc->created_at->copy()->addHours(8)->format('Y-m-d H:i:s')
                    : '',

                'Updated At' => $doc->updated_at
                    ? $doc->updated_at->copy()->addHours(8)->format('Y-m-d H:i:s')
                    : '',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Tracking Code',
            'Document Type',
            'Student ID',
            'Name',
            'Program',
            'Year Level',
            'Status',
            'Remarks',
            'Created At',
            'Updated At',
        ];
    }
}