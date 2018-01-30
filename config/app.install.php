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
                                'type' => 'Zend\Form\Element\Select',
                                'options' => array(
                                    'label' => 'tr_meliscms_tool_site_scheme',
                                    'tooltip' => 'tr_meliscms_tool_site_scheme tooltip',
                                    'value_options' => array(
                                        'http' => 'http://',
                                        'https' => 'https://',
                                    ),
                                ),
                                'attributes' => array(
                                    'id' => 'id_sdom_scheme',
                                    'value' => '',
                                    'required' => 'required',
                                ),
                            ),
                        ),
                        array(
                            'spec' => array(
                                'name' => 'sdom_domain',
                                'type' => 'MelisText',
                                'options' => array(
                                    'label' => 'tr_meliscms_tool_site_domain',
                                    'tooltip' => 'tr_meliscms_tool_site_domain tooltip',
                                ),
                                'attributes' => array(
                                    'id' => 'id_sdom_domain',
                                    'value' => '',
                                    'required' => 'required',
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
                        'pids_id' => array(
                            'name'     => 'pids_page_id_start',
                            'required' => false,
                            'validators' => array(
                                array(
                                    'name'    => 'Digits',
                                    'options' => array(
                                        'messages' => array(
                                            \Zend\Validator\Digits::NOT_DIGITS => 'tr_meliscms_tool_platform_not_digit',
                                            \Zend\Validator\Digits::STRING_EMPTY => '',
                                        ),
                                    ),
                                ),
                            ),
                            'filters'  => array(
                                array('name' => 'StripTags'),
                                array('name' => 'StringTrim'),
                            ),
                        ),
                        'pids_name_select' => array(
                            'name'     => 'pids_name_select',
                            'required' => true,
                            'validators' => array(
                                array(
                                    'name' => 'NotEmpty',
                                    'options' => array(
                                        'messages' => array(
                                            \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscms_tool_platform_empty',
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
                                    'name'    => 'StringLength',
                                    'options' => array(
                                        'encoding' => 'UTF-8',
                                        'max'      => 11,
                                        'messages' => array(
                                            \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscms_tool_platform_value_too_long',
                                        ),
                                    ),
                                ),
                                array(
                                    'name'    => 'Digits',
                                    'options' => array(
                                        'messages' => array(
                                            \Zend\Validator\Digits::NOT_DIGITS => 'tr_meliscms_tool_platform_not_digit',
                                            \Zend\Validator\Digits::STRING_EMPTY => '',
                                        ),
                                    ),
                                ),
                                array(
                                    'name' => 'NotEmpty',
                                    'options' => array(
                                        'messages' => array(
                                            \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscms_tool_platform_empty',

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
                                    'name'    => 'StringLength',
                                    'options' => array(
                                        'encoding' => 'UTF-8',
                                        'max'      => 11,
                                        'messages' => array(
                                            \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscms_tool_platform_value_too_long',
                                        ),
                                    ),
                                ),
                                array(
                                    'name'    => 'Digits',
                                    'options' => array(
                                        'messages' => array(
                                            \Zend\Validator\Digits::NOT_DIGITS => 'tr_meliscms_tool_platform_not_digit',
                                            \Zend\Validator\Digits::STRING_EMPTY => '',
                                        ),
                                    ),
                                ),
                                array(
                                    'name' => 'NotEmpty',
                                    'options' => array(
                                        'messages' => array(
                                            \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscms_tool_platform_empty',
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
                                    'name'    => 'StringLength',
                                    'options' => array(
                                        'encoding' => 'UTF-8',
                                        'max'      => 11,
                                        'messages' => array(
                                            \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscms_tool_platform_value_too_long',
                                        ),
                                    ),
                                ),
                                array(
                                    'name'    => 'Digits',
                                    'options' => array(
                                        'messages' => array(
                                            \Zend\Validator\Digits::NOT_DIGITS => 'tr_meliscms_tool_platform_not_digit',
                                            \Zend\Validator\Digits::STRING_EMPTY => '',
                                        ),
                                    ),
                                ),
                                array(
                                    'name' => 'NotEmpty',
                                    'options' => array(
                                        'messages' => array(
                                            \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscms_tool_platform_empty',
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
                                    'name'    => 'StringLength',
                                    'options' => array(
                                        'encoding' => 'UTF-8',
                                        'max'      => 11,
                                        'messages' => array(
                                            \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscms_tool_platform_value_too_long',
                                        ),
                                    ),
                                ),
                                array(
                                    'name'    => 'Digits',
                                    'options' => array(
                                        'messages' => array(
                                            \Zend\Validator\Digits::NOT_DIGITS => 'tr_meliscms_tool_platform_not_digit',
                                            \Zend\Validator\Digits::STRING_EMPTY => '',
                                        ),
                                    ),
                                ),
                                array(
                                    'name' => 'NotEmpty',
                                    'options' => array(
                                        'messages' => array(
                                            \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscms_tool_platform_empty',
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
                                    'name'    => 'StringLength',
                                    'options' => array(
                                        'encoding' => 'UTF-8',
                                        'max'      => 11,
                                        'messages' => array(
                                            \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscms_tool_platform_value_too_long',
                                        ),
                                    ),
                                ),
                                array(
                                    'name'    => 'Digits',
                                    'options' => array(
                                        'messages' => array(
                                            \Zend\Validator\Digits::NOT_DIGITS => 'tr_meliscms_tool_platform_not_digit',
                                            \Zend\Validator\Digits::STRING_EMPTY => '',
                                        ),
                                    ),
                                ),
                                array(
                                    'name' => 'NotEmpty',
                                    'options' => array(
                                        'messages' => array(
                                            \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscms_tool_platform_empty',
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
                                    'name'    => 'StringLength',
                                    'options' => array(
                                        'encoding' => 'UTF-8',
                                        'max'      => 11,
                                        'messages' => array(
                                            \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscms_tool_platform_value_too_long',
                                        ),
                                    ),
                                ),
                                array(
                                    'name'    => 'Digits',
                                    'options' => array(
                                        'messages' => array(
                                            \Zend\Validator\Digits::NOT_DIGITS => 'tr_meliscms_tool_platform_not_digit',
                                            \Zend\Validator\Digits::STRING_EMPTY => '',
                                        ),
                                    ),
                                ),
                                array(
                                    'name' => 'NotEmpty',
                                    'options' => array(
                                        'messages' => array(
                                            \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscms_tool_platform_empty',
                                        ),
                                    ),
                                ),
                            ),
                            'filters'  => array(
                                array('name' => 'StripTags'),
                                array('name' => 'StringTrim'),
                            ),
                        ),
                        'sdom_scheme' => array(
                            'name'     => 'sdom_scheme',
                            'required' => true,
                            'validators' => array(
                                array(
                                    'name'    => 'InArray',
                                    'options' => array(
                                        'haystack' => array('http', 'https'),
                                        'messages' => array(
                                            \Zend\Validator\InArray::NOT_IN_ARRAY => 'tr_meliscms_tool_site_scheme_invalid_selection',
                                        ),
                                    )
                                ),
                                array(
                                    'name'    => 'NotEmpty',
                                    'options' => array(
                                        'messages' => array(
                                            \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscms_tool_site_scheme_error_empty',
                                        ),
                                    ),
                                ),
                            ),
                            'filters'  => array(
                            ),
                        ),
                        'sdom_domain' => array(
                            'name'     => 'sdom_domain',
                            'required' => true,
                            'validators' => array(
                                array(
                                    'name'    => 'StringLength',
                                    'options' => array(
                                        'encoding' => 'UTF-8',
                                        'max'      => 50,
                                        'messages' => array(
                                            \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscms_tool_site_domain_error_long',
                                        ),
                                    ),
                                ),
                                array(
                                    'name' => 'NotEmpty',
                                    'options' => array(
                                        'messages' => array(
                                            \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscms_tool_site_domain_error_empty',
                                        ),
                                    ),
                                ),
                            ),
                            'filters'  => array(
                                array('name' => 'StripTags'),
                                array('name' => 'StringTrim'),
                            ),
                        ),
                    ),
                ), // end melis_installer_platform_id
            ),
        ),
    ),
);