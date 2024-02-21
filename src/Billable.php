<?php


namespace MeeeetDev\CashierConnect;


use Laravel\Cashier\Cashier;
use MeeeetDev\CashierConnect\Concerns\CanCharge;
use MeeeetDev\CashierConnect\Concerns\ManagesPayout;
use MeeeetDev\CashierConnect\Concerns\ManagesPerson;
use MeeeetDev\CashierConnect\Concerns\ManagesAccount;
use MeeeetDev\CashierConnect\Concerns\ManagesBalance;
use MeeeetDev\CashierConnect\Concerns\ManagesTransfer;
use MeeeetDev\CashierConnect\Concerns\ManagesAccountLink;
use MeeeetDev\CashierConnect\Concerns\ManagesApplePayDomain;
use MeeeetDev\CashierConnect\Concerns\ManagesConnectCustomer;
use MeeeetDev\CashierConnect\Concerns\ManagesConnectProducts;
use MeeeetDev\CashierConnect\Concerns\ManagesConnectSubscriptions;

/**
 * Added to models for Stripe Connect functionality.
 *
 * @package MeeeetDev\CashierConnect
 */
trait Billable
{

    use ManagesAccount;
    use ManagesAccountLink;
    use ManagesPerson;
    use ManagesBalance;
    use ManagesTransfer;
    use ManagesConnectCustomer;
    use ManagesConnectSubscriptions;
    use ManagesConnectProducts;
    use CanCharge;
    use ManagesPayout;
    use ManagesApplePayDomain;


    /**
     * The default Stripe API options for the current Billable model.
     *
     * @param array $options
     * @param bool $sendAsAccount
     * @return array
     */
    public function stripeAccountOptions(array $options = [], bool $sendAsAccount = false): array
    {
        // Include Stripe Account id if present. This is so we can make requests on the behalf of the account.
        // Read more: https://stripe.com/docs/api/connected_accounts?lang=php.
        if ($sendAsAccount && $this->hasStripeAccount()) {
            $options['stripe_account'] = $this->stripeAccountId();
        }

        // Workaround for Cashier 12.x 
        if (version_compare(Cashier::VERSION, '12.15.0', '<=')) {
            return array_merge(Cashier::stripeOptions($options));
        }

        $stripeOptions = Cashier::stripe($options);

        return array_merge($options, [
            'api_key' => $stripeOptions->getApiKey()
        ]);
    }

    public function establishTransferCurrency($providedCurrency = null)
    {
        if ($providedCurrency) {
            return $providedCurrency;
        }

        if ($this->defaultCurrency) {
            return $this->defaultCurrency;
        }

        return config('cashierconnect.currency');
    }
}
