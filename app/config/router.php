<?php

$router = $di->getRouter();



$router->handle($_SERVER['REQUEST_URI']);
