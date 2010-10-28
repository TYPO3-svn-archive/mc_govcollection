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
class tx_mcgovcollection_form_renderer extends tslib_pibase { 
	private $url; // url of the requested uri
	private $subparts = array(); // Array of all Subparts of the template
	private $template; // File mit dem Template
	public $extKey = 'mc_govcollection'; // Extension-Key
	public $scriptRelPath = 'fe_plugins/form/class.tx_mcgovcollection_form_renderer.php';
	public $cObj; // content rendering class
	public $prefixId = 'tx_mcgovcollection_form'; // Same as class name	
	
	public function __construct($templatepath, $conf, $cObj) {	
		parent::__construct();

		// instanciate the objects and vars
		$this->conf = $conf;
		$this->url = t3lib_div::getIndpEnv('REQUEST_URI');
		$this->cObj = $cObj;
		$this->template = t3lib_div::getUrl($templatepath);
		$this->pi_loadLL();
		
		// substitute all src-attributes with the right path
		$this->template = preg_replace("/src=\"/", "src=\"".t3lib_extMgm::siteRelPath($this->extKey)."fe_plugins/form/templates/", $this->template);
		
		// Labels durch Werte aus der Locallang.xml-Datei ersetzen
		$this->template = preg_replace_callback("/#LLL#(.*?)#LLL#/", array($this, 'translation_callback'), $this->template);
		
		$this->subparts['overview']['cont'] = $this->cObj->getSubpart($this->template, '###OVERVIEW_CONT###');
		$this->subparts['overview']['row'] = $this->cObj->getSubpart($this->template, '###OVERVIEW_ROW###');
		
		$this->subparts['confirm'] = $this->cObj->getSubpart($this->template, '###CONFIRM_VIEW###');
		$this->subparts['datarow'] = $this->cObj->getSubpart($this->template, '###CONFIRM_DATA_ROW###');
		$this->subparts['mail'] = $this->cObj->getSubpart($this->template, '###MAIL_BODY###');
		$this->subparts['maildatarow'] = $this->cObj->getSubpart($this->template, '###MAIL_DATA_ROW###');
		
		$this->subparts['single'] = $this->cObj->getSubpart($this->template, '###SINGLE_VIEW###');
		$this->subparts['form'] = $this->cObj->getSubpart($this->template, '###FORM_VIEW###');
		
		$this->subparts['img']['link'] = $this->cObj->getSubpart($this->template, '###LINK_IMG###');
		$this->subparts['img']['form'] = $this->cObj->getSubpart($this->template, '###FORM_IMG###');
		$this->subparts['img']['info'] = $this->cObj->getSubpart($this->template, '###INFO_IMG###');
	}
	
	public function translation_callback($matches) {
		return $this->pi_getLL($matches[1], 'no translation found');
	}
	
	public function renderOverview($rows) {
		$content = '';
		
		$content .= $this->subparts['overview']['cont'];
		
		foreach($rows as $row) {
			$ma['###TITLE###'] = $row['title'];
			$ma['###INFO###'] = $this->pi_linkTP($this->subparts['img']['info'], array($this->prefixId.'[offerId]' => $row['uid'], $this->prefixId.'[action]' => 'info'), 1);
			$ma['###PRICE###'] = ($row['price']==0?'-':$row['price']);
			if(strcmp($row['type'],'formular')==0) {
				$ma['###FORM###'] = $this->pi_linkTP($this->subparts['img']['form'], array($this->prefixId.'[offerId]' => $row['uid'], $this->prefixId.'[action]' => 'form'), 1);
				$ma['###LINK###'] = '&nbsp;';
			} else {	
				$ma['###FORM###'] = '&nbsp;';
				$ma['###LINK###'] = strlen($row['link'])>0?$this->cObj->getTypoLink($this->subparts['img']['link'], $row['link'], '', '_blank'):'&nbsp;';
			}
		
			$content .= $this->cObj->substituteMarkerArray($this->subparts['overview']['row'], $ma);
		}
		
		return $content;
	}
	
	public function renderForm($row, $renderedForm) {
		$ma['###TITLE###'] = $row['title'];
		$ma['###PRICE###'] = $row['price'];
		$ma['###FORM###'] = $renderedForm;
		$ma['###BACK###'] = $this->pi_linkToPage('[zurück...]', $GLOBALS['TSFE']->id);
		
		return $this->cObj->substituteMarkerArray($this->subparts['form'], $ma);
	}
	
	public function renderMail($row, $data) {
		$ma['###TITLE###'] = $row['title'];
		$ma['###PRICE###'] = $row['price'];
		
		$ma['###DATA###'] = '';
		foreach($data as $label => $value) {
			$rma['###LABEL###'] = $label;
			$rma['###VALUE###'] = $value;
			
			$ma['###DATA###'] .= $this->cObj->substituteMarkerArray($this->subparts['maildatarow'], $rma);
		}
		
		return $this->cObj->substituteMarkerArray($this->subparts['mail'], $ma);
	}
	
	public function renderConfirm($row, $data) {
		$ma['###TITLE###'] = $row['title'];
		$ma['###PRICE###'] = $row['price'];
		$ma['###BACK###'] = $this->pi_linkToPage($this->pi_getLL('back'), $GLOBALS['TSFE']->id);
		
		$ma['###DATA###'] = '';
		foreach($data as $label => $value) {
			$rma['###LABEL###'] = $label;
			$rma['###DATA###'] = $value;
			
			$ma['###DATA###'] .= $this->cObj->substituteMarkerArray($this->subparts['datarow'], $rma);
		}
		
		return $this->cObj->substituteMarkerArray($this->subparts['confirm'], $ma);
	}
	
	public function renderSingle($row) {
		$ma = array();
		$ma['###TITLE###'] = $row['title'];
		$ma['###PRICE###'] = $row['price'];
		
		//Kontaktinformationen
		if(strcmp($row['type'],'formular')==0) {
			$ma['###FORM###'] = $this->pi_linkTP($this->subparts['img']['form'], array($this->prefixId.'[offerId]' => $row['uid'], $this->prefixId.'[action]' => 'form'), 1);
			$ma['###LINK###'] = '<span style="padding-left: 12px">-</span>';
		} else {	
			$ma['###FORM###'] = '<span style="padding-left: 12px">-</span>';
			$ma['###LINK###'] = strlen($row['link'])>0?$this->cObj->getTypoLink($this->subparts['img']['link'], $row['link'], '', '_blank'):'&nbsp;';
		}
		
		// Beschreibung
		$ma['###DESCRIPTION###'] = strlen($row['description'])>0?$this->pi_RTEcssText($row['description']):'&nbsp;';
		$ma['###BACK###'] = $this->pi_linkToPage($this->pi_getLL('back'), $GLOBALS['TSFE']->id);
		
		return $this->cObj->substituteMarkerArray($this->subparts['single'], $ma);
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mc_govcollection/fe_plugins/form/class.tx_mcgovcollection_form_renderer.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mc_govcollection/fe_plugins/form/class.tx_mcgovcollection_form_renderer.php']);
}
?>