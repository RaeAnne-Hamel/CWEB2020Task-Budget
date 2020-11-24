<?php

//init-db.php
/***
 * Purpose to initialize the connection to the database
 * and setup annotation configuration in the entities
 * IMPORTANT! This file must be included on every page that requires access to the database
 */
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Annotations\AnnotationRegistry;

require_once "vendor/autoload.php";

//if (class_exists(AnnotationRegistry::class)) {
    AnnotationRegistry::registerLoader('class_exists');
//}
// Create a simple "default" Doctrine ORM configuration for Annotations
$isDevMode = true;
$proxyDir = null;
$cache = null;
$useSimpleAnnotationReader = false;
$config = Setup::createAnnotationMetadataConfiguration([__DIR__.'/entities'], $isDevMode, $proxyDir, $cache, $useSimpleAnnotationReader);

// database configuration parameters
//IMPORTANT: MUST enable the pdo_sqlite extension in php.ini 
//		look for ;extension=pdo_sqlite and change it to extension=php_pdo_sqlite.dll
$conn = ['driver' => 'pdo_sqlite','path' => __DIR__ . '/databases/doctrine.db'];

/*********************
 * obtaining the entity manager
 * - this will be the main way we interact with the database going forward
 ********************/
$entityManager = EntityManager::create($conn, $config);