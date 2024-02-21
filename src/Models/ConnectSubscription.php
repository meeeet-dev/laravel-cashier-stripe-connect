<?php

namespace MeeeetDev\CashierConnect\Models;

use Stripe\Subscription;
use Illuminate\Database\Eloquent\Model;
use Stripe\Exception\ApiErrorException;
use MeeeetDev\CashierConnect\StripeEntity;

class ConnectSubscription extends Model
{
    use StripeEntity;

    protected $guarded = [];
    protected $table = 'connected_subscriptions';

    public function items()
    {
        return $this->hasMany(ConnectSubscriptionItem::class, 'connected_subscription_id', 'id');
    }

    /**
     * Gets the stripe subscription for the model
     * @return Subscription
     * @throws ApiErrorException
     */
    public function asStripeSubscription()
    {
        return Subscription::retrieve($this->stripe_id, $this->stripeAccountOptions([], $this->stripe_account_id));
    }
}
