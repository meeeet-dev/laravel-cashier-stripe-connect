<?php

namespace MeeeetDev\CashierConnect\Concerns;

use Stripe\Charge;
use Stripe\Balance;
use Stripe\Transfer;
use Stripe\PaymentIntent;
use Illuminate\Support\Str;
use Stripe\Exception\ApiErrorException;
use MeeeetDev\CashierConnect\Exceptions\AccountNotFoundException;

/**
 * Manages Customers that belong to a connected account (not the platform account)
 *
 * @package MeeeetDev\CashierConnect\Concerns
 */
trait ManagesConnectCustomer
{

    /* TODO - Not entirely sure what needs to be done from the merchant's perspective in regards to customers. */
    /* TODO - I suspect getting one and many of their customer records and corresponding parent models, need to assess how intensive that is on database */

}
