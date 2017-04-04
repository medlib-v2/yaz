<?php

/**
 * Structure                          Value                     Notes
 * -------------------               ------                     -----
 * Phrase                               1                       default
 * Word                                 2                       supported
 * Key                                  3                       supported
 * Year                                 4                       supported
 * Date (normalized)	                5	                    supported
 * Word list	                        6	                    supported
 * Date (un-normalized)                 100                     unsupported
 * Name (normalized)                    101                     unsupported
 * Name (un-normalized)                 102                     unsupported
 * Structure                            103                     unsupported
 * Urx                                  104                     supported
 * Free-form-text                       105                     supported
 * Document-text                        106                     supported
 * Local-number                         107                     supported
 * String                               108                     unsupported
 * Numeric string                       109                     supported
 **/

return [
    'structures' => [
        'phrase'    => '4=1',   // Phrase
        'word'      => '4=2',   // Word
        'key'       => '4=3',   // Key
        'year'      => '4=4',   // Year
        'date'      => '4=5',   // Date (normalized)
        'list'      => '4=6',   // Word list
        'urx'       => '4=104', // Urx
        'text'      => '4=105', // Free form text
        'document'  => '4=106', // Document-text
        'number'    => '4=107', // Local-number,
        'string'    => '4=109'  // Numeric string
    ]
];
