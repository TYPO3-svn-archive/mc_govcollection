<?php
$TCA['tx_mcgovcollection_contact'] = array (
	'ctrl' => $TCA['tx_mcgovcollection_contact']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,name,adress,city,contactname,tel,fax,email,description,web,category'
		),
	'feInterface' => $TCA['tx_mcgovcollection_contact']['feInterface'],
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
				'foreign_table'       => 'tx_mcgovcollection_contact',
				'foreign_table_where' => 'AND tx_mcgovcollection_contact.pid=###CURRENT_PID### AND tx_mcgovcollection_contact.sys_language_uid IN (-1,0)',
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
			'l10n_mode' => 'exclude', 
			'l10n_display' => 'defaultAsReadonly',       
			'label' => 'LLL:EXT:mc_govcollection/locallang_db.xml:tx_mcgovcollection_contact.name',        
			'config' => array (
				'type' => 'input',    
				'size' => '30',    
				'eval' => 'required',
			)
		),
		'adress' => array (        
			'exclude' => 0,  
			'l10n_mode' => 'exclude', 
			'l10n_display' => 'defaultAsReadonly',     
			'label' => 'LLL:EXT:mc_govcollection/locallang_db.xml:tx_mcgovcollection_contact.adress',        
			'config' => array (
				'type' => 'input',    
				'size' => '30',
			)
		),
		'city' => array (        
			'exclude' => 0,  
			'l10n_mode' => 'exclude',
			'l10n_display' => 'defaultAsReadonly',      
			'label' => 'LLL:EXT:mc_govcollection/locallang_db.xml:tx_mcgovcollection_contact.city',        
			'config' => array (
				'type' => 'input',    
				'size' => '30',
			)
		),
		'contactname' => array (        
			'exclude' => 0,   
			'l10n_mode' => 'exclude',
			'l10n_display' => 'defaultAsReadonly',     
			'label' => 'LLL:EXT:mc_govcollection/locallang_db.xml:tx_mcgovcollection_contact.contactname',        
			'config' => array (
				'type' => 'input',    
				'size' => '30',
			)
		),
		'tel' => array (        
			'exclude' => 0,
			'l10n_mode' => 'exclude',
			'l10n_display' => 'defaultAsReadonly',        
			'label' => 'LLL:EXT:mc_govcollection/locallang_db.xml:tx_mcgovcollection_contact.tel',        
			'config' => array (
				'type' => 'input',    
				'size' => '30',
			)
		),
		'fax' => array (        
			'exclude' => 0,  
			'l10n_mode' => 'exclude',
			'l10n_display' => 'defaultAsReadonly',      
			'label' => 'LLL:EXT:mc_govcollection/locallang_db.xml:tx_mcgovcollection_contact.fax',        
			'config' => array (
				'type' => 'input',    
				'size' => '30',
			)
		),
		'email' => array (        
			'exclude' => 0,
			'l10n_mode' => 'exclude',
			'l10n_display' => 'defaultAsReadonly',        
			'label' => 'LLL:EXT:mc_govcollection/locallang_db.xml:tx_mcgovcollection_contact.email',        
			'config' => array (
				'type' => 'input',    
				'size' => '30',
			)
		),
		'description' => array (        
			'exclude' => 0,        
			'label' => 'LLL:EXT:mc_govcollection/locallang_db.xml:tx_mcgovcollection_contact.description',        
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
		'web' => array (        
			'exclude' => 0,
			'l10n_mode' => 'exclude',
			'l10n_display' => 'defaultAsReadonly',        
			'label' => 'LLL:EXT:mc_govcollection/locallang_db.xml:tx_mcgovcollection_contact.web',        
			'config' => array (
				'type'     => 'input',
				'size'     => '30',
			)
		),
		'category' => array (        
			'exclude' => 0,
			'l10n_mode' => 'exclude',
			'l10n_display' => 'defaultAsReadonly',        
			'label' => 'LLL:EXT:mc_govcollection/locallang_db.xml:tx_mcgovcollection_contact.category',        
			'config' => array (
				'type' => 'select',    
				'foreign_table' => 'tx_mcgovcollection_contact_cat',    
				'foreign_table_where' => 'ORDER BY tx_mcgovcollection_contact_cat.uid',    
				'size' => 1,    
				'minitems' => 0,
				'maxitems' => 1,
			)
		),
	),
	'types' => array (
		'0' => array(
			'showitem' => '
				hidden, 
				category,
				name;;1, 
				email;;2, 
				contactname,
				web, 
				--div--;LLL:EXT:mc_govcollection/locallang_db.xml:tx_mcgovcollection_contact.register.description,
				description;;;richtext[]:rte_transform[mode=ts_css|imgpath=uploads/tx_mcgovcollection/rte/], 
		')
	),
	'palettes' => array (
		'1' => array('showitem' => 'adress, city'),
		'2' => array('showitem' => 'tel, fax')
	)
);
                                            ?>