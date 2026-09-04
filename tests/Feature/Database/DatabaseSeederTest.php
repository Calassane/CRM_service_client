<?php

namespace Tests\Feature\Database;

use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_creates_the_mvp_demo_data(): void
    {
        $this->seed();

        $this->assertDatabaseHas('users', [
            'email' => 'demo@bollirental.africa',
        ]);
        $this->assertDatabaseCount('clients', 15);
        $this->assertDatabaseCount('reservations', 25);
        $this->assertTrue(Reservation::query()->whereDoesntHave('client')->doesntExist());
    }
}
