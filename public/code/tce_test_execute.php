<?php
//============================================================+
// File name   : tce_test_execute.php
// Begin       : 2004-05-29
// Last Update : 2023-03-31 (Update fitur deteksi tanpa aktivitas dan cache session)
// 
// Description : Execute a specific test dengan fitur deteksi tidak aktif 
//               dan perbaikan bug jawaban terganti karena cache session
//
// Author: Nicola Asuni (Update oleh Developer)
// 
// (c) Copyright:
//               Nicola Asuni - Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//
// License: See LICENSE.TXT for more information.
//============================================================+

/**
 * @file
 * Execute a specific test.
 * @package com.tecnick.tcexam.public
 * @author Nicola Asuni
 * @since 2004-05-29
 */

// Nonaktifkan cache untuk menghindari cache session yang bermasalah
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once('../config/tce_config.php');

$pagelevel = K_AUTH_PUBLIC_TEST_EXECUTE;
$thispage_title = $l['t_test_execute'];
$thispage_title_icon = '';
$thispage_description = $l['hp_test_execute'];
require_once('../../shared/code/tce_authorization.php');
require_once('../../shared/code/tce_functions_form.php');
require_once('../../shared/code/tce_functions_test.php');

$formname = 'testform';

$test_id = 0;
$testlog_id = 0;
$testuser_id = 0;
$answpos = array();
$answer_text = '';
$test_comment = '';
$reaction_time = 0;

if (isset($_REQUEST['testid']) and ($_REQUEST['testid'] > 0)) {
    $test_id = intval($_REQUEST['testid']);
    // check for test password
    $tph = F_getTestPassword($test_id);
    if (!empty($tph) and !checkPassword($tph.$test_id.$_SESSION['session_user_id'].$_SESSION['session_user_ip'], $_SESSION['session_test_login'])) {
        // display login page
        require_once('../code/tce_page_header.php');
        echo F_testLoginForm($_SERVER['SCRIPT_NAME'], 'form_test_login', 'post', 'multipart/form-data', $test_id);
        require_once('../code/tce_page_footer.php');
        exit(); // break page here
    }
    if (isset($_REQUEST['repeat']) and ($_REQUEST['repeat'] == 1)) {
        // mark previous test attempts as repeated
        F_repeatTest($test_id);
    }
    if (isset($_REQUEST['starttest']) and ($_REQUEST['starttest'] == 1)) {
        F_startTest($test_id);
    }
    
    if (F_executeTest($test_id)) {
        if (!empty($_REQUEST['testuser_id'])) {
            $testuser_id = intval($_REQUEST['testuser_id']);
        }
        if (!empty($_REQUEST['ls-answdatas'])) {
            $ls_answdatas = $_REQUEST['ls-answdatas'];
        }
        if (!empty($_REQUEST['ls-unsure'])) {
            $ls_unsure = $_REQUEST['ls-unsure'];
        }
        if (!empty($_REQUEST['testlogid'])) {
            $testlog_id = intval($_REQUEST['testlogid']);
        }
        if (!empty($_REQUEST['answpos'])) {
            if (is_numeric($_REQUEST['answpos'])) {
                $answpos = array($_REQUEST['answpos'] => 1);
            } else {
                $answpos = (array) $_REQUEST['answpos'];
            }
        }
        
        if (!empty($_REQUEST['answertext'])) {
            $answer_text = $_REQUEST['answertext'];
        }
        if (!empty($_REQUEST['reaction_time'])) {
            $reaction_time = intval($_REQUEST['reaction_time']);
        }

        if (!empty($_REQUEST['forceterminate']) && F_isRightTestlogUser($test_id, $testlog_id)) {
            if ($_REQUEST['forceterminate'] == 'lasttimedquestion') {
                // update last question
                F_updateQuestionLog($test_id, $testlog_id, $answpos, $answer_text, $reaction_time);
            }
            // terminate the test (lock the test to status=4)
            F_terminateUserTest($test_id);
            // redirect the user to the index page
            if(strlen(K_ENDTEST_PAGE) > 0){
                $testend_page = K_ENDTEST_PAGE.'?testid='.$test_id;
            } else {
                $testend_page = 'index.php';
            }
            header('Location: '.$testend_page);
            echo '<?xml version="1.0" encoding="'.$l['a_meta_charset'].'"?'.'>'.K_NEWLINE;
            echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">'.K_NEWLINE;
            echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="'.$l['a_meta_language'].'" lang="'.$l['a_meta_language'].'" dir="'.$l['a_meta_dir'].'">'.K_NEWLINE;
            echo '<head>'.K_NEWLINE;
            echo '<title>REDIRECT</title>'.K_NEWLINE;
            echo '<meta http-equiv="refresh" content="0;url='.$testend_page.'" />'.K_NEWLINE;
            echo '</head>'.K_NEWLINE;
            echo '<body>'.K_NEWLINE;
            echo '<a href="'.$testend_page.'">INDEX...</a>'.K_NEWLINE;
            echo '</body>'.K_NEWLINE;
            echo '</html>'.K_NEWLINE;
            exit;
        }

        // the user is authorized to execute the selected test
        $test_name = F_getTestName($test_id);
        $thispage_title .= ': '.F_getTestName($test_id);

        require_once('../code/tce_page_header.php');

        if(!isset($_POST['question-block'])){
            echo '<div class="container">'.K_NEWLINE;
            // -------------------------
            // Tambahan: Deteksi Tanpa Aktivitas
            // -------------------------
            ?>
<script>
let inactivityTimer;
let isBlocked = false;
const BLOCK_TIME = 5 * 60 * 1000; // 5 menit dalam milidetik
const INACTIVITY_TIME = 15000; // 15 detik tanpa aktivitas

// Buat elemen untuk pesan blokir
const blockMessageDiv = document.createElement("div");
blockMessageDiv.style.position = "fixed";
blockMessageDiv.style.top = "0";
blockMessageDiv.style.left = "0";
blockMessageDiv.style.width = "100%";
blockMessageDiv.style.height = "100%";
blockMessageDiv.style.backgroundColor = "rgba(0, 0, 0, 0.8)";
blockMessageDiv.style.color = "white";
blockMessageDiv.style.display = "none";
blockMessageDiv.style.justifyContent = "center";
blockMessageDiv.style.alignItems = "center";
blockMessageDiv.style.fontSize = "24px";
blockMessageDiv.style.fontWeight = "bold";
document.body.appendChild(blockMessageDiv);

// Cek blokir menggunakan cookie
function checkBlockStatus() {
    let blockTimestamp = getCookie("blockedUntil");
    let currentTime = new Date().getTime();

    if (blockTimestamp && currentTime < blockTimestamp) {
        isBlocked = true;
        displayBlockMessage(blockTimestamp - currentTime);
        startBlockCountdown(blockTimestamp);
    }
}

// Set Cookie
function setCookie(name, value, expirationMinutes) {
    const d = new Date();
    d.setTime(d.getTime() + (expirationMinutes * 60 * 1000));
    document.cookie = `${name}=${value}; expires=${d.toUTCString()}; path=/`;
}

// Get Cookie
function getCookie(name) {
    const nameEQ = `${name}=`;
    const ca = document.cookie.split(';');
    for (let c of ca) {
        c = c.trim();
        if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

// Blokir pengguna
function blockUser() {
    if (isBlocked) return;
    isBlocked = true;
    let blockUntil = Date.now() + BLOCK_TIME;
    setCookie("blockedUntil", blockUntil, 5);
    displayBlockMessage(BLOCK_TIME);
    startBlockCountdown(blockUntil);
}

// Tampilkan pesan blokir
function displayBlockMessage(remainingTime) {
    updateBlockMessage(remainingTime);
    blockMessageDiv.style.display = "flex";
}

// Perbarui pesan blokir
function updateBlockMessage(remainingTime) {
    let minutes = Math.floor(remainingTime / 60000);
    let seconds = ((remainingTime % 60000) / 1000).toFixed(0);
    blockMessageDiv.innerHTML = `<div>Akses diblokir selama 5 menit.<br>Waktu tunggu: <span id="blockCountdown">${minutes}:${seconds < 10 ? '0' + seconds : seconds}</span></div>`;
}

// Mulai countdown blokir
function startBlockCountdown(blockUntil) {
    updateBlockMessage(blockUntil - Date.now()); // Update awal
    let blockInterval = setInterval(() => {
        let remainingTime = blockUntil - Date.now();
        if (remainingTime <= 0) {
            clearInterval(blockInterval);
            document.cookie = "blockedUntil=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/";
            blockMessageDiv.style.display = "none";
            location.reload(); // Reload setelah blokir selesai
        } else {
            updateBlockMessage(remainingTime);
        }
    }, 1000);
}

// Reset timer aktivitas
function resetInactivityTimer() {
    if (isBlocked) return;
    clearTimeout(inactivityTimer);
    inactivityTimer = setTimeout(() => {
        blockUser();
    }, INACTIVITY_TIME);
}

// Event listener untuk aktivitas
["mousemove", "keydown", "scroll", "click"].forEach(event => {
    document.addEventListener(event, resetInactivityTimer);
});

// Cek status blokir saat halaman dimuat
document.addEventListener("DOMContentLoaded", () => {
    checkBlockStatus();
    if (!isBlocked) {
        resetInactivityTimer();
    }
});

</script>



            <?php
        }

        //include('../../shared/code/tce_page_timer.php');
        
        if (!isset($_REQUEST['terminationform'])){
            if (F_isRightTestlogUser($test_id, $testlog_id)) {
                // Jika form telah disubmit, update data testlog
                if(!isset($_POST['question-block']) or isset($_POST['save-answer'])){
                    if(F_updateQuestionLog($test_id, $testlog_id, $answpos, $answer_text, $reaction_time)){
                        if(isset($ls_answdatas)){
                            $fp = fopen(K_PATH_ANSWDATA.$testuser_id.'_answdata.txt', 'w');
                            fwrite($fp, stripslashes($ls_answdatas));
                            fclose($fp);
                        }
                        if(isset($ls_unsure)){
                            $fpu = fopen(K_PATH_ANSWDATA.$testuser_id.'_unsure.txt', 'w');
                            fwrite($fpu, stripslashes($ls_unsure));
                            fclose($fpu);
                        }    
                    }
                    // Pastikan session tersimpan dengan baik
                    session_write_close();
                }
                // Update test comment dari user
                if (isset($_REQUEST['testcomment']) and (!empty($_REQUEST['testcomment']))) {
                    $test_comment = $_REQUEST['testcomment'];
                    F_updateTestComment($test_id, $test_comment);
                }
                if ((isset($_REQUEST['nextquestion']) or (isset($_REQUEST['autonext']) and ($_REQUEST['autonext'] == 1))) and ($_REQUEST['nextquestionid'] > 0)) {
                    // go to next question
                    $testlog_id = 0 + intval($_REQUEST['nextquestionid']);
                } elseif (isset($_REQUEST['prevquestion']) and ($_REQUEST['prevquestionid'] > 0)) {
                    // go to previous question
                    $testlog_id = intval($_REQUEST['prevquestionid']);
                } else {
                    // go to selected question
                    foreach ($_POST as $key => $value) {
                        if (preg_match('/jumpquestion_([0-9]+)/', $key, $matches) > 0) {
                            $testlog_id = intval($matches[1]);
                            break;
                        }
                    }
                }
            }
        }
        // confirmation form to terminate the test
        if (isset($_REQUEST['terminatetest']) and (!empty($_REQUEST['terminatetest']))) {
            $num_omitted_questions = F_getNumOmittedQuestions($test_id);
            $omitted_msg = '';
            $forceTerminate = 1;
            if ($num_omitted_questions > 0) {
                if(K_FORCE_ANSWER_ALL==false){
                    $forceTerminate = 1;
                    $addMsg = '';
                } else {
                    $forceTerminate = 0;
                    $addMsg = $l['m_addMsg'];
                }
                $omitted_msg = '<span style="color:#990000;font-size:120%">[ '.$l['h_questions_unanswered'].': '.$num_omitted_questions.' ] '.$addMsg.'</span>';
            }
            F_print_error('WARNING', $l['m_confirm_test_termination'].' '.$omitted_msg);
            ?>
            <div class="confirmbox">
            <form onsubmit="reloadCont.style.display='block';backdrop('1','1')" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" enctype="multipart/form-data" id="form_test_terminate">
            <div>
            <input type="hidden" name="testid" id="testid" value="<?php echo $test_id; ?>" />
            <input type="hidden" name="testuserid" id="testuser_id" value="<?php echo $testuser_id; ?>" />
            <input type="hidden" name="testlogid" id="testlogid" value="<?php echo $testlog_id; ?>" />
            <input type="hidden" name="terminationform" id="terminationform" value="1" />
            <input type="hidden" name="display_time" id="display_time" value="" />
            <input type="hidden" name="reaction_time" id="reaction_time" value="" />
            <?php
            if($forceTerminate==1){
                echo '<button type="button" id="forceterminatebtn" onclick="document.getElementById(\'forceterminate\').click();delAllMatchLsCache(document.getElementById(\'testuser_id\').value)">'.$l['w_terminate'].'</button>';
                F_submit_button('forceterminate', $l['w_terminate'].'" class="hidden"', $l['w_terminate_exam']);
            }
            F_submit_button('cancel', $l['w_back'], $l['w_back']);
            echo F_getCSRFTokenField().K_NEWLINE;
            ?>
            </div>
            </form>
            </div>
            <?php
        } else {
            if(!isset($_POST['question-block'])){
                echo '<form action="'.$_SERVER['SCRIPT_NAME'].'" method="post" enctype="multipart/form-data" id="'.$formname.'"';
                echo '>'.K_NEWLINE;
                echo '<div>'.K_NEWLINE;
            }
            // tampilkan soal dan menu navigasi
            echo F_questionForm($test_id, $testlog_id, $formname);
            
            if(!isset($_POST['question-block'])){
                if (isset($_REQUEST['finish']) and ($_REQUEST['finish'] > 0)) {
                    $finish = 1;
                } else {
                    $finish = 0;
                }
            }
            if(!isset($_POST['question-block'])){
                echo '<input type="hidden" name="finish" id="finish" value="'.$finish.'" />'.K_NEWLINE;
                echo '<input type="hidden" name="display_time" id="display_time" value="" />'.K_NEWLINE;
                echo '<input type="hidden" name="reaction_time" id="reaction_time" value="" />'.K_NEWLINE;
            }
            // textarea untuk komentar user
            if(!isset($_POST['question-block'])){
                echo F_testComment($test_id).K_NEWLINE;
            }
            // tombol terminasi ujian
            if(!isset($_POST['question-block'])){
                echo '<div id="forceterminateCont">'.K_NEWLINE;
                F_submit_button('terminatetest', $l['w_terminate_exam'], $l['w_terminate_exam']);
                echo '</div>'.K_NEWLINE;
            }
            if(!isset($_POST['question-block'])){
                echo K_NEWLINE;
                echo '</div>'.K_NEWLINE;
                echo F_getCSRFTokenField().K_NEWLINE;
                echo '</form>'.K_NEWLINE;
            }
        }

        // Mulai countdown jika ada examtime
        if (isset($examtime)) {
            if(!isset($_POST['question-block'])){
                echo '<script type="text/javascript">'.K_NEWLINE;
                echo '//<![CDATA['.K_NEWLINE;
                echo 'var TL = false;'.K_NEWLINE;
                echo 'if(document.getElementById("timeout_logout").value==="1"){TL=true}else{TL=false}'.K_NEWLINE;
                echo 'if(!enable_countdown) {'.K_NEWLINE;
                
                $testendtime = strtotime(F_getTestEndTime($test_id));
                if(($examtime-$testendtime)>0){
                    echo '    FJ_start_timer(\'true\', '.(time() - $testendtime).', \''.addslashes($l['m_exam_end_time']).'\', TL);'.K_NEWLINE;
                } else {
                    echo '    FJ_start_timer(\'true\', '.(time() - $examtime).', \''.addslashes($l['m_exam_end_time']).'\', TL);'.K_NEWLINE;
                }
                
                echo '}'.K_NEWLINE;
                echo '//]]>'.K_NEWLINE;
                echo '</script>'.K_NEWLINE;
            }
        }
    } else {
        if(isset($_POST['question-block'])){
            echo 'Oops, Anda tidak diperkenankan meneruskan Ujian. Hal ini bisa disebabkan karena beberapa hal antara lain yaitu:';
            echo '<ol>';
            echo '<li>Waktu / jadwal ujian telah berakhir / kadaluarsa;</li>';
            echo '<li>Anda tidak memiliki hak akses terhadap ujian;</li>';
            echo '<li>Pengaturan waktu ujian telah berubah;</li>';
            echo '<li>Jadwal ujian belum tersedia.</li>';
            echo '</ol>';
            echo '<p>Anda bisa <a href="index.php">klik disini</a> untuk kembali ke beranda dan melihat jadwal ujian yang ada.</p>';
            echo '<a onclick="reloadCont.style.display=\'block\';backdrop(\'1\',\'1\');window.location.replace(\'tce_test_execute.php?testid=3&amp;repeat=1\');" title="jalankan test" class="xmlbutton"><span class="icon-home-outline"></span> KEMBALI KE BERANDA</a>';
            die();
        }
        header('Location: index.php');
        echo '<?xml version="1.0" encoding="'.$l['a_meta_charset'].'"?'.'>'.K_NEWLINE;
        echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">'.K_NEWLINE;
        echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="'.$l['a_meta_language'].'" lang="'.$l['a_meta_language'].'" dir="'.$l['a_meta_dir'].'">'.K_NEWLINE;
        echo '<head>'.K_NEWLINE;
        echo '<title>REDIRECT</title>'.K_NEWLINE;
        echo '<meta http-equiv="refresh" content="0;url=index.php" />'.K_NEWLINE;
        echo '</head>'.K_NEWLINE;
        echo '<body>'.K_NEWLINE;
        echo '<a href="index.php">INDEX...</a>'.K_NEWLINE;
        echo '</body>'.K_NEWLINE;
        echo '</html>'.K_NEWLINE;
        exit;
    }
} else {
    require_once('../code/tce_page_header.php');
    echo '<div class="container">'.K_NEWLINE;
    echo '<div id="broken_testpage">'.K_NEWLINE;
    echo '<p>'.$l['p_testpage_broken'].'</p>'.K_NEWLINE;
    echo '</div>'.K_NEWLINE;
    echo '</div>';
}
if(!isset($_POST['question-block'])){
    echo '<div class="pagehelp">'.$l['hp_test_execute'].'</div>'.K_NEWLINE;
    echo '</div>'.K_NEWLINE; // container
    require_once('../code/tce_page_footer.php');
}
//============================================================+
// END OF FILE
//============================================================+
