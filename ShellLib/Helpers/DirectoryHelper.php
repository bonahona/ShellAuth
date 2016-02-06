<?php

function GetAllFiles($directory)
{
    $directoryIgnores = array('.', '..');       // Used to filter out rhe current dir and parent dir from any directory when iterating

    $allFiles = scandir($directory);
    $allValidFiles = array_diff($allFiles, $directoryIgnores);
    
    return $allValidFiles;
}

function Directory($localPath){
    return APPLICATION_ROOT . $localPath;
}

function ViewPath($core, $controller, $view){
    return APPLICATION_ROOT . $core->GetViewFolder() . '/' . $controller . '/' . $view . '.php';
}

function PartialViewPath($core, $view)
{
    return APPLICATION_ROOT . $core->GetPartialFolder() . '/' . $view . '.php';
}

function LayoutPath($core, $layout){
    return APPLICATION_ROOT . $core->GetLayoutFolder() . '/' .  $layout . '.php';
}