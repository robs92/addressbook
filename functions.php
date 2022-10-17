<?php

function openDB(): SQLite3
{
    return new SQLite3("db.sqlite");
}

function echoBackToOverview(): void
{
    echo "Back to <a href='./'>overview</a><br>";
}

function getRequiredFields(): array
{
    return ["first_name","last_name","street","house_no","zip_code","city","country"];
}

function getOptionalFields(): array
{
    return ["email","phone_no","birthday","notes"];
}

function getLabelByColumn(string $col) :string
{
    $labels["id"] = "ID";
    $labels["last_name"] = "Last name";
    $labels["first_name"] = "First name";
    $labels["street"] = "Street";
    $labels["house_no"] = "House Number";
    $labels["zip_code"] = "ZIP Code";
    $labels["city"] = "City";
    $labels["country"] = "Country";
    $labels["email"] = "E-Mail";
    $labels["phone_no"] = "Phone Number";
    $labels["birthday"] = "Birthday";
    $labels["notes"] = "Notes";

    if(array_key_exists($col,$labels)) return $labels[$col];

    return $col;
}

function insertInUsers(array $fields): int
{
    $db = openDB();
    $sql = setSQLInsert("users",$fields);

    //return last insert id if successful
    if ($db->exec($sql)) return $db->lastInsertRowid();
    return 0;
}

function updateUsersById(array $fields): bool
{
    $db = openDB();
    $sql = setSQLUpdate("users",$fields);

    return $db->exec($sql);
}

function deleteUser(int $id): bool
{
    $db = openDB();
    $sql = "DELETE FROM users WHERE id = $id";

    return $db->exec($sql);
}

function setSQLInsert(string $table,array $fields): string
{
    $stmtBegin = "INSERT INTO $table ( ";
    $required = getRequiredFields();
    $optional = getOptionalFields();
    $values = " VALUES ( ";

    foreach ($required as $key) {
        $stmtBegin .= $key.", ";
        $values .= "'".$fields[$key]."', ";
    }

    //Set optional fields, these are not checked yet
    foreach ($optional as $key){
        $stmtBegin .= $key.", ";
        if(!array_key_exists($key,$fields) || empty($fields[$key])){
            $values .= "NULL, ";
        }
        else $values .= "'".$fields[$key]."', ";
    }

    //Remove last , and set ) and return complete statement
    $stmtBegin = removeLastTwoSignsSetCloseBracket($stmtBegin);
    $values = removeLastTwoSignsSetCloseBracket($values);

    return $stmtBegin.$values;
}

function setSQLUpdate(string $table,array $fields): string
{
    $stmt = "UPDATE $table SET ";
    $required = getRequiredFields();
    $optional = getOptionalFields();

    foreach ($required as $key){
        $stmt .= $key." = '".$fields[$key]."', ";
    }

    //Set optional fields, these are not checked yet
    foreach ($optional as $key){
        $stmt .= $key." = ";
        if(!array_key_exists($key,$fields) || empty($fields[$key])){
            $stmt .= "NULL, ";
        }
        else $stmt .= "'".$fields[$key]."', ";
    }

    //Remove last , and space. Set WHERE and return statement
    $stmt = substr($stmt, 0, -2);
    $stmt .= " WHERE id = ".$fields["id"].";";

    return $stmt;
}

function removeLastTwoSignsSetCloseBracket(string $string): string
{
    $string = substr($string, 0, -2);
    return $string." )";
}
