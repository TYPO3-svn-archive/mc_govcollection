<?php
$TCA['tx_mcgovcollection_topic_group'] = array (
	'ctrl' => $TCA['tx_mcgovcollection_topic_group']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,title,description,area'
	),
	'feInterface' => $TCA['tx_mcgovcollection_topic_group']['feInterface'],
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
				'foreign_table'       => 'tx_mcgovcollection_topic_group',
				'foreign_table_where' => 'AND tx_mcgovcollection_topic_group.pid=###CURRENT_PID### AND tx_mcgovcollection_topic_group.sys_language_uid IN (-1,0)',
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
		'title' => array (        
			'exclude' => 0,
			'label' => 'LLL:EXT:mc_govcollection/locallang_db.xml:tx_mcgovcollection_topic_group.title',        
			'config' => array (
				'type' => 'input',    
				'size' => '30',    
				'eval' => 'required',
			)
		),
		'description' => array (
			'exclude' => 0,        
			'label' => 'LLL:EXT:mc_govcollection/locallang_db.xml:tx_mcgovcollection_topic_group.description',        
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
		'area' => array (        
			'exclude' => 0,
			'l10n_mode' => 'exclude', 
			'l10n_display' => 'defaultAsReadonly',        
			'label' => 'LLL:EXT:mc_govcollection/locallang_db.xml:tx_mcgovcollection_topic_group.area',        
			'config' => array (
				'type' => 'select',    
				'foreign_table' => 'tx_mcgovcollection_topic_area',    
				'foreign_table_where' => 'AND sys_language_uid IN (0,-1) ORDER BY tx_mcgovcollection_topic_area.uid',    
				'size' => 1,    
				'minitems' => 0,
				'maxitems' => 1,
			)
		),
	),
	'types' => array (
		'0' => array('showitem' => '
			hidden, 
			area,
			title,
			description;;;richtext[]:rte_transform[mode=ts_css|imgpath=uploads/tx_mcgovcollection/rte/]
		'),
	),
	'palettes' => array (
		'1' => array('showitem' => '')
	)
);
?>