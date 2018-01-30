<?php
return array(
    'plugins' => array(
        'melis_engine_setup' => array(
            'forms' => array(
                'melis_installer_platform_data' => array(
                    'attributes' => array(
                        'name' => 'melis_installer_platform_data',
                        'id'   => 'melis_installer_platform_data',
                        'method' => 'POST',
                        'action' => '',
                    ),
                    'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                    'elements'  => array(
                        array(
                            'spec' => array(
                                'name' => 'sdom_scheme',
                                'type' => 'text',
                                'options' => array(
                                    'label' => 'tr_meliscms_tool_site_scheme',
                                    'tooltip' => 'tr_meliscms_tool_site_scheme info',
                                ),
                                'attributes' => array(
                                    'id' => 'sdom_scheme',
                                    'value' => 'http',
                                    'placeholder' => 'http',
                                    'class' => 'form-control',
                                ),
                            ),
                        ),
                        array(
                            'spec' => array(
                                'name' => 'sdom_domain',
                                'type' => 'text',
                                'options' => array(
                                    'label' => 'tr_meliscms_tool_site_domain',
                                    'tooltip' => 'tr_meliscms_tool_site_domain toolti[',
                                ),
                                'attributes' => array(
                                    'id' => 'sdom_domain',
                                    'value' => 'sample.com',
                                    'placeholder' => 'sample.com',
                                    'class' => 'form-control',
                                ),
                            ),
                        ),
                        array(
                            'spec' => array(
                                'name' => 'pids_page_id_start',
                                'type' => 'MelisText',
                                'options' => array(
                                    'label' => 'tr_meliscms_tool_platform_pids_page_id_start',
                                    'tooltip' => 'tr_meliscms_tool_platform_pids_page_id_start tooltip',
                                ),
                                'attributes' => array(
                                    'id' => 'pids_page_id_start',
                                    'value' => '1',
                                    'placeholder' => '1',
                                )
                            )
                        ),
                        array(
                            'spec' => array(
                                'name' => 'pids_page_id_current',
                                'type' => 'MelisText',
                                'options' => array(
                                    'label' => 'tr_meliscms_tool_platform_pids_page_id_current',
                                    'tooltip' => 'tr_meliscms_tool_platform_pids_page_id_current tooltip',
                                ),
                                'attributes' => array(
                                    'id' => 'pids_page_id_current',
                                    'value' => '1',
                                    'placeholder' => '1',
                                    'class' => 'form-control',
                                ),
                            ),
                        ),
                        
                        array(
                            'spec' => array(
                                'name' => 'pids_page_id_end',
                                'type' => 'MelisText',
                                'options' => array(
                                    'label' => 'tr_meliscms_tool_platform_pids_page_id_end',
                                    'tooltip' => 'tr_meliscms_tool_platform_pids_page_id_end tooltip',
                                ),
                                'attributes' => array(
                                    'id' => 'pids_page_id_end',
                                    'value' => '1000',
                                    'placeholder' => '1000',
                                    'class' => 'form-control',
                                ),
                            ),
                        ),
                        array(
                            'spec' => array(
                                'name' => 'pids_tpl_id_start',
                                'type' => 'MelisText',
                                'options' => array(
                                    'label' => 'tr_meliscms_tool_platform_pids_tpl_id_start',
                                    'tooltip' => 'tr_meliscms_tool_platform_pids_tpl_id_start tooltip',
                                ),
                                'attributes' => array(
                                    'id' => 'pids_tpl_id_start',
                                    'value' => '1',
                                    'placeholder' => '1',
                                    'class' => 'form-control',
                                ),
                            ),
                        ),
                        array(
                            'spec' => array(
                                'name' => 'pids_tpl_id_current',
                                'type' => 'MelisText',
                                'options' => array(
                                    'label' => 'tr_meliscms_tool_platform_pids_tpl_id_current',
                                    'tooltip' => 'tr_meliscms_tool_platform_pids_tpl_id_current tooltip',
                                ),
                                'attributes' => array(
                                    'id' => 'pids_tpl_id_current',
                                    'value' => '1',
                                    'placeholder' => '1',
                                )
                            )
                        ),
                        array(
                            'spec' => array(
                                'name' => 'pids_tpl_id_end',
                                'type' => 'MelisText',
                                'options' => array(
                                    'label' => 'tr_meliscms_tool_platform_pids_tpl_id_end',
                                    'tooltip' => 'tr_meliscms_tool_platform_pids_tpl_id_end info',
                                ),
                                'attributes' => array(
                                    'id' => 'pids_tpl_id_end',
                                    'value' => '1000',
                                    'placeholder' => '1000',
                                )
                            )
                        ),
                    ), // end elements
                    'input_filter' => array(
                        'sdom_scheme' => array(
                            'name'     => 'sdom_scheme',
                            'required' => true,
                            'validators' => array(
                                array(
                                    'name' => 'NotEmpty',
                                    'options' => array(
                                        'messages' => array(
                                            \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_scheme_install_empty',
                                        ),
                                    ),
                                ),
                            ),
                            'filters'  => array(
                                array('name' => 'StripTags'),
                                array('name' => 'StringTrim'),
                            ),
                        ),
                        'sdom_domain' => array(
                            'name'     => 'sdom_domain',
                            'required' => true,
                            'validators' => array(
                                array(
                                    'name' => 'NotEmpty',
                                    'options' => array(
                                        'messages' => array(
                                            \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_domain_install_sdom_domain_empty',
                                        ),
                                    ),
                                ),
                            ),
                            'filters'  => array(
                                array('name' => 'StripTags'),
                                array('name' => 'StringTrim'),
                            ),
                        ),
                        'pids_page_id_start' => array(
                            'name'     => 'pids_page_id_start',
                            'required' => true,
                            'validators' => array(
                                array(
                                    'name' => 'IsInt',
                                    'options' => array(
                                        'messages' => array(
                                            \Zend\I18n\Validator\IsInt::NOT_INT => 'pids_page_id_start must be an Integer'
                                        ),
                                    ),
                                ),
                                array(
                                    'name' => 'NotEmpty',
                                    'options' => array(
                                        'messages' => array(
                                            \Zend\Validator\NotEmpty::IS_EMPTY => 'pids_page_id_start must not be empty',
                                        ),
                                    ),
                                ),
                            ),
                            'filters'  => array(
                                array('name' => 'StripTags'),
                                array('name' => 'StringTrim'),
                            ),
                        ),
                        'pids_page_id_current' => array(
                            'name'     => 'pids_page_id_current',
                            'required' => true,
                            'validators' => array(
                                array(
                                    'name' => 'IsInt',
                                    'options' => array(
                                        'messages' => array(
                                            \Zend\I18n\Validator\IsInt::NOT_INT => 'pids_page_id_current must be an Integer'
                                        ),
                                    ),
                                ),
                                array(
                                    'name' => 'NotEmpty',
                                    'options' => array(
                                        'messages' => array(
                                            \Zend\Validator\NotEmpty::IS_EMPTY => 'pids_page_id_current must not be empty',
                                        ),
                                    ),
                                ),
                            ),
                            'filters'  => array(
                                array('name' => 'StripTags'),
                                array('name' => 'StringTrim'),
                            ),
                        ),
                        'pids_page_id_end' => array(
                            'name'     => 'pids_page_id_end',
                            'required' => true,
                            'validators' => array(
                                array(
                                    'name' => 'IsInt',
                                    'options' => array(
                                        'messages' => array(
                                            \Zend\I18n\Validator\IsInt::NOT_INT => 'pids_page_id_end must be an Integer'
                                        ),
                                    ),
                                ),
                                array(
                                    'name' => 'NotEmpty',
                                    'options' => array(
                                        'messages' => array(
                                            \Zend\Validator\NotEmpty::IS_EMPTY => 'pids_page_id_end must not be empty',
                                        ),
                                    ),
                                ),
                            ),
                            'filters'  => array(
                                array('name' => 'StripTags'),
                                array('name' => 'StringTrim'),
                            ),
                        ),
                        'pids_tpl_id_start' => array(
                            'name'     => 'pids_tpl_id_start',
                            'required' => true,
                            'validators' => array(
                                array(
                                    'name' => 'IsInt',
                                    'options' => array(
                                        'messages' => array(
                                            \Zend\I18n\Validator\IsInt::NOT_INT => 'pids_tpl_id_start must be an Integer'
                                        ),
                                    ),
                                ),
                                array(
                                    'name' => 'NotEmpty',
                                    'options' => array(
                                        'messages' => array(
                                            \Zend\Validator\NotEmpty::IS_EMPTY => 'pids_tpl_id_start must not be empty',
                                        ),
                                    ),
                                ),
                            ),
                            'filters'  => array(
                                array('name' => 'StripTags'),
                                array('name' => 'StringTrim'),
                            ),
                        ),
                        'pids_tpl_id_current' => array(
                            'name'     => 'pids_tpl_id_current',
                            'required' => true,
                            'validators' => array(
                                array(
                                    'name' => 'IsInt',
                                    'options' => array(
                                        'messages' => array(
                                            \Zend\I18n\Validator\IsInt::INVALID => 'pids_tpl_id_current must be an Integer'
                                        ),
                                    ),
                                ),
                                array(
                                    'name' => 'NotEmpty',
                                    'options' => array(
                                        'messages' => array(
                                            \Zend\Validator\NotEmpty::IS_EMPTY => 'pids_tpl_id_current must not be empty',
                                        ),
                                    ),
                                ),
                            ),
                            'filters'  => array(
                                array('name' => 'StripTags'),
                                array('name' => 'StringTrim'),
                            ),
                        ),
                        'pids_tpl_id_end' => array(
                            'name'     => 'pids_tpl_id_end',
                            'required' => true,
                            'validators' => array(
                                array(
                                    'name' => 'IsInt',
                                    'options' => array(
                                        'messages' => array(
                                            \Zend\I18n\Validator\IsInt::NOT_INT => 'pids_tpl_id_end must be an Integer'
                                        ),
                                    ),
                                ),
                                array(
                                    'name' => 'NotEmpty',
                                    'options' => array(
                                        'messages' => array(
                                            \Zend\Validator\NotEmpty::IS_EMPTY => 'pids_tpl_id_end must not be empty',
                                        ),
                                    ),
                                ),
                            ),
                            'filters'  => array(
                                array('name' => 'StripTags'),
                                array('name' => 'StringTrim'),
                            ),
                        ),
                    ), // end input_filter
                ), // end melis_installer_platform_id
            ),
        ),
    ),
);