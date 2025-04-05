<?php
//============================================================+
// File name   : tmf_reset_user.php
// Begin       : 2023-01-21
// Last Update : 2023-01-21
//
// Description : Reset user after blocked by multi login policy
//
// Author: Maman Sulaeman
//
// (c) Copyright:
//               Maman Sulaeman
//               Xamzone Apps Universe
//============================================================+


require_once('../config/tce_config.php');

$pagelevel = K_AUTH_INDEX;
require_once('../../shared/code/tce_authorization.php');

// $thispage_title = $l['t_user_select'];
$thispage_title = 'Reset Peserta';
$thispage_title_icon = '<i class="pe-7s-shuffle icon-gradient bg-happy-itmeo"></i> ';
$thispage_help = 'Halaman ini digunakan untuk melakukan reset peserta ketika ia terblokir karena kebijakan login ganda. Secara teknis peserta terblokir karena levelnya diubah menjadi 0, halaman ini memulihkan peserta tersebut menjadi level 1 kembali';

require_once('../code/tce_page_header.php');
require_once('../../shared/code/tce_functions_form_admin.php');
require_once('tmf_functions_user_select_supervisor.php');

// set default values
if (isset($new_group_id)) {
    $new_group_id = intval($new_group_id);
} else {
    $new_group_id = 0;
}
if (!isset($order_field)) {
    $order_field='user_lastname,user_firstname';
}
if (!isset($orderdir)) {
    $orderdir=0;
}
if (!isset($firstrow)) {
    $firstrow=0;
}
if (!isset($rowsperpage)) {
    $rowsperpage=K_MAX_ROWS_PER_PAGE;
}
if (!isset($searchterms)) {
    $searchterms='';
}
if (isset($_REQUEST['group_id'])) {
    $group_id = intval($_REQUEST['group_id']);
} else {
    $group_id = 0;
}
if (!F_isAuthorizedEditorForGroup($group_id)) {
    F_print_error('ERROR', $l['m_authorization_denied'], true);
}

echo '<form action="'.$_SERVER['SCRIPT_NAME'].'" method="post" enctype="multipart/form-data" id="form_userselect">'.K_NEWLINE;

echo '<div class="main-card mb-3 card">'.K_NEWLINE;
echo '<div class="card-body">'.K_NEWLINE;

echo 'Fitur tidak tersedia pada versi ini, silakan bisa menghubungi admin untuk mendapatkan versi yang lebih lengkap';
echo '</div></div>';
require_once('../code/tce_page_footer.php');

//============================================================+
// END OF FILE
//============================================================+
