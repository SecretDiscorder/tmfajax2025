<?php
session_start();
require_once('../../shared/config/tce_db_config.php');

include('tmf_chat_db_conn.php');
// Jika fungsi fetch_user_chat_history() ada di file terpisah, misalnya:
// require_once('tmf_chat_functions.php');

session_start();

if (isset($_POST['to_user_id']) && isset($_POST['chat_message'])) {
    // Pastikan untuk membuat record di tce_chat_log agar log_id valid
    $logQuery = "INSERT INTO " . K_TABLE_CHAT_LOG . " (user_id) VALUES (:user_id)";
    $logStmt = $connect->prepare($logQuery);
    $logStmt->execute(array(':user_id' => $_SESSION['user_id']));
    $log_id = $connect->lastInsertId();

    $data = array(
       ':log_id'      => $log_id,
       ':receiver_id' => $_POST['to_user_id'],
       ':sender_id'   => $_SESSION['user_id'],
       ':message'     => $_POST['chat_message'],
       ':status'      => '1',
       ':to_group_id' => 0
    );

    $query = "
    INSERT INTO " . K_TABLE_CHAT_MSG . " 
    (log_id, sender_id, receiver_id, message, status, to_group_id) 
    VALUES (:log_id, :sender_id, :receiver_id, :message, :status, :to_group_id)
    ";

    try {
        $stmt = $connect->prepare($query);
        $stmt->execute($data);
        echo fetch_user_chat_history($_SESSION['user_id'], $_POST['to_user_id'], $connect);
    } catch (PDOException $e) {
        echo "SQL Error: " . $e->getMessage();
        error_log("SQL Error: " . $e->getMessage());
    }
}
?>