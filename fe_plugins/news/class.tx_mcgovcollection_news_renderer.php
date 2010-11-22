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
class tx_mcgovcollection_news_renderer extends tslib_pibase { 
	private $url; // url of the requested uri
	private $subparts = array(); // Array of all Subparts of the template
	private $template; // File mit dem Template
	public $extKey = 'mc_govcollection'; // Extension-Key
	public $scriptRelPath = 'fe_plugins/news/class.tx_mcgovcollection_news_renderer.php';
	public $cObj; // content rendering class
	public $prefixId = 'tx_mcgovcollection_news'; // Same as class name	
	
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
		$this->template = preg_replace("/src=\"/", "src=\"".t3lib_extMgm::siteRelPath($this->extKey)."fe_plugins/news/templates/", $this->template);
		
		// Labels durch Werte aus der Locallang.xml-Datei ersetzen
		$this->template = preg_replace_callback("/#LLL#(.*?)#LLL#/", array($this, 'translation_callback'), $this->template);
		
		$this->subparts['overview']['row'] = $this->cObj->getSubpart($this->template, '###OVERVIEW_ROW###');
		$this->subparts['overview']['paging'] = $this->cObj->getSubpart($this->template, '###PAGE_NAVIGATION###');
		
		$this->subparts['single'] = $this->cObj->getSubpart($this->template, '###SINGLE_VIEW###');
		
		$this->subparts['feeds']['rss2'] = $this->cObj->getSubpart($this->template, '###TEMPLATE_RSS2###');
		$this->subparts['feeds']['rss2_item'] = $this->cObj->getSubpart($this->template, '###RSS2_ITEM###');
		
		$tempcss = '<link rel="stylesheet" type="text/css" href="'.t3lib_extMgm::siteRelPath($this->extKey).'fe_plugins/news/templates/format.css" media="all" />';
		$GLOBALS['TSFE']->additionalHeaderData[$this->prefixId] .= $tempcss;
	}
	
	public function translation_callback($matches) {
		return $this->pi_getLL($matches[1], 'no translation found');
	}
	
	public function renderRow($row) {
		$ma = array();
		$ma['###DATE###'] = date('d.m.Y', $row['date']);
		$ma['###TITLE###'] = $row['title'];
		$ma['###MORE###'] = $this->pi_linkTP($this->pi_getLL('more'), array($this->prefixId.'[eventId]' => $row['uid']), 1);

		// Text ist Subtitle or a cropped part of bodytext
		if(strlen($row['subtitle']) > 0) {
			$ma['###SUBTITLE###'] = $row['subtitle'];
		} else {
			$ma['###SUBTITLE###'] = $this->cObj->stdWrap($row['bodytext'], array('stripHtml' => 1, 'crop' => $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'crop_length').' | ... | true'));
		}
		
		return $this->cObj->substituteMarkerArray($this->subparts['overview']['row'], $ma);
	}
	
	public function renderPaging($start, $end, $total) {
		$itemcountperpage = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'item_per_page');
		$content = '';
		
		$ma['###START###'] = $start;
		$ma['###END###'] = $end;
		$ma['###TOTAL###'] = $total;
		$ma['###ARROWS###'] = '';
		
		if($start > 1) {
			$ma['###ARROWS###'] .= $this->pi_linkTP('<div class="paging_arr" title="'.$this->pi_getLL('paging.tostart').'">&nbsp;</div>', array($this->prefixId.'[paging]' => 0), 1);
			$ma['###ARROWS###'] .= $this->pi_linkTP('<div class="paging_arr" title="'.$this->pi_getLL('paging.previous').'" style="background-position: 0px -16px;">&nbsp;</div>', array($this->prefixId.'[paging]' => ($start-$itemcountperpage-1<0?0:$start-$itemcountperpage-1)), 1);
		} else {
			$ma['###ARROWS###'] .= '<div class="paging_arr" style="background-position: -16px 0px;">&nbsp;</div>';
			$ma['###ARROWS###'] .= '<div class="paging_arr" style="background-position: -16px -16px;">&nbsp;</div>';
		}
		if($end < $total) {
			$ma['###ARROWS###'] .= $this->pi_linkTP('<div class="paging_arr" title="'.$this->pi_getLL('paging.next').'" style="background-position: 0px -32px;">&nbsp;</div>', array($this->prefixId.'[paging]' => $start + $itemcountperpage-1), 1);
			$ma['###ARROWS###'] .= $this->pi_linkTP('<div class="paging_arr" title="'.$this->pi_getLL('paging.toend').'" style="background-position: 0px -48px;">&nbsp;</div>', array($this->prefixId.'[paging]' => ($total-$itemcountperpage<0?0:$total-$itemcountperpage)), 1);
		} else {
			$ma['###ARROWS###'] .= '<div class="paging_arr" style="background-position: -16px -32px;">&nbsp;</div>';
			$ma['###ARROWS###'] .= '<div class="paging_arr" style="background-position: -16px -48px;">&nbsp;</div>';
		}
		
		return $this->cObj->substituteMarkerArray($this->subparts['overview']['paging'], $ma);
	}
	
	public function renderSingle($row) {
		$ma = array();
		$ma['###DATE###'] = date('d.m.Y', $row['date']);
		$ma['###TITLE###'] = $row['title'];
		$ma['###SUBTITLE###'] = $row['subtitle'];
		$ma['###TEXT###'] = $this->pi_RTEcssText($row['bodytext']);
		$ma['###BACK###'] = $this->pi_linkToPage($this->pi_getLL('back'), $GLOBALS['TSFE']->id);
		
		return $this->cObj->substituteMarkerArray($this->subparts['single'], $ma);
	}
	
	public function renderRss2Feed($rows) {
		$charset = ($GLOBALS['TSFE']->metaCharset ? $GLOBALS['TSFE']->metaCharset : 'iso-8859-1');
		if ($lConf['xmlDeclaration']) {
			$ma['###XML_DECLARATION###'] = trim($lConf['xmlDeclaration']);
		} else {
			$ma['###XML_DECLARATION###'] = '<?xml version="1.0" encoding="' . $charset . '"?>';
		}
		$ma['###SITE_TITLE###'] = $GLOBALS['TSFE']->TYPO3_CONF_VARS['SYS']['sitename'].' - '.$GLOBALS['TSFE']->page['title'];
		$ma['###SITE_LINK###'] = t3lib_div::getIndpEnv('TYPO3_SITE_URL');
		
		$ma['###CONTENT###'] = '';
		foreach($rows as $row) {
			$rma['###TITLE###'] = $row['title'];
			$rma['###SUBHEADER###'] = $row['subtitle'];
			$rma['###CONTENT###'] = $row['bodytext'];
			$rma['###NEWS_DATE###'] = date('d.m.Y', $row['date']);
			$rma['###LINK###'] = t3lib_div::getIndpEnv('TYPO3_SITE_URL').$this->cObj->typoLink_URL(array('parameter' => $GLOBALS['TSFE']->id, 'additionalParams' => '&'.$this->prefixId.'[eventId]='.$row['uid']));
			
			$ma['###CONTENT###'] .= $this->cObj->substituteMarkerArray($this->subparts['feeds']['rss2_item'], $rma);
		}
		
		return $this->cObj->substituteMarkerArray($this->subparts['feeds']['rss2'], $ma);
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mc_govcollection/fe_plugins/news/class.tx_mcgovcollection_news_renderer.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mc_govcollection/fe_plugins/news/class.tx_mcgovcollection_news_renderer.php']);
}