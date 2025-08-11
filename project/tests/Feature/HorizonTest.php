<?php

use App\Models\User;

describe('test horizon page', function () {
    it('admin can horizon dashboard', function () {
        $admin = User::where('login', 'admin')->first();
        $this->actingAs($admin);
        $response = $this->get('/horizon');
        expect($response->getStatusCode())->toBe(200);
    });

    it('user restricted to horizon', function () {
        $user = User::where('login', 'user')->first();
        $this->actingAs($user);
        $response = $this->get('/horizon');
        expect($response->getStatusCode())->toBe(403);
    });
});
