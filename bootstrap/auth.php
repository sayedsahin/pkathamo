<?php

use App\Models\User;
use App\Supports\Auth;

Auth::setResolver(function (int $id) {
    $user = new User();
    return $user->find($id);
});