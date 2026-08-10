<?php

return [
    [
        "title" => "words.main",
        "children" => [
            [
                "children" => [
                    [
                        "size" => "6",
                        "children" => [
                            [
                                "type" => "requiredTitle",
                                "name" => "title",
                                "params" => [
                                    "label" => "words.title",
                                    "validation" => ["required" => null]
                                ],
                            ]
                        ],
                    ],
                    [
                        "size" => "6",
                        "children" => [
                            [
                                "type" => "requiredRouteName",
                                "name" => "routeName",
                                "params" => [
                                    "label" => "words.route_name",
                                    "validation" => ["routeName" => null]
                                ],
                            ]
                        ],
                    ]
                ],
            ],
            [
                "children" => [
                    [
                        "size" => "3",
                        "children" => [
                            [
                                "type" => "requiredStatus",
                                "name" => "status",
                                "value" => true,
                                "params" => [
                                    "label" => "words.status",
                                    "valueType" => "bool"
                                ],
                            ]
                        ],
                    ],
                    [
                        "size" => "9",
                        "children" => [
                            [
                                "type" => "advancedParent",
                                "name" => "parent",
                                "value" => 0,
                                "params" => [
                                    "label" => "words.select_parent",
                                    "valueType" => "int"
                                ],
                            ]
                        ],
                    ]
                ],
            ],
        ],
    ],
    [
        "title" => "words.seo",
        "children" => [
            [
                "children" => [
                    [
                        "size" => "12",
                        "children" => [
                            [
                                "type" => "requiredSeoKeyword",
                                "name" => "seoKeyword",
                                "params" => [
                                    "label" => "words.seo_keywords",
                                ],
                            ],
                            [
                                "type" => "requiredSeoDescription",
                                "name" => "seoDescription",
                                "params" => [
                                    "label" => "words.seo_description",
                                ],
                            ]
                        ],
                    ]
                ],
            ]
        ],
    ],
    [
        "title" => "words.advanced",
        "children" => [
            [
                "children" => [
                    [
                        "size" => "12",
                        "children" => [
                            [
                                "type" => "requiredTemplate",
                                "name" => "template",
                                "value" => 0,
                                "params" => [
                                    "label" => "words.template",
                                    "valueType" => "int"
                                ],
                            ]
                        ],
                    ]
                ],
            ],
            [
                "children" => [
                    [
                        "size" => "6",
                        "children" => [
                            [
                                "type" => "requiredPublishStart",
                                "name" => "publishStart",
                                "params" => [
                                    "label" => "words.publish_start_date",
                                ],
                            ]
                        ],
                    ],
                    [
                        "size" => "6",
                        "children" => [
                            [
                                "type" => "requiredPublishEnd",
                                "name" => "publishEnd",
                                "params" => [
                                    "label" => "words.publish_end_date",
                                ],
                            ]
                        ],
                    ]
                ],
            ]
        ],
    ],
];
