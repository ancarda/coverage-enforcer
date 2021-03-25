<?php

declare(strict_types=1);

namespace Ancarda\CodeCoverage;

use RuntimeException;
use SimpleXMLElement;

final class Auditor
{
    private const EXIT_MISC = 1;
    private const EXIT_COVERAGE = 2;

    private static function loadXML(string $filePath): SimpleXMLElement
    {
        if (!file_exists($filePath)) {
            throw new RuntimeException(
                sprintf('File %s does not exist', $filePath),
                self::EXIT_MISC
            );
        }

        $file = simplexml_load_file($filePath);
        if ($file === false) {
            throw new RuntimeException(
                sprintf('Failed to read or parse %s', $filePath),
                self::EXIT_MISC
            );
        }

        return $file;
    }

    private static function formatNumber(float $num): string
    {
        // if the number is an integer, just return that
        if ($num == intval($num)) {
            return (string) intval($num);
        }

        return sprintf('%.2f', $num);
    }

    public static function assertStatementsCovered(string $filePath, float $target): void
    {
        $clover = self::loadXML($filePath);

        $stmtCovered = $clover->project->metrics['coveredstatements'];
        $stmtCount   = $clover->project->metrics['statements'];
        $coverage = ($stmtCovered / $stmtCount) * 100;

        if ($target > $coverage) {
            throw new RuntimeException(
                sprintf(
                    //'Coverage is %f%%, but needs to be %s%f%%',
                    'Coverage is %s%%, but needs to be %s%s%%',
                    self::formatNumber($coverage),
                    ($target < 100) ? 'at-least ' : '',
                    self::formatNumber($target)
                ),
                self::EXIT_COVERAGE
            );
        }
    }
}
