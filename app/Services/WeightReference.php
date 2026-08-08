<?php

namespace App\Services;

class WeightReference
{
    /**
     * Approximate WHO Child Growth Standards median (50th percentile)
     * weight-for-age in kg, by age in whole months. General reference only —
     * not a substitute for a pediatrician's assessment.
     */
    protected const MEDIANS = [
        'male' => [
            0 => 3.3, 1 => 4.5, 2 => 5.6, 3 => 6.4, 4 => 7.0, 5 => 7.5, 6 => 7.9,
            7 => 8.3, 8 => 8.6, 9 => 8.9, 10 => 9.2, 11 => 9.4, 12 => 9.6,
            15 => 10.3, 18 => 10.9, 21 => 11.5, 24 => 12.2,
        ],
        'female' => [
            0 => 3.2, 1 => 4.2, 2 => 5.1, 3 => 5.8, 4 => 6.4, 5 => 6.9, 6 => 7.3,
            7 => 7.6, 8 => 7.9, 9 => 8.2, 10 => 8.5, 11 => 8.7, 12 => 8.9,
            15 => 9.6, 18 => 10.2, 21 => 10.8, 24 => 11.5,
        ],
    ];

    /**
     * Linearly interpolated median weight (kg) for a given age in months.
     * Ages outside the 0-24 month reference range are clamped to the ends.
     */
    public static function medianForAge(float $ageInMonths, string $sex): float
    {
        $table = static::MEDIANS[$sex] ?? static::averagedTable();

        $ageInMonths = max(0, min(24, $ageInMonths));

        $months = array_keys($table);
        $lower = $months[0];
        $upper = end($months);

        foreach ($months as $month) {
            if ($month <= $ageInMonths) {
                $lower = $month;
            }
            if ($month >= $ageInMonths) {
                $upper = $month;
                break;
            }
        }

        if ($lower === $upper) {
            return $table[$lower];
        }

        $progress = ($ageInMonths - $lower) / ($upper - $lower);

        return round($table[$lower] + $progress * ($table[$upper] - $table[$lower]), 2);
    }

    /**
     * Classify a weight against the median for the baby's age/sex into a
     * simple, non-diagnostic band: normal / higher / lower spectrum.
     */
    public static function classify(float $weightKg, float $ageInMonths, string $sex): array
    {
        $median = static::medianForAge($ageInMonths, $sex);
        $diffPercent = $median > 0 ? (($weightKg - $median) / $median) * 100 : 0;

        return match (true) {
            $diffPercent > 10 => ['label' => 'Higher spectrum', 'color' => 'amber', 'median' => $median, 'diff_percent' => $diffPercent],
            $diffPercent < -10 => ['label' => 'Lower spectrum', 'color' => 'amber', 'median' => $median, 'diff_percent' => $diffPercent],
            default => ['label' => 'Normal range', 'color' => 'emerald', 'median' => $median, 'diff_percent' => $diffPercent],
        };
    }

    protected static function averagedTable(): array
    {
        $average = [];

        foreach (static::MEDIANS['male'] as $month => $value) {
            $average[$month] = round(($value + static::MEDIANS['female'][$month]) / 2, 2);
        }

        return $average;
    }
}
