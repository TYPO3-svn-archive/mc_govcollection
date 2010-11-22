<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}
$TCA['tx_mcgovcollection_news'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:mc_govcollection/locallang_db.xml:tx_mcgovcollection_news',		
		'label'     => 'title',	
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'languageField'            => 'sys_language_uid',	
		'transOrigPointerField'    => 'l10n_parent',	
		'transOrigDiffSourceField' => 'l10n_diffsource',	
		'default_sortby' => 'ORDER BY crdate',	
		'delete' => 'deleted',	
		'enablecolumns' => array (		
			'disabled' => 'hidden',	
			'starttime' => 'starttime',	
			'endtime' => 'endtime',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'res/tca/tca_news.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'res/images/icon_tx_mcgovcollection_news.gif',
	),
);

$TCA['tx_mcgovcollection_cat'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:mc_govcollection/locallang_db.xml:tx_mcgovcollection_cat',		
		'label'     => 'title',	
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'languageField'            => 'sys_language_uid',	
		'transOrigPointerField'    => 'l10n_parent',	
		'transOrigDiffSourceField' => 'l10n_diffsource',	
		'default_sortby' => 'ORDER BY crdate',	
		'delete' => 'deleted',	
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'res/tca/tca_cat.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'res/images/icon_tx_mcgovcollection_cat.gif',
	),
);

$TCA['tx_mcgovcollection_contact'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:mc_govcollection/locallang_db.xml:tx_mcgovcollection_contact',        
		'label'     => 'name',    
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs'	=> true,
		'languageField'            => 'sys_language_uid',    
		'transOrigPointerField'    => 'l10n_parent',    
		'transOrigDiffSourceField' => 'l10n_diffsource',    
		'default_sortby' => 'ORDER BY name',    
		'delete' => 'deleted',    
		'enablecolumns' => array (        
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'res/tca/tca_contact.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'res/images/icon_tx_mcgovcollection_contact.gif',
	),
);

$TCA['tx_mcgovcollection_contact_cat'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:mc_govcollection/locallang_db.xml:tx_mcgovcollection_contact_cat',        
		'label'     => 'name',    
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'languageField'            => 'sys_language_uid',    
		'transOrigPointerField'    => 'l10n_parent',    
		'transOrigDiffSourceField' => 'l10n_diffsource',    
		'default_sortby' => 'ORDER BY name',    
		'delete' => 'deleted',    
		'enablecolumns' => array (        
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'res/tca/tca_contact_cat.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'res/images/icon_tx_mcgovcollection_contact_cat.gif',
	),
);

$TCA['tx_mcgovcollection_form'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:mc_govcollection/locallang_db.xml:tx_mcgovcollection_form',        
		'label'     => 'title',    
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'type' 	=> 'type',
		'dividers2tabs'	=> true,
		'languageField'            => 'sys_language_uid',    
		'transOrigPointerField'    => 'l10n_parent',    
		'transOrigDiffSourceField' => 'l10n_diffsource',    
		'default_sortby' => 'ORDER BY title',    
		'delete' => 'deleted',    
		'enablecolumns' => array (        
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'res/tca/tca_form.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'res/images/icon_tx_mcgovcollection_form.gif',
	),
);

$TCA['tx_mcgovcollection_topic_area'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:mc_govcollection/locallang_db.xml:tx_mcgovcollection_topic_area',        
		'label'     => 'title',    
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'type' 	=> 'type',
		'dividers2tabs'	=> true,
		'languageField'            => 'sys_language_uid',    
		'transOrigPointerField'    => 'l10n_parent',    
		'transOrigDiffSourceField' => 'l10n_diffsource',    
		'default_sortby' => 'ORDER BY title',    
		'delete' => 'deleted',    
		'enablecolumns' => array (        
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'res/tca/tca_topic_area.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'res/images/icon_tx_mcgovcollection_topic_area.gif',
	),
);

$TCA['tx_mcgovcollection_topic_group'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:mc_govcollection/locallang_db.xml:tx_mcgovcollection_topic_group',        
		'label'     => 'title',    
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'type' 	=> 'type',
		'dividers2tabs'	=> true,
		'languageField'            => 'sys_language_uid',    
		'transOrigPointerField'    => 'l10n_parent',    
		'transOrigDiffSourceField' => 'l10n_diffsource',    
		'default_sortby' => 'ORDER BY title',    
		'delete' => 'deleted',    
		'enablecolumns' => array (        
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'res/tca/tca_topic_group.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'res/images/icon_tx_mcgovcollection_topic_group.gif',
	),
);

$TCA['tx_mcgovcollection_file'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:mc_govcollection/locallang_db.xml:tx_mcgovcollection_file',        
		'label'     => 'title',    
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'languageField'            => 'sys_language_uid',    
		'transOrigPointerField'    => 'l10n_parent',    
		'transOrigDiffSourceField' => 'l10n_diffsource',    
		'default_sortby' => 'ORDER BY title',    
		'delete' => 'deleted',    
		'enablecolumns' => array (        
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'res/tca/tca_file.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'res/images/icon_tx_mcgovcollection_file.gif',
	),
);

$TCA['tx_mcgovcollection_file_cat'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:mc_govcollection/locallang_db.xml:tx_mcgovcollection_file_cat',        
		'label'     => 'name',    
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'languageField'            => 'sys_language_uid',    
		'transOrigPointerField'    => 'l10n_parent',    
		'transOrigDiffSourceField' => 'l10n_diffsource',    
		'default_sortby' => 'ORDER BY name',    
		'delete' => 'deleted',    
		'enablecolumns' => array (        
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'res/tca/tca_file_cat.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'res/images/icon_tx_mcgovcollection_file_cat.gif',
	),
);

t3lib_div::loadTCA('tt_content');

// Pi1 Subtypes Config
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_news']='layout,select_key';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_news'] ='pi_flexform';
t3lib_extMgm::addPiFlexFormValue($_EXTKEY.'_news', 'FILE:EXT:'.$_EXTKEY . '/fe_plugins/news/flexform.xml');

// Pi2 Subtypes Config
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_contact']='layout,select_key';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_contact'] ='pi_flexform';
t3lib_extMgm::addPiFlexFormValue($_EXTKEY.'_contact', 'FILE:EXT:'.$_EXTKEY . '/fe_plugins/contact/flexform.xml');

// Pi3 Subtypes Config
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_form']='layout,select_key';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_form'] ='pi_flexform';
t3lib_extMgm::addPiFlexFormValue($_EXTKEY.'_form', 'FILE:EXT:'.$_EXTKEY . '/fe_plugins/form/flexform.xml');

// Pi3 Subtypes Config
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_file']='layout,select_key';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_file'] ='pi_flexform';
t3lib_extMgm::addPiFlexFormValue($_EXTKEY.'_file', 'FILE:EXT:'.$_EXTKEY . '/fe_plugins/file/flexform.xml');

// Add Plugins
t3lib_extMgm::addPlugin(
	array(
		'LLL:EXT:mc_govcollection/locallang_db.xml:tt_content.list_type_news', 
		$_EXTKEY . '_news', 
		t3lib_extMgm::extRelPath($_EXTKEY) . 'ext_icon.gif'
	),
	'list_type');

t3lib_extMgm::addPlugin(
	array(
		'LLL:EXT:mc_govcollection/locallang_db.xml:tt_content.list_type_contact', 
		$_EXTKEY . '_contact', 
		t3lib_extMgm::extRelPath($_EXTKEY) . 'ext_icon.gif'
	),
	'list_type');
	
t3lib_extMgm::addPlugin(
	array(
		'LLL:EXT:mc_govcollection/locallang_db.xml:tt_content.list_type_form', 
		$_EXTKEY . '_form', 
		t3lib_extMgm::extRelPath($_EXTKEY) . 'ext_icon.gif'
	),
	'list_type');
	
t3lib_extMgm::addPlugin(
	array(
		'LLL:EXT:mc_govcollection/locallang_db.xml:tt_content.list_type_file', 
		$_EXTKEY . '_file', 
		t3lib_extMgm::extRelPath($_EXTKEY) . 'ext_icon.gif'
	),
	'list_type');
?>