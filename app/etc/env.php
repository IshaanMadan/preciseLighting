<?php
return [
  'backend' => [
    'frontName' => 'preciselighting_admin'
  ],
  'crypt' => [
    'key' => '11b741b8f07da2f1e829d3f10092fd40'
  ],
  'db' => [
    'table_prefix' => '',
    'connection' => [
      'default' => [
        'host' => 'localhost',
        'dbname' => 'preciselighting',
        'username' => 'preciselighting',
        'password' => '2mvawuia8dy2y15',
        'active' => '1'
      ]
    ]
  ],
  'resource' => [
    'default_setup' => [
      'connection' => 'default'
    ]
  ],
  'x-frame-options' => 'SAMEORIGIN',
  'MAGE_MODE' => 'developer',
  'session' => [
    'save' => 'files'
  ],
  'cache_types' => [
    'config' => 1,
    'layout' => 1,
    'block_html' => 1,
    'collections' => 1,
    'reflection' => 1,
    'db_ddl' => 1,
    'eav' => 1,
    'customer_notification' => 1,
    'config_integration' => 1,
    'config_integration_api' => 1,
    'full_page' => 1,
    'translate' => 1,
    'config_webservice' => 1,
    'amasty_shopby' => 1,
    'compiled_config' => 1
  ],
  'install' => [
    'date' => 'Wed, 13 Jun 2018 18:10:48 +0000'
  ]
];
