<?php

namespace App\Policies;

use App\Models\TestSuite;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TestSuitePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TestSuite  $testSuite
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, TestSuite $testSuite)
    {
        return $user->hasTeamPermission($testSuite->team, 'read');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasTeamPermission($user->currentTeam, 'create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TestSuite  $testSuite
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, TestSuite $testSuite)
    {
        return $user->hasTeamPermission($testSuite->team, 'update');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TestSuite  $testSuite
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, TestSuite $testSuite)
    {
        return $user->hasTeamPermission($testSuite->team, 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TestSuite  $testSuite
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, TestSuite $testSuite)
    {
        return $user->hasTeamPermission($testSuite->team, 'delete');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TestSuite  $testSuite
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, TestSuite $testSuite)
    {
        return $user->hasTeamPermission($testSuite->team, 'admin');
    }
}
