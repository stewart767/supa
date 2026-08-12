<?php

namespace App\Services;

use App\Models\Setting;

class AdmissionCategoryCalculatorService
{
    /**
     * Automatically calculate the admission category based on configurable business rules.
     */
    public function calculate(string $admissionType, ?float $gpa, ?int $acseePoints): string
    {
        $minDirectEntryGpa = (float) Setting::get('direct_entry_min_gpa', 3.0);
        $minDirectEntryPoints = (int) Setting::get('direct_entry_min_points', 5);

        if ($admissionType === 'Diploma') {
            if ($gpa !== null && $gpa >= $minDirectEntryGpa) {
                return 'Direct Entry';
            }
            return 'Foundation';
        }

        if ($admissionType === 'Form Six') {
            if ($acseePoints !== null && $acseePoints >= $minDirectEntryPoints) {
                return 'Direct Entry';
            }
            return 'Foundation';
        }

        return 'Direct Entry';
    }
}
