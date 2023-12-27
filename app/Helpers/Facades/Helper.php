<?php

namespace App\Helpers\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static organId()
 *
 * @method static organAccess($field = null)
 *
 * @method static prefectures()
 *
 * @method static numberOrder($iteration, $currentPage, $perPage)
 *
 * @see \App\Helpers\AppHelpers
 *
 */
class Helper extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'helper';
    }
}
