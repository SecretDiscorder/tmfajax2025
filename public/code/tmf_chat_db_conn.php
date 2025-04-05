<?php
// database_connection.php

require_once('../../shared/config/tce_config.php');
require_once('../../shared/config/tce_db_config.php');

if(!K_CHAT_FEATURE){
    echo 'Sorry, chat feature is disabled right now!';
    die();
}

$connect = new PDO("mysql:host=".K_DATABASE_HOST.";dbname=".K_DATABASE_NAME.";charset=utf8mb4", K_DATABASE_USER_NAME, K_DATABASE_USER_PASSWORD);

date_default_timezone_set(K_TIMEZONE);
function fetch_user_last_activity($user_id, $connect)
{
    $query = "
    SELECT login_time AS last_activity FROM ".K_TABLE_CHAT_LOG." 
    WHERE user_id = '$user_id' 
    ORDER BY login_time DESC 
    LIMIT 1
    ";
    $statement = $connect->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    foreach($result as $row)
    {
        return $row['last_activity'];
    }
}


function fetch_user_test_status($user_id, $connect)
{
    $query = "
    SELECT testuser_status FROM ".K_TABLE_TEST_USER."
    WHERE testuser_user_id = '$user_id' ORDER BY testuser_id DESC
    LIMIT 1
    ";
    $statement = $connect->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    foreach($result as $row)
    {
        return $row['testuser_status'];
    }
}
function fetch_user_chat_history($sender_id, $receiver_id, $connect)
{
    $query = "
    SELECT * FROM " . K_TABLE_CHAT_MSG . " 
    WHERE (sender_id = :sender_id AND receiver_id = :receiver_id)
       OR (sender_id = :receiver_id AND receiver_id = :sender_id)
    ORDER BY sent_at ASC
    ";
    
    $statement = $connect->prepare($query);
    $statement->execute([
        ':sender_id'   => $sender_id,
        ':receiver_id' => $receiver_id
    ]);
    $result = $statement->fetchAll();
    
    $output = '<ul class="list-unstyled">';
    foreach($result as $row) {
        $user_name = '';
        $dynamic_background = '';
        $chat_message = '';
        if($row["sender_id"] == $sender_id) {
            if($row["status"] == '2') {
                $chat_message = '<em>This message has been removed</em>';
                $user_name = '<b class="text-success">You</b>';
            } else {
                $chat_message = $row['message'];
                $user_name = '<button type="button" class="btn btn-danger btn-xs remove_chat" id="'.$row['msg_id'].'">x</button>&nbsp;<b class="text-success">You</b>';
            }
            $dynamic_background = 'background-color:#ffe6e6;';
        } else {
            if($row["status"] == '2') {
                $chat_message = '<em>This message has been removed</em>';
            } else {
                $chat_message = $row["message"];
            }
            $user_name = '<b class="text-danger">'.get_user_name($row['sender_id'], $connect).'</b>';
            $dynamic_background = 'background-color:#ffffe6;';
        }
        $output .= '
        <li style="border-bottom:1px dotted #ccc; padding:8px; '.$dynamic_background.'">
            <p>'.$user_name.' - '.$chat_message.'
                <div align="right">
                    - <small><em>'.$row['sent_at'].'</em></small>
                </div>
            </p>
        </li>';
    }
    $output .= '</ul>';
    
    // Update status pesan yang belum terbaca menjadi '0'
    $updateQuery = "
    UPDATE " . K_TABLE_CHAT_MSG . " 
    SET status = '0' 
    WHERE sender_id = :receiver_id 
      AND receiver_id = :sender_id 
      AND status = '1'
    ";
    $updateStmt = $connect->prepare($updateQuery);
    $updateStmt->execute([
        ':receiver_id' => $receiver_id,
        ':sender_id'   => $sender_id
    ]);
    
    return $output;
}


function get_user_name($user_id, $connect)
{
    $query = "SELECT user_name FROM ".K_TABLE_USERS." WHERE user_id = '$user_id'";
    $statement = $connect->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    foreach($result as $row)
    {
        return $row['user_name'];
    }
}

function count_unseen_message($sender_id, $receiver_id, $connect)
{
    $query = "
    SELECT * FROM ".K_TABLE_CHAT_MSG." 
    WHERE sender_id = '$sender_id' 
      AND receiver_id = '$receiver_id' 
      AND status = '1'
    ";
    $statement = $connect->prepare($query);
    $statement->execute();
    $count = $statement->rowCount();
    $output = '';
    if($count > 0)
    {
        $output = '<span class="label label-success">'.$count.'</span>';
    }
    return $output;
}
function fetch_is_type_status($user_id, $connect)
{
    $query = "
    SELECT login_time FROM ".K_TABLE_CHAT_LOG." 
    WHERE user_id = '".$user_id."' 
    ORDER BY login_time DESC 
    LIMIT 1
    ";  
    $statement = $connect->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    $output = '';
    foreach($result as $row)
    {
        $output = '<small><em><span class="text-muted">Last login: '.$row["login_time"].'</span></em></small>';
    }
    return $output;
}


function fetch_group_chat_history($connect, $group_id)
{
    // Perhatikan: Jika fitur group chat menggunakan kolom tambahan (misalnya, to_group_id),
    // pastikan struktur tabel dan query-nya sudah sesuai. Contoh di bawah ini menggunakan receiver_id = '0' untuk pesan grup.
    $query = "
    SELECT * FROM ".K_TABLE_CHAT_MSG." 
    WHERE receiver_id = '0' AND to_group_id = '".$group_id."' 
    ORDER BY sent_at DESC
    ";

    $statement = $connect->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    $output = '<ul class="list-unstyled">';
    foreach($result as $row)
    {
        $user_name = '';
        $dynamic_background = '';
        $chat_message = '';
        if($row["sender_id"] == $_SESSION["user_id"])
        {
            if($row["status"] == '2')
            {
                $chat_message = '<em>This message has been removed</em>';
                $user_name = '<b class="text-success">You</b>';
            }
            else
            {
                $chat_message = $row["message"];
                $user_name = '<button type="button" class="btn btn-danger btn-xs remove_chat" id="'.$row['msg_id'].'">x</button>&nbsp;<b class="text-success">You</b>';
            }
            $dynamic_background = 'background-color:#ffe6e6;';
        }
        else
        {
            if($row["status"] == '2')
            {
                $chat_message = '<em>This message has been removed</em>';
            }
            else
            {
                $chat_message = $row["message"];
            }
            $user_name = '<b class="text-danger">'.get_user_name($row['sender_id'], $connect).'</b>';
            $dynamic_background = 'background-color:#ffffe6;';
        }
        $output .= '
        <li style="border-bottom:1px dotted #ccc; padding:8px; '.$dynamic_background.'">
            <p>'.$user_name.' - '.$chat_message.'
                <div align="right">
                    - <small><em>'.$row['sent_at'].'</em></small>
                </div>
            </p>
        </li>
        ';
    }
    $output .= '</ul>';
    return $output;
}
?>
