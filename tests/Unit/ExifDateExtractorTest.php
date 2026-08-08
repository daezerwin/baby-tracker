<?php

namespace Tests\Unit;

use App\Services\ExifDateExtractor;
use PHPUnit\Framework\TestCase;

class ExifDateExtractorTest extends TestCase
{
    public function test_parses_a_valid_exif_datetime_string(): void
    {
        $date = ExifDateExtractor::parseExifDateTime('2026:08:01 14:32:05');

        $this->assertNotNull($date);
        $this->assertSame('2026-08-01 14:32:05', $date->format('Y-m-d H:i:s'));
    }

    public function test_trims_surrounding_whitespace(): void
    {
        $date = ExifDateExtractor::parseExifDateTime("  2026:01:15 08:00:00 \0");

        $this->assertNotNull($date);
        $this->assertSame('2026-01-15 08:00:00', $date->format('Y-m-d H:i:s'));
    }

    public function test_returns_null_for_an_unparsable_string(): void
    {
        $this->assertNull(ExifDateExtractor::parseExifDateTime('not a date'));
        $this->assertNull(ExifDateExtractor::parseExifDateTime(''));
        $this->assertNull(ExifDateExtractor::parseExifDateTime('0000:00:00 00:00:00'));
    }
}
