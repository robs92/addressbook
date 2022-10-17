<?php

require_once 'functions.php';

echo "<h1>Address book</h1>";
echo "To create a new user please click <a href='newUser.php'>here</a>.<br>For showing notes please have a look to details.<br><br>";

//Check if sort is set and the columns that set are okay
$sort = filter_input(INPUT_GET, "sort", FILTER_SANITIZE_STRING);

if($sort)
{
    $sort = explode("|",$sort);
    $possibleColumns = array_merge(getRequiredFields(),getOptionalFields(),["id"]);
    $ok = false;
    foreach ($possibleColumns as $column) {
        if($column == $sort[0]){
            $ok = true;
            break;
        }
    }

    //In array "sort" key 1 has to exist. It must be asc or desc
    if(!($ok && array_key_exists(1,$sort) && (($sort[1] == "asc" || $sort[1] == "desc")))) $ok = false;

    if($ok){
        $orderBy = " ORDER BY ".$sort[0]." ".$sort[1];
    }
    else{
        echo "Sorry, sort keys were wrong. Table will be sort by default.<br><br>"; //Not an error, just an information
    }
}

//Get all users from database. Add Order by if selected
$db = openDB();
$sqlAllUsers = "SELECT * FROM users";
if(isset($orderBy)) $sqlAllUsers .= $orderBy;
$sqlAllUsers .= ";";
$result = $db->query($sqlAllUsers);

//Get all Columns, but delete "notes". Notes are only shown in details
$allColumns = array_merge(["id"],getRequiredFields(),getOptionalFields());
if (($key = array_search("notes", $allColumns)) !== false) {
    unset($allColumns[$key]);
}

//Build the table --> form for sorting
echo "
<form method='get'>
<table>
    <tr>
        <th><button>Don't sort</button></th>";
        foreach ($allColumns as $column){
            echo"<th>".getLabelByColumn($column)." <button name='sort' value='$column|asc'>&uarr;</button><button name='sort' value='$column|desc'>&darr;</button></th>";
        }
echo"</tr>";

while($row = $result->fetchArray(SQLITE3_ASSOC) ) {
    echo "<tr>";
    echo "<td><a href='showUser.php?userid=".$row["id"]."'>Details</a></td>";
    foreach ($allColumns as $column){
        echo"<td>".htmlspecialchars($row[$column])."</td>";
    }
    echo "</tr>";
}

echo "</table></form>";
