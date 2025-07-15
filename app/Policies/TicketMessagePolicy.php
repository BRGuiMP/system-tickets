<?php

namespace App\Policies;

use App\Models\TicketMessage;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TicketMessagePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TicketMessage $ticketMessage): bool
    {
        return $user->tipo === 'atendente' || 
               $ticketMessage->ticket->usuario_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TicketMessage $ticketMessage): bool
    {
        return $user->id === $ticketMessage->autor_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TicketMessage $ticketMessage): bool
    {
        return $user->tipo === 'atendente' || $user->id === $ticketMessage->autor_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TicketMessage $ticketMessage): bool
    {
        return $user->tipo === 'atendente';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TicketMessage $ticketMessage): bool
    {
        return $user->tipo === 'atendente';
    }
}
