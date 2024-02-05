<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Exception;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseMigrations;

    private Generator $faker;

    protected static $customer = [
        'name' => 'John',
        'email' => 'john@mail.com',
        'is_admin' => false
    ];

    protected static $customerTwo = [
        'name' => 'Jack',
        'email' => 'jack@mail.com',
        'is_admin' => false
    ];

    protected static $admin = [
        'name' => 'Admin',
        'email' => 'admin@mail.com',
        'is_admin' => true
    ];

    protected static $boardroomActive = [
        'name' => 'Boardroom 1',
        'active' => true
    ];

    protected static $boardroomDeactivated = [
        'name' => 'Boardroom 2',
        'active' => false
    ];

    public function setUp(): void
    {
        parent::setUp();

        $this->faker = Factory::create();

        Artisan::call('migrate:refresh');
    }

    public function __get($key)
    {
        if ($key === 'faker')
            return $this->faker;

        throw new Exception('Unknown Key Requested');
    }
}
