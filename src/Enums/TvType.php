<?php

namespace Astrotomic\Tmdb\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self SCRIPTED()
 * @method static self REALITY()
 * @method static self MINISERIES()
 * @method static self DOCUMENTARY()
 * @method static self TALK_SHOW()
 * @method static self NEWS()
 */
class TvType extends Enum
{
    protected static function values(): array
    {
        return [
            'SCRIPTED' => 'Scripted',
            'REALITY' => 'Reality',
            'MINISERIES' => 'Miniseries',
            'DOCUMENTARY' => 'Documentary',
            'TALK_SHOW' => 'Talk Show',
            'NEWS' => 'News',
            // TODO: Add more values, need testing, no official documentation available
        ];
    }
}
