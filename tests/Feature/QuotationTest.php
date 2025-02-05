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

    private $user;
    private $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test user and get token
        $this->user = User::factory()->create();
        $response = $this->postJson('/api/login', [
            'email' => $this->user->email,
            'password' => 'password'
        ]);

        $this->token = $response->json('access_token');

        // Seed required currencies
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
            'ages' => '25,35',
            'currency_id' => 'EUR',
            'start_date' => '2024-03-15',
            'end_date' => '2024-03-20'
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'quotation_id',
                    'total',
                    'currency_id',
                ]
            ]);
    }

    public function test_cannot_create_quotation_without_auth(): void
    {
        // First logout
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/logout');

        // Then try to create quotation without token
        $response = $this->postJson('/api/quotations', [
            'ages' => '25,35',
            'currency_id' => 'EUR',
            'start_date' => '2024-03-15',
            'end_date' => '2024-03-20'
        ]);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }

    public function test_can_list_quotations(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson('/api/quotations');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'total',
                    'currency',
                    'start_date',
                    'end_date',
                    'created_at'
                ]
            ]);
    }

    public function test_validates_required_fields(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/quotations', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['ages', 'currency_id', 'start_date', 'end_date']);
    }
}
