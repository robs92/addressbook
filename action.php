<?php

require_once 'functions.php';

echoBackToOverview();

//For every option we need "action"
$action = strtolower(filter_input(INPUT_POST, "action", FILTER_SANITIZE_STRING));
if (empty($action)) exit("Not all parameters were set.");

if($action == "save" || $action == "create") {
    //Are all required POST-params set?
    $requiredValues = getRequiredFields();
    foreach ($requiredValues as $key) {
        if(!array_key_exists($key,$_POST) || empty($_POST[$key])) exit("Not all parameters were set.");
    }

    if($action == "save"){
        //For saving we need the id
        if(!array_key_exists("id",$_POST)) exit("Not all parameters were set.");
        if(!updateUsersById($_POST)) exit("Changes were not saved in Database.");
        echo "Changes were saved in database.";
    }
    elseif($action == "create"){
        $newId = insertInUsers($_POST);
        if($newId == false ) exit("Creation failed.");
        echo "Creation successful. For showing user click <a href='showUser.php?userid=$newId'>here</a>";
    }
    else{
        exit("Not all parameters were set.");
    }
}
elseif($action == "delete"){
    //For deleting we need the id
    if(!array_key_exists("id",$_POST)) exit("Not all parameters were set.");
    if(!deleteUser($_POST["id"])) exit("User were not deleted. Go back to <a href='showUser.php?userid=".$_POST["id"]."'>User</a>.");
    echo "User successful deleted.";
}
