<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010 Maurus Caflisch <caflisch@kns.ch>
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
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */

require_once(PATH_tslib.'class.tslib_pibase.php');
require_once(t3lib_extMgm::extPath('mc_govcollection').'fe_plugins/contact/class.tx_mcgovcollection_contact_renderer.php');

/**
 * Plugin 'News - GovCol' for the 'mc_govcollection' extension.
 *
 * @author	Maurus Caflisch <caflisch@kns.ch>
 * @package	TYPO3
 * @subpackage	tx_mcgovcollection
 */
class tx_mcgovcollection_contact extends tslib_pibase {
	var $prefixId      = 'tx_mcgovcollection_contact';		// Same as class name
	var $scriptRelPath = 'fe_plugins/contact/class.tx_mcgovcollection_contact.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'mc_govcollection';	// The extension key.
	var $pi_checkCHash = true;
	private $renderer = null;

	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function main($content, $conf) {
		$this->conf = $conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();
		$this->pi_initPIflexForm();
		$this->renderer = new tx_mcgovcollection_contact_renderer(t3lib_extMgm::extPath($this->extKey).'fe_plugins/contact/templates/template.html', $conf, $this->cObj);
		
		$content = '';
		
		if(isset($this->piVars['contactId']) && $this->piVars['contactId'] > 0) {
			$item = $this->getContact($this->piVars['contactId']);
			
			// Einzelansicht
			$content .= $this->renderer->renderSingle($item);
		} else {
			$items = $this->getAllContacts();
			$content .= $this->renderer->renderOverview($items);
		}
		
		return $this->pi_wrapInBaseClass($content);
	}
	
	private function getContact($uid) {
		// Get a Single-Record with the localized description
		$record = $this->pi_getRecord('tx_mcgovcollection_contact', $uid);
		
		$where = '';
		$where .= 'l10n_parent = '.$uid.' ';
		$where .= 'AND sys_language_uid = '.$GLOBALS['TSFE']->sys_language_uid.' ';
		$where .= 'AND (hidden = 0 OR hidden IS NULL) ';
		$where .= 'AND (deleted = 0 OR deleted IS NULL) ';
		
		$langols = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'tx_mcgovcollection_contact', $where);
		if($GLOBALS['TYPO3_DB']->sql_num_rows($langols)==1 && $langol = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($langols)) {
			$record['description'] = $langol['description'];
		}
		
		return $record;
	}
	
	private function getAllContacts() {
		$where = '';
		$where .= 'pid IN ('.$this->pi_getPidList($this->cObj->data['pages'], $this->cObj->data['recursive']).') ';
		$where .= 'AND (hidden = 0 OR hidden IS NULL) ';
		$where .= 'AND (deleted = 0 OR deleted IS NULL) ';
		$where .= 'AND category IN ('.$this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'categorySelection').') ';
		$where .= 'AND sys_language_uid IN (-1, 0) ';
		
		return $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('*', 'tx_mcgovcollection_contact', $where, '', 'name');
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mc_govcollection/fe_plugins/contact/class.tx_mcgovcollection_contact.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mc_govcollection/fe_plugins/contact/class.tx_mcgovcollection_contact.php']);
}
	
?>