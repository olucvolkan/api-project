<?php

namespace Tests\Feature;

use App\Models\AgeLoad;
use App\Models\Currency;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuotationTest extends TestCase
{
    use RefreshDatabase;

    private string $token;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test user and get token
        $this->user = User::factory()->create();
        $this->token = auth()->login($this->user);

        // Seed required data
        Currency::create([
            'code' => 'EUR',
            'name' => 'Euro',
            'symbol' => '€'
        ]);

        // Create all age loads
        AgeLoad::create(['from_range' => 18, 'max_range' => 30, 'load' => 0.6]);
        AgeLoad::create(['from_range' => 31, 'max_range' => 40, 'load' => 0.7]);
        AgeLoad::create(['from_range' => 41, 'max_range' => 50, 'load' => 0.8]);
        AgeLoad::create(['from_range' => 51, 'max_range' => 60, 'load' => 0.9]);
        AgeLoad::create(['from_range' => 61, 'max_range' => 70, 'load' => 1.0]);
    }

    public function test_can_create_quotation(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/quotations', [
            'ages' => '25,30',
            'currency_id' => 1,
            'start_date' => '2024-03-15',
            'end_date' => '2024-03-20'
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'total',
                    'currency_id',
                    'quotation_id'
                ]
            ]);
    }

    public function test_cannot_create_quotation_with_invalid_age(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/quotations', [
            'ages' => '15,80', // Invalid ages
            'currency_id' => 1,
            'start_date' => '2024-03-15',
            'end_date' => '2024-03-20'
        ]);

        $response->assertStatus(422);
    }

    public function test_can_list_quotations(): void
    {
        // First create a quotation
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/quotations', [
            'ages' => '25,30',
            'currency_id' => 1,
            'start_date' => '2024-03-15',
            'end_date' => '2024-03-20'
        ]);

        // Then get the list
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson('/api/quotations');

        $response->assertStatus(200)
            ->assertJsonStructure(
                [
                    '*' => [
                        'total',
                        'currency_id',
                        'quotation_id'
                    ]
                ]
            );
    }

    public function test_unauthorized_user_cannot_access_quotations(): void
    {
        // First logout the user
        auth()->logout();

        // Try to access quotations without token
        $response = $this->getJson('/api/quotations');

        $response->assertStatus(401);
    }
}
