<?php
/***************************************************************
*  Copyright notice
*
*  (c) 1999-2010 Kasper Skaarhoj (kasperYYYY@typo3.com)
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*  A copy is found in the textfile GPL.txt and important notices to the license
*  from the author is found in LICENSE.txt distributed with these scripts.
*
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * Wizard to help make forms (fx. for tt_content elements) of type 'form'.
 *
 * $Id: wizard_forms.php 8429 2010-07-28 09:19:00Z ohader $
 * Revised for TYPO3 3.6 November/2003 by Kasper Skaarhoj
 * XHTML compliant
 *
 * @author	Kasper Skaarhoj <kasperYYYY@typo3.com>
 */

define('TYPO3_MOD_PATH', '../typo3conf/ext/mc_govcollection/wizard/');
$BACK_PATH='../../../../typo3/';
require $BACK_PATH.'init.php';
require $BACK_PATH.'template.php';

include_once(PATH_t3lib.'class.t3lib_tcemain.php');

$LANG->includeLLFile('EXT:mc_govcollection/wizard/locallang.xml');

class tx_mcgovcollection_wizard_forms {
	/**
	 * document template object
	 *
	 * @var mediumDoc
	 */
	var $doc;
	var $content;				// Content accumulation for the module.
	var $template;
	
	// Internal, static: GPvars
	var $P;						// Wizard parameters, coming from TCEforms linking to the wizard.
	var $FORMCFG;				// The array which is constantly submitted by the multidimensional form of this wizard.
	var $special;				// Indicates if the form is of a dedicated type, like "formtype_mail" (for tt_content element "Form")
	
	var $av_field_types = array('text', 'select', 'textarea', 'checkbox', 'radio', 'email', 'date');
	var $av_layout_types = array('grouptitle', 'spacer');
	
	var $temp_form = array();
	
	/**
	 * Initialization the class
	 *
	 * @return	void
	 */
	function init()	{
		// GPvars:
		$this->P = t3lib_div::_GP('P');
		$this->special = t3lib_div::_GP('special');
		$this->FORMCFG = t3lib_div::_GP('FORMCFG');
		
		// Document template object:
		$this->doc = t3lib_div::makeInstance('template');
		$this->doc->backPath = $GLOBALS['BACK_PATH'];
		$this->doc->setModuleTemplate(TYPO3_MOD_PATH.'template/mc_wizard_forms.html');
		$this->doc->JScode=$this->doc->wrapScriptTags('
			function jumpToUrl(URL,formEl)	{	//
				window.location.href = URL;
			}
			
			function actionItem(action, id) {
				document.getElementById(\'mc_wizard_forms[action]\').value = action;
				document.getElementById(\'mc_wizard_forms[id]\').value = id;
				document.getElementById(\'mc_wizard_form\').submit();
			}
		');
		
		// Gespeicherte Felder in temp_form legen (wird von processInput überschrieben, falls änderungen da)
		$row = t3lib_BEfunc::getRecord($this->P['table'],$this->P['uid']);
		$this->temp_form = t3lib_div::xml2array($row[$this->P['field']]);
		
		// Setting form tag:
		list($rUri) = explode('#',t3lib_div::getIndpEnv('REQUEST_URI'));
		$this->doc->form ='<form action="'.htmlspecialchars($rUri).'" method="post" id="mc_wizard_form" name="mc_wizard_form">';
		
		// Content-Template
		$this->template = t3lib_div::getUrl('template/mc_wizard_forms_cont.html');
		$this->template = preg_replace_callback("/#LLL#(.*?)#LLL#/", array($this, 'translation_callback'), $this->template);
		
	}
	
	/**
	 * Helper-Function for Translation-Callback
	 * @param $matches
	 */
	private function translation_callback($matches) {
		return $GLOBALS['LANG']->getLL($matches[1]);
	}
	
	/**
	 * Main function for rendering the form wizard HTML
	 *
	 * @return	void
	 */
	function main()	{
		$body = '';
		
		// Get the Header-Content
		if ($this->P['table'] && $this->P['field'] && $this->P['uid'])	{
			$body .= $this->doc->section($GLOBALS['LANG']->getLL('title'), '', 0, 1);
		} else {
			$body .= $this->doc->section($GLOBALS['LANG']->getLL('title'), '<span class="typo3-red">' . $GLOBALS['LANG']->getLL('table_noData',1) . '</span>', 0, 1);
		}
		
		// Process the main-Content
		$body .= $this->renderContent();

		// Setting up the buttons and markers for docheader
		$docHeaderButtons = $this->getButtons();
		$markers['CSH'] = $docHeaderButtons['csh'];
		$markers['CONTENT'] = $body;

		// Build the module
		$this->content = $this->doc->startPage('Form Wizard');
		$this->content .= $this->doc->moduleBody($this->pageinfo, $docHeaderButtons, $markers);
		$this->content .= $this->doc->endPage();
		echo $this->doc->insertStylesAndJS($this->content);
	}
	
	function processInput() {
		// Felder in temp_form legen
		if(isset($_POST['mc_wizard_forms']['field']) || isset($_POST['mc_wizard_forms']['new'])) {
			$this->temp_form = $_POST['mc_wizard_forms']['field'];
		}
		
		// Process Save or SaveAndClose-Action
		if ($_POST['savedok_x'] || $_POST['saveandclosedok_x'])	{
			// Make TCEmain object:
			$tce = t3lib_div::makeInstance('t3lib_TCEmain');
			$tce->stripslashes_values=0;

			// Put content into the data array:
			$data=array();
			$data[$this->P['table']][$this->P['uid']][$this->P['field']]=t3lib_div::array2xml_cs($this->temp_form, 'Form');

			// Perform the update:
			$tce->start($data,array());
			$tce->process_datamap();

			// If the save/close button was pressed, then redirect the screen:
			if ($_POST['saveandclosedok_x']) {
				t3lib_utility_Http::redirect(t3lib_div::sanitizeLocalUrl($this->P['returnUrl']));
			}
		}
		
		// Neues Feld hinzufügen
		if($_POST['mc_wizard_forms']['new'] && strlen($_POST['mc_wizard_forms']['new']) > 0) {
			$this->temp_form[] = array('type' => $_POST['mc_wizard_forms']['new']);
		}
		
		// Actions
		if($_POST['mc_wizard_forms']['action'] && strlen($_POST['mc_wizard_forms']['action']) > 0) {
			switch($_POST['mc_wizard_forms']['action']) {
				case 'up':
					if(isset($this->temp_form[$_POST['mc_wizard_forms']['id'] - 1])) {
						$tempfield = $this->temp_form[$_POST['mc_wizard_forms']['id'] - 1];
						$this->temp_form[$_POST['mc_wizard_forms']['id'] - 1] = $this->temp_form[$_POST['mc_wizard_forms']['id']];
						$this->temp_form[$_POST['mc_wizard_forms']['id']] = $tempfield;
					}
					break;
				case 'down':
					if(isset($this->temp_form[$_POST['mc_wizard_forms']['id'] + 1])) {
						$tempfield = $this->temp_form[$_POST['mc_wizard_forms']['id'] + 1];
						$this->temp_form[$_POST['mc_wizard_forms']['id'] + 1] = $this->temp_form[$_POST['mc_wizard_forms']['id']];
						$this->temp_form[$_POST['mc_wizard_forms']['id']] = $tempfield;
					}
					break;
				case 'del':
					unset($this->temp_form[$_POST['mc_wizard_forms']['id']]);
					break;
				default:
					break;
			}
		}
	}

	/**
	 * Create the panel of buttons for submitting the form or otherwise perform operations.
	 *
	 * @return array all available buttons as an assoc. array
	 */
	protected function getButtons() {
		$buttons = array();
		
		if ($this->P['table'] && $this->P['field'] && $this->P['uid']) {
			// Close
			$buttons['close'] = '<a href="#" onclick="' . htmlspecialchars('jumpToUrl(unescape(\'' . rawurlencode(t3lib_div::sanitizeLocalUrl($this->P['returnUrl'])) . '\')); return false;') . '">' .
				t3lib_iconWorks::getSpriteIcon('actions-document-close', array('title' => $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.php:rm.closeDoc', TRUE))) . 
		  		'</a>';

			// Save
			$buttons['save'] = '<input type="image" class="c-inputButton" name="savedok"' . t3lib_iconWorks::skinImg($this->doc->backPath, 'gfx/savedok.gif') . ' title="' . $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.php:rm.saveDoc', 1) . '" />';

			// Save & Close
			$buttons['save_close'] = '<input type="image" class="c-inputButton" name="saveandclosedok"' . t3lib_iconWorks::skinImg($this->doc->backPath, 'gfx/saveandclosedok.gif') . ' title="' . $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.php:rm.saveCloseDoc', 1) . '" />';

			// Reload
			$buttons['reload'] = '<input type="image" class="c-inputButton" name="_refresh"' . t3lib_iconWorks::skinImg($this->doc->backPath, 'gfx/refresh_n.gif') . ' title="' . $GLOBALS['LANG']->getLL('forms_refresh', 1) . '" />';
		}

		return $buttons;
	}
	
	function renderContent() {
		$content = '';
		
		// Get Suparts
		$subparts['main'] = t3lib_parsehtml::getSubpart($this->template, '###MAIN_CONT###');
		$subparts['newFieldRow'] = t3lib_parsehtml::getSubpart($this->template, '###NEW_FIELD_ROW###');
		// Render available Field-types to the newfield panel
		foreach($this->av_field_types as $type) {
			$nma['###TYPE###'] = $type;
			$nma['###LABEL###'] = $GLOBALS['LANG']->getLL('formfield.'.$type.'.label');
			
			$newContMa['###FIELDTYPES###'] .= t3lib_parsehtml::substituteMarkerArray($subparts['newFieldRow'], $nma);
		}
		foreach($this->av_layout_types as $type) {
			$nma['###TYPE###'] = $type;
			$nma['###LABEL###'] = $GLOBALS['LANG']->getLL('layoutfield.'.$type.'.label');
			
			$newContMa['###LAYOUTTYPES###'] .= t3lib_parsehtml::substituteMarkerArray($subparts['newFieldRow'], $nma);
		}
		$ma['###NEW_CONT###'] = t3lib_parsehtml::substituteMarkerArray(t3lib_parsehtml::getSubpart($this->template, '###NEW_FIELD###'), $newContMa);
		$subparts['field_cont'] = t3lib_parsehtml::getSubpart($this->template, '###FIELD_CONT###');

		// Render FORM_CONT
		$ma['###FORM_CONT###'] = '';
		foreach($this->temp_form as $id => $formfield) {
			$ffma = array();
			$ffma['###ID###'] = $id;
			$ffma['###TYPE###'] = $formfield['type'];
			$ffma['###TYPE_NAME###'] = $GLOBALS['LANG']->getLL('formfield.'.$formfield['type'].'.label');
			$ffma['###LABEL###'] = $formfield['label'];
			$ffma['###HELP###'] = $formfield['help'];
			$ffma['###CHECKED_REQUIRED_TRUE###'] = (strcmp($formfield['required'],'true')==0?'checked="true"':'');
			$ffma['###CHECKED_REQUIRED_FALSE###'] = (strcmp($formfield['required'],'true')!=0?'checked="true"':'');

			// extendet config for fields
			$ffma['###DEFAULT###'] = $formfield['default'];
			$ffma['###OPTIONS###'] = $formfield['options'];
			$ffma['###ROWS###'] = $formfield['rows'];
			
			$ffma['###FIELDOPTIONS###'] .= t3lib_parsehtml::substituteMarkerArray(t3lib_parsehtml::getSubpart($this->template, '###'.strtoupper($formfield['type']).'_FORM###'), $ffma);
			$ma['###FORM_CONT###'] .= t3lib_parsehtml::substituteMarkerArray($subparts['field_cont'], $ffma);
		}
		
		$content .= t3lib_parsehtml::substituteMarkerArray($subparts['main'], $ma);
		
		return $content;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mc_govcollection/wizard/class.tx_mcgovcollection_wizard_forms.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mc_govcollection/wizard/class.tx_mcgovcollection_wizard_forms.php']);
}

// Make instance:
$SOBE = t3lib_div::makeInstance('tx_mcgovcollection_wizard_forms');
$SOBE->init();
$SOBE->processInput();
$SOBE->main();
?>