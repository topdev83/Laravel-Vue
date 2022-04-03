<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    /** @var User  */
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    /**
     *
     * @test
     */
    public function test_can_create_user()
    {
        $user = factory(User::class)
            ->make();
        $this->actingAs($this->user)
            ->postJson(
                '/api/users',
                $user->toArray()
            )
            ->assertSuccessful();

        $this->assertDatabaseHas('users', [
            'email' => $user->email,
            'companyname' => $user->companyname,
        ]);
    }

    /**
     * Test User can get list of users
     *
     * @test
     */
    public function test_can_list_users()
    {
        $this->actingAs($this->user)
            ->getJson('/api/users')
            ->assertStatus(200)
            ->assertJsonCount(User::query()->count(),'users');

    }

    /**
     *
     * @test
     */
    public function can_get_specific_user()
    {
        $this->actingAs($this->user)
            ->getJson('/api/users/'. $this->user->id)
            ->assertSuccessful()
            ->assertJson([
                'user' => $this->user->toArray()
            ]);
    }

    /**
     *
     * @test
     */
    public function can_update_specific_user()
    {
        $this->actingAs($this->user)
            ->patchJson('/api/users/'. $this->user->id,[
                'companyname' => 'updated name'
            ])
            ->assertSuccessful();

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'companyname' => 'updated name',
        ]);
    }

    /**
     * @test
     */
    public function can_delete_specified_user()
    {
        $this->actingAs($this->user)
            ->deleteJson('/api/users/'. $this->user->id)
            ->assertSuccessful();

        $this->assertDatabaseMissing('users', [
            'id' => $this->user->id,
        ]);
    }
}
