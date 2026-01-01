<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // Pour l'auto-ajustement des colonnes
use Maatwebsite\Excel\Concerns\WithStyles;     // Pour les styles de cellules
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet; // Pour le typage fort de WithStyles

class UsersExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function collection()
    {
        return User::where('role', '!=', 'Admin') // Ou votre condition de rôle
            ->withSum('champs', 'aire')
            ->get();
    }

    public function map($user): array
    {
        return [
            $user->nom,
            $user->telephone,
            $user->email,
            $user->champs_sum_aire ?? 0,
            $user->type_culture,
            $user->region,
        ];
    }

    public function headings(): array
    {
        return [
            "Nom de l'Agriculteur",
            "Téléphone",
            "Email",
            "Surface Totale (ha)",
            "Type de Culture",
            "Région"
        ];
    }

    // Implémentation de WithStyles
    public function styles(Worksheet $sheet)
    {
        // Style pour la première ligne (les en-têtes)
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF4CAF50'], // Vert forêt pour les en-têtes
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Style pour toutes les données (lignes 2 et suivantes)
        $sheet->getStyle('A2:F' . $sheet->getHighestRow())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FFDDDDDD'], // Bordures grises claires
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
        ]);

        // Alterner les couleurs de ligne pour une meilleure lisibilité
        // C'est un peu plus complexe et nécessite WithEvents plutôt que WithStyles direct
        // mais nous pouvons le simuler avec une boucle si la performance n'est pas critique
        // Ou utiliser un événement 'AfterSheet'
        for ($i = 2; $i <= $sheet->getHighestRow(); $i++) {
            if ($i % 2 == 0) { // Lignes paires
                $sheet->getStyle('A' . $i . ':F' . $i)->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFE8F5E9'], // Vert très clair
                    ],
                ]);
            }
        }

        // Alignement spécifique pour la colonne "Surface Totale"
        $sheet->getStyle('D2:D' . $sheet->getHighestRow())->applyFromArray([
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
            ],
        ]);
    }
}