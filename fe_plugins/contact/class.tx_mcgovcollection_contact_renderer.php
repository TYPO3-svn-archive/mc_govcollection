<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2005-2006  Maurus Caflisch (maurus.caflisch@fh-htwchur.ch)
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
 * Public API for TemplaVoila
 *
 * $Id$
 *
 * @author     Maurus Caflisch <maurus.caflisch@fh-htwchur.ch>
 */
class tx_mcgovcollection_contact_renderer extends tslib_pibase { 
	private $url; // url of the requested uri
	private $subparts = array(); // Array of all Subparts of the template
	private $template; // File mit dem Template
	public $extKey = 'mc_govcollection'; // Extension-Key
	public $scriptRelPath = 'fe_plugins/contact/class.tx_mcgovcollection_contact_renderer.php';
	public $cObj; // content rendering class
	public $prefixId = 'tx_mcgovcollection_contact'; // Same as class name	
	
	public function __construct($templatepath, $conf, $cObj) {	
		parent::__construct();

		// instanciate the objects and vars
		$this->conf = $conf;
		$this->url = t3lib_div::getIndpEnv('REQUEST_URI');
		$this->cObj = $cObj;
		$this->template = t3lib_div::getUrl($templatepath);
		$this->pi_loadLL();
		$this->pi_initPIflexForm();
		
		// substitute all src-attributes with the right path
		$this->template = preg_replace("/src=\"/", "src=\"".t3lib_extMgm::siteRelPath($this->extKey)."fe_plugins/contact/templates/", $this->template);
		
		// Labels durch Werte aus der Locallang.xml-Datei ersetzen
		$this->template = preg_replace_callback("/#LLL#(.*?)#LLL#/", array($this, 'translation_callback'), $this->template);
		
		$this->subparts['overview']['cont'] = $this->cObj->getSubpart($this->template, '###OVERVIEW_CONT###');
		$this->subparts['overview']['row'] = $this->cObj->getSubpart($this->template, '###OVERVIEW_ROW###');
		
		$this->subparts['img']['info'] = $this->cObj->getSubpart($this->template, '###INFO_IMG###');
		$this->subparts['img']['mail'] = $this->cObj->getSubpart($this->template, '###MAIL_IMG###');

		$this->subparts['single'] = $this->cObj->getSubpart($this->template, '###SINGLE_VIEW###');
	}
	
	public function translation_callback($matches) {
		return $this->pi_getLL($matches[1], 'no translation found');
	}
	
	public function renderOverview($rows) {
		$content = '';
		
		$content .= $this->subparts['overview']['cont'];
		
		foreach($rows as $row) {
			$ma['###NAME###'] = $row['name'];
			
			$ma['###INFO###'] = $this->pi_linkTP($this->subparts['img']['info'], array($this->prefixId.'[contactId]' => $row['uid']), 1);
			$ma['###EMAIL###'] = strlen($row['email'])>0?$this->cObj->getTypoLink($this->subparts['img']['mail'], $row['email']):'&nbsp;';
			
			$ma['###WEB###'] = strlen($row['web'])>0?$this->cObj->getTypoLink($row['web'], $row['web'], '', '_blank'):'&nbsp;';	
		
			$content .= $this->cObj->substituteMarkerArray($this->subparts['overview']['row'], $ma);
		}
		
		return $content;
	}
	
	public function renderSingle($row) {
		$ma = array();
		$ma['###NAME###'] = $row['name'];
		$ma['###WEB###'] = strlen($row['web'])>0?$this->cObj->getTypoLink($row['web'], $row['web'], '', '_blank'):'-';
		
		
		//Kontaktinformationen
		$ma['###CONTACT###'] = '';
		$ma['###CONTACT###'] .= strlen($row['contactname'])>0?'<p class="bodytext">'.$row['contactname'].'</p>':'';
		
		$ma['###CONTACT###'] .= '<p class="bodytext">';
		$ma['###CONTACT###'] .= strlen($row['email'])>0?(strlen($row['email'])>0?$this->cObj->getTypoLink($row['email'], $row['email']):'&nbsp;').'<br />':'';
		$ma['###CONTACT###'] .= strlen($row['tel'])>0?$this->pi_getLL("label.tel").' '.$row['tel'].'<br />':'';
		$ma['###CONTACT###'] .= strlen($row['fax'])>0?$this->pi_getLL("label.fax").' '.$row['fax'].'<br />':'';
		$ma['###CONTACT###'] .= '</p>';
		
		$ma['###CONTACT###'] .= '<p class="bodytext">';
		$ma['###CONTACT###'] .= strlen($row['adress'])>0?$row['adress'].'<br />':'';
		$ma['###CONTACT###'] .= strlen($row['city'])>0?$row['city'].'<br />':'';
		$ma['###CONTACT###'] .= '</p>';
		
		// Beschreibung
		$ma['###DESCRIPTION###'] = strlen($row['description'])>0?$this->pi_RTEcssText($row['description']):'&nbsp;';
		$ma['###BACK###'] = $this->pi_linkToPage($this->pi_getLL('back'), $GLOBALS['TSFE']->id);
		
		return $this->cObj->substituteMarkerArray($this->subparts['single'], $ma);
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mc_govcollection/fe_plugins/contact/class.tx_mcgovcollection_contact_renderer.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mc_govcollection/fe_plugins/contact/class.tx_mcgovcollection_contact_renderer.php']);
}
?>