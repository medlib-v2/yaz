<?php

return [
    'BNF' => [
        'fullname' => 'BN Fance',
        'instance' => 'BNF',
        'dsn' => 'yaz://Z3950:Z3950_BNF@z3950.bnf.fr:2211/TOUT-UTF8', //TOUT
        'format' => 'unimarc',
        'elementset' => 'F',
        'options' => [
            'protocol' => 2,
            'user' => 'Z3950',
            'password' => 'Z3950_BNF',
            'charset' => 'UTF-8',
            'preferredMessageSize' => 10240,
            'maximumRecordSize' => 10240,

        ],
    ],
    'OPAC' => [
        'fullname' => 'OPAC SBN',
        'instance' => 'OPAC',
        'dsn' => 'yaz://opac.sbn.it:2100/nopac',
        'format' => 'UNIMARC',
        'options' => [
            'protocol' => 2,
            'charset' => 'UTF-8',
            'preferredMessageSize' => 10240,
            'maximumRecordSize' => 10240,
        ],
    ],

    /**
     * Address: z3950.copac.ac.uk
     * Port: 210
     * Database name: COPAC
     * Record syntax: XML or SUTRS
     */
    'COPAC' => [
        'fullname' => 'COPAC',
        'instance' => 'COPAC',
        'dsn' => 'yaz://z3950.copac.ac.uk:210/COPAC',
        'format' => 'XML',
        'elementset' => 'F',
        'options' => [
            'protocol' => 2,
            'charset' => 'UTF-8',
            'preferredMessageSize' => 10240,
            'maximumRecordSize' => 10240,

        ],
    ],

    'VOYAGE' => [
        'fullname' => 'Library Of Congress',
        'instance' => 'VOYAGE',
        'dsn' => 'yaz://z3950.loc.gov:7090/voyager',
        'format' => 'marc21', // ou USMARC
        'elementset' => 'F',
        'options' => [
            'protocol' => 2,
            'charset' => 'UTF-8',
            'preferredMessageSize' => 10240,
            'maximumRecordSize' => 10240,
        ],
    ],

    /**
     * Address: carmin.sudoc.abes.fr
     * Port: 210
     * Database name: abes-z39-public
     * Record syntax: UNIMARC
     * Status : OK
     */
    'SUDOC' => [
        'fullname' => 'SUDOC',
        'instance' => 'SUDOC',
        'dsn' => 'yaz://carmin.sudoc.abes.fr:210/abes-z39-public',
        'format' => 'UNIMARC',
        'elementset' => 'F',
        'options' => [
            'protocol' => 2,
            'charset' => 'UTF-8',
            'preferredMessageSize' => 10240,
            'maximumRecordSize' => 10240,
        ],
    ],

    'LCDB' => [
        'fullname' => 'Library of Congress Online Catalog',
        'instance' => 'LCDB',
        'dsn' => 'yaz://lx2.loc.gov:210/LCDB',
        'format' => 'MARCXML',
        'elementset' => 'F',
        'options' => [
            'protocol' =>   2,
            'charset' => 'UTF-8',
            'preferredMessageSize' => 10240,
            'maximumRecordSize' => 10240,

        ],
    ],

    'BVIV' => [
        'fullname' => 'University of Victoria - McPherson Library',
        'instance' => 'BVIV',
        'dsn' => 'yaz://voyager.law.uvic.ca:7590/VOYAGER',
        'format' => 'MARC21',
        'elementset' => 'F',
        'options' => [
            'protocol' => 2,
            'charset' => 'UTF-8',
            'preferredMessageSize' => 10240,
            'maximumRecordSize' => 10240,

        ],
    ],

    'OLUC' => [
        'fullname' => 'WorldCat',
        'instance' => 'OLUC',
        'dsn' => 'yaz://zcat.oclc.org:210/OLUCWorldCat',
        'format' => 'MARCXML',
        'elementset' => 'F',
        'options' => [
            'protocol' => 2,
            'charset' => 'UTF-8',
            'preferredMessageSize' => 10240,
            'maximumRecordSize' => 10240,
        ],
    ],

    /**
     * Address: ariane2.ulaval.ca
     * Port: 2200
     * Database name: UNICORN
     * Record syntax: USMARC
     * Status : OK
     */
    'ULQC' => [
        'fullname' => 'Univ Laval (QC)',
        'instance' => 'ULQC',
        'dsn' => 'yaz://ariane2.ulaval.ca:2200/UNICORN',
        'format' => 'USMARC',
        'elementset' => 'F',
        'options' => [
            'protocol' => 2,
            'charset' => 'UTF-8',
            'preferredMessageSize' => 10240,
            'maximumRecordSize' => 10240,

        ],
    ],
    
    'UNOX' => [
        'fullname' => 'Univ Oxford',
        'instance' => 'UNOX',
        'dsn' => 'yaz://SUNCAT:SUNCAT@library.ox.ac.uk:210/ADVANCE',
        'format' => 'USMARC',
        'elementset' => 'F',
        'options' => [
            'protocol' => 2,
            'charset' => 'UTF-8',
            'preferredMessageSize' => 10240,
            'maximumRecordSize' => 10240,

        ],
    ],

    'UCL' => [
        'fullname' => 'Libellule | Université Catholique de Louvain',
        'instance' => 'UCL',
        'dsn' => 'yaz://bib.sipr.ucl.ac.be:3590/DEFAULT',
        'format' => 'unimarc',
        'elementset' => 'F',
        'options' => [
            'protocol' => 2,
            'charset' => 'UTF-8',
            'preferredMessageSize' => 10240,
            'maximumRecordSize' => 10240,

        ],
    ],

    /**
     * Address: amicus.collectionscanada.gc.ca
     * Port: 210
     * Database name: ['ANY', 'UC', 'NL', 'LC', 'FS', 'NA', 'NFB', 'SE', 'AU'],
     * Record syntax: MARC21
     */
    'NLC' => [
        'fullname' => 'Bibliothèque et Archives Canada',
        'instance' => 'NLC',
        'dsn' => 'yaz://amicus.collectionscanada.gc.ca:210/ANY',
        'format' => 'MARC21',
        'elementset' => 'F',
        'options' => [
            'protocol' => 2,
            'charset' => 'UTF-8',
            'preferredMessageSize' => 10240,
            'maximumRecordSize' => 10240,

        ],
    ],

    'SAF' => [
        'fullname' => 'Subject Authority File',
        'instance' => 'SAF',
        'dsn' => 'yaz://lx2.loc.gov:210/SAF',
        'format' => 'MARCXML',
        'elementset' => 'F',
        'options' => [
            'protocol' => 2,
            'charset' => 'UTF-8',
            'preferredMessageSize' => 10240,
            'maximumRecordSize' => 10240,

        ]
    ],
];
