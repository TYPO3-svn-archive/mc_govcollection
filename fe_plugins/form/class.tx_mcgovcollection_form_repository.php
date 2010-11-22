<?php
require_once(t3lib_extMgm::extPath('mc_govcollection').'fe_plugins/form/model/class.tx_mcgovcollection_topic_area.php');
require_once(t3lib_extMgm::extPath('mc_govcollection').'fe_plugins/form/model/class.tx_mcgovcollection_topic_group.php');
require_once(t3lib_extMgm::extPath('mc_govcollection').'fe_plugins/form/model/class.tx_mcgovcollection_service.php');

class tx_mcgovcollection_form_repository extends tslib_pibase {

	public function __construct($cObj) {
		$this->cObj = $cObj;
	}
	
	public function getAllAreas() {
		$where = '';
		$where .= 'pid IN ('.$this->pi_getPidList($this->cObj->data['pages'], $this->cObj->data['recursive']).') ';
		$where .= 'AND (hidden = 0 OR hidden IS NULL) AND (deleted = 0 OR deleted IS NULL) ';
		$where .= 'AND sys_language_uid IN (-1, 0) ';
		
		$rows = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'tx_mcgovcollection_topic_area', $where, '', 'title');
		$result = array();
		while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($rows)) {
			$result[] = $this->getArea($row['uid']);
		}
		
		return $result;
	}
	
	public function getAreaByGroup($groupid) {
		$group = $this->pi_getRecord('tx_mcgovcollection_topic_group', $groupid);
		
		return $this->getArea($group['area']);
	}
	
	public function getArea($uid) {
		// Get a Single-Record with the localized description
		$record = $this->pi_getRecord('tx_mcgovcollection_topic_area', $uid);
		
		// Localization
		$where = '';
		$where .= 'l10n_parent = '.$uid.' ';
		$where .= 'AND sys_language_uid = '.$GLOBALS['TSFE']->sys_language_uid.' ';
		$where .= 'AND (hidden = 0 OR hidden IS NULL) AND (deleted = 0 OR deleted IS NULL) ';
		
		$langols = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'tx_mcgovcollection_topic_area', $where);
		if($GLOBALS['TYPO3_DB']->sql_num_rows($langols)==1 && $langol = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($langols)) {
			$record['title'] = $langol['title'];
			$record['description'] = $langol['description'];
		}
		
		// Create Object
		$res = new tx_mcgovcollection_topic_area($uid, $record['title'], $record['description']);
		
		// Add Groups to Area
		$where = '';
		$where .= 'pid IN ('.$this->pi_getPidList($this->cObj->data['pages'], $this->cObj->data['recursive']).') ';
		$where .= 'AND (area = '.$uid.') ';
		$where .= 'AND (hidden = 0 OR hidden IS NULL) ';
		$where .= 'AND (deleted = 0 OR deleted IS NULL) ';
		$where .= 'AND sys_language_uid IN (-1, 0) ';
		
		$rows = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'tx_mcgovcollection_topic_group', $where, '', 'title');
		$result = array();
		while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($rows)) {
			$res->addGroup($this->getGroup($row['uid']));
		}
		
		// Return Area-Object
		return $res;
	}
	
	private function getGroup($uid) {
		// Get a Single-Record with the localized description
		$record = $this->pi_getRecord('tx_mcgovcollection_topic_group', $uid);
		
		$where = '';
		$where .= 'l10n_parent = '.$uid.' ';
		$where .= 'AND sys_language_uid = '.$GLOBALS['TSFE']->sys_language_uid.' ';
		$where .= 'AND (hidden = 0 OR hidden IS NULL) ';
		$where .= 'AND (deleted = 0 OR deleted IS NULL) ';
		
		$langols = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'tx_mcgovcollection_topic_group', $where);
		if($GLOBALS['TYPO3_DB']->sql_num_rows($langols)==1 && $langol = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($langols)) {
			$record['title'] = $langol['title'];
			$record['description'] = $langol['description'];
		}
		
		$res = new tx_mcgovcollection_topic_group($uid, $record['title'], $record['description']);
		
		// Get Services for this group
		$where = '';
		$where .= 'pid IN ('.$this->pi_getPidList($this->cObj->data['pages'], $this->cObj->data['recursive']).') ';
		$where .= 'AND (hidden = 0 OR hidden IS NULL) AND (deleted = 0 OR deleted IS NULL) ';
		$where .= 'AND (topic_group = '.$uid.') ';
		$where .= 'AND sys_language_uid IN (-1, 0) ';
		
		$rows = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'tx_mcgovcollection_form', $where, '', 'title');
		$result = array();
		while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($rows)) {
			$res->addService($this->getOffer($row['uid']));
		}
		
		return $res;
	}
	
	public function getOffer($uid) {
		// Get a Single-Record with the localized description
		$record = $this->pi_getRecord('tx_mcgovcollection_form', $uid);
		
		$where = '';
		$where .= 'l10n_parent = '.$uid.' ';
		$where .= 'AND sys_language_uid = '.$GLOBALS['TSFE']->sys_language_uid.' ';
		$where .= 'AND (hidden = 0 OR hidden IS NULL) ';
		$where .= 'AND (deleted = 0 OR deleted IS NULL) ';
		
		$langols = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'tx_mcgovcollection_form', $where);
		if($GLOBALS['TYPO3_DB']->sql_num_rows($langols)==1 && $langol = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($langols)) {
			$record['title'] = $langol['title'];
			$record['formconfig'] = $langol['formconfig'];
			$record['description'] = $langol['description'];
			$record['link'] = $langol['link'];
		}
		
		return new tx_mcgovcollection_service($uid, $record['title'], $record['description'], $record['type'], $record['link'], $record['price'], $record['topic_group']);
	}
}