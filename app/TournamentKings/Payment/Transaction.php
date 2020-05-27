<?php

namespace App\TournamentKings\Payment;

class Transaction
{
    public $transactionId          = null;
    public $transactionType        = 'square';
    public $amount                 = 0.0;
    /*
     * Any other time, we'd just assign an instance of the model, but the queueing system
     * will then want to serialize such data, and this will restrict what you can define in
     * the model class.
     */
    public $userId                 = 0;
    public $transactableModelId    = 0;
    public $transactableModelClass = '';

    // Additional data relevant to the particular transaction
    public $data = [];
}
