<?php

require_once 'ControllerBase.php';
require_once 'FilePathHelper.php';

$split = preg_split('@/@', $_SERVER['REQUEST_URI'], null, PREG_SPLIT_NO_EMPTY);
$elements = count($split);
$controller = $elements > 0 ? $split[0] : "Home";
$controllerName = $controller . 'Controller';
$filePathHelper = new FilePathHelper();
$controllerFilePath = $filePathHelper->fileExists("../Controller/$controllerName.php");
$method = $elements > 1 ? $split[1] : '';
$controllerAndMethodInvoked = false;
if ($controllerFilePath !== false) {
  require_once $controllerFilePath;
  $controllerInstance = new $controllerName;
  if (method_exists($controllerInstance, $method)) {
    $controllerInstance->$method(array_slice($split, 2));
    $controllerAndMethodInvoked = true;
  } else if (method_exists($controllerInstance, 'Index')) {
    $controllerInstance->Index(array_slice($split, 1));
    $controllerAndMethodInvoked = true;
  }
}
if (!$controllerAndMethodInvoked) {
  require_once '../Controller/PageController.php';
  $pageController = new PageController;
  $pageController->Fetch($split);
}
