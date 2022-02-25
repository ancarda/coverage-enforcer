<?php

declare(strict_types=1);

namespace Ancarda\CodeCoverage;

use RuntimeException;
use SimpleXMLElement;
use libXMLError;

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

        // Restore the LibXML error state
        $usePhpErrors = libxml_use_internal_errors(true);
        $file = simplexml_load_file($filePath);
        libxml_use_internal_errors($usePhpErrors);

        if ($file === false) {
            // Since $file is false, there is garanteed to be an error
            /** @var libXMLError $lastError */
            $lastError = libxml_get_last_error();

            throw new RuntimeException(
                sprintf('Parse Error: %s', $lastError->message),
                self::EXIT_MISC
            );
        }

        return $file;
    }

    /**
     * Pretty format a float
     *
     * This function formats a float like an integer if it's fractional part
     * is zero, whereas formats it with two decimal places for a non-zero
     * fractional part.
     */
    private static function formatNumber(float $num): string
    {
        if ($num == intval($num)) {
            return (string) intval($num);
        }

        return sprintf('%.2f', $num);
    }

    public static function assertStatementsCovered(string $filePath, float $target): void
    {
        $clover = self::loadXML($filePath);

        $stmtCovered = (int) $clover->project->metrics['coveredstatements'];
        $stmtCount   = (int) $clover->project->metrics['statements'];
        $coverage = ($stmtCovered / $stmtCount) * 100;

        if ($target > $coverage) {
            throw new RuntimeException(
                sprintf(
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
