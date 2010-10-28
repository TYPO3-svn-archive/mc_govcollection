<?php

########################################################################
# Extension Manager/Repository config file for ext "mc_govcollection".
#
# Auto generated 28-10-2010 20:26
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Gov Extensions Collection',
	'description' => 'Collection with Extensions useful for a gov-Site',
	'category' => 'plugin',
	'author' => 'Maurus Caflisch',
	'author_email' => 'caflisch@kns.ch',
	'shy' => '',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => 1,
	'createDirs' => 'uploads/tx_mcgovcollection/rte/,uploads/mc_govcollection_file',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => '',
	'version' => '1.0.0',
	'constraints' => array(
		'depends' => array(
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:75:{s:12:"ext_icon.gif";s:4:"a5cd";s:17:"ext_localconf.php";s:4:"5b5a";s:14:"ext_tables.php";s:4:"9079";s:14:"ext_tables.sql";s:4:"cf6b";s:16:"locallang_db.xml";s:4:"dd91";s:16:"locallang_ff.xml";s:4:"5697";s:55:"fe_plugins/contact/class.tx_mcgovcollection_contact.php";s:4:"bb6f";s:64:"fe_plugins/contact/class.tx_mcgovcollection_contact_renderer.php";s:4:"f665";s:31:"fe_plugins/contact/flexform.xml";s:4:"262b";s:32:"fe_plugins/contact/locallang.xml";s:4:"0c72";s:42:"fe_plugins/contact/templates/template.html";s:4:"cc82";s:49:"fe_plugins/file/class.tx_mcgovcollection_file.php";s:4:"affb";s:58:"fe_plugins/file/class.tx_mcgovcollection_file_renderer.php";s:4:"3445";s:28:"fe_plugins/file/flexform.xml";s:4:"bbc4";s:29:"fe_plugins/file/locallang.xml";s:4:"bf08";s:38:"fe_plugins/file/templates/download.gif";s:4:"0fac";s:38:"fe_plugins/file/templates/download.png";s:4:"a572";s:34:"fe_plugins/file/templates/info.png";s:4:"5c0d";s:39:"fe_plugins/file/templates/template.html";s:4:"a5be";s:49:"fe_plugins/form/class.tx_mcgovcollection_form.php";s:4:"9737";s:58:"fe_plugins/form/class.tx_mcgovcollection_form_renderer.php";s:4:"6c8b";s:28:"fe_plugins/form/flexform.xml";s:4:"6d52";s:29:"fe_plugins/form/locallang.xml";s:4:"2278";s:34:"fe_plugins/form/templates/form.jpg";s:4:"84f5";s:34:"fe_plugins/form/templates/info.png";s:4:"5c0d";s:34:"fe_plugins/form/templates/link.jpg";s:4:"7f01";s:39:"fe_plugins/form/templates/template.html";s:4:"de7e";s:49:"fe_plugins/news/class.tx_mcgovcollection_news.php";s:4:"16ba";s:58:"fe_plugins/news/class.tx_mcgovcollection_news_renderer.php";s:4:"87cb";s:28:"fe_plugins/news/flexform.xml";s:4:"3729";s:29:"fe_plugins/news/locallang.xml";s:4:"e40a";s:36:"fe_plugins/news/templates/format.css";s:4:"fba8";s:34:"fe_plugins/news/templates/icon.gif";s:4:"475a";s:36:"fe_plugins/news/templates/paging.gif";s:4:"9526";s:39:"fe_plugins/news/templates/template.html";s:4:"d405";s:19:"lib/jquery-1.4.3.js";s:4:"2e5c";s:22:"lib/jquery.validate.js";s:4:"7c8f";s:18:"res/images/add.gif";s:4:"408a";s:28:"res/images/delete_record.gif";s:4:"e31a";s:42:"res/images/icon_tx_mcgovcollection_cat.gif";s:4:"c17f";s:46:"res/images/icon_tx_mcgovcollection_contact.gif";s:4:"8146";s:50:"res/images/icon_tx_mcgovcollection_contact_cat.gif";s:4:"5fe4";s:43:"res/images/icon_tx_mcgovcollection_file.gif";s:4:"71e4";s:47:"res/images/icon_tx_mcgovcollection_file_cat.gif";s:4:"369f";s:43:"res/images/icon_tx_mcgovcollection_form.gif";s:4:"8941";s:43:"res/images/icon_tx_mcgovcollection_news.gif";s:4:"288e";s:19:"res/tca/tca_cat.php";s:4:"b5b6";s:23:"res/tca/tca_contact.php";s:4:"c730";s:27:"res/tca/tca_contact_cat.php";s:4:"bc70";s:20:"res/tca/tca_file.php";s:4:"f974";s:24:"res/tca/tca_file_cat.php";s:4:"30cb";s:20:"res/tca/tca_form.php";s:4:"1243";s:20:"res/tca/tca_news.php";s:4:"0a19";s:41:"wizard/class.tx_mcgovcollection_forms.php";s:4:"dbcf";s:48:"wizard/class.tx_mcgovcollection_wizard_forms.php";s:4:"6ae9";s:20:"wizard/locallang.xml";s:4:"f201";s:21:"wizard/images/add.png";s:4:"309a";s:26:"wizard/images/calendar.png";s:4:"edbd";s:26:"wizard/images/checkbox.png";s:4:"daf3";s:24:"wizard/images/delete.png";s:4:"bea3";s:22:"wizard/images/down.png";s:4:"51fc";s:23:"wizard/images/email.png";s:4:"af58";s:22:"wizard/images/file.png";s:4:"32c1";s:24:"wizard/images/passwd.png";s:4:"fa05";s:23:"wizard/images/radio.png";s:4:"3893";s:24:"wizard/images/remove.png";s:4:"e691";s:24:"wizard/images/select.png";s:4:"6998";s:22:"wizard/images/text.png";s:4:"a7ec";s:26:"wizard/images/textarea.png";s:4:"e243";s:20:"wizard/images/up.png";s:4:"8351";s:25:"wizard/images/website.png";s:4:"eca2";s:26:"wizard/template/format.css";s:4:"3d7d";s:38:"wizard/template/mc_renderer_forms.html";s:4:"3695";s:36:"wizard/template/mc_wizard_forms.html";s:4:"786e";s:41:"wizard/template/mc_wizard_forms_cont.html";s:4:"ca27";}',
	'suggests' => array(
	),
);

?>