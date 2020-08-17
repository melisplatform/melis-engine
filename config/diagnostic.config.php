<?php

return array(

    'plugins' => array(
        'diagnostic' => array(
            'conf' => array(
                'rightsDisplay' => 'none',
            ),
            'MelisEngine' => array(
                // location of your test folder inside the module
                'testFolder' => 'test',
                // moduleTestName is the name of your test folder inside 'testFolder'
                'moduleTestName' => 'MelisEngineTest',
                // this should be properly setup so we can recreate the factory of the database
                'db' => array(
                    // the keys will used as the function name when generated,
                ),
                // these are the various types of methods that you would like to give payloads for testing
                // you don't have to put all the methods in the test controller,
                // instead, just put the methods that will be needing or requiring the payloads for your test.
                'methods' => array(
                    // the key name should correspond to what your test method name in the unit test controller
                    'testBasicMelisEngineTestSuccess' => array(
                        'payloads' => array()
                    ),
                ),
            ),
        ),
    ),
);

