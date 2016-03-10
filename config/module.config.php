<?php
return [
    'service_manager' => [
        'factories' => [
            'Strapieno\Utils\Listener\ListenerManager' => 'Strapieno\Utils\Listener\ListenerManagerFactory'
        ],
        'invokables' => [
            'Strapieno\Utils\Delegator\AttachListenerDelegator' =>  'Strapieno\Utils\Delegator\AttachListenerDelegator'
        ],
        'aliases' => [
            'listenerManager' => 'Strapieno\Utils\Listener\ListenerManager'
        ]
    ],
    'router' => [
        'routes' => [
            'api-rest' => [
                'child_routes' => [
                    'nightclub' => [
                        'child_routes' => [
                            'review' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/review',
                                    'defaults' => [
                                        'controller' => 'Strapieno\NightClubReview\Api\V1\Rest\Controller'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ],
    'matryoshka-apigility' => [
        'matryoshka-connected' => [
            'Strapieno\NightClubReview\Api\V1\Rest\ConnectedResource' => [
                'model' => 'Strapieno\NightClubReview\Model\ReviewModelService',
                'collection_criteria' => 'Strapieno\NightClubReview\Model\Criteria\ReviewCollectionCriteria',
                'entity_criteria' => 'Strapieno\Model\Criteria\NotIsolatedActiveRecordCriteria'
            ]
        ]
    ],
    'zf-rest' => [
        'Strapieno\NightClubReview\Api\V1\Rest\Controller' => [
            'service_name' => 'nightclubReview',
            'listener' => 'Strapieno\NightClubReview\Api\V1\Rest\ConnectedResource',
            'route_name' => 'api-rest/nightclub/review',
            'route_identifier_name' => 'nightclub_review_id',
            'collection_name' => 'reviews',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [],
            'page_size' => 10,
            'page_size_param' => 'page_size',
            'collection_class' => 'Zend\Paginator\Paginator', // FIXME function?
        ]
    ],
    'zf-content-negotiation' => [
        'accept_whitelist' => [
            'Strapieno\NightClubReview\Api\V1\Rest\Controller' => [
                'application/hal+json',
                'application/json',
            ],
        ],
        'content_type_whitelist' => [
            'Strapieno\NightClubReview\Api\V1\Rest\Controller' => [
                'application/json'
            ],
        ],
    ],
    'zf-hal' => [
        // map each class (by name) to their metadata mappings
        'metadata_map' => [
            'Strapieno\NightClubReview\Model\Entity\NightClubReviewEntity' => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api-rest/nightclub/review',
                'route_identifier_name' => 'nightclub_review_id',
                'hydrator' => 'NightClubReviewApiHydrator',
            ],
        ],
    ],
    'zf-content-validation' => [
        'Strapieno\NightClubReview\Api\V1\Rest\Controller' => [
            'input_filter' => 'Strapieno\NightClubReview\Model\InputFilter\DefaultInputFilter',
            'POST' => 'Strapieno\NightClubReview\Model\InputFilter\CreateInputFilter'
        ]
    ]
];
