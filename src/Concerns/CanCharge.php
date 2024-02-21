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
 * Manages balance for the Stripe connected account.
 *
 * @package MeeeetDev\CashierConnect\Concerns
 */
trait CanCharge
{

    /**
     * Creates a direct charge
     * @param int $amount
     * @param string|null $currencyToUse
     * @param array $options
     * @return PaymentIntent
     * @throws AccountNotFoundException
     * @throws ApiErrorException
     */
    public function createDirectCharge(int $amount, string $currencyToUse = null, array $options = []): PaymentIntent
    {

        $this->assertAccountExists();

        // Create payload for the transfer.
        $options = array_merge([
            'amount' => $amount,
            'currency' => Str::lower($this->establishTransferCurrency($currencyToUse)),
        ], $options);

        // APPLY PLATFORM FEE COMMISSION - SET THIS AGAINST THE MODEL
        if (isset($this->commission_type) && isset($this->commission_rate)) {
            if ($this->commission_type === 'percentage') {
                $options['application_fee_amount'] = round($this->calculatePercentageFee($amount));
            } else {
                $options['application_fee_amount'] = round($this->commission_rate);
            }
        }


        return PaymentIntent::create($options, $this->stripeAccountOptions([], true));
    }

    public function createDestinationCharge(int $amount, string $currencyToUse = null, array $options = [], bool $onBehalfOf = false): PaymentIntent
    {

        $this->assertAccountExists();

        // Create payload for the transfer.
        $options = array_merge([
            'amount' => $amount,
            'transfer_data' => [
                'destination' => $this->stripeAccountId()
            ],
            'currency' => Str::lower($this->establishTransferCurrency($currencyToUse)),
        ], $options);

        if ($onBehalfOf) {
            $options['on_behalf_of'] = $this->stripeAccountId();
        }

        // APPLY PLATFORM FEE COMMISSION - SET THIS AGAINST THE MODEL
        if (isset($this->commission_type) && isset($this->commission_rate)) {
            if ($this->commission_type === 'percentage') {
                $options['application_fee_amount'] = round($this->calculatePercentageFee($amount), 2);
            } else {
                $options['application_fee_amount'] = round($this->commission_rate, 2);
            }
        }

        return PaymentIntent::create($options, $this->stripeAccountOptions());
    }



    private function calculatePercentageFee($amount)
    {
        if ($this->commission_rate < 100) {
            return ($this->commission_rate / 100) * $amount;
        } else {
            throw new \Exception('You cannot charge more than 100% fee.');
        }
    }
}
