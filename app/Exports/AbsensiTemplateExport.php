<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AbsensiTemplateExport implements WithMultipleSheets
{
    protected $karyawan;

    public function __construct($karyawan)
    {
        $this->karyawan = $karyawan;
    }

    public function sheets(): array
    {
        return [
            new AbsensiTemplateDataSheet(),
            new AbsensiTemplateReferenceSheet($this->karyawan),
        ];
    }
}

class AbsensiTemplateDataSheet implements FromArray, WithHeadings, WithStyles, WithTitle, WithEvents
{
    public function array(): array
    {
        return [
            [
                'ADM001',
                '2024-01-15',
                '08:00',
                '17:00',
                'hadir',
                'Hari pertama masuk kerja',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'nik',
            'tanggal',
            'jam_masuk',
            'jam_keluar',
            'status',
            'keterangan',
        ];
    }

    public function title(): string
    {
        return 'Data Absensi';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '4F46E5']]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                foreach (range('A', 'F') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                $validation = $sheet->getCell('E2')->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST);
                $validation->setErrorStyle(DataValidation::STYLE_INFORMATION);
                $validation->setAllowBlank(false);
                $validation->setShowInputMessage(true);
                $validation->setShowErrorMessage(true);
                $validation->setFormula1('"hadir,izin,sakit,alpha"');
            },
        ];
    }
}

class AbsensiTemplateReferenceSheet implements FromArray, WithHeadings, WithTitle, WithStyles
{
    protected $karyawan;

    public function __construct($karyawan)
    {
        $this->karyawan = $karyawan;
    }

    public function array(): array
    {
        $rows = [];
        foreach ($this->karyawan as $k) {
            $rows[] = [$k->nik, $k->nama_lengkap, $k->jabatan?->nama_jabatan ?? '-'];
        }
        return $rows;
    }

    public function headings(): array
    {
        return ['NIK (gunakan ini)', 'Nama Karyawan', 'Jabatan'];
    }

    public function title(): string
    {
        return 'Referensi NIK';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '059669']]],
        ];
    }
}
