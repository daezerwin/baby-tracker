<?php

namespace App\Services;

use Carbon\Carbon;
use Carbon\CarbonImmutable;

class AgeCalculator
{
    protected CarbonImmutable $dateOfBirth;

    protected CarbonImmutable $now;

    public function __construct(Carbon|CarbonImmutable|string $dateOfBirth, Carbon|CarbonImmutable|null $now = null)
    {
        $this->dateOfBirth = CarbonImmutable::parse($dateOfBirth)->startOfDay();
        $this->now = $now ? CarbonImmutable::parse($now) : CarbonImmutable::now();
    }

    public function totalDays(): int
    {
        return $this->dateOfBirth->diffInDays($this->now);
    }

    public function totalWeeks(): int
    {
        return intdiv($this->totalDays(), 7);
    }

    public function totalMonths(): int
    {
        return $this->dateOfBirth->diffInMonths($this->now);
    }

    public function years(): int
    {
        return $this->dateOfBirth->diffInYears($this->now);
    }

    /**
     * Human-friendly age, e.g. "3 months, 2 weeks" or "5 days".
     */
    public function label(): string
    {
        $days = $this->totalDays();

        if ($days < 14) {
            return $days === 1 ? '1 day' : "{$days} days";
        }

        if ($this->totalMonths() < 1) {
            $weeks = $this->totalWeeks();

            return $weeks === 1 ? '1 week' : "{$weeks} weeks";
        }

        if ($this->years() >= 1) {
            $years = $this->years();
            $remainingMonths = $this->totalMonths() % 12;

            $label = $years === 1 ? '1 year' : "{$years} years";

            if ($remainingMonths > 0) {
                $label .= ', '.($remainingMonths === 1 ? '1 month' : "{$remainingMonths} months");
            }

            return $label;
        }

        $months = $this->totalMonths();
        $remainingWeeks = intdiv($this->totalDays() - $months * 30, 7);

        $label = $months === 1 ? '1 month' : "{$months} months";

        if ($remainingWeeks > 0) {
            $label .= ', '.($remainingWeeks === 1 ? '1 week' : "{$remainingWeeks} weeks");
        }

        return $label;
    }

    public function toArray(): array
    {
        return [
            'days' => $this->totalDays(),
            'weeks' => $this->totalWeeks(),
            'months' => $this->totalMonths(),
            'years' => $this->years(),
            'label' => $this->label(),
        ];
    }
}
