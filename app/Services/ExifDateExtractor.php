<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Throwable;

class ExifDateExtractor
{
    /**
     * Attempt to read the "date taken" from a photo's EXIF metadata.
     * Returns null when the file has no readable EXIF date (most photos
     * that weren't taken on a camera/phone, or non-JPEG/TIFF uploads).
     */
    public static function extract(UploadedFile $file): ?Carbon
    {
        if (! function_exists('exif_read_data')) {
            return null;
        }

        if (! in_array($file->getMimeType(), ['image/jpeg', 'image/tiff'], true)) {
            return null;
        }

        $exif = @exif_read_data($file->getRealPath(), 'EXIF', true);

        if (! is_array($exif)) {
            return null;
        }

        $raw = $exif['EXIF']['DateTimeOriginal']
            ?? $exif['EXIF']['DateTimeDigitized']
            ?? $exif['IFD0']['DateTime']
            ?? null;

        return $raw ? static::parseExifDateTime($raw) : null;
    }

    /**
     * EXIF timestamps are formatted like "2026:08:01 14:32:05". Cameras
     * without a clock set often write all-zero placeholders instead.
     */
    public static function parseExifDateTime(string $raw): ?Carbon
    {
        $raw = trim($raw);

        if ($raw === '' || str_starts_with($raw, '0000')) {
            return null;
        }

        try {
            $date = Carbon::createFromFormat('Y:m:d H:i:s', $raw);
        } catch (Throwable) {
            return null;
        }

        return $date && $date->year >= 1970 ? $date : null;
    }
}
