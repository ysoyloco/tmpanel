<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Subject;

class SubjectPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Subject $subject): bool
    {
        return $user->isAdmin() || $user->id === $subject->user_id;
    }
}
