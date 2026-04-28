<?php

use App\Supports\Auth;

Auth::setResolver(function (int $id) {
    return db()
    ->table('users')
    ->select('id', 'name', 'email', 'username')
    ->find($id);
});