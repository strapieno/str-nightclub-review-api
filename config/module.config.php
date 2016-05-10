<?php
return [
    // Config of inject nightclub_id in body params
    'inject-route-params' => [
        'Strapieno\NightClubReview\Api\V1\Rest\Controller' => [
            'nightclub_id'
        ]
    ],
    'service-listeners' => [
        'invokables' => [
            'Strapieno\Utils\Listener\InjectRouteParamsInRequest' => 'Strapieno\Utils\Listener\InjectRouteParamsInRequest'
        ]
    ],
    'attach-listeners' => [
        'Application' => [
            'Strapieno\Utils\Listener\InjectRouteParamsInRequest'
        ]
    ],
    'service_manager' => [
        'delegators' => [
            'Application' => [
                'Strapieno\Utils\Delegator\AttachListenerDelegator',
            ]
        ],
    ],
    // Config of nightclub_id in route exist
    'nightclub-not-found' => [
        'api-rest/nightclub/review'
    ],
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

    'service-listeners' => [
        'initializers' => [
            'Strapieno\NightClub\Model\NightClubModelInizializer'
        ],
        'invokables' => [
            'Strapieno\NightClubReview\Api\V1\Listener\NightClubRestListener'
            => 'Strapieno\NightClubReview\Api\V1\Listener\NightClubRestListener'
        ]
    ],
    'attach-listeners' => [
        'Strapieno\NightClubReview\Api\V1\Rest\Controller' => [
            'Strapieno\NightClubReview\Api\V1\Listener\NightClubRestListener'
        ]
    ],
    'controllers' => [
        'delegators' => [
            'Strapieno\NightClubReview\Api\V1\Rest\Controller' => [
                'Strapieno\Utils\Delegator\AttachListenerDelegator',
            ]
        ],
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
                                    'route' => '/review[/:review_id]',
                                    'defaults' => [
                                        'controller' => 'Strapieno\NightClubReview\Api\V1\Rest\Controller'
                                    ],
                                    'constraints' => [
                                        'review_id' => '[0-9a-f]{24}'
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
                'entity_criteria' => 'Strapieno\Model\Criteria\NotIsolatedActiveRecordCriteria',
                'hydrator' => 'NightClubReviewApiHydrator'
            ]
        ]
    ],
    'zf-rest' => [
        'Strapieno\NightClubReview\Api\V1\Rest\Controller' => [
            'service_name' => 'nightclubReview',
            'listener' => 'Strapieno\NightClubReview\Api\V1\Rest\ConnectedResource',
            'route_name' => 'api-rest/nightclub/review',
            'route_identifier_name' => 'review_id',
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
            'Strapieno\NightClubReview\Model\Entity\ReviewEntity' => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api-rest/nightclub/review',
                'route_identifier_name' => 'review_id',
                'hydrator' => 'NightClubReviewApiHydrator',
            ],
        ],
    ],
    'zf-content-validation' => [
        'Strapieno\NightClubReview\Api\V1\Rest\Controller' => [
            'input_filter' => 'Strapieno\NightClubReview\Api\InputFilter\DefaultInputFilter',
        ]
    ],
    'strapieno_input_filter_specs' => [
        'Strapieno\NightClubReview\Api\InputFilter\DefaultReviewInputFilter' => [
            'merge' => 'Strapieno\NightClubReview\Model\InputFilter\DefaultReviewInputFilter',
        ],
        'Strapieno\NightClubReview\Api\InputFilter\DefaultInputFilter' => [
            'merge' => 'Strapieno\NightClubReview\Model\InputFilter\DefaultInputFilter',
            "nightclub_id" => [
                'name' => 'nightclub_id',
                'require' => true,
                'allow_empty' => false
            ],
            "rating" => [
                'name' => 'rating',
                'type' => 'Strapieno\NightClubReview\Api\InputFilter\DefaultReviewInputFilter',
            ]
        ]
    ]
];
