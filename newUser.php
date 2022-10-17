<?php

require_once 'functions.php';

echoBackToOverview();
echo "<h1>Create a new user</h1>";
echo "<p>* fields are required</p>";

$requiredColumns = getRequiredFields();
$optionalColumns = getOptionalFields();

echo "<form action='action.php' method='post'>";

//Set input types
foreach ($requiredColumns as $column) {
    echo "<label for='$column'>*".getLabelByColumn($column).": </label>";
    echo "<input type='text' id='$column' name='$column' required><br><br>";
}

foreach ($optionalColumns as $column){
    $fieldType = match ($column) {
        "email" => "email",
        "birthday" => "date",
        "notes" => "textarea",
        default => "text",
    };

    echo "<label for='$column'>".getLabelByColumn($column).": </label>";
    if($fieldType != "textarea"){
        echo "<input type='$fieldType' id='$column' name='$column'><br><br>";
    }
    else{
        echo "<textarea id='$column' name='$column' rows='5' cols='50'></textarea><br><br>";
    }
}

echo "<input type='submit' value='Create' name='action'>";
echo "</form>";
