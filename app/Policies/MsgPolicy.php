<?php

namespace App\Policies;

use App\User;
use App\MsgIn;
use Illuminate\Auth\Access\HandlesAuthorization;

class MsgPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the msgIn.
     *
     * @param  \App\User  $user
     * @param  \App\MsgIn  $msgIn
     * @return mixed
     */
    public function view(User $user, MsgIn $msg)
    {
        if ($user->id == $msg->receiver || $user->id == $msg->sender) {
            return TRUE;
        }
    }

    /**
     * Determine whether the user can create msgIns.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the msgIn.
     *
     * @param  \App\User  $user
     * @param  \App\MsgIn  $msgIn
     * @return mixed
     */
    public function update(User $user, MsgIn $msgIn)
    {
        //
    }

    /**
     * Determine whether the user can delete the msgIn.
     *
     * @param  \App\User  $user
     * @param  \App\MsgIn  $msgIn
     * @return mixed
     */
    public function delete(User $user, MsgIn $msgIn)
    {
        //
    }
}
