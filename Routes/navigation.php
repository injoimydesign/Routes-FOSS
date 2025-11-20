<?php
/**
 * Routes extension - Navigation configuration
 * 
 * @copyright Xavier
 * @license   Apache-2.0
 */

return [
    'group' => [
        'location' => 'system',
        'index' => 600,
        'label' => 'Routes',
        'class' => 'i-location',
    ],
    'subpages' => [
        [
            'location' => 'routes',
            'label' => 'All Routes',
            'index' => 100,
            'uri' => 'routes',
            'class' => '',
        ],
    ],
];
