<?php

namespace Tests\Unit;

use App\Services\WeightReference;
use PHPUnit\Framework\TestCase;

class WeightReferenceTest extends TestCase
{
    public function test_returns_exact_table_value_at_known_months(): void
    {
        $this->assertSame(3.3, WeightReference::medianForAge(0, 'male'));
        $this->assertSame(9.6, WeightReference::medianForAge(12, 'male'));
        $this->assertSame(11.5, WeightReference::medianForAge(24, 'female'));
    }

    public function test_interpolates_between_known_months(): void
    {
        // Male: month 12 = 9.6, month 15 = 10.3 → halfway (13.5) should be the midpoint.
        $this->assertSame(9.95, WeightReference::medianForAge(13.5, 'male'));
    }

    public function test_clamps_ages_outside_the_reference_range(): void
    {
        $this->assertSame(WeightReference::medianForAge(0, 'male'), WeightReference::medianForAge(-5, 'male'));
        $this->assertSame(WeightReference::medianForAge(24, 'male'), WeightReference::medianForAge(36, 'male'));
    }

    public function test_falls_back_to_an_averaged_table_for_unknown_sex(): void
    {
        $median = WeightReference::medianForAge(0, 'other');

        $this->assertEqualsWithDelta(3.25, $median, 0.01);
    }

    public function test_classifies_weight_bands(): void
    {
        $normal = WeightReference::classify(9.6, 12, 'male');
        $this->assertSame('Normal range', $normal['label']);

        $higher = WeightReference::classify(12.0, 12, 'male');
        $this->assertSame('Higher spectrum', $higher['label']);

        $lower = WeightReference::classify(7.5, 12, 'male');
        $this->assertSame('Lower spectrum', $lower['label']);
    }
}
