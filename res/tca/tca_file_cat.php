<?php
if (!defined ('TYPO3_MODE'))     die ('Access denied.');

$TCA['tx_mcgovcollection_file_cat'] = array (
	'ctrl' => $TCA['tx_mcgovcollection_file_cat']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,name,parentcat'
	),
	'feInterface' => $TCA['tx_mcgovcollection_file_cat']['feInterface'],
	'columns' => array (
		'sys_language_uid' => array (        
			'exclude' => 1,
			'label'  => 'LLL:EXT:lang/locallang_general.xml:LGL.language',
			'config' => array (
				'type'                => 'select',
				'foreign_table'       => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xml:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.default_value', 0)
				)
			)
		),
		'l10n_parent' => array (        
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude'     => 1,
			'label'       => 'LLL:EXT:lang/locallang_general.xml:LGL.l18n_parent',
			'config'      => array (
				'type'  => 'select',
				'items' => array (
					array('', 0),
				),
				'foreign_table'       => 'tx_mcgovcollection_file_cat',
				'foreign_table_where' => 'AND tx_mcgovcollection_file_cat.pid=###CURRENT_PID### AND tx_mcgovcollection_file_cat.sys_language_uid IN (-1,0)',
			)
		),
		'l10n_diffsource' => array (        
			'config' => array (
				'type' => 'passthrough'
			)
		),
		'hidden' => array (        
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		'name' => array (        
			'exclude' => 0,        
			'label' => 'LLL:EXT:mc_govcollection/locallang_db.xml:tx_mcgovcollection_file_cat.name',        
			'config' => array (
				'type' => 'input',    
				'size' => '30',
			)
		),
		'parentcat' => array (        
			'exclude' => 0,        
			'label' => 'LLL:EXT:mc_govcollection/locallang_db.xml:tx_mcgovcollection_file_cat.parentcat',        
			'config' => array (
				'type' => 'select',    
				'items' => array (
					array('',0),
				),
				'foreign_table' => 'tx_mcgovcollection_file_cat',    
				'foreign_table_where' => 'ORDER BY tx_mcgovcollection_file_cat.name',    
				'size' => 1,    
				'minitems' => 0,
				'maxitems' => 1,
			)
		),
	),
	'types' => array (
		'0' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, name, parentcat')
	),
	'palettes' => array (
		'1' => array('showitem' => '')
	)
);
?>