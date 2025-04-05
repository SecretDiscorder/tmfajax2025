<?php
//============================================================+
// File name   : tce_user_change_password.php
// Begin       : 2010-09-17
// Last Update : 2023-03-25
//
// Description : Form to change user password
//
// Author: Nicola Asuni (revisi oleh developer)
// 
// (c) Copyright:
//               Nicola Asuni - Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//
// License:
//    Copyright (C) 2004-2018 Nicola Asuni - Tecnick.com LTD
//    See LICENSE.TXT file for more information.
//============================================================+

require_once('../config/tce_config.php');

$pagelevel = K_AUTH_USER_CHANGE_PASSWORD;
$thispage_title = $l['t_user_change_password'];
$thispage_title_icon = '<span class="icon-lock"></span>';
require_once('../../shared/code/tce_authorization.php');
require_once('../../shared/config/tce_user_registration.php');
require_once('../../shared/code/tce_functions_form.php');
require_once('../code/tce_page_header.php');

$user_id = intval($_SESSION['session_user_id']);

// Daftar field yang wajib diisi
$_REQUEST['ff_required'] = 'currentpassword,newpassword,newpassword_repeat';
$_REQUEST['ff_required_labels'] = htmlspecialchars($l['w_current_password'].','.$l['w_new_password'].','.$l['w_new_password'], ENT_COMPAT, $l['a_meta_charset']);

// Proses pengiriman data form
switch ($menu_mode) {
    case 'update': { // Update user
        if ($formstatus = F_check_form_fields()) {
            // Validasi password baru
            if (empty($newpassword) or empty($newpassword_repeat) or ($newpassword !== $newpassword_repeat)) {
                F_print_error('WARNING', $l['m_different_passwords']);
                $formstatus = false;
                F_stripslashes_formfields();
                break;
            }
            $sql = 'SELECT user_password FROM '.K_TABLE_USERS.' WHERE user_id='.$user_id;
            if ($r = F_db_query($sql, $db)) {
                if (!($m = F_db_fetch_array($r)) || ($currentpassword !== $m['user_password'])) {
                    F_print_error('WARNING', $l['m_login_wrong']);
                    $formstatus = false;
                    F_stripslashes_formfields();
                    break;
                }
            } else {
                F_display_db_error(false);
                break;
            }
            // Update password secara langsung (tanpa hash)
            $sql = 'UPDATE '.K_TABLE_USERS.' SET
                user_password=\''.F_escape_sql($db, $newpassword).'\'
                WHERE user_id='.$user_id;
            if (!$r = F_db_query($sql, $db)) {
                F_display_db_error(false);
            } else {
                F_print_error('MESSAGE', $l['m_password_updated']);
            }
        }
        break;
    }
    default: {
        break;
    }
} // end switch

echo '<div class="container">'.K_NEWLINE;
echo '<div class="gsoformbox">'.K_NEWLINE;
echo '<form action="'.$_SERVER['SCRIPT_NAME'].'" method="post" enctype="multipart/form-data" id="form_editor">'.K_NEWLINE;

// Menggunakan fungsi getFormRowTextInputIcon untuk input password
echo getFormRowTextInputIcon('currentpassword', $l['w_current_password'], $l['h_password'].'" class="lh2 d-iblock', '', '', '', 255, false, false, true, '', 'pos-rel', '<span class="icon mailpwdicon icon-eye" id="showPass"></span><span class="icon mailpwdicon icon-eye-blocked" id="hidePass"></span>');
echo getFormRowTextInputIcon('newpassword', $l['w_new_password'], $l['h_password'].'" class="lh2 d-iblock', ' ('.$l['d_password_lenght'].')', '', '', 255, false, false, true, '', 'pos-rel', '<span class="icon mailpwdicon icon-eye" id="showPass_new"></span><span class="icon mailpwdicon icon-eye-blocked" id="hidePass_new"></span>');
echo getFormRowTextInputIcon('newpassword_repeat', $l['w_new_password'], $l['h_password_repeat'].'" class="lh2 d-iblock', ' ('.$l['w_repeat'].')', '', '', 255, false, false, true, '', 'pos-rel', '<span class="icon mailpwdicon icon-eye" id="showPass_repeat"></span><span class="icon mailpwdicon icon-eye-blocked" id="hidePass_repeat"></span>');

echo '<div class="row">'.K_NEWLINE;
F_submit_button('update', $l['w_update'], $l['h_update']);
echo '</div>'.K_NEWLINE;
echo F_getCSRFTokenField().K_NEWLINE;
echo '</form>'.K_NEWLINE;
echo '</div>'.K_NEWLINE;
echo '<div class="pagehelp">'.$l['hp_user_change_password'].'</div>'.K_NEWLINE;
echo '</div>'.K_NEWLINE;

require_once(dirname(__FILE__).'/tce_page_footer.php');
//============================================================+
// END OF FILE
//============================================================+
