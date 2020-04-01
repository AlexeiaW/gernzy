<?php

namespace Gernzy\Server\GraphQL\Queries;

use \App;
use Gernzy\Server\Exceptions\GernzyException;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Checkout
{
    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[]  $args The arguments that were passed into the field.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     */
    public function getStripeSecret($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $sessionService = App::make('Gernzy\SessionService');
        $cartService = App::make('Gernzy\ServerService');


        if (empty($sessionService->get())) {
            throw new GernzyException(
                'The session does not exist.',
                'Please make sure the token is valid.'
            );
        }

        $cartTotal = $cartService->getCartTotal();
        $stripeService = App::make('Stripe\StripeService');
        $secret = $stripeService->getSecret($cartTotal, 'usd');

        return $secret;
    }
}
