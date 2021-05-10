<?php

declare(strict_types=1);

namespace MezzioInstallerTest;

use MezzioInstaller\OptionalPackages;
use ReflectionClass;

class RemoveDevDependenciesTest extends OptionalPackagesTestCase
{
    /** @var string[] */
    private $standardDependencies = [
        'php',
        'roave/security-advisories',
        'mezzio/mezzio',
        'mezzio/mezzio-helpers',
        'laminas/laminas-stdlib',
        'phpunit/phpunit',
    ];

    /** @var string[] List of dev dependencies intended for removal. */
    private $devDependencies;

    /** @var OptionalPackages */
    private $installer;

    protected function setUp(): void
    {
        parent::setUp();

        // Get list of dev dependencies expected to remove from
        // OptionalPackages class
        $r                     = new ReflectionClass(OptionalPackages::class);
        $props                 = $r->getDefaultProperties();
        $this->devDependencies = $props['devDependencies'];

        $this->installer = $this->createOptionalPackages();
    }

    public function testComposerHasAllDependencies()
    {
        $this->assertPackages($this->standardDependencies, $this->installer);
        $this->assertPackages($this->devDependencies, $this->installer);
    }

    public function testDevDependenciesAreRemoved()
    {
        // Remove development dependencies
        $this->installer->removeDevDependencies();

        $this->assertPackages($this->standardDependencies, $this->installer);
        $this->assertNotPackages($this->devDependencies, $this->installer);
    }
}
