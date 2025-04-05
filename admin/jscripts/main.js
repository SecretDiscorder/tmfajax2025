$("span#mmCloseBtn").click(function(){
	$("div#scrollayer").toggle();
});
$("a.white").parent().css({"visibility":"hidden","height":"0"});

$("span.active").parents().eq(1).show();

$("ul.menu li a").click(function(){
	// alert($(this).name);
	$(this).next("ul").slideToggle();
	$("ul.menu li a").not(this).next("ul").slideUp();
});

$("div.pagehelp").prepend("<i class='fas fa-info info-icon'></i>");
/**
$str = $("span#user-answer").text();
$str2 = $("ol.question").html();
//$str = 'this is a [b]bolded[/b] and [i]italic[/i] string';

// The array of regex patterns to look for
$format_search =  [
    /\[img\](.*?)\[\/img\]/ig,
	/\[b\](.*?)\[\/b\]/ig,
    /\[i\](.*?)\[\/i\]/ig,
    /\[u\](.*?)\[\/u\]/ig
]; // note: NO comma after the last entry

// The matching array of strings to replace matches with
$format_replace = [
    '<img src="$1" />',
	'<strong>$1</strong>',
    '<em>$1</em>',
    '<span style="text-decoration: underline;">$1</span>'
];

// Perform the actual conversion
for (var i =0;i<$format_search.length;i++) {
  $str = $str.replace($format_search[i], $format_replace[i]);
}
for (var i =0;i<$format_search.length;i++) {
  $str2 = $str2.replace($format_search[i], $format_replace[i]);
}
$("span#user-answer").html($str);
$("ol.question").html($str2);**/

$('#user_groups,#form_testeditor #subject_id[multiple]').selectize({
	plugins: ['remove_button']
});

/* $('#user_groups').selectize({
	plugins: ['remove_button']
}); */

$("#showPass").click(function(){
	$(this).toggle();
	$("#hidePass").toggle();
	$("#xuser_password").attr("type","text");
});

$("#hidePass").click(function(){
	$(this).toggle();
	$("#showPass").toggle();
	$("#xuser_password").attr("type","password");
});

$(".warning").click(function(){$(this).hide()})
$(".error").click(function(){$(this).hide()})
$(".message").click(function(){$(this).hide()})

if(document.getElementById("test_id")){
	var f_testName = document.getElementById("test_id")[document.getElementById("test_id").selectedIndex].textContent;
	if(f_testName.length<4){
		f_testName = 'All Test';
	}
}

var f_groupLbl = document.querySelector("label[for=group_id]").textContent;
var f_userLbl = document.querySelector("label[for=user_id]").textContent;

var f_userName = '';
if(document.getElementById("user_id")){
	f_userName = document.getElementById("user_id")[document.getElementById("user_id").selectedIndex].textContent;
}
if(f_userName.length<4){
	f_userName = 'All Users';
}

var f_groupName = '';
if(document.getElementById("group_id")){
	f_groupName = document.getElementById("group_id")[document.getElementById("group_id").selectedIndex].textContent;
}

if(f_groupName.length<4){
	f_groupName = 'All Groups';
}

var f_insName = document.getElementById("insName").textContent;
var f_pageName = document.querySelector(".h-title h1").textContent;
var addr1 = K_ADDRESS_LINE1.replace(/ /g, '%20');
var addr2 = K_ADDRESS_LINE2.replace(/ /g, '%20');
var addr3 = K_ADDRESS_LINE3.replace(/ /g, '%20');

function exportTableToExcel(tableID, filename = f_insName+' - '+f_pageName+' - '+f_testName+' - '+f_groupName){
	setTimeout(function(){
		let eltd = document.querySelectorAll("#test_result_users td:first-child");
		let tdlength = eltd.length;

		for(let i = 0; i < tdlength; i++){
			eltd[i].firstChild.remove();
			// eltd[i].firstChild.style.display = "none"
		}
	},1000)
	setTimeout(function(){
		var downloadLink;
		var dataType = 'application/vnd.ms-excel';
		var tableSelect = document.getElementById(tableID);
		// var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
		var tableHTML = '<h4>'+f_insName.replace(/ /g, '%20')+'<br/>'+addr1+'<br/>'+addr2+'<br/>'+addr3+'</h4><h5>'+f_pageName.replace(/ /g, '%20')+'%20:%20'+f_testName.replace(/ /g, '%20')+'<br/>'+f_groupLbl+'%20:%20'+f_groupName.replace(/ /g, '%20')+'<br/>'+f_userLbl+'%20:%20'+f_userName.replace(/ /g, '%20')+'</h5>'+tableSelect.outerHTML.replace(/ /g, '%20').replace(/#/g, '%23').replace(/checkbox/g, 'hidden').replace(/href/g, 'href-data').replace(/table/g, 'table%20border=1');
		// var tableHTML = tableSelect;
		
		// Specify file name
		filename = filename?filename+'.xls':'excel_data.xls';
		
		// Create download link element
		downloadLink = document.createElement("a");
		
		document.body.appendChild(downloadLink);
		
		if(navigator.msSaveOrOpenBlob){
			var blob = new Blob(['\ufeff', tableHTML], {
				type: dataType
			});
			navigator.msSaveOrOpenBlob( blob, filename);
		}else{
			// Create a link to the file
			downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
		
			// Setting the file name
			downloadLink.download = filename;
			
			//triggering the function
			downloadLink.click();
			
			window.location.reload()
		}
	},1000)
	// setTimeout(function(){window.location.reload()},500)
}

function toggleIsRight(a,b,c){
	
$.ajax({
		'url': 'tmf_toggle_answer.php?answer_isright='+a+'&answer_question_id='+b+'&answer_id='+c,
		'type': 'GET',
		// 'beforeSend': function(){$("div#generated").css({"padding":"7px 14px","color":"#fff","background":"yellow","borderRadius":"5px","marginBottom":"25px"});$("div#generated span#done").html("<b><span class='d-iblock pwrap bg-fuchsia mt-10 bd-white boxshd'>&infin; LOADING . . . </span></b>")},
		'success': function(result){
			if(result==="1"){
				if(a===0){
					alert("right answer has been removed");
				}else{
					alert("right answer has been added");
				}
			}
			location.reload()
		}		
});


}

//eg: #form_resultallusers td input[type=checkbox]
function checkAll(a){
	var cbArr = document.querySelectorAll(a);
	for(let i=0; i < cbArr.length; i++){
		if(!cbArr[i].checked){
			cbArr[i].click()
		}
	}
}

//uncheck all
function unCheckAll(a){
	var cbArr = document.querySelectorAll(a);
	for(let i=0; i < cbArr.length; i++){
		if(cbArr[i].checked){
			cbArr[i].click()
		}
	}	
}

$('#test_id').select2();
$('select#testuser_id').select2();
$('#group_id').select2();
$('#user_id').select2();
$('#display_mode').select2();
$('#module_id').select2();
$('#subject_module_id').select2();
$('#form_subjecteditor #subject_id').select2();
$('#question_subject_id').select2();
$('#question_id').select2();
$('#answer_question_id').select2();
$('#answer_id').select2();

if(document.getElementById("checkall1")){
	document.getElementById("checkall1").parentNode.setAttribute("class","ltrdir");
}


function toggleAnswer(){
	let a;
	let hideAns = document.getElementById("hide_answers").checked;
	// alert(hideAns);
	if(hideAns===false){a = "unset"}else{a = "none"}
	// alert(a);
	let olans = document.querySelectorAll("ol.answer");
	let i;
	for(i=0; i<olans.length; i++){
		olans[i].style.display = a;
	}
}