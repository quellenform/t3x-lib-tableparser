<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'TYPO3 Library: Table Parser',
    'description' => '',
    'category' => 'services',
    'state' => 'beta',
    'clearcacheonload' => true,
    'author' => 'Stephan Kellermayr',
    'author_email' => 'typo3@quellenform.at',
    'author_company' => 'Kellermayr KG',
    'version' => '0.1.1',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.0-14.99',
            'extbase' => ''
        ],
        'conflicts' => [],
        'suggests' => []
    ]
];
