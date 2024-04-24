<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\StaticAnalysis\HappyPath\AssertNotImplementsInterface;

use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

class Assert extends \PHPUnit\Framework\Assert
{
    use ImplementsInterfaceTrait;
}

/**
 * @throws ExpectationFailedException
 * @throws InvalidArgumentException
 * @throws \Tailors\PHPUnit\InvalidArgumentException
 */
function consume(string $expected, string $actual): string
{
    Assert::assertNotImplementsInterface($expected, $actual);

    return $actual;
}

// vim: syntax=php sw=4 ts=4 et:
