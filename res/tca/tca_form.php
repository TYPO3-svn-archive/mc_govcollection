<?php
$TCA['tx_mcgovcollection_form'] = array (
	'ctrl' => $TCA['tx_mcgovcollection_form']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,title,formconfig,link,description,price'
	),
	'feInterface' => $TCA['tx_mcgovcollection_form']['feInterface'],
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
				'foreign_table'       => 'tx_mcgovcollection_form',
				'foreign_table_where' => 'AND tx_mcgovcollection_form.pid=###CURRENT_PID### AND tx_mcgovcollection_form.sys_language_uid IN (-1,0)',
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
		'type' => array (
			'exclude' => 0,
			'l10n_mode' => 'exclude', 
			'l10n_display' => 'defaultAsReadonly',
			'label' => 'LLL:EXT:mc_govcollection/locallang_db.xml:tx_mcgovcollection_form.type',
			'config' => array (
				'type' => 'select',
				'items' => array (
					0 => array('LLL:EXT:mc_govcollection/locallang_db.xml:tx_mcgovcollection_form.type.item.0', 'link'),
					1 => array('LLL:EXT:mc_govcollection/locallang_db.xml:tx_mcgovcollection_form.type.item.1', 'formular'),
				),
				'default' => 'link'
			)
		),
		'title' => array (        
			'exclude' => 0,
			'label' => 'LLL:EXT:mc_govcollection/locallang_db.xml:tx_mcgovcollection_form.title',        
			'config' => array (
				'type' => 'input',    
				'size' => '30',    
				'eval' => 'required',
			)
		),
		'link' => array (
			'exclude' => 0,
			'label' => 'LLL:EXT:mc_govcollection/locallang_db.xml:tx_mcgovcollection_form.link',
			'config' => array (
				'type' => 'input',
				'size' => '30',
				'eval' => 'trim',
				'wizards' => array (
					'_PADDING' => 4,
					'link' => array (
						'type' => 'popup',
						'title' => 'Link',
						'icon' => 'link_popup.gif',
						'script' => 'browse_links.php?mode=wizard',
						'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
					)
				)
			)
		),
		'formconfig' => array(
			'label' => 'LLL:EXT:mc_govcollection/locallang_db.xml:tx_mcgovcollection_form.formconfig',
			'config' => array (
				'type' => 'text',    
				'cols' => '48',
				'rows' => '5',
				'wizards' => array(
					'_PADDING' => 4,
					'_VALGIN' => 'middle',
					'form' => array(
						'notNewRecords' => 1,
						'enableByTypeConfig' => 1,
						'type' => 'script',
						'title' => 'Forms wizard',
						'icon' => 'wizard_forms.gif',
						'script' => 'EXT:mc_govcollection/wizard/class.tx_mcgovcollection_wizard_forms.php',
					),
				),
			)
		),
		'description' => array (
			'exclude' => 0,        
			'label' => 'LLL:EXT:mc_govcollection/locallang_db.xml:tx_mcgovcollection_form.description',        
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
		'price' => array (
			'exclude' => 0,
			'l10n_mode' => 'exclude', 
			'l10n_display' => 'defaultAsReadonly',
			'label' => 'LLL:EXT:mc_govcollection/locallang_db.xml:tx_mcgovcollection_form.price',
			'config' => array (
				'type' => 'input',    
				'size' => '10',    
				'eval' => 'double2',
			)
		),
		'topic_group' => array (        
			'exclude' => 0,
			'l10n_mode' => 'exclude', 
			'l10n_display' => 'defaultAsReadonly',        
			'label' => 'LLL:EXT:mc_govcollection/locallang_db.xml:tx_mcgovcollection_form.group',        
			'config' => array (
				'type' => 'select',    
				'foreign_table' => 'tx_mcgovcollection_topic_group',    
				'foreign_table_where' => 'AND sys_language_uid IN (0,-1) ORDER BY tx_mcgovcollection_topic_group.uid',    
				'size' => 1,    
				'minitems' => 0,
				'maxitems' => 1,
			)
		),
		'level' => array (
			'exclude' => 0,
			'l10n_mode' => 'exclude', 
			'l10n_display' => 'defaultAsReadonly',
			'label' => 'LLL:EXT:mc_govcollection/locallang_db.xml:tx_mcgovcollection_topic_group.level',        
			'config' => array (
				'type' => 'select',
				'items' => array (
					array('LLL:EXT:mc_govcollection/locallang_db.xml:tx_mcgovcollection_topic_group.level.1', 1),
					array('LLL:EXT:mc_govcollection/locallang_db.xml:tx_mcgovcollection_topic_group.level.2', 2),
					array('LLL:EXT:mc_govcollection/locallang_db.xml:tx_mcgovcollection_topic_group.level.3', 3)
				),
				'size' => 1,    
				'minitems' => 0,
				'maxitems' => 1,
			)
		)
	),
	'types' => array (
		'0' => array('showitem' => '
			hidden, 
			type,
			title,
			topic_group,
			level,  
			link, 
			--div--;LLL:EXT:mc_govcollection/locallang_db.xml:tx_mcgovcollection_form.register.details,
			description;;;richtext[]:rte_transform[mode=ts_css|imgpath=uploads/tx_mcgovcollection/rte/],
			price'
		),
		'formular' => array('showitem' => '
			hidden,
			type,
			title, 
			topic_group,
			level,  
			formconfig;;;nowrap:wizards[form], 
			--div--;LLL:EXT:mc_govcollection/locallang_db.xml:tx_mcgovcollection_form.register.details,
			description;;;richtext[]:rte_transform[mode=ts_css|imgpath=uploads/tx_mcgovcollection/rte/],
			price' 
		),
		'link' => array('showitem' => '
			hidden,
			type,
			title, 
			topic_group, 
			level, 
			link, 
			--div--;LLL:EXT:mc_govcollection/locallang_db.xml:tx_mcgovcollection_form.register.details,
			description;;;richtext[]:rte_transform[mode=ts_css|imgpath=uploads/tx_mcgovcollection/rte/],
			price' 
		)
	),
	'palettes' => array (
		'1' => array('showitem' => '')
	)
);
?>