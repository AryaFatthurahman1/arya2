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

class KaryawanTemplateExport implements WithMultipleSheets
{
    protected $jabatan;
    protected $satuanKerja;

    public function __construct($jabatan, $satuanKerja)
    {
        $this->jabatan = $jabatan;
        $this->satuanKerja = $satuanKerja;
    }

    public function sheets(): array
    {
        return [
            new KaryawanTemplateDataSheet(),
            new KaryawanTemplateReferenceSheet($this->jabatan, $this->satuanKerja),
        ];
    }
}

class KaryawanTemplateDataSheet implements FromArray, WithHeadings, WithStyles, WithTitle, WithEvents
{
    public function array(): array
    {
        return [
            [
                'ADM001',
                'Contoh Nama Lengkap',
                'Jakarta',
                '1990-01-15',
                'L',
                'Islam',
                'belum_menikah',
                'Jl. Contoh Alamat No. 1, Jakarta',
                '081234567890',
                'contoh@hr.test',
                1,
                1,
                '2024-01-01',
                'tetap',
                'BCA',
                '1234567890',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'nik',
            'nama_lengkap',
            'tempat_lahir',
            'tanggal_lahir',
            'jenis_kelamin',
            'agama',
            'status_pernikahan',
            'alamat',
            'telepon',
            'email',
            'jabatan_id',
            'satuan_kerja_id',
            'tanggal_masuk',
            'status_karyawan',
            'nama_bank',
            'nomor_rekening',
        ];
    }

    public function title(): string
    {
        return 'Data Karyawan';
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
                foreach (range('A', 'P') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Add data validation for jenis_kelamin (L/P)
                $validation = $sheet->getCell('E2')->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST);
                $validation->setErrorStyle(DataValidation::STYLE_INFORMATION);
                $validation->setAllowBlank(false);
                $validation->setShowInputMessage(true);
                $validation->setShowErrorMessage(true);
                $validation->setFormula1('"L,P"');

                // Add data validation for status_pernikahan
                $validation2 = $sheet->getCell('G2')->getDataValidation();
                $validation2->setType(DataValidation::TYPE_LIST);
                $validation2->setErrorStyle(DataValidation::STYLE_INFORMATION);
                $validation2->setAllowBlank(false);
                $validation2->setShowInputMessage(true);
                $validation2->setShowErrorMessage(true);
                $validation2->setFormula1('"belum_menikah,menikah,cerai"');

                // Add data validation for status_karyawan
                $validation3 = $sheet->getCell('N2')->getDataValidation();
                $validation3->setType(DataValidation::TYPE_LIST);
                $validation3->setErrorStyle(DataValidation::STYLE_INFORMATION);
                $validation3->setAllowBlank(false);
                $validation3->setShowInputMessage(true);
                $validation3->setShowErrorMessage(true);
                $validation3->setFormula1('"tetap,kontrak,percobaan"');
            },
        ];
    }
}

class KaryawanTemplateReferenceSheet implements FromArray, WithHeadings, WithTitle, WithStyles
{
    protected $jabatan;
    protected $satuanKerja;

    public function __construct($jabatan, $satuanKerja)
    {
        $this->jabatan = $jabatan;
        $this->satuanKerja = $satuanKerja;
    }

    public function array(): array
    {
        $rows = [];
        foreach ($this->jabatan as $j) {
            $rows[] = ['Jabatan', $j->id, $j->nama_jabatan, 'Gaji Pokok: Rp ' . number_format($j->gaji_pokok, 0, ',', '.')];
        }
        foreach ($this->satuanKerja as $s) {
            $rows[] = ['Satuan Kerja', $s->id, $s->nama_satuan_kerja, $s->lokasi ?: '-'];
        }
        return $rows;
    }

    public function headings(): array
    {
        return ['Tipe', 'ID (gunakan ini)', 'Nama', 'Keterangan'];
    }

    public function title(): string
    {
        return 'Referensi ID';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '059669']]],
        ];
    }
}
