<?php
//window+r, powershell , [Environment]::SetEnvironmentVariable("Path", $env:Path + ";E:\php", "User")
require_once 'init-db.php';

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);