<?php

namespace App\Helpers;

class Constant
{
    const CHARGES_TYPE = [
        'Hourly' => 1,
        'PerDay' => 2,
        'Weekly' => 3,
        'Monthly' => 4,
        'Yearly' => 5,
        'Other' => 6,
    ];

    const CHARGES_TYPE_VAL = [
        1 => 'Hourly',
        2 => 'PerDay',
        3 => 'Weekly',
        4 => 'Monthly',
        5 => 'Yearly',
        6 => 'Other',
    ];

    const CHARGES_TYPE_RULE = [1,2,3,4,5,6];

    const REQUEST_STATUS = [
        'Open' => 1,
        'InProcess' => 2,
        'InCorrect' => 3,
        'Completed' => 4,
        'Approved' => 5,
        'Closed' => 6,
        'Satisfied' => 7,
        'UnSatisfied' => 8,
    ];
    
    const REQUEST_STATUS_VAL = [
        1 => 'Open',
        2 => 'InProcess',
        3 => 'InCorrect',
        4 => 'Completed',
        5 => 'Approved',
        6 => 'Closed',
        7 => 'Satisfied',
        8 => 'UnSatisfied',
    ];

    const REQUEST_STATUS_COLOR = [
        1 => 'danger',
        2 => 'warning',
        3 => 'info',
        4 => 'primary',
        5 => 'success',
        6 => 'dark',
        7 => 'success',
        8 => 'danger',
    ];


    const REQUEST_STATUS_RULE = [1,2,3,4,5,6,7,8];

    const ORDER_STATUS = [
        'New'  => 1,
        'Accept' => 2,
        'Rejected' => 3,
        'Canceled' => 4,
        'Payed' => 5,
        'InProgress' => 6,
        'Delivered' => 7,
        'Received' => 8,
        'InProgress' => 9,
    ];

}