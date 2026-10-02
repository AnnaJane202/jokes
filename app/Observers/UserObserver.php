<?php

namespace App\Observers;

use App\Models\Role;
use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $role = Role::firstOrCreate(['title' => 'user']);
        $user->roles()->attach($role->id);
    }

//    protected function getDefaultRoleId()
//    {
//        return 2; // ID роли 'user'
//    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }

    public function deleting(User $user): void
    {
        $user->roles()->detach();
        $user->likes()->delete();
        $user->comments()->delete();
        $user->violations()->delete();
        $user->reports()->delete();
        // $user->posts()->delete(); // ← только если хотите удалять посты
    }
}
