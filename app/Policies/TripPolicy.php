<?php

namespace App\Policies;

use App\Models\Trip;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class TripPolicy
{
    use HandlesAuthorization;

    public function checkRoles(User $user, array $roles): bool
    {
        return in_array($user->role, $roles);
    }


    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return Response|bool
     */
    public function viewAny(User $user): Response|bool
    {
        $allowedRoles = ['user', 'guide', 'admin', 'superAdmin'];
        return in_array($user->role, $allowedRoles);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Trip $trip
     * @return Response|bool
     */
    public function view(User $user, Trip $trip): Response|bool
    {
        $allowedRoles = ['user', 'guide', 'admin', 'superAdmin'];
        return in_array($user->role, $allowedRoles);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return Response|bool
     */
    public function create(User $user)
    {
        $allowedRoles = [ 'admin', 'superAdmin'];
        return in_array($user->role, $allowedRoles);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Trip $trip
     * @return Response|bool
     */
    public function update(User $user, Trip $trip)
    {
        $allowedRoles = [ 'admin', 'superAdmin'];
        return in_array($user->role, $allowedRoles);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Trip $trip
     * @return Response|bool
     */
    public function delete(User $user, Trip $trip): Response|bool
    {
        $allowedRoles = [ 'admin', 'superAdmin'];
        return in_array($user->role, $allowedRoles);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Trip $trip
     * @return Response|bool
     */
    public function restore(User $user, Trip $trip): Response|bool
    {
        $allowedRoles = [ 'admin', 'superAdmin'];
        return in_array($user->role, $allowedRoles);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Trip $trip
     * @return Response|bool
     */
    public function forceDelete(User $user, Trip $trip): Response|bool
    {
        $allowedRoles = [ 'admin', 'superAdmin'];
        return in_array($user->role, $allowedRoles);
    }
}
