<?php
/**
 * @see       https://github.com/zendframework/zend-expressive-skeleton for the canonical source repository
 * @copyright Copyright (c) 2017 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-expressive-skeleton/blob/master/LICENSE.md New BSD License
 */

namespace ExpressiveInstallerTest;

use ExpressiveInstaller\OptionalPackages;
use Prophecy\Argument;

class RequestInstallTypeTest extends OptionalPackagesTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->installer = $this->createOptionalPackages();
    }

    public function installSelections()
    {
        return [
            OptionalPackages::INSTALL_MINIMAL => ['1', OptionalPackages::INSTALL_MINIMAL],
            OptionalPackages::INSTALL_FLAT    => ['2', OptionalPackages::INSTALL_FLAT],
            OptionalPackages::INSTALL_MODULAR => ['3', OptionalPackages::INSTALL_MODULAR],
        ];
    }

    /**
     * @dataProvider installSelections
     *
     * @param string $selection
     * @param string $expected
     */
    public function testRequestInstallTypeReturnsExpectedConstantValue($selection, $expected)
    {
        $this->io
            ->ask(Argument::that([__CLASS__, 'assertQueryPrompt']), '2')
            ->willReturn($selection);

        $this->assertSame($expected, $this->installer->requestInstallType());
    }

    public function testWillContinueToPromptUntilValidAnswerPresented()
    {
        $io     = $this->io;
        $prompt = 'What type of installation would you like?';
        $tries  = mt_rand(1, 10);

        // Handle a call to ask() by looping $tries times
        $handle = function () use ($io, &$tries, &$handle) {
            if (0 === $tries) {
                // Valid choice to complete the loop
                return '1';
            }

            // Otherwise, ask again.
            $tries -= 1;
            $io->ask(Argument::that([__CLASS__, 'assertQueryPrompt']), '2')->will($handle);
            return 'n';
        };

        $this->io
            ->ask(Argument::that([__CLASS__, 'assertQueryPrompt']), '2')
            ->will($handle);

        $this->io
            ->write(Argument::containingString('Invalid answer'))
            ->shouldBeCalledTimes($tries);

        $this->assertSame(OptionalPackages::INSTALL_MINIMAL, $this->installer->requestInstallType());
        $this->assertEquals(0, $tries);
    }

    public static function assertQueryPrompt($value)
    {
        $value = is_array($value) ? array_shift($value) : $value;

        self::assertThat(
            false !== strstr($value, 'What type of installation would you like?'),
            self::isTrue(),
            'Unexpected prompt value'
        );

        return true;
    }
}
