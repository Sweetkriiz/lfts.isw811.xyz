<?php

namespace App\Policies;

use App\Models\Idea;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class IdeaPolicy
{
    public function view(User $user, Idea $idea): bool
    {
        return $user->is($idea->user);
    }

     public function create(User $user): bool
    {
        return $user->isAdmin();
    }
}
