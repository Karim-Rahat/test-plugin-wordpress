<?php

namespace WPSmsPro\Vendor\libphonenumber;

use WPSmsPro\Vendor\libphonenumber\Leniency\Possible;
use WPSmsPro\Vendor\libphonenumber\Leniency\StrictGrouping;
use WPSmsPro\Vendor\libphonenumber\Leniency\Valid;
use WPSmsPro\Vendor\libphonenumber\Leniency\ExactGrouping;
class Leniency
{
    public static function POSSIBLE()
    {
        return new Possible();
    }
    public static function VALID()
    {
        return new Valid();
    }
    public static function STRICT_GROUPING()
    {
        return new StrictGrouping();
    }
    public static function EXACT_GROUPING()
    {
        return new ExactGrouping();
    }
}
