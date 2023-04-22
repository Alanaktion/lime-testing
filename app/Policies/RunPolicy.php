<?php

namespace App\Policies;

use App\Models\Run;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RunPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Run $run)
    {
        return $user->id == $run->user_id
            || $user->current_team_id == $run->testSuite->team_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasTeamPermission($user->currentTeam, 'test:run');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Run $run)
    {
        return $user->id == $run->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Run $run)
    {
        return $user->id == $run->user_id;
    }
}
