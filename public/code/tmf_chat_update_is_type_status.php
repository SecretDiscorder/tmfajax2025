<?php
// update_is_type_status.php

include('tmf_chat_db_conn.php');
session_start();

if (isset($_POST["is_type"]) && isset($_SESSION["login_details_id"])) {
    $query = "UPDATE " . K_TABLE_CHAT_LOG . " 
              SET is_type = :is_type 
              WHERE login_details_id = :login_details_id";
    
    $statement = $connect->prepare($query);
    $statement->bindParam(':is_type', $_POST["is_type"]);
    $statement->bindParam(':login_details_id', $_SESSION["login_details_id"]);
    $statement->execute();
}
?>
