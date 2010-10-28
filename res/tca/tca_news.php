<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA['tx_mcgovcollection_news'] = array (
	'ctrl' => $TCA['tx_mcgovcollection_news']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,starttime,endtime,date,title,subtitle,bodytext,category'
	),
	'feInterface' => $TCA['tx_mcgovcollection_news']['feInterface'],
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
				'foreign_table'       => 'tx_mcgovcollection_news',
				'foreign_table_where' => 'AND tx_mcgovcollection_news.pid=###CURRENT_PID### AND tx_mcgovcollection_news.sys_language_uid IN (-1,0)',
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
		'starttime' => array (		
			'exclude' => 1,
			'l10n_mode' => 'exclude', 
			'l10n_display' => 'defaultAsReadonly',
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.starttime',
			'config'  => array (
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				'eval'     => 'date',
				'default'  => '0',
				'checkbox' => '0'
			)
		),
		'endtime' => array (		
			'exclude' => 1,
			'l10n_mode' => 'exclude', 
			'l10n_display' => 'defaultAsReadonly',
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.endtime',
			'config'  => array (
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				'eval'     => 'date',
				'checkbox' => '0',
				'default'  => '0',
				'range'    => array (
					'upper' => mktime(3, 14, 7, 1, 19, 2038),
					'lower' => mktime(0, 0, 0, date('m')-1, date('d'), date('Y'))
				)
			)
		),
		'date' => array (		
			'exclude' => 0,	
			'l10n_mode' => 'exclude', 
			'l10n_display' => 'defaultAsReadonly',	
			'label' => 'LLL:EXT:mc_govcollection/locallang_db.xml:tx_mcgovcollection_news.date',		
			'config' => array (
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				'eval'     => 'date',
				'checkbox' => '0',
				'default'  => '0'
			)
		),
		'title' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:mc_govcollection/locallang_db.xml:tx_mcgovcollection_news.title',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',	
				'eval' => 'required',
			)
		),
		'subtitle' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:mc_govcollection/locallang_db.xml:tx_mcgovcollection_news.subtitle',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',
			)
		),
		'bodytext' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:mc_govcollection/locallang_db.xml:tx_mcgovcollection_news.bodytext',		
			'config' => array (
				'type' => 'text',
				'cols' => '30',
				'rows' => '5',
				'wizards' => array(
					'_PADDING' => 2,
					'RTE' => array(
						'notNewRecords' => 1,
						'RTEonly'       => 1,
						'type'          => 'script',
						'title'         => 'Full screen Rich Text Editing|Formatteret redigering i hele vinduet',
						'icon'          => 'wizard_rte2.gif',
						'script'        => 'wizard_rte.php',
					),
				),
			)
		),
		'category' => array (		
			'exclude' => 0,
			'l10n_mode' => 'exclude', 
			'l10n_display' => 'defaultAsReadonly',		
			'label' => 'LLL:EXT:mc_govcollection/locallang_db.xml:tx_mcgovcollection_news.category',		
			'config' => array (
				'type' => 'select',	
				'foreign_table' => 'tx_mcgovcollection_cat',	
				'foreign_table_where' => 'ORDER BY tx_mcgovcollection_cat.uid',	
				'size' => 1,	
				'minitems' => 0,
				'maxitems' => 1,	
			)
		),
	),
	'types' => array (
		'0' => array(
			'showitem' => '
				hidden;;1, 
				date, 
				title;;;;2-2-2, 
				subtitle;;;;3-3-3, 
				bodytext;;;richtext[]:rte_transform[mode=ts_css|imgpath=uploads/tx_mcgovcollection/rte/], 
				category'
		)
	),
	'palettes' => array (
		'1' => array('showitem' => 'starttime, endtime')
	)
);
?>