<?php

return [
    'type' => 'cover',
    'name' => 'templates.cover',
    'description' => 'templates.cover_description',
    'fields' => [
        [
            'type' => 'text',
            'name' => 'tagline',
            'label' => 'templates.tagline',
        ],
        [
            'type' => 'text',
            'name' => 'title',
            'label' => 'templates.title',
        ],
        [
            'type' => 'textarea',
            'name' => 'description',
            'label' => 'templates.description',
        ],
        [
            'type' => 'image',
            'name' => 'image',
            'label' => 'templates.image',
        ],
        [
            'type' => 'button',
            'name' => 'first_action',
            'label' => 'templates.primary_action',
        ],
        [
            'type' => 'button',
            'name' => 'second_action',
            'label' => 'templates.secondary_action',
        ],
        [
            'type' => 'block_next_button',
            'name' => 'arrow_action',
            'label' => 'templates.arrow_action',
        ],
    ],
    'samples' => [
        [
            'layout' => 7,
            'values' => [
                'tagline' => ['value' => '', 'size' => 18, 'color' => '#FFF'],
                'title' => ['value' => 'Sunglasses', 'size' => 64, 'style' => ['b'], 'color' => '#FFF'],
                'description' => ['value' => 'Rock your look with our eyewear created at the intersection of art, fashion, and technology.', 'size' => 18, 'color' => '#FFF'],
                'image' => ['value' => 'https://images.unsplash.com/photo-1552940519-2c2c6f9064c5?ixlib=rb-1.2.1&auto=format&fit=crop&w=2000&h=1350&q=50'],
                'first_action' => ['value' => 'Shop Now', 'appearance' => 'solid', 'size' => 'medium', 'shape' => 'round-corner', 'background-color' => '#FFF', 'text-color' => '#000'],
                'second_action' => ['value' => '', 'appearance' => 'outline', 'size' => 'medium', 'shape' => 'round-corner', 'text-color' => '#FFF', 'background-color' => '#000'],
                'arrow_action' => ['show' => false],
                'background' => ['style' => 'solid', 'color' => '#FFF'],
            ],
        ],
        [
            'layout' => 10,
            'values' => [
                'tagline' => ['value' => 'Essential skin care for every skin type', 'size' => 18, 'color' => '#191919'],
                'title' => ['value' => 'State of Beauty', 'size' => 60, 'style' => ['b'], 'color' => '#191919'],
                'description' => ['value' => 'Perfect your skin care routine with our body and facial products.', 'size' => 18, 'color' => '#191919'],
                'image' => ['value' => 'https://images.unsplash.com/photo-1568054043324-86c349f926d0?ixlib=rb-1.2.1&auto=format&fit=crop&w=2000&h=2500&q=50'],
                'first_action' => ['value' => 'Shop Now', 'appearance' => 'solid', 'size' => 'medium', 'shape' => 'round-corner', 'background-color' => '#191919', 'text-color' => '#FFF'],
                'second_action' => ['value' => 'Contact Us', 'appearance' => 'outline', 'size' => 'medium', 'shape' => 'round-corner', 'text-color' => '#191919', 'background-color' => '#FFF'],
                'arrow_action' => ['show' => false],
                'background' => ['style' => 'solid', 'color' => '#F6F6F6'],
            ],
        ],
        [
            'layout' => 4,
            'values' => [
                'tagline' => ['value' => '', 'size' => 18, 'color' => '#FFF'],
                'title' => ['value' => 'Chocolate Cake Shop', 'size' => 80, 'style' => ['b'], 'color' => '#FFF'],
                'description' => ['value' => '', 'size' => 18, 'color' => '#FFF'],
                'image' => ['value' => 'https://images.unsplash.com/photo-1536614984430-64652c3ad956?ixlib=rb-1.2.1&auto=format&fit=crop&w=2000&h=1330&q=50'],
                'first_action' => ['value' => 'Order Now', 'appearance' => 'solid', 'size' => 'medium', 'shape' => 'round-corner', 'background-color' => '#FFF', 'text-color' => '#000'],
                'second_action' => ['value' => '', 'appearance' => 'outline', 'size' => 'medium', 'shape' => 'round-corner', 'text-color' => '#FFF', 'background-color' => '#000'],
                'arrow_action' => ['show' => false],
                'background' => ['style' => 'solid', 'color' => '#13212E'],
            ],
        ],
        [
            'layout' => 3,
            'values' => [
                'tagline' => ['value' => '', 'size' => 18, 'color' => '#FFF'],
                'title' => ['value' => 'Jewels of my heart', 'size' => 80, 'style' => ['b'], 'color' => '#FFF'],
                'description' => ['value' => 'Works of art designed to brighten your day.', 'size' => 18, 'color' => '#FFF'],
                'image' => ['value' => 'https://images.unsplash.com/photo-1616413552922-aa3906103397?ixlib=rb-1.2.1&auto=format&fit=crop&w=2000&h=1500&q=50'],
                'first_action' => ['value' => 'Shop Now', 'appearance' => 'solid', 'size' => 'medium', 'shape' => 'round-corner', 'background-color' => '#FFF', 'text-color' => '#000'],
                'second_action' => ['value' => '', 'appearance' => 'outline', 'size' => 'medium', 'shape' => 'round-corner', 'text-color' => '#FFF', 'background-color' => '#000'],
                'arrow_action' => ['show' => false],
                'background' => ['style' => 'solid', 'color' => '#536459'],
            ],
        ],
        [
            'layout' => 8,
            'values' => [
                'tagline' => ['value' => '', 'size' => 22, 'color' => '#191919'],
                'title' => ['value' => 'One-Bowl Meals', 'size' => 80, 'style' => ['b'], 'color' => '#191919'],
                'description' => ['value' => 'Your favorite bowls at one place, healthy and filling. Veggie, fish, chicken, all made from the freshest ingredients.', 'size' => 22, 'color' => '#191919'],
                'image' => ['value' => 'https://images.unsplash.com/photo-1543363136-3f2d17e6d6cd?ixlib=rb-1.2.1&auto=format&fit=crop&w=2000&h=1500&q=50'],
                'first_action' => ['value' => 'Order Now', 'appearance' => 'solid', 'size' => 'medium', 'shape' => 'round-corner', 'background-color' => '#191919', 'text-color' => '#FFF'],
                'second_action' => ['value' => 'Contact Us', 'appearance' => 'solid', 'size' => 'medium', 'shape' => 'round-corner', 'text-color' => '#191919', 'background-color' => '#FFF'],
                'arrow_action' => ['show' => false],
                'background' => ['style' => 'solid', 'color' => '#F6F6F6'],
            ],
        ],
        [
            'layout' => 2,
            'values' => [
                'tagline' => ['value' => 'Unmatched comfort and style', 'size' => 18, 'color' => '#191919'],
                'title' => ['value' => 'Find your perfect look', 'size' => 64, 'style' => ['b'], 'color' => '#191919'],
                'description' => ['value' => 'Style up your ultimate look this season with our collection of exceptional articles.', 'size' => 22, 'color' => '#191919'],
                'image' => ['value' => 'https://images.unsplash.com/photo-1623082819157-25d7e8877ffc?ixlib=rb-1.2.1&auto=format&fit=crop&w=2000&h=1500&q=50'],
                'first_action' => ['value' => 'Shop Now', 'appearance' => 'solid', 'size' => 'medium', 'shape' => 'round-corner', 'background-color' => '#191919', 'text-color' => '#FFF'],
                'second_action' => ['value' => 'Contact Us', 'appearance' => 'outline', 'size' => 'medium', 'shape' => 'round-corner', 'text-color' => '#191919', 'background-color' => '#FFF'],
                'arrow_action' => ['show' => false],
                'background' => ['style' => 'solid', 'color' => '#F6F6F6'],
            ],
        ],
        [
            'layout' => 9,
            'values' => [
                'tagline' => ['value' => '', 'size' => 18, 'color' => '#191919'],
                'title' => ['value' => 'Handmade Cosmetics', 'size' => 60, 'style' => ['b'], 'color' => '#191919'],
                'description' => ['value' => 'We have chosen only the best products from top manufacturers.', 'size' => 18, 'color' => '#191919'],
                'image' => ['value' => 'https://images.unsplash.com/photo-1579111537745-76061e8893dc?ixlib=rb-1.2.1&auto=format&fit=crop&w=2000&h=2000&q=50'],
                'first_action' => ['value' => 'Catalog', 'appearance' => 'solid', 'size' => 'medium', 'shape' => 'round-corner', 'background-color' => '#191919', 'text-color' => '#FFF'],
                'second_action' => ['value' => '', 'appearance' => 'outline', 'size' => 'medium', 'shape' => 'round-corner', 'text-color' => '#191919', 'background-color' => '#FFF'],
                'arrow_action' => ['show' => false],
                'background' => ['style' => 'solid', 'color' => '#F6F6F6'],
            ],
        ],
        [
            'layout' => 1,
            'values' => [
                'tagline' => ['value' => 'Green House', 'size' => 18, 'color' => '#191919'],
                'title' => ['value' => 'Green Plants', 'size' => 80, 'style' => ['b'], 'color' => '#191919'],
                'description' => ['value' => 'Bring life and color to your home.', 'size' => 22, 'color' => '#191919'],
                'image' => ['value' => 'https://images.unsplash.com/photo-1619783547903-33edeced430a?ixlib=rb-1.2.1&auto=format&fit=crop&w=2000&h=1330&q=50'],
                'first_action' => ['value' => 'Shop Now', 'appearance' => 'solid', 'size' => 'medium', 'shape' => 'round-corner', 'background-color' => '#191919', 'text-color' => '#FFF'],
                'second_action' => ['value' => 'Contact Us', 'appearance' => 'outline', 'size' => 'medium', 'shape' => 'round-corner', 'text-color' => '#191919', 'background-color' => '#FFF'],
                'arrow_action' => ['show' => false],
                'background' => ['style' => 'solid', 'color' => '#F6F6F6'],
            ],
        ],
        [
            'layout' => 2,//5
            'values' => [
                'tagline' => ['value' => '', 'size' => 18, 'color' => '#FFF'],
                'title' => ['value' => 'Youth styles and cool prints', 'size' => 64, 'style' => ['b'], 'color' => '#FFF'],
                'description' => ['value' => 'Sharp silhouettes that evoke emotion and can be worn by any woman.', 'size' => 22, 'color' => '#FFF'],
                'image' => ['value' => 'https://images.unsplash.com/photo-1503342250614-ca440786f637?ixlib=rb-1.2.1&auto=format&fit=crop&w=2000&h=2000&q=50'],
                'first_action' => ['value' => 'Catalog', 'appearance' => 'solid', 'size' => 'medium', 'shape' => 'round-corner', 'background-color' => '#FFF', 'text-color' => '#000'],
                'second_action' => ['value' => '', 'appearance' => 'outline', 'size' => 'medium', 'shape' => 'round-corner', 'text-color' => '#FFF', 'background-color' => '#000'],
                'arrow_action' => ['show' => false],
                'background' => ['style' => 'solid', 'color' => '#F6F6F6'],
            ],
        ],
        [
            'layout' => 1,//6
            'values' => [
                'tagline' => ['value' => '', 'size' => 18, 'color' => '#FFF'],
                'title' => ['value' => 'Scandinavian Home', 'size' => 80, 'style' => ['b'], 'color' => '#FFF'],
                'description' => ['value' => 'We make unique furniture to create a comfortable and relaxing environment.', 'size' => 22, 'color' => '#FFF'],
                'image' => ['value' => 'https://images.unsplash.com/photo-1567016432779-094069958ea5?ixlib=rb-1.2.1&auto=format&fit=crop&w=2000&h=2000&q=50'],
                'first_action' => ['value' => 'Catalog', 'appearance' => 'solid', 'size' => 'medium', 'shape' => 'round-corner', 'background-color' => '#FFF', 'text-color' => '#000'],
                'second_action' => ['value' => '', 'appearance' => 'outline', 'size' => 'medium', 'shape' => 'round-corner', 'text-color' => '#FFF', 'background-color' => '#000'],
                'arrow_action' => ['show' => false],
                'background' => ['style' => 'solid', 'color' => '#232D34'],
            ],
        ],
    ],
    'styles' => [
        '#id.cover-layout-1' => [
            'field' => 'image',
            'background-size' => '100%',
            'background-repeat' => 'no-repeat',
            'background-position'=> 'center',
        ],
        '#id.cover-layout-1 .cover-layout-tagline' => [
            'text-align' => 'center',
        ],
        '#id.cover-layout-1 .cover-layout-headline' => [
            'text-align' => 'center',
        ],

        '#id.cover-layout-1 .cover-layout-buttons' => [
            'text-align' => 'center',
        ],
        '#id.cover-layout-1 .cover-layout-description' => [
            'text-align' => 'center',
        ],
        '#id.cover-layout-1 .cover-layout-footer' => [
            'width' => '75%',
            'margin' => '0 auto',
        ],

        //'.cover-layout-1-mobile-1' => [],
        //'.cover-layout-1-mobile-2' => [],
        '#id.cover-layout-2' => [
            'field' => 'image',
            'background-size' => '100%',
            'background-repeat' => 'no-repeat',
            'background-position'=> 'center',
            'min-height'=> '451px',
        ],
        '#id.cover-layout-2 .cover-layout-tagline' => [
            //
        ],
        '#id.cover-layout-2 .cover-layout-headline' => [
            //
        ],
        '#id.cover-layout-2 .cover-layout-footer' => [
            //
        ],
        '#id.cover-layout-2 .cover-layout-wrap' => [
            'margin-right' => '50%',
            'padding-right' => '0',
        ],
        /*'.cover-layout-2-mobile-1' => [],
        '.cover-layout-2-mobile-2' => [],*/
        '#id.cover-layout-3' => [
            'field' => 'background',
        ],

        '#id.cover-layout-3 .cover-layout-wrap' => [
            'text-align' => 'center'
        ],
        '#id.cover-layout-3 .cover-layout-tagline' => [
            'margin-bottom' => '20px'
        ],
        '#id.cover-layout-3 .cover-layout-headline' => [
            'margin-top' => '-60px'
        ],

        '#id.cover-layout-4' => [
            'field' => 'background',
        ],
        '#id.cover-layout-4 .cover-layout-wrap' => [
            'field' => 'image',
            'background-repeat' => 'no-repeat',
            'background-position' => 'right',
            'background-size' => '60% auto',
            'padding' => '0',
            'margin' => '60px',
            'padding-right' => '30%',
        ],
        '#id.cover-layout-5' => [
            'field' => 'image',
            'background-repeat' => 'no-repeat',
            'background-size' => '100% auto',
            'background-position' => 'center',
        ],
        '#id.cover-layout-5 .cover-layout-wrap' => [
            'text-align' => 'center',
        ],
        /*'.cover-layout-5-mobile-1' => [],
        '.cover-layout-5-mobile-2' => [],*/
        '#id.cover-layout-6' => [
            'field' => 'image',
            'background-repeat' => 'no-repeat',
            'background-size' => '100% auto',
            'background-position' => 'center',
        ],
        '#id.cover-layout-6 .cover-layout-footer' => [
            'position' => 'relative',
        ],
        '#id.cover-layout-6 .cover-layout-description' => [
            'position' => 'absolute',
            'right' => '0',
            'width' => '50%',
            'top' => '0px',
            'margin-top' => '-10px',
        ],
        /*'.cover-layout-6-mobile-1' => [],
        '.cover-layout-6-mobile-2' => [],*/
        '#id.cover-layout-7' => [
            'field' => 'image',
            'background-repeat' => 'no-repeat',
            'background-size' => '100% auto',
            'background-position' => 'center',
        ],
        /*'.cover-layout-7-mobile-1' => [],
        '.cover-layout-7-mobile-2' => [],*/
        '#id.cover-layout-8' => [
            'field' => 'image',
            'background-repeat' => 'no-repeat',
            'background-size' => '100% auto',
            'background-position' => 'top center',
        ],
        '#id.cover-layout-8 .cover-layout-wrap' => [
            'text-align' => 'center',
        ],
        '#id.cover-layout-8 .cover-layout-description' => [
            'width' => '75%',
            'margin' => '0 auto',
        ],
        /*'.cover-layout-8-mobile-1' => [],
        '.cover-layout-8-mobile-2' => [],*/
        '#id.cover-layout-9' => [
            'field' => 'image',
            'background-size' => '50% auto',
            'background-repeat' => 'no-repeat',
        ],
        '#id.cover-layout-9 .cover-layout-wrap' => [
            'margin-left' => '50%'
        ],
        '#id.cover-layout-10' => [
            'field' => 'background',
        ],
        '#id.cover-layout-10 .cover-layout-wrap' => [
            'field' => 'image',
            'background-repeat' => 'no-repeat',
            'background-position' => 'right',
            'background-size' => '50% auto',
            'padding-right' => '50%',
        ],
        '#id.cover-layout-11' => [
            'field' => 'image',
            'background-repeat' => 'no-repeat',
            'background-size' => '100% auto',
            'background-position' => 'center',
        ],
        /*'.cover-layout-11-mobile-1' => [],
        '.cover-layout-11-mobile-2' => [],*/
        '#id.cover-layout-12' => [
            'field' => 'image',
            'background-repeat' => 'no-repeat',
            'background-size' => '100% auto',
            'background-position' => 'center',
        ],
        '#id.cover-layout-12 .cover-layout-wrap' => [
            'text-align' => 'center'
        ],
        /*'.cover-layout-12-mobile-1' => [],
        '.cover-layout-12-mobile-2' => [],*/


        '#id .cover-layout-wrap' => [
            'padding' => '60px'
        ],
        '#id .cover-layout-headline' => [
            'margin-top' => '30px',
        ],
        '#id .cover-layout-description' => [
            'margin-top' => '30px',
        ],

        '#id .cover-layout-buttons' => [
            'margin-top' => '40px',
        ],

        '#id .cover-layout-button-primary' => [
            'display' => 'inline-block',
            'margin' => '0 10px',
        ],
        '#id .cover-layout-button-secondary' => [
            'display' => 'inline-block',
            'margin' => '0 10px',
        ],
        '#id .cover-layout-image' => [
            'display' => 'none',
        ],

        '@media screen and (max-width: 576px)' => [
            '#id .cover-layout-wrap' => [
                'padding' => '20px'
            ],

            '#id.cover-layout-1 .cover-layout-footer' => [
                'width' => '100%'
            ],

            '#id.cover-layout-1' => [
                'background-size' => 'auto 100%',
            ],

            '#id  .cover-layout-buttons .cover-layout-button-primary' => [
                'display' => 'block',
                'margin-bottom' => '15px',
            ],
            '#id  .cover-layout-buttons .cover-layout-button-primary .button' => [
                'display' => 'block',
                'text-align' => 'center',
            ],
            '#id  .cover-layout-buttons .cover-layout-button-secondary' => [
                'display' => 'block',
                'margin-bottom' => '15px',
            ],
            '#id .cover-layout-buttons .cover-layout-button-secondary .button' => [
                'display' => 'block',
                'text-align' => 'center',
            ],

            '#id.cover-layout-1-mobile-2 .cover-layout-image' => [
                'display' => 'block',
            ],

            '#id.cover-layout-1-mobile-2' => [
                'background' => 'none',
                'field' => 'background',
            ],

            '#id.cover-layout-2 .cover-layout-wrap' => [
                'margin-right' => '0',
                'padding' => '20px',
            ],
            '#id.cover-layout-2' => [
                'background-size' => 'auto 100%',
            ],
            '#id.cover-layout-2-mobile-2 .cover-layout-image' => [
                'display' => 'block',
            ],
            '#id.cover-layout-2-mobile-2' => [
                'background' => 'none',
                'field' => 'background',
            ],

            '#id.cover-layout-2-mobile-2 .cover-layout-tagline' => [
                'margin-top' => '15px',
            ],

            '#id.cover-layout-2-mobile-2 .cover-layout-wrap' => [
                'text-align' => 'center',
            ],

            '#id.cover-layout-5' => [
                'background-size' => 'auto 100%',
            ],
            '#id.cover-layout-5-mobile-2 .cover-layout-image' => [
                'display' => 'block',
            ],
            '#id.cover-layout-5-mobile-2' => [
                'background' => 'none',
                'field' => 'background',
            ],

            '#id.cover-layout-5-mobile-2 .cover-layout-tagline' => [
                'margin-top' => '15px',
            ],

            '#id.cover-layout-5-mobile-2 .cover-layout-wrap' => [
                'text-align' => 'center',
            ],

            '#id.cover-layout-6' => [
                'background-size' => 'auto 100%',
            ],
            '#id.cover-layout-6-mobile-2 .cover-layout-image' => [
                'display' => 'block',
            ],
            '#id.cover-layout-6-mobile-2' => [
                'background' => 'none',
                'field' => 'background',
            ],

            '#id.cover-layout-6-mobile-2 .cover-layout-tagline' => [
                'margin-top' => '15px',
            ],

            '#id.cover-layout-6-mobile-2 .cover-layout-wrap' => [
                'text-align' => 'center',
            ],
            '#id.cover-layout-6 .cover-layout-description' => [
                'position' => 'initial',
                'width' => '100%',
                'margin-top' => 'auto',
            ],

            '#id.cover-layout-7' => [
                'background-size' => 'auto 100%',
            ],
            '#id.cover-layout-7-mobile-2 .cover-layout-image' => [
                'display' => 'block',
            ],
            '#id.cover-layout-7-mobile-2' => [
                'background' => 'none',
                'field' => 'background',
            ],

            '#id.cover-layout-7-mobile-2 .cover-layout-tagline' => [
                'margin-top' => '15px',
            ],

            '#id.cover-layout-7-mobile-2 .cover-layout-wrap' => [
                'text-align' => 'center',
            ],
            '#id.cover-layout-8' => [
                'background-size' => 'auto 100%',
            ],
            '#id.cover-layout-8-mobile-2 .cover-layout-image' => [
                'display' => 'block',
            ],
            '#id.cover-layout-8-mobile-2' => [
                'background' => 'none',
                'field' => 'background',
            ],

            '#id.cover-layout-8-mobile-2 .cover-layout-tagline' => [
                'margin-top' => '15px',
            ],

            '#id.cover-layout-8-mobile-2 .cover-layout-wrap' => [
                'text-align' => 'center',
            ],

            '#id.cover-layout-11' => [
                'background-size' => 'auto 100%',
            ],
            '#id.cover-layout-11-mobile-2 .cover-layout-image' => [
                'display' => 'block',
            ],
            '#id.cover-layout-11-mobile-2' => [
                'background' => 'none',
                'field' => 'background',
            ],

            '#id.cover-layout-11-mobile-2 .cover-layout-tagline' => [
                'margin-top' => '15px',
            ],

            '#id.cover-layout-11-mobile-2 .cover-layout-wrap' => [
                'text-align' => 'center',
            ],
            '#id.cover-layout-12' => [
                'background-size' => 'auto 100%',
            ],
            '#id.cover-layout-12-mobile-2 .cover-layout-image' => [
                'display' => 'block',
            ],
            '#id.cover-layout-12-mobile-2' => [
                'background' => 'none',
                'field' => 'background',
            ],

            '#id.cover-layout-12-mobile-2 .cover-layout-tagline' => [
                'margin-top' => '15px',
            ],

            '#id.cover-layout-12-mobile-2 .cover-layout-wrap' => [
                'text-align' => 'center',
            ],
        ],
    ],
    'layout' => [
        [
            'id' => 1,
            'structure' => [
                'tag' => 'main',
                'attributes' => ['class' => 'cover-layout-1'],
                'children' => [
                    [
                        'tag' => 'div',
                        'attributes' => ['class' => 'cover-layout-wrap'],
                        'children' => [
                            [
                                'tag' => 'div',
                                'attributes' => ['class' => 'cover-layout-image'],
                                'children' => [
                                    [
                                        'field' => 'image'
                                    ]
                                ]
                            ],
                            [
                                'tag' => 'div',
                                'attributes' => ['class' => 'cover-layout-tagline'],
                                'children' => [
                                    [
                                        'field' => 'tagline'
                                    ]
                                ]
                            ],
                            [
                                'tag' => 'h1',
                                'attributes' => ['class' => 'cover-layout-headline'],
                                'children' => [
                                    [
                                        'field' => 'title'
                                    ]
                                ]
                            ],
                            [
                                'tag' => 'div',
                                'attributes' => ['class' => 'cover-layout-footer'],
                                'children' => [
                                    [
                                        'tag' => 'div',
                                        'attributes' => ['class' => 'cover-layout-description'],
                                        'children' => [
                                            [
                                                'field' => 'description'
                                            ]
                                        ]
                                    ],
                                    [
                                        'tag' => 'div',
                                        'attributes' => ['class' => 'cover-layout-buttons'],
                                        'children' => [
                                            [
                                                'tag' => 'div',
                                                'attributes' => ['class' => 'cover-layout-button-primary'],
                                                'children' => [
                                                    [
                                                        'field' => 'first_action'
                                                    ]
                                                ]
                                            ],
                                            [
                                                'tag' => 'div',
                                                'attributes' => ['class' => 'cover-layout-button-secondary'],
                                                'children' => [
                                                    [
                                                        'field' => 'second_action'
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ],
                                    [
                                        'tag' => 'div',
                                        'attributes' => ['class' => 'cover-layout-arrow'],
                                        'children' => [
                                            [
                                                'field' => 'arrow_action'
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'fields' => [],
            'mobile_layout' => [
                [
                    'id' => 1,
                    'structure' => [],
                ],
                [
                    'id' => 2,
                    'structure' => [],
                ]
            ],
        ],
        [
            'id' => 2,
            'structure' => [
                'layout' => 1,
                'attributes' => ['class' => 'cover-layout-2'],
            ],
            'fields' => [],
            'mobile_layout' => [
                [
                    'id' => 1,
                    'structure' => [],
                ],
                [
                    'id' => 2,
                    'structure' => [],
                ]
            ],
        ],
        [
            'id' => 3,
            'fields' => [],
            'structure' => [
                'tag' => 'main',
                'attributes' => ['class' => 'cover-layout-3'],
                'children' => [
                    [
                        'tag' => 'div',
                        'attributes' => ['class' => 'cover-layout-wrap'],
                        'children' => [
                            [
                                'tag' => 'div',
                                'attributes' => ['class' => 'cover-layout-tagline'],
                                'children' => [
                                    [
                                        'field' => 'tagline'
                                    ]
                                ]
                            ],
                            [
                                'tag' => 'div',
                                'attributes' => ['class' => 'cover-layout-image'],
                                'children' => [
                                    [
                                        'field' => 'image'
                                    ]
                                ]
                            ],
                            [
                                'tag' => 'h1',
                                'attributes' => ['class' => 'cover-layout-headline'],
                                'children' => [
                                    [
                                        'field' => 'title'
                                    ]
                                ]
                            ],
                            [
                                'tag' => 'div',
                                'attributes' => ['class' => 'cover-layout-footer'],
                                'children' => [
                                    [
                                        'tag' => 'div',
                                        'attributes' => ['class' => 'cover-layout-description'],
                                        'children' => [
                                            [
                                                'field' => 'description'
                                            ]
                                        ]
                                    ],
                                    [
                                        'tag' => 'div',
                                        'attributes' => ['class' => 'cover-layout-buttons'],
                                        'children' => [
                                            [
                                                'tag' => 'div',
                                                'attributes' => ['class' => 'cover-layout-button-primary'],
                                                'children' => [
                                                    [
                                                        'field' => 'first_action'
                                                    ]
                                                ]
                                            ],
                                            [
                                                'tag' => 'div',
                                                'attributes' => ['class' => 'cover-layout-button-secondary'],
                                                'children' => [
                                                    [
                                                        'field' => 'second_action'
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ],
                                    [
                                        'tag' => 'div',
                                        'attributes' => ['class' => 'cover-layout-arrow'],
                                        'children' => [
                                            [
                                                'field' => 'arrow_action'
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
        ],
        [
            'id' => 4,
            'fields' => [],
            'structure' => [
                'layout' => 1,
                'attributes' => ['class' => 'cover-layout-4'],
            ],
        ],
        [
            'id' => 5,
            'fields' => [],
            'structure' => [
                'layout' => 1,
                'attributes' => ['class' => 'cover-layout-5'],
            ],
            'mobile_layout' => [
                [
                    'id' => 1,
                    'structure' => [],
                ],
                [
                    'id' => 2,
                    'structure' => [],
                ]
            ],
        ],
        [
            'id' => 6,
            'fields' => [],
            'structure' => [
                'layout' => 1,
                'attributes' => ['class' => 'cover-layout-6'],
            ],
            'mobile_layout' => [
                [
                    'id' => 1,
                    'structure' => [],
                ],
                [
                    'id' => 2,
                    'structure' => [],
                ]
            ],
        ],
        [
            'id' => 7,
            'fields' => [],
            'structure' => [
                'layout' => 1,
                'attributes' => ['class' => 'cover-layout-7'],
            ],
            'mobile_layout' => [
                [
                    'id' => 1,
                    'structure' => [],
                ],
                [
                    'id' => 2,
                    'structure' => [],
                ]
            ],
        ],
        [
            'id' => 8,
            'fields' => [],
            'structure' => [
                'layout' => 1,
                'attributes' => ['class' => 'cover-layout-8'],
            ],
            'mobile_layout' => [
                [
                    'id' => 1,
                    'structure' => [],
                ],
                [
                    'id' => 2,
                    'structure' => [],
                ]
            ],
        ],
        [
            'id' => 9,
            'fields' => [],
            'structure' => [
                'layout' => 1,
                'attributes' => ['class' => 'cover-layout-9'],
            ],
        ],
        [
            'id' => 10,
            'fields' => [],
            'structure' => [
                'layout' => 1,
                'attributes' => ['class' => 'cover-layout-10'],
            ],
        ],
        [
            'id' => 11,
            'fields' => [],
            'structure' => [
                'layout' => 1,
                'attributes' => ['class' => 'cover-layout-11'],
            ],
            'mobile_layout' => [
                [
                    'id' => 1,
                    'structure' => [],
                ],
                [
                    'id' => 2,
                    'structure' => [],
                ]
            ],
        ],
        [
            'id' => 12,
            'fields' => [],
            'structure' => [
                'layout' => 1,
                'attributes' => ['class' => 'cover-layout-12'],
            ],
            'mobile_layout' => [
                [
                    'id' => 1,
                    'structure' => [],
                ],
                [
                    'id' => 2,
                    'structure' => [],
                ]
            ],
        ],
    ]
];
