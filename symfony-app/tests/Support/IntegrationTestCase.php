<?php

declare(strict_types=1);

namespace App\Tests\Support;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class IntegrationTestCase extends KernelTestCase
{
    use DatabaseTrait;

    protected function setUp(): void
    {
        parent::setUp();
    }
}
