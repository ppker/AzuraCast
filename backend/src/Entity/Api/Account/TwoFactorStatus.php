<?php

declare(strict_types=1);

namespace App\Entity\Api\Account;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Api_Account_TwoFactorStatus',
    required: [
        'two_factor_enabled',
    ],
    type: 'object'
)]
final readonly class TwoFactorStatus
{
    public function __construct(
        #[OA\Property(
            description: 'The current two-factor status for this account.',
        )]
        public bool $two_factor_enabled,
    ) {
    }
}
