<?php 
return array(
	'plugins' => array(
		'microservice' => array(
			'MelisEngine' => array(
				'MelisPageService' => array(
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
				),
				'MelisTreeService' => array(
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
				),
			),
		),
	),
);