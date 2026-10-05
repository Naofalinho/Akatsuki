<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guests are redirected to login when they visit the admin dashboard', function () {
    $response = $this->get(route('admin.dashboard'));
    $response->assertRedirect(route('login'));
});

test('customers can use the storefront and personal orders but not the admin area', function () {
    $user = User::factory()->create(['role' => 'user']);
    $this->actingAs($user);

    $this->get(route('home'))
        ->assertOk()
        ->assertDontSee(route('admin.dashboard'), false);
    $this->get(route('mes-commandes'))->assertOk();
    $this->get(route('admin.dashboard'))->assertForbidden();
    $this->get(route('admin.produits'))->assertForbidden();
});

test('admins can visit the storefront and the admin dashboard', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee(route('admin.dashboard'), false);
    $this->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('Tableau de bord');
});

test('customers are redirected to the storefront after login', function () {
    $user = User::factory()->create(['role' => 'user']);

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect(route('home', absolute: false));
});
