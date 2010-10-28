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
class tx_mcgovcollection_file_renderer extends tslib_pibase { 
	private $url; // url of the requested uri
	private $subparts = array(); // Array of all Subparts of the template
	private $template; // File mit dem Template
	public $extKey = 'mc_govcollection'; // Extension-Key
	public $scriptRelPath = 'fe_plugins/file/class.tx_mcgovcollection_file_renderer.php';
	public $cObj; // content rendering class
	public $prefixId = 'tx_mcgovcollection_file'; // Same as class name	
	
	public function __construct($templatepath, $conf, $cObj) {	
		parent::__construct();

		// instanciate the objects and vars
		$this->conf = $conf;
		$this->url = t3lib_div::getIndpEnv('REQUEST_URI');
		$this->cObj = $cObj;
		$this->template = t3lib_div::getUrl($templatepath);
		$this->pi_loadLL();
		
		// substitute all src-attributes with the right path
		$this->template = preg_replace("/src=\"/", "src=\"".t3lib_extMgm::siteRelPath($this->extKey)."fe_plugins/file/templates/", $this->template);
		
		// Labels durch Werte aus der Locallang.xml-Datei ersetzen
		$this->template = preg_replace_callback("/#LLL#(.*?)#LLL#/", array($this, 'translation_callback'), $this->template);
		
		$this->subparts['overview']['row'] = $this->cObj->getSubpart($this->template, '###OVERVIEW_ROW###');
		$this->subparts['overview']['cat'] = $this->cObj->getSubpart($this->template, '###CAT_ROW###');
		
		$this->subparts['single'] = $this->cObj->getSubpart($this->template, '###SINGLE_VIEW###');
		$this->subparts['form'] = $this->cObj->getSubpart($this->template, '###FORM_VIEW###');
		
		$this->subparts['img']['info'] = $this->cObj->getSubpart($this->template, '###INFO_IMG###');
		$this->subparts['img']['down'] = $this->cObj->getSubpart($this->template, '###DOWN_IMG###');
	}
	
	public function translation_callback($matches) {
		return $this->pi_getLL($matches[1], 'no translation found');
	}
	
	public function renderOverview($rows) {
		$content = '';
		
		foreach($rows as $row) {
			$content .= $this->rendRow($row);
		}
		
		return $content;
	}
	
	public function renderCatOverview($cats, $items) {
		$content = '';
		
		foreach($cats as $cat) {
			$ma['###NAME###'] = $cat['name'];
			
			$catfiles = '';
			foreach($items as $item) {
				if($item['category']==$cat['uid']) {
					$catfiles .= $this->rendRow($item);
				}
			}
			if(strlen($catfiles)>0) {
				$content .= $this->cObj->substituteMarkerArray($this->subparts['overview']['cat'], $ma);
				$content .= $catfiles;
			}
		}
		
		return $content;
	}
	
	private function rendRow($row) {
		$ma['###TITLE###'] = $this->cObj->getTypoLink($row['title'], 'uploads/mc_govcollection_file/'.$row['file'], '', '_blank');
		if(preg_match('/\\.(.*)$/', $row['file'], $matches) && file_exists('typo3/gfx/fileicons/'.$matches[1].'.gif')) {
			$ma['###SRC###'] = 'src="typo3/gfx/fileicons/'.$matches[1].'.gif"';
		} else {
			$ma['###SRC###'] = 'src="typo3/gfx/fileicons/default.gif"';
		}
		$ma['###ICONS###'] = $this->pi_linkTP($this->subparts['img']['info'], array($this->prefixId.'[fileId]' => $row['uid']), 1);
		$ma['###ICONS###'] .= $this->cObj->getTypoLink($this->subparts['img']['down'], 'uploads/mc_govcollection_file/'.$row['file'], '', '_blank');
	
		return $this->cObj->substituteMarkerArray($this->subparts['overview']['row'], $ma);
	}
	
	public function renderSingle($row) {
		$ma = array();
		$ma['###TITLE###'] = $row['title'];
		$ma['###FILELINK###'] = $this->cObj->getTypoLink($row['title'], 'uploads/mc_govcollection_file/'.$row['file'], '', '_blank');
		if(preg_match('/\\.(.*)$/', $row['file'], $matches) && file_exists('typo3/gfx/fileicons/'.$matches[1].'.gif')) {
			$ma['###SRC###'] = 'src="typo3/gfx/fileicons/'.$matches[1].'.gif"';
		} else {
			$ma['###SRC###'] = 'src="typo3/gfx/fileicons/default.gif"';
		}
		
		// Beschreibung
		$ma['###DESCRIPTION###'] = strlen($row['description'])>0?$this->pi_RTEcssText($row['description']):'&nbsp;';
		$ma['###BACK###'] = $this->pi_linkToPage($this->pi_getLL('back'), $GLOBALS['TSFE']->id);
		
		return $this->cObj->substituteMarkerArray($this->subparts['single'], $ma);
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mc_govcollection/fe_plugins/file/class.tx_mcgovcollection_file_renderer.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mc_govcollection/fe_plugins/file/class.tx_mcgovcollection_file_renderer.php']);
}