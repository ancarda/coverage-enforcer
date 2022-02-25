<?php

declare(strict_types=1);

namespace Tests;

use Ancarda\CodeCoverage\Auditor;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class AudiorTest extends TestCase
{
    public function testAuditorWithPerfectCoverage(): void
    {
        $filePath = __DIR__ . '/files/100-percent-coverage.xml';

        Auditor::assertStatementsCovered($filePath, 100);
        // No news is good news
    }

    public function testAuditorFailsWithFractionalCoverage(): void
    {
        $filePath = __DIR__ . '/files/uneven-coverage.xml';

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Coverage is 97.06%, but needs to be at-least 97.50%');

        Auditor::assertStatementsCovered($filePath, 97.5);
    }

    public function testAuditorFailsWithNoCodeCoverage(): void
    {
        $filePath = __DIR__ . '/files/0-percent-coverage.xml';

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Coverage is 0%, but needs to be 100%');

        Auditor::assertStatementsCovered($filePath, 100);
    }

    public function testAuditorThrowsIfTheFileIsNotValidXML(): void
    {
        $filePath = __DIR__ . '/files/parse-failure.xml';

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Parse Error');

        Auditor::assertStatementsCovered($filePath, 100);
    }

    public function testAuditorThrowsIfTheFileDoesNotExist(): void
    {
        $filePath = __DIR__ . '/files/no-such-file';

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File ' . $filePath . ' does not exist');

        Auditor::assertStatementsCovered($filePath, 0);
    }

    public function testWillResetLibXmlErrorHandling(): void
    {
        $filePath = __DIR__ . '/files/100-percent-coverage.xml';

        libxml_use_internal_errors(true);
        Auditor::assertStatementsCovered($filePath, 80);
        self::assertTrue(libxml_use_internal_errors());

        libxml_use_internal_errors(false);
        Auditor::assertStatementsCovered($filePath, 90);
        self::assertFalse(libxml_use_internal_errors());
    }
}
