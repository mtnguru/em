<?php

use Drupal\az_user\CreateUserList;
use Drupal\Core\DrupalKernel;
use Symfony\Component\HttpFoundation\Request;

// define('DRUPAL_DIR', '../../../../../docroot');
//$autoloader = require_once DRUPAL_DIR . '/autoload.php';
$autoloader = require_once __DIR__ . '/autoload.php';
$kernel = new DrupalKernel('prod', $autoloader);
$request = Request::createFromGlobals();
chdir(DRUPAL_DIR);
$response = $kernel->handle($request);
$kernel->terminate($request, $response);

$set = [
//'email_new_content' => 'immediate',
  'role' => 'administrator',
//'group_member' => '5',
//'group_role' => 'theories-contributor',
];

$results = CreateUserList::queryUsers($set);

foreach ($results as $result) {
  // Make name_norm lowercase, it seems not possible in PDO query?
  print ($result->uid . "  " . $result->name . "  " . $result->mail . "  " . $result->type . "\n");
}

//My custom code here can now access the entire Drupal API