<?php

/**
 * Relation                          Value                          Notes
 * -------------------               ------                         ------
 * Less than                           1                            supported
 * Less than or equal                  2                            supported
 * Equal                               3                            default
 * Greater or equal                    4                            supported
 * Greater than                        5                            supported
 * Not equal                           6                            supported
 * Phonetic                            100                          unsupported
 * Stem                                101                          unsupported
 * Relevance                           102                          supported
 * AlwaysMatches                       103                          supported *
 **/

return [
    'relations' => [
        'all'   => '2=103', // Always Matches
        'any'   => '2=3',   // Always Matches
        '<'     => '2=1',   // Less than
        'le'    => '2=2',   // Less than or equal
        'eq'    => '2=3',   // Equal
        'exact'    => '2=3',   // Equal
        'ge'    => '2=4',   // Greater or equal
        '>'        => "2=5",   // Greater than
        '<>'    => '2=6',   // Not equal
    ]
];
