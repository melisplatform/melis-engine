<?php 
return array(
	'plugins' => array(
		'microservice' => array(
			'MelisEngine' => array(
				'MelisPageService' => array(
				    '_description' => 'tr_melisengine_ws_desc_page',
					/**
					 * method getDatasPage
					 * @param idPage (required)
					 * @param type 
					 */
					'getDatasPage' => array(
						'attributes' => array(
							'name'	=> 'microservice_form',
							'id'	=> 'microservice_form',
							'method'=> 'POST',
							'action'=> $_SERVER['REQUEST_URI'],
						),
						'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
						'elements' => array(
							array(
								'spec' => array(
									'name' => 'idPage',
									'type' => 'Text',
									'options' => array(
										'label' => 'idPage',
									),
									'attributes' => array(
										'id' => 'idPage',
										'value' => '',
										'class' => '',
										'placeholder' => '10',
										'data-type' => 'int',
									),
								),
							),
						),
						'input_filter' => array(
							'idPage' => array(
								'name' => 'idPage',
								'required' => true,
								'validators' => array(
									array(
										'name' => 'IsInt',
										'options' => array(
											'message' => array(
												\Laminas\I18n\Validator\IsInt::INVALID => 'idPage must be an integer'
											),
										),
									),
								),
								'filters' => array(
									array('name' => 'StripTags'),
									array('name' => 'StringTrim')
								),
							),
						),
					),
                    'searchPage' => array(
                        'attributes' => array('name' => 'microservice_form', 'id' => 'microservice_form', 'method' => 'POST', 'action' => $_SERVER['REQUEST_URI']),
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => array(
                            array('spec' => array('name' => 'value', 'type' => 'Text', 'options' => array('label' => 'value'), 'attributes' => array('id' => 'value', 'value' => '', 'placeholder' => 'home', 'data-type' => 'string'))),
                        ),
                        'input_filter' => array(
                            'value' => array('name' => 'value', 'required' => true, 'validators' => array(array('name' => 'NotEmpty')), 'filters' => array(array('name' => 'StripTags'), array('name' => 'StringTrim'))),
                        ),
                    ),
                    'getPageLanguageList' => array(
                        'attributes' => array('name' => 'microservice_form', 'id' => 'microservice_form', 'method' => 'POST', 'action' => $_SERVER['REQUEST_URI']),
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => array(
                            array('spec' => array('name' => 'pageId', 'type' => 'Text', 'options' => array('label' => 'pageId'), 'attributes' => array('id' => 'pageId', 'value' => '', 'placeholder' => '10', 'data-type' => 'int'))),
                        ),
                        'input_filter' => array(
                            'pageId' => array('name' => 'pageId', 'required' => true, 'validators' => array(array('name' => 'IsInt')), 'filters' => array(array('name' => 'StripTags'), array('name' => 'StringTrim'))),
                        ),
                    ),
                    'getPageLanguageById' => array(
                        'attributes' => array('name' => 'microservice_form', 'id' => 'microservice_form', 'method' => 'POST', 'action' => $_SERVER['REQUEST_URI']),
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => array(
                            array('spec' => array('name' => 'pageId', 'type' => 'Text', 'options' => array('label' => 'pageId'), 'attributes' => array('id' => 'pageId', 'value' => '', 'placeholder' => '10', 'data-type' => 'int'))),
                            array('spec' => array('name' => 'langId', 'type' => 'Text', 'options' => array('label' => 'langId'), 'attributes' => array('id' => 'langId', 'value' => '', 'placeholder' => '1', 'data-type' => 'int'))),
                        ),
                        'input_filter' => array(
                            'pageId' => array('name' => 'pageId', 'required' => true, 'validators' => array(array('name' => 'IsInt')), 'filters' => array(array('name' => 'StripTags'), array('name' => 'StringTrim'))),
                            'langId' => array('name' => 'langId', 'required' => true, 'validators' => array(array('name' => 'IsInt')), 'filters' => array(array('name' => 'StripTags'), array('name' => 'StringTrim'))),
                        ),
                    ),
                    'getPageById' => array(
                        'attributes' => array('name' => 'microservice_form', 'id' => 'microservice_form', 'method' => 'POST', 'action' => $_SERVER['REQUEST_URI']),
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => array(
                            array('spec' => array('name' => 'idPage', 'type' => 'Text', 'options' => array('label' => 'idPage'), 'attributes' => array('id' => 'idPage', 'value' => '', 'placeholder' => '10', 'data-type' => 'int'))),
                        ),
                        'input_filter' => array(
                            'idPage' => array('name' => 'idPage', 'required' => true, 'validators' => array(array('name' => 'IsInt')), 'filters' => array(array('name' => 'StripTags'), array('name' => 'StringTrim'))),
                        ),
                    ),
				),
				'MelisTreeService' => array(
				    '_description' => 'tr_melisengine_ws_desc_tree',
					/**
					 * method getPageChildren
					 * @param idPage (required)
					 * @param publishedOnly 
					 */
					'getPageChildren' => array(
						'attributes' => array(
							'name'	=> 'microservice_form',
							'id'	=> 'microservice_form',
							'method'=> 'POST',
							'action'=> $_SERVER['REQUEST_URI'],
						),
						'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
						'elements' => array(
							array(
								'spec' => array(
									'name' => 'idPage',
									'type' => 'Text',
									'options' => array(
										'label' => 'idPage',
									),
									'attributes' => array(
										'id' => 'idPage',
										'value' => '',
										'class' => '',
										'placeholder' => '10',
										'data-type' => 'int'
									),
								),
							),
							array(
								'spec' => array(
									'name' => 'publishedOnly',
									'type' => 'Text',
									'options' => array(
										'label' => 'publishedOnly',
									),
									'attributes' => array(
										'id' => 'publishedOnly',
										'value' => '',
										'class' => '',
										'placeholder' => '0',
										'data-type' => 'bool'
									),
								),
							),
						),
						'input_filter' => array(
							'idPage' => array(
								'name' => 'idPage',
								'required' => true,
								'validators' => array(
									array(
										'name' => 'IsInt',
										'options' => array(
											'message' => array(
												\Laminas\I18n\Validator\IsInt::INVALID => 'idPage must be an integer'
											),
										),
									),
								),
								'filters' => array(
									array('name' => 'StripTags'),
									array('name' => 'StringTrim')
								),
							),
							'publishedOnly' => array(
								'name' => 'publishedOnly',
								'required' => false,
								'validators' => array(
									array(
										'name' => 'NotEmpty',
										'options' => array(
											'message' => array(
												\Laminas\Validator\NotEmpty::IS_EMPTY => '0 or 1' 
											),
										),
									),
								),
								'filters' => array(
									array('name' => 'StripTags'),
									array('name' => 'StringTrim')
								),
							),
						),
					),
					/**
					 * method getPageFather
					 * @param idPage (required)
					 * @param type
					 */
					'getPageFather' => array(
						'attributes' => array(
							'name'	=> 'microservice_form',
							'id'	=> 'microservice_form',
							'method'=> 'POST',
							'action'=> $_SERVER['REQUEST_URI'],
						),
						'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
						'elements' => array(
							array(
								'spec' => array(
									'name' => 'idPage ',
									'type' => 'Text',
									'options' => array(
										'label' => 'idPage',
									),
									'attributes' => array(
										'id' => 'idPage',
										'value' => '',
										'class' => '',
										'placeholder' => '10',
										'data-type' => 'int'
									),
								),
							),
							array(
								'spec' => array(
									'name' => 'type',
									'type' => 'Text',
									'options' => array(
										'label' => 'type',
									),
									'attributes' => array(
										'id' => 'type',
										'value' => '',
										'class' => '',
										'placeholder' => 'published',
										'data-type' => 'string'
									),
								),
							),
						),
						'input_filter' => array(
							'idPage' => array(
								'name' => 'idPage',
								'required' => true,
								'validators' => array(
									array(
										'name' => 'IsInt',
										'options' => array(
											'message' => array(
												\Laminas\I18n\Validator\IsInt::INVALID => 'idPage must be an Integer'
											),
										),
									),
								),
								'filters' => array(
									array('name' => 'StripTags'),
									array('name' => 'StringTrim')
								),
							),
							'type' => array(
								'name' => 'type',
								'required' => false,
								'validators' => array(
									array(
										'name' => 'NotEmpty',
										'options' => array(
											'message' => array(
												\Laminas\Validator\NotEmpty::IS_EMPTY => 'Please enter type'
											),
										),
									),
								),
								'filters' => array(
									array('name' => 'StripTags'),
									array('name' => 'StringTrim')
								),
							),
						),
					),
					/**
					 * method getPageBreadcrumb
					 * @param idpage (required)
					 * @param typeLinkOnly
					 * @param allPages
					 */
					'getPageBreadcrumb' => array(
						'attributes' => array(
							'name'	=> 'microservice_form',
							'id'	=> 'microservice_form',
							'method'=> 'POST',
							'action'=> $_SERVER['REQUEST_URI'],
						),
						'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
						'elements' => array(
							array(
								'spec' => array(
									'name' => 'idPage',
									'type' => 'Text',
									'options' => array(
										'label' => 'idPage',
									),
									'attributes' => array(
										'id' => 'idPage',
										'value' => '',
										'class' => '',
										'placeholder' => '3',
										'data-type' => 'int'
									),
								),
							),
							array(
								'spec' => array(
									'name' => 'typeLinkOnly',
									'type' => 'Text',
									'options' => array(
										'label' => 'typeLinkOnly',
									),
									'attributes' => array(
										'id' => 'typeLinkOnly',
										'value' => '',
										'class' => '',
										'placeholder' => '1',
										'data-type' => 'bool'
									),
								),
							),
							array(
								'spec' => array(
									'name' => 'allPages',
									'type' => 'Text',
									'options' => array(
										'label' => 'allPages',
									),
									'attributes' => array(
										'id' => 'allPages',
										'value' => '',
										'class' => '',
										'placeholder' => 'true',
										'data-type' => 'bool'
									),
								),
							),
						),
						'input_filter' => array(
							'idPage' => array(
								'name' => 'idPage',
								'required' => true,
								'validators' => array(
									array(
										'name' => 'IsInt',
										'options' => array(
											'message' => array(
												\Laminas\I18n\Validator\IsInt::INVALID => 'idPage must be an integer'
											),
										),
									),
								),
								'filters' => array(
									array('name' => 'StripTags'),
									array('name' => 'StringTrim')
								),
							),
							'typeLinkOnly' => array(
								'name' => 'typeLinkOnly',
								'required' => false,
								'validators' => array(
									array(
										'name' => 'NotEmpty',
										'options' => array(
											'message' => array(
												\Laminas\Validator\NotEmpty::IS_EMPTY => ''
											),
										),
									),
								),
								'filters' => array(
									array('name' => 'StripTags'),
									array('name' => 'StringTrim')
								),
							),
							'allPages' => array(
								'name' => 'allPages',
								'required' => false,
								'validators' => array(
									array(
										'name' => 'NotEmpty',
										'options' => array(
											'message' => array(
												\Laminas\Validator\NotEmpty::IS_EMPTY => ''
											),
										),
									),
								),
								'filters' => array(
									array('name' => 'StripTags'),
									array('name' => 'StringTrim')
								),
							),
						),
					),
					/**
					 * method getPageLink
					 * @param idPage (required)
					 * @param absolute
					 */
					'getPageLink' => array(
						'attributes' => array(
							'name'	=> 'microservice_form',
							'id'	=> 'microservice_form',
							'method'=> 'POST',
							'action'=> $_SERVER['REQUEST_URI'],
						),
						'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
						'elements' => array(
							array(
								'spec' => array(
									'name' => 'idPage',
									'type' => 'Text',
									'options' => array(
										'label' => 'idPage',
									),
									'attributes' => array(
										'id' => 'idPage',
										'value' => '',
										'class' => '',
										'placeholder' => '3',
										'data-type' => 'int'
									),
								),
							),
							array(
								'spec' => array(
									'name' => 'absolute',
									'type' => 'Text',
									'options' => array(
										'label' => 'absolute',
									),
									'attributes' => array(
										'id' => 'absolute',
										'value' => '',
										'class' => '',
										'placeholder' => 'false',
										'data-type' => 'bool'
									),
								),
							),
						),
						'input_filter' => array(
							'idPage' => array(
								'name' => 'idPage',
								'required' => true,
								'validators' => array(
									array(
										'name' => 'IsInt',
										'options' => array(
											'message' => array(
												\Laminas\I18n\Validator\IsInt::INVALID => 'idPage must be an integer'
											),
										),
									),
								),
								'filters' => array(
									array('name' => 'StripTags'),
									array('name' => 'StringTrim')
								),
							),
							'absolute' => array(
								'name' => 'absolute',
								'required' => false,
								'validators' => array(
									array(
										'name' => 'NotEmpty',
										'options' => array(
											'message' => array(
												\Laminas\Validator\NotEmpty::IS_EMPTY => ''
											),
										),
									),
								),
								'filters' => array(
									array('name' => 'StripTags'),
									array('name' => 'StringTrim')
								),
							),
						),
					),
					/**
					 * method  getDomainByPageId
					 * @param idPage (requried)
					 */
					'getDomainByPageId' => array(
						'attributes' => array(
							'name'	=> 'microservice_form',
							'id'	=> 'microservice_form',
							'method'=> 'POST',
							'action'=> $_SERVER['REQUEST_URI'],
						),
						'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
						'elements' => array(
							array(
								'spec' => array(
									'name' => 'idPage',
									'type' => 'Text',
									'options' => array(
										'label' => 'idPage',
									),
									'attributes' => array(
										'id' => 'idPage',
										'value' => '',
										'class' => '',
										'placeholder' => '3',
										'data-type' => 'int'
									),
								),
							),
						),
						'input_filter' => array(
							'idPage' => array(
								'name' => 'idPage',
								'required' => true,
								'validators' => array(
									array(
										'name' => 'IsInt',
										'options' => array(
											'message' => array(
												\Laminas\I18n\Validator\IsInt::INVALID => 'idPage must be an integer'
											),
										),
									),
								),
								'filters' => array(
									array('name' => 'StripTags'),
									array('name' => 'StringTrim')
								),
							),
						),
					),
					/**
					 * method  getSiteByPageId
					 * @param idPage (required)
					 */
					'getSiteByPageId' => array(
						'attributes' => array(
							'name'	=> 'microservice_form',
							'id'	=> 'microservice_form',
							'method'=> 'POST',
							'action'=> $_SERVER['REQUEST_URI'],
						),
						'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
						'elements' => array(
							array(
								'spec' => array(
									'name' => 'idPage',
									'type' => 'Text',
									'options' => array(
										'label' => 'idPage',
									),
									'attributes' => array(
										'id' => 'idPage',
										'value' => '',
										'class' => '',
										'placeholder' => '3',
										'data-type' => 'int'
									),
								),
							),
						),
						'input_filter' => array(
							'idPage' => array(
								'name' => 'idPage',
								'required' => true,
								'validators' => array(
									array(
										'name' => 'IsInt',
										'options' => array(
											'message' => array(
												\Laminas\I18n\Validator\IsInt::INVALID => 'idPage must be an integer'
											),
										),
									),
								),
								'filters' => array(
									array('name' => 'StripTags'),
									array('name' => 'StringTrim')
								),
							),
						),
					),
					/**
					 * method getPrevNextPage
					 * @param idPage (requried)
					 * @param publishedOnly
					 */
					'getPrevNextPage' => array(
						'attributes' => array(
							'name'	=> 'microservice_form',
							'id'	=> 'microservice_form',
							'method'=> 'POST',
							'action'=> $_SERVER['REQUEST_URI'],
						),
						'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
						'elements' => array(
							array(
								'spec' => array(
									'name' => 'idPage',
									'type' => 'Text',
									'options' => array(
										'label' => 'idPage',
									),
									'attributes' => array(
										'id' => 'idPage',
										'value' => '',
										'class' => '',
										'placeholder' => '3',
										'data-type' => 'int'
									),
								),
							),
							array(
								'spec' => array(
									'name' => 'publishedOnly',
									'type' => 'Text',
									'options' => array(
										'label' => 'publishedOnly',
									),
									'attributes' => array(
										'id' => 'publishedOnly',
										'value' => '',
										'class' => '',
										'placeholder' => '1',
										'data-type' => 'bool'
									),
								),
							),
						),
						'input_filter' => array(
							'idPage' => array(
								'name' => 'idPage',
								'required' => true,
								'validators' => array(
									array(
										'name' => 'IsInt',
										'options' => array(
											'message' => array(
												\Laminas\I18n\Validator\IsInt::INVALID => 'idPage must be an integer'
											),
										),
									),
								),
								'filters' => array(
									array('name' => 'StripTags'),
									array('name' => 'StringTrim')
								),
							),
							'publishedOnly' => array(
								'name' => 'publishedOnly',
								'required' => false,
								'validators' => array(
									array(
										'name' => 'NotEmpty',
										'options' => array(
											'message' => array(
												\Laminas\Validator\NotEmpty::IS_EMPTY => ''
											),
										),
									),
								),
								'filters' => array(
									array('name' => 'StripTags'),
									array('name' => 'StringTrim')
								),
							),
						),
					),
                    'getAllPages' => array(
                        'attributes' => array('name' => 'microservice_form', 'id' => 'microservice_form', 'method' => 'POST', 'action' => $_SERVER['REQUEST_URI']),
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => array(
                            array('spec' => array('name' => 'idPage', 'type' => 'Text', 'options' => array('label' => 'idPage'), 'attributes' => array('id' => 'idPage', 'value' => '', 'placeholder' => '3', 'data-type' => 'int'))),
                        ),
                        'input_filter' => array(
                            'idPage' => array('name' => 'idPage', 'required' => true, 'validators' => array(array('name' => 'IsInt')), 'filters' => array(array('name' => 'StripTags'), array('name' => 'StringTrim'))),
                        ),
                    ),
                    'getHomePageLink' => array(
                        'attributes' => array('name' => 'microservice_form', 'id' => 'microservice_form', 'method' => 'POST', 'action' => $_SERVER['REQUEST_URI']),
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => array(
                            array('spec' => array('name' => 'idPage', 'type' => 'Text', 'options' => array('label' => 'idPage'), 'attributes' => array('id' => 'idPage', 'value' => '', 'placeholder' => '3', 'data-type' => 'int'))),
                        ),
                        'input_filter' => array(
                            'idPage' => array('name' => 'idPage', 'required' => true, 'validators' => array(array('name' => 'IsInt')), 'filters' => array(array('name' => 'StripTags'), array('name' => 'StringTrim'))),
                        ),
                    ),
                    'getPageLinkByLocale' => array(
                        'attributes' => array('name' => 'microservice_form', 'id' => 'microservice_form', 'method' => 'POST', 'action' => $_SERVER['REQUEST_URI']),
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => array(
                            array('spec' => array('name' => 'idPage', 'type' => 'Text', 'options' => array('label' => 'idPage'), 'attributes' => array('id' => 'idPage', 'value' => '', 'placeholder' => '3', 'data-type' => 'int'))),
                            array('spec' => array('name' => 'locale', 'type' => 'Text', 'options' => array('label' => 'locale'), 'attributes' => array('id' => 'locale', 'value' => '', 'placeholder' => 'en_EN', 'data-type' => 'string'))),
                        ),
                        'input_filter' => array(
                            'idPage' => array('name' => 'idPage', 'required' => true, 'validators' => array(array('name' => 'IsInt')), 'filters' => array(array('name' => 'StripTags'), array('name' => 'StringTrim'))),
                            'locale' => array('name' => 'locale', 'required' => true, 'validators' => array(array('name' => 'NotEmpty')), 'filters' => array(array('name' => 'StripTags'), array('name' => 'StringTrim'))),
                        ),
                    ),
				),
                'MelisEngineLangService' => array(
                    '_description' => 'tr_melisengine_ws_desc_lang',
                    'getLocaleByLangId' => array(
                        'attributes' => array('name' => 'microservice_form', 'id' => 'microservice_form', 'method' => 'POST', 'action' => $_SERVER['REQUEST_URI']),
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => array(
                            array('spec' => array('name' => 'langId', 'type' => 'Text', 'options' => array('label' => 'langId'), 'attributes' => array('id' => 'langId', 'value' => '', 'placeholder' => '1', 'data-type' => 'int'))),
                        ),
                        'input_filter' => array(
                            'langId' => array('name' => 'langId', 'required' => true, 'validators' => array(array('name' => 'IsInt')), 'filters' => array(array('name' => 'StripTags'), array('name' => 'StringTrim'))),
                        ),
                    ),
                    'getLangByLocale' => array(
                        'attributes' => array('name' => 'microservice_form', 'id' => 'microservice_form', 'method' => 'POST', 'action' => $_SERVER['REQUEST_URI']),
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => array(
                            array('spec' => array('name' => 'locale', 'type' => 'Text', 'options' => array('label' => 'locale'), 'attributes' => array('id' => 'locale', 'value' => '', 'placeholder' => 'en_EN', 'data-type' => 'string'))),
                        ),
                        'input_filter' => array(
                            'locale' => array('name' => 'locale', 'required' => true, 'validators' => array(array('name' => 'NotEmpty')), 'filters' => array(array('name' => 'StripTags'), array('name' => 'StringTrim'))),
                        ),
                    ),
                    'getSiteLanguage' => array(
                        'attributes' => array('name' => 'microservice_form', 'id' => 'microservice_form', 'method' => 'POST', 'action' => $_SERVER['REQUEST_URI']),
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => array(
                            array('spec' => array('name' => '_trigger', 'type' => 'Hidden', 'options' => array('label' => '_trigger'), 'attributes' => array('id' => '_trigger', 'value' => '1', 'placeholder' => '', 'data-type' => ''))),
                        ),
                        'input_filter' => array(
                            '_trigger' => array('name' => '_trigger', 'required' => false, 'validators' => array(), 'filters' => array(array('name' => 'StripTags'), array('name' => 'StringTrim'))),
                        ),
                    ),
                    'getLangDataById' => array(
                        'attributes' => array('name' => 'microservice_form', 'id' => 'microservice_form', 'method' => 'POST', 'action' => $_SERVER['REQUEST_URI']),
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => array(
                            array('spec' => array('name' => 'langId', 'type' => 'Text', 'options' => array('label' => 'langId'), 'attributes' => array('id' => 'langId', 'value' => '', 'placeholder' => '1', 'data-type' => 'int'))),
                        ),
                        'input_filter' => array(
                            'langId' => array('name' => 'langId', 'required' => true, 'validators' => array(array('name' => 'IsInt')), 'filters' => array(array('name' => 'StripTags'), array('name' => 'StringTrim'))),
                        ),
                    ),
                ),
                'MelisEnginePageDefaultUrlsService' => array(
                    '_description' => 'tr_melisengine_ws_desc_pagedefaulturls',
                    'getPageDefaultUrl' => array(
                        'attributes' => array('name' => 'microservice_form', 'id' => 'microservice_form', 'method' => 'POST', 'action' => $_SERVER['REQUEST_URI']),
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => array(
                            array('spec' => array('name' => 'pageId', 'type' => 'Text', 'options' => array('label' => 'pageId'), 'attributes' => array('id' => 'pageId', 'value' => '', 'placeholder' => '10', 'data-type' => 'int'))),
                        ),
                        'input_filter' => array(
                            'pageId' => array('name' => 'pageId', 'required' => true, 'validators' => array(array('name' => 'IsInt')), 'filters' => array(array('name' => 'StripTags'), array('name' => 'StringTrim'))),
                        ),
                    ),
                ),
                'MelisGdprService' => array(
                    '_description' => 'tr_melisengine_ws_desc_gdpr',
                    'getGdprBannerText' => array(
                        'attributes' => array('name' => 'microservice_form', 'id' => 'microservice_form', 'method' => 'POST', 'action' => $_SERVER['REQUEST_URI']),
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => array(
                            array('spec' => array('name' => 'siteId', 'type' => 'Text', 'options' => array('label' => 'siteId'), 'attributes' => array('id' => 'siteId', 'value' => '', 'placeholder' => '1', 'data-type' => 'int'))),
                            array('spec' => array('name' => 'langId', 'type' => 'Text', 'options' => array('label' => 'langId'), 'attributes' => array('id' => 'langId', 'value' => '', 'placeholder' => '1', 'data-type' => 'int'))),
                        ),
                        'input_filter' => array(
                            'siteId' => array('name' => 'siteId', 'required' => true, 'validators' => array(array('name' => 'IsInt')), 'filters' => array(array('name' => 'StripTags'), array('name' => 'StringTrim'))),
                            'langId' => array('name' => 'langId', 'required' => true, 'validators' => array(array('name' => 'IsInt')), 'filters' => array(array('name' => 'StripTags'), array('name' => 'StringTrim'))),
                        ),
                    ),
                ),
			),
		),
	),
);