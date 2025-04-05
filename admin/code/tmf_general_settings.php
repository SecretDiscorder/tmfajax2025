<?php
require_once('../config/tce_config.php');

$pagelevel = K_AUTH_ADMIN_USERS;
require_once('../../shared/code/tce_authorization.php');
require_once('../../shared/config/tce_user_registration.php');

$thispage_title = "General Settings";
$thispage_title_icon = "<i class='fas fa-cogs'></i> ";

require_once('tce_page_header.php');
require_once('../../shared/code/tce_functions_form.php');

// var_dump($_POST);
if (!isset($_POST['answer_all_questions']) or (empty($_POST['answer_all_questions']))) {
    $answer_all_questions = false;
} else {
    $answer_all_questions = F_getBoolean($_REQUEST['answer_all_questions']);
}

if (!isset($_POST['show_terminate_when_all_answered']) or (empty($_POST['show_terminate_when_all_answered']))) {
    $show_terminate_when_all_answered = false;
} else {
    $show_terminate_when_all_answered = F_getBoolean($_REQUEST['show_terminate_when_all_answered']);
}

if (!isset($_POST['show_save_button']) or (empty($_POST['show_save_button']))) {
    $show_save_button = false;
} else {
    $show_save_button = F_getBoolean($_REQUEST['show_save_button']);
}

if (!isset($_POST['chat_feature']) or (empty($_POST['chat_feature']))) {
    $chat_feature = false;
} else {
    $chat_feature = F_getBoolean($_REQUEST['chat_feature']);
}

if (!isset($_POST['display_test_desc']) or (empty($_POST['display_test_desc']))) {
    $display_test_desc = false;
} else {
    $display_test_desc = F_getBoolean($_REQUEST['display_test_desc']);
}

if (!isset($_POST['jsonFile']) or (empty($_POST['jsonFile']))) {
    $jsonFile = false;
} else {
    $jsonFile = F_getBoolean($_REQUEST['jsonFile']);
}

if (!isset($_POST['createAllJsonFileOnStartup']) or (empty($_POST['createAllJsonFileOnStartup']))) {
    $createAllJsonFileOnStartup = false;
} else {
    $createAllJsonFileOnStartup = F_getBoolean($_REQUEST['createAllJsonFileOnStartup']);
}

if (!isset($_POST['cacheFeature']) or (empty($_POST['cacheFeature']))) {
    $cacheFeature = false;
} else {
    $cacheFeature = F_getBoolean($_REQUEST['cacheFeature']);
}

if (!isset($_POST['clearStorageOnLogin']) or (empty($_POST['clearStorageOnLogin']))) {
    $clearStorageOnLogin = false;
} else {
    $clearStorageOnLogin = F_getBoolean($_REQUEST['clearStorageOnLogin']);
}

if (!isset($_POST['hideExpTest']) or (empty($_POST['hideExpTest']))) {
    $hideExpTest = false;
} else {
    $hideExpTest = F_getBoolean($_REQUEST['hideExpTest']);
}

if (!isset($_POST['PDFResult']) or (empty($_POST['PDFResult']))) {
    $PDFResult = false;
} else {
    $PDFResult = F_getBoolean($_REQUEST['PDFResult']);
}

if (!isset($_POST['pubPageHelp']) or (empty($_POST['pubPageHelp']))) {
    $pubPageHelp = false;
} else {
    $pubPageHelp = F_getBoolean($_REQUEST['pubPageHelp']);
}

if (!isset($_POST['logoImg']) or (empty($_POST['logoImg']))) {
    $logoImg = false;
} else {
    $logoImg = F_getBoolean($_REQUEST['logoImg']);
}

if (!isset($_POST['SSGenJSON']) or (empty($_POST['SSGenJSON']))) {
    $SSGenJSON = false;
} else {
    $SSGenJSON = F_getBoolean($_REQUEST['SSGenJSON']);
}

if (!isset($_POST['triggerCacheAllFromServer']) or (empty($_POST['triggerCacheAllFromServer']))) {
    $triggerCacheAllFromServer = false;
} else {
    $triggerCacheAllFromServer = F_getBoolean($_REQUEST['triggerCacheAllFromServer']);
}

if (!isset($_POST['forgotPass']) or (empty($_POST['forgotPass']))) {
    $forgotPass = false;
} else {
    $forgotPass = F_getBoolean($_REQUEST['forgotPass']);
}

if (!isset($_POST['userReg']) or (empty($_POST['userReg']))) {
    $userReg = false;
} else {
    $userReg = F_getBoolean($_REQUEST['userReg']);
}

if (!isset($_POST['enable_greeting']) or (empty($_POST['enable_greeting']))) {
    $enable_greeting = false;
} else {
    $enable_greeting = F_getBoolean($_REQUEST['enable_greeting']);
}

if (!isset($_POST['enable_delay']) or (empty($_POST['enable_delay']))) {
    $enable_delay = false;
} else {
    $enable_delay = F_getBoolean($_REQUEST['enable_delay']);
}

if (!isset($_POST['enable_langsel']) or (empty($_POST['enable_langsel']))) {
    $enable_langsel = false;
} else {
    $enable_langsel = F_getBoolean($_REQUEST['enable_langsel']);
}

if(isset($_POST['update'])){
	$arr = array();
	$arr['siteName'] = strip_tags($_POST['siteName']);
	$arr['siteDesc'] = strip_tags($_POST['siteDesc']);
	$arr['siteAuthor'] = strip_tags($_POST['siteAuthor']);
	$arr['siteReplyTo'] = strip_tags($_POST['siteReplyTo']);
	$arr['siteKeyword'] = strip_tags($_POST['siteKeyword']);
	$arr['defLang'] = strip_tags($_POST['defLang']);
	$arr['logoutURL'] = strip_tags($_POST['logoutURL']);
	$arr['jsWarn'] = $_POST['jsWarn'];
	$arr['clientDisMsg'] = $_POST['clientDisMsg'];
	$arr['timezone'] = strip_tags($_POST['timezone']);
	$arr['clientUA'] = strip_tags($_POST['clientUA']);
	$arr['clientVer'] = strip_tags($_POST['clientVer']);
	$arr['appName'] = strip_tags($_POST['appName']);
	$arr['appShortName'] = strip_tags($_POST['appShortName']);
	$arr['institutionName'] = strip_tags($_POST['institutionName']);
	$arr['endtest_page'] = strip_tags($_POST['endtest_page']);
	$arr['addrLine1'] = strip_tags($_POST['addrLine1']);
	$arr['addrLine2'] = strip_tags($_POST['addrLine2']);
	$arr['addrLine3'] = strip_tags($_POST['addrLine3']);
	// $arr['institutionLogo'] = strip_tags($_POST['institutionLogo']);
	$arr['answer_all_questions'] = $answer_all_questions;
	$arr['show_terminate_when_all_answered'] = $show_terminate_when_all_answered;
	$arr['show_save_button'] = $show_save_button;
	$arr['hideExpTest'] = $hideExpTest;
	$arr['PDFResult'] = $PDFResult;
	$arr['pubPageHelp'] = $pubPageHelp;
	$arr['jsonFile'] = $jsonFile;
	$arr['SSGenJSON'] = $SSGenJSON;
	$arr['triggerCacheAllFromServer'] = $triggerCacheAllFromServer;
	$arr['forgotPass'] = $forgotPass;
	$arr['userReg'] = $userReg;
	$arr['enable_langsel'] = $enable_langsel;
	$arr['createAllJsonFileOnStartup'] = $createAllJsonFileOnStartup;
	$arr['cacheFeature'] = $cacheFeature;
	$arr['enable_delay'] = $enable_delay;
	$arr['clearStorageOnLogin'] = $clearStorageOnLogin;
	$arr['chat_feature'] = $chat_feature;
	$arr['display_test_desc'] = $display_test_desc;
	$arr['loginBg'] = strip_tags($_POST['loginBg']);
	$arr['loginBgPosition'] = strip_tags($_POST['loginBgPosition']);
	$arr['defFont'] = strip_tags($_POST['defFont']);
	$arr['logoImg'] = strip_tags($_POST['logoImg']);
	$arr['loginBgSize'] = strip_tags($_POST['loginBgSize']);
	$arr['loginBgBlend'] = strip_tags($_POST['loginBgBlend']);

	$fp = fopen(K_PATH_MAIN.'shared/config/tmf_general_settings.json', 'w');
	fwrite($fp, serialize($arr));
	// fwrite($fp, json_encode($qblock_arr));
	fclose($fp);
	
	$manifest_arr = array(
		"background_color"=>"white",
		"description"=>strip_tags($_POST['appName']),
		"name"=>strip_tags($_POST['appName']." - ".$_POST['institutionName']),
		"short_name"=>strip_tags($_POST['appShortName']." ".$_POST['institutionName']),
		"display"=>"standalone",
		"icons" => [
			array(
				"src"=>"android-icon-36x36.png",
				"sizes"=>"36x36",
				"type"=>"image/png",
				"density"=>"0.75"
			),
			array(
				   "src"=>"android-icon-48x48.png",
				   "sizes"=>"48x48",
				   "type"=>"image/png",
				   "density"=>"1.0"
			),
			array(
			   "src"=>"android-icon-72x72.png",
			   "sizes"=>"72x72",
			   "type"=>"image/png",
			   "density"=>"1.5"
			),
			array(
				   "src"=>"android-icon-96x96.png",
				   "sizes"=>"96x96",
				   "type"=>"image/png",
				   "density"=>"2.0"
			),
			array(
				   "src"=>"android-icon-144x144.png",
				   "sizes"=>"144x144",
				   "type"=>"image/png",
				   "density"=>"3.0"
			),
			array(
				   "src"=>"android-icon-192x192.png",
				   "sizes"=>"192x192",
				   "type"=>"image/png",
				   "density"=>"4.0"
			)],
		"start_url"=>K_PATH_HOST.K_PATH_TCEXAM."public/code/index.php?utm_source=homescreen"	
		);
		
	$fpm = fopen(K_PATH_MAIN.'a2hs/site.webmanifest', 'w');
	fwrite($fpm, stripcslashes(json_encode($manifest_arr)));
	fclose($fpm);
}

if(isset($_POST['update-greeting'])){
	$arr = array();
	$arr['enable_greeting'] = $enable_greeting;
	$arr['greetLine1'] = strip_tags($_POST['greetLine1']);
	$arr['greetLine2'] = strip_tags($_POST['greetLine2']);
	
	$fp = fopen(K_PATH_MAIN.'public/config/tmf_greetings.json', 'w');
	fwrite($fp, serialize($arr));
	// fwrite($fp, json_encode($qblock_arr));
	fclose($fp);
}

if(isset($_POST['update-addinfologin'])){
	$arr = array();
	$arr['ail_beforefield'] = strip_tags($_POST['ail_beforefield']);
	$arr['ail_afterfield'] = strip_tags($_POST['ail_afterfield']);
	
	$fp = fopen(K_PATH_MAIN.'public/config/tmf_additional_info_login.json', 'w');
	fwrite($fp, serialize($arr));
	// fwrite($fp, json_encode($qblock_arr));
	fclose($fp);
}

if(isset($_POST['update-timerwarning'])){
	$arr = array();
	$arr['almostend1_time'] = strip_tags($_POST['almostend1_time']);
	$arr['almostend1_msg'] = strip_tags($_POST['almostend1_msg']);
	$arr['almostend1_bg'] = strip_tags($_POST['almostend1_bg']);
	$arr['almostend1_col'] = strip_tags($_POST['almostend1_col']);
	$arr['almostend2_time'] = strip_tags($_POST['almostend2_time']);
	$arr['almostend2_msg'] = strip_tags($_POST['almostend2_msg']);
	$arr['almostend2_bg'] = strip_tags($_POST['almostend2_bg']);
	$arr['almostend2_col'] = strip_tags($_POST['almostend2_col']);
	
	$arr['lastsec_msg'] = strip_tags($_POST['lastsec_msg']);
	$arr['lastsec_bg'] = strip_tags($_POST['lastsec_bg']);
	$arr['lastsec_col'] = strip_tags($_POST['lastsec_col']);
	
	$fp = fopen(K_PATH_MAIN.'public/config/tmf_timer_warning.json', 'w');
	fwrite($fp, serialize($arr));
	// fwrite($fp, json_encode($qblock_arr));
	fclose($fp);
}

$json = unserialize(file_get_contents(K_PATH_MAIN.'shared/config/tmf_general_settings.json'));
$jsonGreetings = unserialize(file_get_contents(K_PATH_MAIN.'public/config/tmf_greetings.json'));
$lbam = unserialize(file_get_contents(K_PATH_MAIN.'public/config/tmf_additional_info_login.json'));
$tm = unserialize(file_get_contents(K_PATH_MAIN.'public/config/tmf_timer_warning.json'));
//var_dump(is_array($json));

echo '<div class="container">'.K_NEWLINE;
echo '<div class="tceformbox">'.K_NEWLINE;
echo '<form action="'.$_SERVER['SCRIPT_NAME'].'" method="POST">';

echo '<fieldset>';
echo '<legend>Site Settings</legend>';
echo getFormRowTextInput('appName', 'Application Description', 'Application Description', '', $json['appName'], '', 255, false, false, false);
echo getFormRowTextInput('appShortName', 'Application Short Description', 'Application Short Description', '', $json['appShortName'], '', 255, false, false, false);
echo getFormRowTextInput('siteName', 'Site Name', 'Site Name', '', $json['siteName'], '', 255, false, false, false);
echo getFormRowTextInput('siteDesc', 'Site Description', 'Site Description', '', $json['siteDesc'], '', 255, false, false, false);
echo getFormRowTextInput('siteAuthor', 'Site Author', 'Site Author', '', $json['siteAuthor'], '', 255, false, false, false);
echo getFormRowTextInput('siteReplyTo', 'Site Reply-to', 'Site Reply-to', '', $json['siteReplyTo'], '', 255, false, false, false);
echo getFormRowTextInput('siteKeyword', 'Site Keyword', 'Site Keyword', '', $json['siteKeyword'], '', 255, false, false, false);
echo getFormRowTextInput('timezone', 'Timezone Setting', 'Timezone Setting', 'Set your own timezone here.<br/>Possible values are listed on:<br/><a href="http://php.net/manual/en/timezones.php">http://php.net/manual/en/timezones.php</a>', $json['timezone'], '', 255, false, false, false);
echo getFormRowTextInput('defLang', 'Default Language', 'Default Language', '2-letters code for default language.<br/><i>example:</i> ar, az, bg, br, cn, de, el, en, es, fa, fr, he, hi, hu, id, it, jp, mr, ms, nl, pl, ro, ru, tr, ur, vn', $json['defLang'], '', 255, false, false, false);
echo getFormRowCheckBox('enable_langsel', 'Enable Language Selector', 'If enable, show language selector on top right of the page<br/><i>default value: enable</i>', 'If enable, show language selector on top right of the page<br/><i>default value: enable</i>', 1, $json['enable_langsel'], false, '');
echo getFormRowCheckBox('pubPageHelp', 'Public Page Help', 'If enable, display page help on the bottom of the page. default value: disable', 'If enable, display page help on the bottom of the page.<br/><i>default value: disable</i>', 1, $json['pubPageHelp'], false, '');
echo getFormRowCheckBox('forgotPass', 'Show Forgot Password Link', 'If enable, show link to reset user password<br/><i>default value: enable</i>', 'If enable, show link to reset user password<br/><i>default value: enable</i>', 1, $json['forgotPass'], false, '');
echo getFormRowCheckBox('userReg', 'Enable Self Registration', 'If enable, show self registration link<br/><i>default value: enable</i>', 'If enable, show self registration link<br/><i>default value: enable</i>', 1, $json['userReg'], false, '');
echo getFormRowTextInput('logoutURL', 'Logout URL', 'Logout URL', 'URL to be redirected at logout.<br/>Can be relative or absolute URL.<br/>example:<br/><a href="#">logout.html</a>, <a href="#">../../logout.html</a>, <a href="https://xamzonelinux.blogspot.com" target="blank">https://xamzonelinux.blogspot.com</a><br/>(leave empty for default).', $json['logoutURL'], '', 255, false, false, false);

echo '<div class="row">'.K_NEWLINE;
echo '<span class="label">'.K_NEWLINE;
echo '<label for="defFont">Global Font</label>'.K_NEWLINE;
echo '</span>'.K_NEWLINE;
echo '<span class="formw">'.K_NEWLINE;
echo '<select name="defFont" id="defFont" size="0">'.K_NEWLINE;
// echo '<option value="System Default">System Default</option>'.K_NEWLINE;
foreach(glob(K_PATH_MAIN.'fonts/external/*') as $filename){
	// echo $filename;
	echo '<option value="'.basename($filename).'" ';
	if(basename($filename)===$json['defFont']){
		echo ' selected="selected"';
	}
	echo '>'.basename($filename).'</option>'.K_NEWLINE;
}

// $listFonts = array('System Default','Roboto','OpenSans');
// echo '<option value=""></option>'.K_NEWLINE;
/* foreach($listFonts as $key => $value){
	echo '<option value="'.$value.'" ';
	if($value===$json['defFont']){
		echo ' selected="selected"';
	}
	echo '>'.$value.'</option>'.K_NEWLINE;
} */

echo '</select>'.K_NEWLINE;
echo '<span class="labeldesc">System Default font is less consistent across device, because each user may change default font. For consistent look you can choose non-system default provided here.<br/><br/>If you paste the text from Microsoft Word, it may contain non-websafe font like Symbol or Wingdings. This font may unreadable correctly if the test taker access the test from non-Microsoft platform, like Android. To solve this problem, please provide font Symbol or Wingdings, and put on <b>fonts</b> directory. The Symbol font must be renamed to: <b><u>symbol.ttf</u></b>, and the Wingdings font must be renamed to: <b><u>wingdings.ttf</u></b>.<br/><br/>Sorry, we are not provide this font because of licensing term.</span></span>'.K_NEWLINE;
echo '</div>'.K_NEWLINE;
// $field_name, $name, $description = '', $value = '', $disabled = false, $prefix = ''
echo getFormRowTextBox('jsWarn', 'Javascript Warning if disabled (you can use HTML tag)', 'Javascript Warning', stripcslashes($json['jsWarn']), false, '');
echo getFormRowTextInput('clientUA', 'Client User Agent', 'Client User Agent', 'Leave empty to accept all client user agent', $json['clientUA'], '', 255, false, false, false);
echo getFormRowTextInput('clientVer', 'Client Min. Ver', 'Client Min. Ver', 'Minimum version of client browser.<br/>eg: if you intended your client access the site from Chrome ver. 61 or higher, please type 60 in this form', $json['clientVer'], '', 255, false, false, false);
echo getFormRowTextBox('clientDisMsg', 'Message if client blocked (you can use HTML tag)', 'Message if client blocked (you can use HTML tag)', stripcslashes($json['clientDisMsg']), false, '');
echo '</fieldset>';

echo '<fieldset>';
echo '<legend class="bg-indigo">Institution Data</legend>';
echo getFormRowTextInput('institutionName', 'Institution Name', 'Institution Name', '', $json['institutionName'], '', 255, false, false, false);
echo getFormRowTextInput('addrLine1', 'Address Line 1', 'Address Line 1', '', $json['addrLine1'], '', 255, false, false, false);
echo getFormRowTextInput('addrLine2', 'Address Line 2', 'Address Line 2', '', $json['addrLine2'], '', 255, false, false, false);
echo getFormRowTextInput('addrLine3', 'Address Line 3', 'Address Line 3', '', $json['addrLine3'], '', 255, false, false, false);
// echo getFormRowTextInput('institutionLogo', 'Institution Logo', 'Institution Logo', '', $json['institutionLogo'], '', 255, false, false, false);

echo '<div class="row">'.K_NEWLINE;
echo '<span class="label">'.K_NEWLINE;
echo '<label for="logoImg">Logo Image</label>'.K_NEWLINE;
echo '</span>'.K_NEWLINE;
echo '<span class="formw">'.K_NEWLINE;
echo '<select name="logoImg" id="logoImg" size="0">'.K_NEWLINE;

foreach(glob(K_PATH_CACHE.'logo/*') as $filename){
	echo '<option value="'.basename($filename).'" ';
	if(basename($filename)===$json['logoImg']){
		echo ' selected="selected"';
	}
	echo '>'.basename($filename).'</option>'.K_NEWLINE;
}
echo '</select>'.K_NEWLINE;
echo '<span class="labeldesc">upload your institution logo image from <a href="tce_filemanager.php?d='.urlencode(K_PATH_CACHE).'logo%2F&v=1" target="blank">File Manager</a></span></span>'.K_NEWLINE;
echo '</div>'.K_NEWLINE;

echo '</fieldset>';
echo '<fieldset>';
echo '<legend class="bg-purple">Test Settings</legend>';
echo getFormRowCheckBox('answer_all_questions', 'Answer all questions', 'If enable all questions must be answered before stopping the test. <i>default value: enable</i>', 'If enable all questions must be answered before stopping the test. <i>default value: enable</i>', 1, $json['answer_all_questions'], false, '');
echo getFormRowCheckBox('show_terminate_when_all_answered', 'Show Terminate button only when all answered', 'If enable, show terminate button only when all question has been marked answered.<br/><i>default value: disable</i>', 'If enable, show terminate button only when all question has been marked answered.<br/><i>default value: disable</i>', 1, $json['show_terminate_when_all_answered'], false, '');
echo getFormRowCheckBox('show_save_button', 'Show Save Button', 'If enable, show save button below question navigation.<br/><i>default value: enable</i>', 'If enable, show save button below question navigation.<br/><i>default value: enable</i>', 1, $json['show_save_button'], false, '');
echo getFormRowCheckBox('display_test_desc', 'Test Description', 'If enable show test description before executing the test.<br/><i>default value: enable</i>', 'If enable show test description before executing the test.<br/><i>default value: enable</i>', 1, $json['display_test_desc'], false, '');
echo getFormRowCheckBox('hideExpTest', 'Hide Expired Test', 'If enable hide expired test.<br/><i>default value: disable</i>', 'If enable hide expired test.<br/><i>default value: disable</i>', 1, $json['hideExpTest'], false, '');
echo getFormRowCheckBox('PDFResult', 'PDF Result', 'If enable, user can export result test to PDF.<br/><i>default value: enable</i>', 'If enable, user can export result test to PDF.<br/><i>default value: enable</i>', 1, $json['PDFResult'], false, '');
echo getFormRowTextInput('endtest_page', 'Custom page after user stopping test', 'Custom page after user stopping test', 'leave empty to default', $json['endtest_page'], '', 255, false, false, false);

// echo getFormRowCheckBox('fileUpload', 'File Upload', 'If enable, add ability to upload specific file on text question type', 'If enable, add ability to upload specific file on text question type', 1, $json['fileUpload'], false, '');
echo '</fieldset>';

echo '<fieldset>';
echo '<legend class="bg-teal">Login Background</legend>';
echo getFormRowTextInput('loginBg', 'Login Background Image', 'Login Background Image', 'Can be path to image or image URL Address. Leave blank to disable.<br/>Example: https://source.unsplash.com/lI1z94nf0RM/1600x900<br/>or ../../images/background.jpg', $json['loginBg'], '', 255, false, false, false);

// echo getFormRowTextInput('loginBgPosition', 'Position', 'Position', '', $json['loginBgPosition'], '', 255, false, false, false);
echo '<div class="row">'.K_NEWLINE;
echo '<span class="label">'.K_NEWLINE;
echo '<label for="loginBgPosition">Position</label>'.K_NEWLINE;
echo '</span>'.K_NEWLINE;
echo '<span class="formw">'.K_NEWLINE;
echo '<select name="loginBgPosition" id="loginBgPosition" size="0">'.K_NEWLINE;
$loginBgPosition = array('bottom','center','left','right','top','revert','unset','inherit','initial');
// echo '<option value=""></option>'.K_NEWLINE;
foreach($loginBgPosition as $key => $value){
	echo '<option value="'.$value.'" ';
	if($value===$json['loginBgPosition']){
		echo ' selected="selected"';
	}
	echo '>'.$value.'</option>'.K_NEWLINE;
}
echo '</select>'.K_NEWLINE;
echo '</span>'.K_NEWLINE;
echo '</div>'.K_NEWLINE;

// echo getFormRowTextInput('loginBgSize', 'Size', 'Size', '', $json['loginBgSize'], '', 255, false, false, false);
echo '<div class="row">'.K_NEWLINE;
echo '<span class="label">'.K_NEWLINE;
echo '<label for="loginBgSize">Size</label>'.K_NEWLINE;
echo '</span>'.K_NEWLINE;
echo '<span class="formw">'.K_NEWLINE;
echo '<select name="loginBgSize" id="loginBgSize" size="0">'.K_NEWLINE;
$loginBgSize = array('auto','contain','cover','inherit','initial','revert','unset');
// echo '<option value=""></option>'.K_NEWLINE;
foreach($loginBgSize as $key => $value){
	echo '<option value="'.$value.'" ';
	if($value===$json['loginBgSize']){
		echo ' selected="selected"';
	}
	echo '>'.$value.'</option>'.K_NEWLINE;
}
echo '</select>'.K_NEWLINE;
echo '</span>'.K_NEWLINE;
echo '</div>'.K_NEWLINE;

// echo getFormRowTextInput('loginBgBlend', 'Blending Mode', 'Blending Mode', '', $json['loginBgBlend'], '', 255, false, false, false);

echo '<div class="row">'.K_NEWLINE;
echo '<span class="label">'.K_NEWLINE;
echo '<label for="loginBgBlend">Blend Mode</label>'.K_NEWLINE;
echo '</span>'.K_NEWLINE;
echo '<span class="formw">'.K_NEWLINE;
echo '<select name="loginBgBlend" id="loginBgBlend" size="0">'.K_NEWLINE;
$blendMode = array('color','color-burn','color-dodge','darken','difference','exclusion','hard-light','hue','inherit','initial','lighten','luminosity','multiply','normal','overlay','revert','saturation','screen','soft-light','unset');
// echo '<option value=""></option>'.K_NEWLINE;
foreach($blendMode as $key => $value){
	echo '<option value="'.$value.'" ';
	if($value===$json['loginBgBlend']){
		echo ' selected="selected"';
	}
	echo '>'.$value.'</option>'.K_NEWLINE;
}
echo '</select>'.K_NEWLINE;
echo '</span>'.K_NEWLINE;
echo '</div>'.K_NEWLINE;
echo '</fieldset>';

echo '<fieldset>';
echo '<legend class="bg-dark">Performance</legend>';
// SSGenJSON
echo getFormRowCheckBox('SSGenJSON', 'Create JSON File on Server Side', 'If enable, server generate all question (testlogid) in JSON File on first question load. It also help other features below.<br/><i>default value: enable</i>', 'If enable, server generate all question (testlogid) in JSON File on first question load. It also help other features below.<br/><i>default value: disable</i>', 1, $json['SSGenJSON'], false, '');
echo getFormRowCheckBox('triggerCacheAllFromServer', 'Triggering Cache All From Server', 'Triggering load all cache after generating JSON File from server side<br/><i>default value: disable</i>', 'Triggering load all cache after generating JSON File from server side<br/><i>default value: disable</i>', 1, $json['triggerCacheAllFromServer'], false, '');
echo getFormRowCheckBox('jsonFile', 'Create JSON File', 'If enable, all displayed question will be stored in JSON File for faster loading in the next time. This JSON File triggered from client side when question has been displayed. If the JSON File was created before, then it will cache question block in client device on second visit if Cache Feature below enabled.<br/><i>default value: enable</i>', 'If enable, all displayed question will be stored in JSON File for faster loading in the next time. This JSON File triggered from client side when question has been displayed. If the JSON File was created before, then it will cache question block in client device on second visit if Cache Feature below enabled.<br/><i>default value: disable</i>', 1, $json['jsonFile'], false, '');
echo getFormRowCheckBox('createAllJsonFileOnStartup', 'Create JSON File on Startup (Cache All Question Block)', 'If enable, all question in current test will be stored in JSON File when first question load on client side. If JSON File was created before, then it will cache all question in client device if Cache Feature below enabled. WARNING! This feature will request all testlogid on first load question on client side through AJAX. It will increase client-server network usage.<br/><i>default value: enable</i>', 'If enable, all question in current test will be stored in JSON File when first question load on client side. If JSON File was created before, then it will cache all question in client device if Cache Feature below enabled. WARNING! This feature will request all testlogid on first load question on client side through AJAX. It will increase client-server network usage.<br/><i>default value: disable</i>', 1, $json['createAllJsonFileOnStartup'], false, '');
echo getFormRowCheckBox('cacheFeature', 'Cache Feature', 'If enable, browser will cache the question block, so user can navigate question in offline mode.<br/><i>default value: enable</i>', 'If enable, browser will cache the question block, so user can navigate question in offline mode. Cache Feature may use a lot of user\'s storage depend on the amount of questions size. To use this feature, make sure your site protocol is HTTPS and warn your user to spare diskspace if you enable this feature.<br/><i>default value: disable</i>', 1, $json['cacheFeature'], false, '');
echo getFormRowCheckBox('clearStorageOnLogin', 'Clear Local Storage on Login', 'If enable, local storage and cache storage will be cleared on login screen page.<br/><i>default value: enable</i>', 'If enable, local storage and cache storage will be cleared on login screen page.<br/><i>default value: enable</i>', 1, $json['clearStorageOnLogin'], false, '');
echo getFormRowCheckBox('enable_delay', 'Enable delay before starting test', 'If enable, add some random delay before executing new test.<br/><i>default value: disable</i>', 'If enable, add some random delay before executing new test.<br/><i>default value: disable</i>', 1, $json['enable_delay'], false, '');
echo '</fieldset>';

echo '<fieldset>';
echo '<legend class="bg-red">Chat Feature (Experimental)</legend>';
echo getFormRowCheckBox('chat_feature', 'Chat Feature', 'Chat Feature', '<i>default value: disable</i>', 1, $json['chat_feature'], false, '');
echo '</fieldset>';

echo '<div class="row jc-center">';
F_submit_button('update', $l['w_update'].'" class="bg-dark mb-10" style="width:100%', $l['h_update']);
echo '</div>';
echo F_getCSRFTokenField().K_NEWLINE;
echo '</form>';

echo '<form action="'.$_SERVER['SCRIPT_NAME'].'" method="POST">';
echo '<fieldset>';
echo '<legend class="bg-teal">After Login Greeting</legend>';
echo getFormRowCheckBox('enable_greeting', 'Enable Greeting', 'Enable Greeting', '<i>default value: disable</i>', 1, $jsonGreetings['enable_greeting'], false, '');
echo getFormRowTextInput('greetLine1', 'Greeting Line 1', 'Greeting Line 1', '', $jsonGreetings['greetLine1'], '', 255, false, false, false);
echo getFormRowTextInput('greetLine2', 'Greeting Line 2', 'Greeting Line 2', '', $jsonGreetings['greetLine2'], '', 255, false, false, false);
echo '</fieldset>';
echo '<div class="row jc-center">';
F_submit_button('update-greeting', $l['w_update'].'" class="bg-teal mb-10" style="width:100% ', $l['h_update']);
echo '</div>';
echo F_getCSRFTokenField().K_NEWLINE;
echo '</form>';

echo '<form action="'.$_SERVER['SCRIPT_NAME'].'" method="POST">';
echo '<fieldset>';
echo '<legend class="bg-indigo">Login Box Additional Message</legend>';
echo getFormRowTextInput('ail_beforefield', 'Message before Login Field', 'Message before Login Field', 'Leave blank to disable', $lbam['ail_beforefield'], '', 255, false, false, false);
echo getFormRowTextInput('ail_afterfield', 'Message after Login Field', 'Message after Login Field', 'Leave blank to disable', $lbam['ail_afterfield'], '', 255, false, false, false);
echo '</fieldset>';
echo '<div class="row jc-center">';
F_submit_button('update-addinfologin', $l['w_update'].'" class="bg-indigo mb-10" style="width:100%', $l['h_update']);
echo '</div>';
echo F_getCSRFTokenField().K_NEWLINE;
echo '</form>';

echo '<form action="'.$_SERVER['SCRIPT_NAME'].'" method="POST">';
echo '<fieldset>';
echo '<legend class="bg-yellow ft-black">Timer Warning</legend>';
echo getFormRowTextInput('almostend1_time', 'Show first warning on', 'Show first warning on', 'last minute', $tm['almostend1_time'], '', 255, false, false, false);
echo getFormRowTextInput('almostend1_msg', 'First warning message', 'First warning message', 'Leave blank to disable', $tm['almostend1_msg'], '', 255, false, false, false);
echo getFormRowTextInput('almostend1_bg', 'First warning background color', 'First warning background color', 'Leave blank to disable', $tm['almostend1_bg'], '', 255, false, false, false);
echo getFormRowTextInput('almostend1_col', 'First warning text color', 'First warning text color', 'Leave blank to disable', $tm['almostend1_col'], '', 255, false, false, false);

echo getFormRowTextInput('almostend2_time', 'Show second warning on', 'Show second warning on', 'last minute', $tm['almostend2_time'], '', 255, false, false, false);
echo getFormRowTextInput('almostend2_msg', 'Second warning message', 'Second warning message', 'Leave blank to disable', $tm['almostend2_msg'], '', 255, false, false, false);
echo getFormRowTextInput('almostend2_bg', 'Second warning background color', 'Second warning background color', 'Leave blank to disable', $tm['almostend2_bg'], '', 255, false, false, false);
echo getFormRowTextInput('almostend2_col', 'Second warning text color', 'Second warning text color', 'Leave blank to disable', $tm['almostend2_col'], '', 255, false, false, false);
echo getFormRowTextInput('lastsec_msg', '10 last second message', '10 last second message', 'Leave blank to disable', $tm['lastsec_msg'], '', 255, false, false, false);
echo getFormRowTextInput('lastsec_bg', '10 last second background color', '10 last second background color', 'Leave blank to disable', $tm['lastsec_bg'], '', 255, false, false, false);
echo getFormRowTextInput('lastsec_col', '10 last second text color', '10 last second text color', 'Leave blank to disable', $tm['lastsec_col'], '', 255, false, false, false);
echo '</fieldset>';
echo '<div class="row jc-center">';
F_submit_button('update-timerwarning', $l['w_update'].'" class="ft-black mb-10" style="width:100%;background:#fbc02d', $l['h_update']);
echo '</div>';
echo F_getCSRFTokenField().K_NEWLINE;
echo '</form>';

echo '</div>';
echo '</div>';


require_once('../code/tce_page_footer.php');
?>