<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Constraint\ExtendsClass;
use Tailors\PHPUnit\Examples\Inheritance\ExampleTrait;

/**
 * @small
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @coversNothing
 */
#[CoversClass(ExtendsClassTrait::class)]
final class ExtendsClassTraitTest extends TestCase
{
    use ExtendsClassTrait;

    public static function provExtendsClass(): array
    {
        $template = 'Failed asserting that %s does not extend class %s.';

        return [
            'ExtendsClassTraitTest.php:'.__LINE__ => [
                'class'   => \Exception::class,
                'subject' => \ErrorException::class,
                'message' => sprintf($template, \ErrorException::class, \Exception::class),
            ],

            'ExtendsClassTraitTest.php:'.__LINE__ => [
                'class'   => \Exception::class,
                'subject' => new \ErrorException(),
                'message' => sprintf($template, 'object '.\ErrorException::class, \Exception::class),
            ],
        ];
    }

    public static function provNotExtendsClass(): array
    {
        $template = 'Failed asserting that %s extends class %s.';

        return [
            'ExtendsClassTraitTest.php:'.__LINE__ => [
                'class'   => \Error::class,
                'subject' => \ErrorException::class,
                'message' => sprintf($template, \ErrorException::class, \Error::class),
            ],
            'ExtendsClassTraitTest.php:'.__LINE__ => [
                'class'   => \Error::class,
                'subject' => new \ErrorException(),
                'message' => sprintf($template, 'object '.\ErrorException::class, \Error::class),
            ],
            'ExtendsClassTraitTest.php:'.__LINE__ => [
                'class'   => \Error::class,
                'subject' => 'lorem ipsum',
                'message' => sprintf($template, "'lorem ipsum'", \Error::class),
            ],
            'ExtendsClassTraitTest.php:'.__LINE__ => [
                'class'   => \Error::class,
                'subject' => 123,
                'message' => sprintf($template, '123', \Error::class),
            ],
        ];
    }

    /**
     * @param mixed $subject
     */
    #[DataProvider('provExtendsClass')]
    public function testAssertExtendsClassSucceeds(string $class, $subject, string $message): void
    {
        self::assertExtendsClass($class, $subject);
    }

    /**
     * @param mixed $subject
     */
    #[DataProvider('provNotExtendsClass')]
    public function testAssertExtendsClassFails(string $class, $subject, string $message): void
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage($message);

        self::assertExtendsClass($class, $subject);
    }

    /**
     * @param mixed $subject
     */
    #[DataProvider('provNotExtendsClass')]
    public function testAssertNotExtendsClassSucceeds(string $class, $subject, string $message): void
    {
        self::assertNotExtendsClass($class, $subject);
    }

    /**
     * @param mixed $subject
     */
    #[DataProvider('provExtendsClass')]
    public function testAssertNotExtendsClassFails(string $class, $subject, string $message): void
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage($message);

        self::assertNotExtendsClass($class, $subject);
    }

    /**
     * @param mixed $subject
     */
    #[DataProvider('provExtendsClass')]
    public function testExtendsClass(string $class, $subject, string $message): void
    {
        self::assertThat($subject, self::extendsClass($class));
    }

    /**
     * @param mixed $subject
     */
    #[DataProvider('provNotExtendsClass')]
    public function testNotExtendsClass(string $class, $subject, string $message): void
    {
        self::assertThat($subject, self::logicalNot(self::extendsClass($class)));
    }

    public static function provExtendsClassThrowsInvalidArgumentException(): array
    {
        $template = 'Argument 1 passed to %s::create() must be a class-string';

        return [
            'ExtendsClassTraitTest.php:'.__LINE__ => [
                'argument' => 'non-class string',
                'message'  => sprintf($template, ExtendsClass::class),
            ],

            'ExtendsClassTraitTest.php:'.__LINE__ => [
                'argument' => \Throwable::class,
                'message'  => sprintf($template, ExtendsClass::class),
            ],

            'ExtendsClassTraitTest.php:'.__LINE__ => [
                'argument' => ExampleTrait::class,
                'message'  => sprintf($template, ExtendsClass::class),
            ],
        ];
    }

    #[DataProvider('provExtendsClassThrowsInvalidArgumentException')]
    public function testExtendsClassThrowsInvalidArgumentException(string $argument, string $message): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage($message);

        self::extendsClass($argument);
    }
}

// vim: syntax=php sw=4 ts=4 et:
