<?php
// remove_chat.php

include('tmf_chat_db_conn.php');

if (isset($_POST["chat_message_id"])) {
    $query = "UPDATE " . K_TABLE_CHAT_MSG . " 
              SET status = '2' 
              WHERE msg_id = :msg_id";
    
    $statement = $connect->prepare($query);
    $statement->bindParam(':msg_id', $_POST["chat_message_id"], PDO::PARAM_INT);
    $statement->execute();
}
?>
