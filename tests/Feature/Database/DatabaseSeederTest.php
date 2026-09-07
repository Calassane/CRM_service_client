<?php

namespace Tests\Feature\Database;

use App\Models\CustomerCall;
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
        $this->assertDatabaseCount('users', 4);
        $this->assertDatabaseCount('clients', 15);
        $this->assertDatabaseCount('reservations', 25);
        $this->assertDatabaseCount('tags', 6);
        $this->assertDatabaseCount('customer_calls', 75);
        $this->assertTrue(Reservation::query()->whereDoesntHave('client')->doesntExist());
        $this->assertTrue(CustomerCall::query()->whereDoesntHave('client')->doesntExist());
        $this->assertTrue(CustomerCall::query()->whereDoesntHave('agent')->doesntExist());
        $this->assertTrue(
            CustomerCall::query()
                ->join('reservations', 'reservations.id', '=', 'customer_calls.reservation_id')
                ->whereColumn('customer_calls.client_id', '!=', 'reservations.client_id')
                ->doesntExist(),
        );
        $this->assertGreaterThan(0, CustomerCall::query()->has('tags')->count());
    }

    public function test_database_seeder_can_be_run_more_than_once_without_duplicates(): void
    {
        $this->seed();
        $this->seed();

        $this->assertDatabaseCount('users', 4);
        $this->assertDatabaseCount('clients', 15);
        $this->assertDatabaseCount('reservations', 25);
        $this->assertDatabaseCount('tags', 6);
        $this->assertDatabaseCount('customer_calls', 75);
    }
}
