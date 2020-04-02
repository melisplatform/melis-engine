<?php
return array(
    'plugins' => array(
        'melis_engine_setup' => array(
            'conf' => array(
                'rightsDisplay' => 'none'
            ),
            'forms' => array(
                'melis_installer_platform_data' => array(
                    'attributes' => array(
                        'name' => 'melis_installer_platform_data',
                        'id'   => 'melis_installer_platform_data',
                        'method' => 'POST',
                        'action' => '',
                    ),
                    'hydrator'  => 'Laminas\Stdlib\Hydrator\ArraySerializable',
                    'elements'  => array(
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
                                    'text-required' => '*',
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
                                    'text-required' => '*',
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
                                    'text-required' => '*',
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
                                    'text-required' => '*',
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
                                    'text-required' => '*',
                                )
                            )
                        ),
                        array(
                            'spec' => array(
                                'name' => 'pids_tpl_id_end',
                                'type' => 'MelisText',
                                'options' => array(
                                    'label' => 'tr_meliscms_tool_platform_pids_tpl_id_end',
                                    'tooltip' => 'tr_meliscms_tool_platform_pids_tpl_id_end tooltip',
                                ),
                                'attributes' => array(
                                    'id' => 'pids_tpl_id_end',
                                    'value' => '1000',
                                    'placeholder' => '1000',
                                    'text-required' => '*',
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
                                            \Laminas\Validator\Digits::NOT_DIGITS => 'tr_meliscms_tool_platform_not_digit',
                                            \Laminas\Validator\Digits::STRING_EMPTY => '',
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
                                            \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscms_tool_platform_value_too_long',
                                        ),
                                    ),
                                ),
                                array(
                                    'name'    => 'Digits',
                                    'options' => array(
                                        'messages' => array(
                                            \Laminas\Validator\Digits::NOT_DIGITS => 'tr_meliscms_tool_platform_not_digit',
                                            \Laminas\Validator\Digits::STRING_EMPTY => '',
                                        ),
                                    ),
                                ),
                                array(
                                    'name' => 'NotEmpty',
                                    'options' => array(
                                        'messages' => array(
                                            \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscms_tool_platform_empty',

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
                                            \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscms_tool_platform_value_too_long',
                                        ),
                                    ),
                                ),
                                array(
                                    'name'    => 'Digits',
                                    'options' => array(
                                        'messages' => array(
                                            \Laminas\Validator\Digits::NOT_DIGITS => 'tr_meliscms_tool_platform_not_digit',
                                            \Laminas\Validator\Digits::STRING_EMPTY => '',
                                        ),
                                    ),
                                ),
                                array(
                                    'name' => 'NotEmpty',
                                    'options' => array(
                                        'messages' => array(
                                            \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscms_tool_platform_empty',
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
                                            \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscms_tool_platform_value_too_long',
                                        ),
                                    ),
                                ),
                                array(
                                    'name'    => 'Digits',
                                    'options' => array(
                                        'messages' => array(
                                            \Laminas\Validator\Digits::NOT_DIGITS => 'tr_meliscms_tool_platform_not_digit',
                                            \Laminas\Validator\Digits::STRING_EMPTY => '',
                                        ),
                                    ),
                                ),
                                array(
                                    'name' => 'NotEmpty',
                                    'options' => array(
                                        'messages' => array(
                                            \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscms_tool_platform_empty',
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
                                            \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscms_tool_platform_value_too_long',
                                        ),
                                    ),
                                ),
                                array(
                                    'name'    => 'Digits',
                                    'options' => array(
                                        'messages' => array(
                                            \Laminas\Validator\Digits::NOT_DIGITS => 'tr_meliscms_tool_platform_not_digit',
                                            \Laminas\Validator\Digits::STRING_EMPTY => '',
                                        ),
                                    ),
                                ),
                                array(
                                    'name' => 'NotEmpty',
                                    'options' => array(
                                        'messages' => array(
                                            \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscms_tool_platform_empty',
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
                                            \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscms_tool_platform_value_too_long',
                                        ),
                                    ),
                                ),
                                array(
                                    'name'    => 'Digits',
                                    'options' => array(
                                        'messages' => array(
                                            \Laminas\Validator\Digits::NOT_DIGITS => 'tr_meliscms_tool_platform_not_digit',
                                            \Laminas\Validator\Digits::STRING_EMPTY => '',
                                        ),
                                    ),
                                ),
                                array(
                                    'name' => 'NotEmpty',
                                    'options' => array(
                                        'messages' => array(
                                            \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscms_tool_platform_empty',
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
                                            \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscms_tool_platform_value_too_long',
                                        ),
                                    ),
                                ),
                                array(
                                    'name'    => 'Digits',
                                    'options' => array(
                                        'messages' => array(
                                            \Laminas\Validator\Digits::NOT_DIGITS => 'tr_meliscms_tool_platform_not_digit',
                                            \Laminas\Validator\Digits::STRING_EMPTY => '',
                                        ),
                                    ),
                                ),
                                array(
                                    'name' => 'NotEmpty',
                                    'options' => array(
                                        'messages' => array(
                                            \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscms_tool_platform_empty',
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