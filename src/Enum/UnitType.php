<?php

namespace App\Enum;

use OpenApi\Attributes\Schema;

#[Schema(
    schema: 'UnitType',
)]
enum UnitType: string
{
    case NONE = 'none';
    case GRAM = 'gram';
    case MILLILITRE = 'millilitre';
    case AMOUNT = 'amount';
}
