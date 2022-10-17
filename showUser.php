<?php

require_once 'functions.php';

echoBackToOverview();
echo "<h1>Show user</h1>";

//Get all GET-params and are all set?
$userId = filter_input(INPUT_GET, "userid", FILTER_SANITIZE_NUMBER_INT);

if (empty($userId)) exit("Not all parameters were set.");

//Get user by id from database if exists
$db = openDB();
$stmt = "SELECT * FROM 'users' WHERE id=$userId;";
$result = $db->querySingle($stmt,true);

if(empty($result)) exit("No user with id $userId found!");

//Build form for editing user
echo "<form action='action.php' method='post'>";
foreach ($result as $key=>$value) {
    $value = htmlspecialchars($value); //For inputs like javascript etc.
    $label = "<label for='$key'>".getLabelByColumn($key).": </label>";

    //Some inputs have other specifics. e.g. "id" is hidden + number
    switch ($key){
        case "id":
            echo "<input type='number' name='$key' value='$value' hidden>";
            break;
        case "notes":
            echo "$label<textarea id='$key' name='$key' rows='5' cols='50'>$value</textarea><br><br>";
            break;
        default:
            //Not all inputs are text.
            if($key == "email") $inputType = "email";
            elseif($key == "birthday") $inputType = "date";
            else $inputType = "text";

            echo "$label<input type='$inputType' id='$key' name='$key' value='$value'><br><br>";
    }
}

echo "<input type='submit' value='Save' name='action'>
</form>";

//Build form for deleting user
echo "<form action='action.php' method='post'>";
foreach ($result as $key=>$value) {
    $value = htmlspecialchars($value); //For inputs like javascript etc.
    if($key == "id"){
        echo "<input type='number' name='$key' value='$value' hidden>";
        break;
    }
}
echo "To delete the user click on this button and then to the button below.<br>";
echo "<input type='button' id='deletion' value='I want to delete' name='deletion' onclick='enableButton()'><br>";
echo "<input type='submit' id='delAction' value='Delete' name='action'>";

//On loading page, button for delete will be disabled. If user wants to delete he has to click two buttons
echo"
<script>
    window.onload = function () {
        document.getElementById('delAction').disabled = true;
    }

    function enableButton() {
        document.getElementById('delAction').disabled = false;
    }
</script>
</form>";
