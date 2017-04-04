<?php

/**
 * Position                          Value                  Notes
 * -------------------               ------                 -------
 * First in field                      1                    supported *
 * First in subfield                   2                    supported *
 * Any position in field               3                    default
 **/

return [
    'positions' => [
        'field'   => '3=1', // First in field
        'subfield'=> '3=2', // First in subfield
        'any'     => '3=3' // Any position in field
    ]
];
