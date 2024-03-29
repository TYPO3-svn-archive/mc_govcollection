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
require_once(t3lib_extMgm::extPath('mc_govcollection').'fe_plugins/form/class.tx_mcgovcollection_form_renderer.php');
require_once(t3lib_extMgm::extPath('mc_govcollection').'fe_plugins/form/class.tx_mcgovcollection_form_repository.php');
require_once(t3lib_extMgm::extPath('mc_govcollection').'wizard/class.tx_mcgovcollection_forms.php');

/**
 * Plugin 'News - GovCol' for the 'mc_govcollection' extension.
 *
 * @author	Maurus Caflisch <caflisch@kns.ch>
 * @package	TYPO3
 * @subpackage	tx_mcgovcollection
 */
class tx_mcgovcollection_form extends tslib_pibase {
	var $prefixId      = 'tx_mcgovcollection_form';		// Same as class name
	var $scriptRelPath = 'fe_plugins/form/class.tx_mcgovcollection_form.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'mc_govcollection';	// The extension key.
	var $pi_checkCHash = true;
	private $renderer = null;
	private $repository = null;

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
		$this->renderer = new tx_mcgovcollection_form_renderer(t3lib_extMgm::extPath($this->extKey).'fe_plugins/form/templates/template.html', $conf, $this->cObj);
		$this->repository = new tx_mcgovcollection_form_repository($this->cObj);
		$this->pi_initPIflexForm();
		
		$content = '';
		if(isset($this->piVars['action'])) {
			switch($this->piVars['action']) {
				case 'area':
					$area = $this->repository->getArea($this->piVars['uid']);
					
					$content .= $this->renderer->renderArea($area, 0);
					break;
				case 'group':
					$area = $this->repository->getAreaByGroup($this->piVars['uid']);
					
					$content .= $this->renderer->renderArea($area, $this->piVars['uid']);
					break;
				case 'form':
					$service = $this->repository->getOffer($this->piVars['offerId']);
					
					// Formular-Verarbeitung
					$formsrend = new tx_mcgovcollection_forms('tx_mcgovcollection_form', $service->getUid(), 'formconfig', t3lib_div::getIndpEnv('REQUEST_URI'));
					
					if($formsrend->dataAvailable()) {
						// Save Data in DB
						$inserta['crdate'] = time();
						$inserta['formdata'] = $formsrend->getDataXML();
						$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mcgovcollection_form_log', $inserta);

						// Send a Mail with the data
						$this->sendMail($item, $formsrend->getDataArray());

						// Ausgabe der ‹bersichtsseite
						$content .= $this->renderer->renderConfirm($service, $formsrend->getDataArray());
					} else {
						$content .= $this->renderer->renderForm($service, $formsrend->getForm());
					}
					break;
				case 'info':
					$item = $this->repository->getOffer($this->piVars['offerId']);
					$content .= $this->renderer->renderSingle($item);
					break;
				default:
					break;	
			}
		} else {
			$items = $this->repository->getAllAreas();
			$content .= $this->renderer->renderStart($items);
		}
		
		return $this->pi_wrapInBaseClass($content);
	}
	
	private function sendMail($item, $dataArray) {
		$mailer = new t3lib_htmlmail();
		$mailer->start();
		
		$mailer->recipient = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'deliverEmail');
		$mailer->from_email = 'mailer@localhost.ch';
		$mailer->from_name = 'Datamailer www.trin.ch';
		$mailer->subject = $this->pi_getLL('mail.subject').' '.$item['title'];
		$mailer->returnPath = 'caflisch@trin.ch';
		
		$mailer->theParts['html']['content'] = $mailer->encodeMsg($this->renderer->renderMail($item, $dataArray));
		$mailer->addPlain('Inhalt ohne HTML');
		
		$mailer->setHeaders();
		$mailer->setContent();
		$mailer->sendTheMail();
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mc_govcollection/fe_plugins/form/class.tx_mcgovcollection_form.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mc_govcollection/fe_plugins/form/class.tx_mcgovcollection_form.php']);
}
	
?>