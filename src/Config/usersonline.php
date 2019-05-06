<?php
/*
* File:     usersonline.php
* Category: config
* Author:   s. Schenker
* Created:  28.06.18
*/

return [

    /*
    |--------------------------------------------------------------------------
    | Online Timeout (Minutes)
    |--------------------------------------------------------------------------
    |
    | User will show as online until this inactivity timeout
    |
    */
    'online_timeout' => env('USER_ONLINE_TIMEOUT', 10)
];
