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
		
		$this->subparts['start'] = $this->cObj->getSubpart($this->template, '###START###');
		$this->subparts['overview']['row'] = $this->cObj->getSubpart($this->template, '###OVERVIEW_ROW###');
		$this->subparts['overview']['group'] = $this->cObj->getSubpart($this->template, '###GROUP_OVERVIEW###');
		$this->subparts['overview']['area'] = $this->cObj->getSubpart($this->template, '###AREA_OVERVIEW###');
		
		$this->subparts['confirm'] = $this->cObj->getSubpart($this->template, '###CONFIRM_VIEW###');
		$this->subparts['datarow'] = $this->cObj->getSubpart($this->template, '###CONFIRM_DATA_ROW###');
		$this->subparts['mail'] = $this->cObj->getSubpart($this->template, '###MAIL_BODY###');
		$this->subparts['maildatarow'] = $this->cObj->getSubpart($this->template, '###MAIL_DATA_ROW###');
		
		$this->subparts['single'] = $this->cObj->getSubpart($this->template, '###SINGLE_VIEW###');
		$this->subparts['form'] = $this->cObj->getSubpart($this->template, '###FORM_VIEW###');
		
		$this->subparts['img']['link'] = $this->cObj->getSubpart($this->template, '###LINK_IMG###');
		$this->subparts['img']['form'] = $this->cObj->getSubpart($this->template, '###FORM_IMG###');
		$this->subparts['img']['info'] = $this->cObj->getSubpart($this->template, '###INFO_IMG###');
		
		$tempjs = '<script type="text/javascript" src="'.t3lib_extMgm::siteRelPath($this->extKey).'lib/jquery-1.4.3.js"></script>';
		$GLOBALS['TSFE']->additionalHeaderData[$this->prefixId] .= $tempjs;
	}
	
	public function translation_callback($matches) {
		return $this->pi_getLL($matches[1], 'no translation found');
	}
	
	public function renderStart($areas) {
		$content = '';
		
		foreach($areas as $area) {
			$ma['###TITLE###'] = $area->getTitle();
			$ma['###MORE###'] = $this->pi_linkTP($this->pi_getLL('more'), array($this->prefixId.'[uid]' => $area->getUid(), $this->prefixId.'[action]' => 'area'), 1);
			$ma['###DESCRIPTION###'] = strlen($area->getDescription())>0?$this->pi_RTEcssText($area->getDescription()):'&nbsp;';
			
			$groups = array();
			foreach($area->getGroups() as $group) {
				if(count($group->getServices()) > 0) {
					$groups[] .= $this->pi_linkTP($group->getTitle(), array($this->prefixId.'[uid]' => $group->getUid(), $this->prefixId.'[action]' => 'group'), 1);
				}				
			}
			$ma['###GROUPS###'] = implode(' | ', $groups);
			
			if(strlen($ma['###GROUPS###'])>0) {
				$content .= $this->cObj->substituteMarkerArray($this->subparts['start'], $ma);
			}
		}
		
		return $content;
	}
	
	public function renderArea($area, $actgroupuid) {
		$content = '';

		$ma['###AREA_TITLE###'] = $area->getTitle();
		$ma['###BACK###'] = $this->pi_linkTP($this->pi_getLL('backtoOverview'));
		$ma['###GROUPS###'] = '';
		 
		foreach($area->getGroups() as $group) {
			$mag['###TITLE###'] = $area->getTitle().' - '.$group->getTitle();
			$mag['###GROUPUID###'] = $group->getUid();
			if($actgroupuid == $group->getUid()) {
				$mag['###DISPLAY###'] = 'inline';
			} else {
				$mag['###DISPLAY###'] = 'none';
			}
			$mag['###SERVICES###'] = '';
			
			foreach($group->getServices() as $service) {
				$mas['###TITLE###'] = $service->getTitle();
				$mas['###INFO###'] = '&nbsp;';
				if(strlen($service->getDescription())>0) {
					$mas['###INFO###'] = $this->pi_linkTP($this->subparts['img']['info'], array($this->prefixId.'[offerId]' => $service->getUid(), $this->prefixId.'[action]' => 'info'), 1);
				} 
				if(strcmp($service->getType(),'formular')==0) {
					$mas['###FORM###'] = $this->pi_linkTP($this->subparts['img']['form'], array($this->prefixId.'[offerId]' => $service->getUid(), $this->prefixId.'[action]' => 'form'), 1);
					$mas['###LINK###'] = '&nbsp;';
				} else {	
					$mas['###FORM###'] = '&nbsp;';
					$mas['###LINK###'] = strlen($service->getLink())>0?$this->cObj->getTypoLink($this->subparts['img']['link'], $service->getLink(), '', '_blank'):'&nbsp;';
				}
			
				$mag['###SERVICES###'] .= $this->cObj->substituteMarkerArray($this->subparts['overview']['row'], $mas);
			}
			if(count($group->getServices()) > 0) {
				$ma['###GROUPS###'] .= $this->cObj->substituteMarkerArray($this->subparts['overview']['group'], $mag);
			}
		}

		return $this->cObj->substituteMarkerArray($this->subparts['overview']['area'], $ma);
	}
	
	public function renderForm($row, $renderedForm) {
		$ma['###TITLE###'] = $row->getTitle();
		$ma['###PRICE###'] = $row->getPrice();
		$ma['###FORM###'] = $renderedForm;
		$ma['###BACK###'] = $this->pi_linkTP($this->pi_getLL('back'), array($this->prefixId.'[uid]' => $row->getGroupUid(), $this->prefixId.'[action]' => 'group'), 1);
		
		return $this->cObj->substituteMarkerArray($this->subparts['form'], $ma);
	}
	
	public function renderMail($row, $data) {
		$ma['###TITLE###'] = $row->getTitle();
		$ma['###PRICE###'] = $row->getPrice();
		
		$ma['###DATA###'] = '';
		foreach($data as $label => $value) {
			$rma['###LABEL###'] = $label;
			$rma['###VALUE###'] = $value;
			
			$ma['###DATA###'] .= $this->cObj->substituteMarkerArray($this->subparts['maildatarow'], $rma);
		}
		
		return $this->cObj->substituteMarkerArray($this->subparts['mail'], $ma);
	}
	
	public function renderConfirm($service, $data) {
		$ma['###TITLE###'] = $service->getTitle();
		$ma['###PRICE###'] = $service->getPrice();
		$ma['###BACK###'] = $this->pi_linkTP($service->getTitle(), array($this->prefixId.'[uid]' => $service->getGroupUid(), $this->prefixId.'[action]' => 'group'), 1);
		
		$ma['###DATA###'] = '';
		foreach($data as $label => $value) {
			$rma['###LABEL###'] = $label;
			$rma['###DATA###'] = $value;
			
			$ma['###DATA###'] .= $this->cObj->substituteMarkerArray($this->subparts['datarow'], $rma);
		}
		
		return $this->cObj->substituteMarkerArray($this->subparts['confirm'], $ma);
	}
	
	public function renderSingle($service) {
		$ma = array();
		$ma['###TITLE###'] = $service->getTitle();
		$ma['###PRICE###'] = $service->getPrice();
		$ma['###BACK###'] = $this->pi_linkTP($this->pi_getLL('back'), array($this->prefixId.'[uid]' => $service->getGroupUid(), $this->prefixId.'[action]' => 'group'), 1);
		
		//Kontaktinformationen
		if(strcmp($service->getType(),'formular')==0) {
			$ma['###FORM###'] = $this->pi_linkTP($this->subparts['img']['form'], array($this->prefixId.'[offerId]' => $service->getUid(), $this->prefixId.'[action]' => 'form'), 1);
			$ma['###LINK###'] = '<span style="padding-left: 12px">-</span>';
		} else {	
			$ma['###FORM###'] = '<span style="padding-left: 12px">-</span>';
			$ma['###LINK###'] = strlen($service->getLink())>0?$this->cObj->getTypoLink($this->subparts['img']['link'], $service->getLink(), '', '_blank'):'&nbsp;';
		}
		
		// Beschreibung
		$ma['###DESCRIPTION###'] = strlen($service->getDescription())>0?$this->pi_RTEcssText($service->getDescription()):'&nbsp;';
		
		return $this->cObj->substituteMarkerArray($this->subparts['single'], $ma);
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mc_govcollection/fe_plugins/form/class.tx_mcgovcollection_form_renderer.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mc_govcollection/fe_plugins/form/class.tx_mcgovcollection_form_renderer.php']);
}
?>