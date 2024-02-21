<?php


namespace MeeeetDev\CashierConnect\Concerns;

use Stripe\Account;
use Stripe\ApplePayDomain;
use Stripe\Exception\ApiErrorException;
use MeeeetDev\CashierConnect\Models\ConnectMapping;
use MeeeetDev\CashierConnect\Exceptions\AccountNotFoundException;
use MeeeetDev\CashierConnect\Exceptions\AccountAlreadyExistsException;

/**
 * Manages a Stripe account for the model.
 *
 * @package MeeeetDev\CashierConnect\Concerns
 */
trait ManagesApplePayDomain
{

    public function addApplePayDomain($domain)
    {
        $this->assertAccountExists();
        return ApplePayDomain::create(['domain_name' => $domain], $this->stripeAccountOptions([], true));
    }

    public function getApplePayDomains()
    {
        $this->assertAccountExists();
        return ApplePayDomain::all([], $this->stripeAccountOptions([], true));
    }
}
