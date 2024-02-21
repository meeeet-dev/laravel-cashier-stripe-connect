<?php


namespace MeeeetDev\CashierConnect\Concerns;

use Carbon\Carbon;
use Stripe\Payout;
use Illuminate\Support\Str;
use Stripe\Exception\ApiErrorException;
use MeeeetDev\CashierConnect\Exceptions\AccountNotFoundException;

/**
 * Manages payout for the Stripe connected account.
 *
 * @package MeeeetDev\CashierConnect\Concerns
 */
trait ManagesPayout
{

    /**
     * Pay
     *
     * @param int $amount Amount to be transferred to your bank account or debit card.
     * @param Carbon $arrival Date the payout is expected to arrive in the bank.
     * @param string $currency Three-letter ISO currency code, in lowercase. Must be a supported currency.
     * @param array $options
     * @return Payout
     * @throws AccountNotFoundException|ApiErrorException
     */
    public function payoutStripeAccount(int $amount, Carbon $arrival, string $currency = 'USD', array $options = []): Payout
    {
        $this->assertAccountExists();

        // Create the payload for payout.
        $options = array_merge($options, [
            'amount' => $amount,
            'currency' => Str::lower($currency),
            'arrival_date' => $arrival->timestamp,
        ]);

        return Payout::create($options, $this->stripeAccountOptions([], true));
    }
}
