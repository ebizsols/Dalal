<?php

app()->booted(function () {
    theme_option()
        ->setField([
            'id'         => 'copyright',
            'section_id' => 'opt-text-subsection-general',
            'type'       => 'text',
            'label'      => __('Copyright'),
            'attributes' => [
                'name'    => 'copyright',
                'value'   => '© 2021 Botble Technologies. All right reserved.',
                'options' => [
                    'class'        => 'form-control',
                    'placeholder'  => __('Change copyright'),
                    'data-counter' => 250,
                ],
            ],
            'helper'     => __('Copyright on footer of site'),
        ])
        ->setField([
            'id'         => 'primary_font',
            'section_id' => 'opt-text-subsection-general',
            'type'       => 'googleFonts',
            'label'      => __('Primary font'),
            'attributes' => [
                'name'  => 'primary_font',
                'value' => 'Nunito Sans',
            ],
        ])
        ->setField([
            'id'         => 'primary_color',
            'section_id' => 'opt-text-subsection-general',
            'type'       => 'customColor',
            'label'      => __('Primary color'),
            'attributes' => [
                'name'  => 'primary_color',
                'value' => '#1d5f6f',
            ],
        ])
        ->setField([
            'id'         => 'primary_color_hover',
            'section_id' => 'opt-text-subsection-general',
            'type'       => 'customColor',
            'label'      => __('Hover primary color'),
            'attributes' => [
                'name'  => 'primary_color_hover',
                'value' => '#063a5d',
            ],
        ])
        ->setField([
            'id'         => 'about-us',
            'section_id' => 'opt-text-subsection-general',
            'type'       => 'textarea',
            'label'      => __('About us'),
            'attributes' => [
                'name'    => 'about-us',
                'value'   => null,
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ])
        ->setField([
            'id'         => 'hotline',
            'section_id' => 'opt-text-subsection-general',
            'type'       => 'text',
            'label'      => __('Hotline'),
            'attributes' => [
                'name'    => 'hotline',
                'value'   => null,
                'options' => [
                    'class'        => 'form-control',
                    'placeholder'  => 'Hotline',
                    'data-counter' => 30,
                ],
            ],
        ])
        ->setField([
            'id'         => 'address',
            'section_id' => 'opt-text-subsection-general',
            'type'       => 'text',
            'label'      => __('Address'),
            'attributes' => [
                'name'    => 'address',
                'value'   => null,
                'options' => [
                    'class'        => 'form-control',
                    'placeholder'  => 'Address',
                    'data-counter' => 120,
                ],
            ],
        ])
        ->setField([
            'id'         => 'email',
            'section_id' => 'opt-text-subsection-general',
            'type'       => 'email',
            'label'      => __('Email'),
            'attributes' => [
                'name'    => 'email',
                'value'   => null,
                'options' => [
                    'class'        => 'form-control',
                    'placeholder'  => 'Email',
                    'data-counter' => 120,
                ],
            ],
        ])
        ->setField([
            'id'         => 'enable_sticky_header',
            'section_id' => 'opt-text-subsection-general',
            'type'       => 'select',
            'label'      => __('Enable sticky header?'),
            'attributes' => [
                'name'    => 'enable_sticky_header',
                'list'    => [
                    'yes' => trans('core/base::base.yes'),
                    'no'  => trans('core/base::base.no'),
                ],
                'value'   => 'yes',
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ])
        ->setField([
            'id'         => 'show_map_on_properties_page',
            'section_id' => 'opt-text-subsection-general',
            'type'       => 'select',
            'label'      => __('Shop map on properties page?'),
            'attributes' => [
                'name'    => 'show_map_on_properties_page',
                'list'    => [
                    'yes' => trans('core/base::base.yes'),
                    'no'  => trans('core/base::base.no'),
                ],
                'value'   => 'yes',
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ])
        ->setField([
            'id'         => 'breadcrumb_background',
            'section_id' => 'opt-text-subsection-general',
            'type'       => 'mediaImage',
            'label'      => __('Breadcrumb background image (1920x280px)'),
            'attributes' => [
                'name'  => 'breadcrumb_background',
                'value' => null,
            ],
        ])
        ->setSection([
            'title'      => __('Social links'),
            'desc'       => __('Social links'),
            'id'         => 'opt-text-subsection-social-links',
            'subsection' => true,
            'icon'       => 'fa fa-share-alt',
        ])
        ->setField([
            'id'         => 'social_links',
            'section_id' => 'opt-text-subsection-social-links',
            'type'       => 'repeater',
            'label'      => __('Social links'),
            'attributes' => [
                'name'   => 'social_links',
                'value'  => null,
                'fields' => [
                    [
                        'type'       => 'text',
                        'label'      => __('Name'),
                        'attributes' => [
                            'name'    => 'social-name',
                            'value'   => null,
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ],
                    ],
                    [
                        'type'       => 'themeIcon',
                        'label'      => __('Icon'),
                        'attributes' => [
                            'name'    => 'social-icon',
                            'value'   => null,
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ],
                    ],
                    [
                        'type'       => 'text',
                        'label'      => __('URL'),
                        'attributes' => [
                            'name'    => 'social-url',
                            'value'   => null,
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ],
                    ],
                ],
            ],
        ])
        ->setSection([
            'title'      => __('Content'),
            'desc'       => __('Theme options for content'),
            'id'         => 'opt-text-subsection-homepage',
            'subsection' => true,
            'icon'       => 'fa fa-edit',
            'fields'     => [
                [
                    'id'         => 'number_of_featured_projects',
                    'type'       => 'number',
                    'label'      => __('Number of featured projects on homepage'),
                    'attributes' => [
                        'name'    => 'number_of_featured_projects',
                        'value'   => 4,
                        'options' => [
                            'class' => 'form-control',
                        ],
                    ],
                ],
                [
                    'id'         => 'number_of_featured_cities',
                    'type'       => 'number',
                    'label'      => __('Number of featured cities on homepage'),
                    'attributes' => [
                        'name'    => 'number_of_featured_cities',
                        'value'   => 10,
                        'options' => [
                            'class' => 'form-control',
                        ],
                    ],
                ],
                [
                    'id'         => 'number_of_properties_for_sale',
                    'type'       => 'number',
                    'label'      => __('Number of properties for sale on homepage'),
                    'attributes' => [
                        'name'    => 'number_of_properties_for_sale',
                        'value'   => 8,
                        'options' => [
                            'class' => 'form-control',
                        ],
                    ],
                ],
                [
                    'id'         => 'number_of_properties_for_rent',
                    'type'       => 'number',
                    'label'      => __('Number of properties for rent on homepage'),
                    'attributes' => [
                        'name'    => 'number_of_properties_for_rent',
                        'value'   => 8,
                        'options' => [
                            'class' => 'form-control',
                        ],
                    ],
                ],
                [
                    'id'         => 'number_of_projects_per_page',
                    'type'       => 'number',
                    'label'      => __('Number of projects per page'),
                    'attributes' => [
                        'name'    => 'number_of_projects_per_page',
                        'value'   => 12,
                        'options' => [
                            'class' => 'form-control',
                        ],
                    ],
                ],
                [
                    'id'         => 'number_of_properties_per_page',
                    'type'       => 'number',
                    'label'      => __('Number of properties per page'),
                    'attributes' => [
                        'name'    => 'number_of_properties_per_page',
                        'value'   => 15,
                        'options' => [
                            'class' => 'form-control',
                        ],
                    ],
                ],
                [
                    'id'         => 'number_of_related_projects',
                    'type'       => 'number',
                    'label'      => __('Number of related projects'),
                    'attributes' => [
                        'name'    => 'number_of_related_projects',
                        'value'   => 8,
                        'options' => [
                            'class' => 'form-control',
                        ],
                    ],
                ],
                [
                    'id'         => 'number_of_related_properties',
                    'type'       => 'number',
                    'label'      => __('Number of related properties'),
                    'attributes' => [
                        'name'    => 'number_of_related_properties',
                        'value'   => 8,
                        'options' => [
                            'class' => 'form-control',
                        ],
                    ],
                ],
                [
                    'id'         => 'home_project_description',
                    'type'       => 'textarea',
                    'label'      => __('The description for projects block'),
                    'attributes' => [
                        'name'    => 'home_project_description',
                        'value'   => null,
                        'options' => [
                            'class' => 'form-control',
                        ],
                    ],
                ],
                [
                    'id'         => 'properties_description',
                    'type'       => 'textarea',
                    'label'      => __('The description for properties block'),
                    'attributes' => [
                        'name'    => 'properties_description',
                        'value'   => null,
                        'options' => [
                            'class' => 'form-control',
                        ],
                    ],
                ],
                [
                    'id'         => 'home_description_for_projects_by_locations',
                    'type'       => 'textarea',
                    'label'      => __('The description for projects by locations block'),
                    'attributes' => [
                        'name'    => 'home_description_for_projects_by_locations',
                        'value'   => null,
                        'options' => [
                            'class' => 'form-control',
                        ],
                    ],
                ],
                [
                    'id'         => 'home_description_for_properties_by_locations',
                    'type'       => 'textarea',
                    'label'      => __('The description for properties by locations block'),
                    'attributes' => [
                        'name'    => 'home_description_for_properties_by_locations',
                        'value'   => null,
                        'options' => [
                            'class' => 'form-control',
                        ],
                    ],
                ],
                [
                    'id'         => 'home_description_for_properties_for_sale',
                    'type'       => 'textarea',
                    'label'      => __('The description for properties for sale block'),
                    'attributes' => [
                        'name'    => 'home_description_for_properties_for_sale',
                        'value'   => null,
                        'options' => [
                            'class' => 'form-control',
                        ],
                    ],
                ],
                [
                    'id'         => 'home_description_for_properties_for_rent',
                    'type'       => 'textarea',
                    'label'      => __('The description for properties for rent block'),
                    'attributes' => [
                        'name'    => 'home_description_for_properties_for_rent',
                        'value'   => null,
                        'options' => [
                            'class' => 'form-control',
                        ],
                    ],
                ],
                [
                    'id'         => 'home_description_for_news',
                    'type'       => 'textarea',
                    'label'      => __('The description for news block'),
                    'attributes' => [
                        'name'    => 'home_description_for_news',
                        'value'   => null,
                        'options' => [
                            'class' => 'form-control',
                        ],
                    ],
                ],
            ],
        ])
        ->setSection([
            'title'      => __('Home search box'),
            'desc'       => __('Theme options for search box on homepage'),
            'id'         => 'opt-text-subsection-homepage-search-box',
            'subsection' => true,
            'icon'       => 'fa fa-search',
            'fields'     => [
                [
                    'id'         => 'home_banner_description',
                    'type'       => 'text',
                    'label'      => __('The description for banner search block'),
                    'attributes' => [
                        'name'    => 'home_banner_description',
                        'value'   => null,
                        'options' => [
                            'class' => 'form-control',
                        ],
                    ],
                ],
                [
                    'id'         => 'home_banner',
                    'type'       => 'mediaImage',
                    'label'      => __('Top banner homepage (1920x600px)'),
                    'attributes' => [
                        'name'  => 'home_banner',
                        'value' => null,
                    ],
                ],
                [
                    'id'         => 'enable_search_projects_on_homepage_search',
                    'type'       => 'select',
                    'label'      => __('Enable search projects on homepage search box?'),
                    'attributes' => [
                        'name'    => 'enable_search_projects_on_homepage_search',
                        'list'    => [
                            'yes' => trans('core/base::base.yes'),
                            'no'  => trans('core/base::base.no'),
                        ],
                        'value'   => 'yes',
                        'options' => [
                            'class' => 'form-control',
                        ],
                    ],
                ],
            ],
        ]);

    // Facebook integration
    theme_option()
        ->setSection([
            'title'      => __('Facebook Integration'),
            'desc'       => __('Facebook Integration'),
            'id'         => 'opt-text-subsection-facebook-integration',
            'subsection' => true,
            'icon'       => 'fab fa-facebook',
        ])
        ->setField([
            'id'         => 'facebook_chat_enabled',
            'section_id' => 'opt-text-subsection-facebook-integration',
            'type'       => 'select',
            'label'      => __('Enable Facebook chat?'),
            'attributes' => [
                'name'    => 'facebook_chat_enabled',
                'list'    => [
                    'no'  => trans('core/base::base.no'),
                    'yes' => trans('core/base::base.yes'),
                ],
                'value'   => 'no',
                'options' => [
                    'class' => 'form-control',
                ],
            ],
            'helper'     => __('To show chat box on that website, please go to :link and add :domain to whitelist domains!',
                [
                    'domain' => Html::link(url('')),
                    'link'   => Html::link('https://www.facebook.com/' . theme_option('facebook_page_id') . '/settings/?tab=messenger_platform'),
                ]),
        ])
        ->setField([
            'id'         => 'facebook_page_id',
            'section_id' => 'opt-text-subsection-facebook-integration',
            'type'       => 'text',
            'label'      => __('Facebook page ID'),
            'attributes' => [
                'name'    => 'facebook_page_id',
                'value'   => null,
                'options' => [
                    'class' => 'form-control',
                ],
            ],
            'helper'     => __('You can get fan page ID using this site :link',
                ['link' => Html::link('https://findmyfbid.com')]),
        ])
        ->setField([
            'id'         => 'facebook_comment_enabled_in_post',
            'section_id' => 'opt-text-subsection-facebook-integration',
            'type'       => 'select',
            'label'      => __('Enable Facebook comment in post detail page?'),
            'attributes' => [
                'name'    => 'facebook_comment_enabled_in_post',
                'list'    => [
                    'yes' => trans('core/base::base.yes'),
                    'no'  => trans('core/base::base.no'),
                ],
                'value'   => 'no',
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ])
        ->setField([
            'id'         => 'facebook_app_id',
            'section_id' => 'opt-text-subsection-facebook-integration',
            'type'       => 'text',
            'label'      => __('Facebook App ID'),
            'attributes' => [
                'name'        => 'facebook_app_id',
                'value'       => null,
                'options'     => [
                    'class' => 'form-control',
                ],
                'placeholder' => 'Ex: 2061237023872679',
            ],
            'helper'     => __('You can create your app in :link',
                ['link' => Html::link('https://developers.facebook.com/apps')]),
        ])
        ->setField([
            'id'         => 'facebook_admins',
            'section_id' => 'opt-text-subsection-facebook-integration',
            'type'       => 'repeater',
            'label'      => __('Facebook Admins'),
            'attributes' => [
                'name'   => 'facebook_admins',
                'value'  => null,
                'fields' => [
                    [
                        'type'       => 'text',
                        'label'      => __('Facebook Admin ID'),
                        'attributes' => [
                            'name'    => 'text',
                            'value'   => null,
                            'options' => [
                                'class'        => 'form-control',
                                'data-counter' => 40,
                            ],
                        ],
                    ],
                ],
            ],
            'helper'     => __('Facebook admins to manage comments :link',
                ['link' => Html::link('https://developers.facebook.com/docs/plugins/comments')]),
        ]);

    add_filter(THEME_FRONT_HEADER, function ($html) {
        if (theme_option('facebook_app_id')) {
            $html .= Html::meta(null, theme_option('facebook_app_id'), ['property' => 'fb:app_id'])->toHtml();
        }

        if (theme_option('facebook_admins')) {
            foreach (json_decode(theme_option('facebook_admins'), true) as $facebookAdminId) {
                if (Arr::get($facebookAdminId, '0.value')) {
                    $html .= Html::meta(null, Arr::get($facebookAdminId, '0.value'), ['property' => 'fb:admins'])
                        ->toHtml();
                }
            }
        }

        return $html;
    }, 1180);

    add_filter(THEME_FRONT_FOOTER, function ($html) {
        return $html . Theme::partial('facebook-integration');
    }, 1180);
});
