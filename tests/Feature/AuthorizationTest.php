<?php

namespace Tests\Feature;

use App\Models\Adoption;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_adopt_another_adopted_pet()
    {
        $user = User::factory()->create();

        $adoptedByUser = User::factory()->create();
        $adoption = Adoption::factory()->create([
            'adopted_by' => $adoptedByUser
        ]);

        $this->followingRedirects()
            ->actingAs($user)
            ->post("/adoptions/$adoption->id/adopt")
            ->assertForbidden();
    }

    public function test_guest_cannot_adopt_at_all()
    {
        $adoption = Adoption::factory()->create();
        $this->assertGuest();
        $this->followingRedirects()
            ->post("/adoptions/$adoption->id/adopt")
            ->assertForbidden();
    }

    public function test_guests_can_visit_adoption_page()
    {
        $adoption = Adoption::factory()->create();
        $this->followingRedirects()
            ->get("/adoptions/$adoption->id")
            ->assertOk();
    }
}
