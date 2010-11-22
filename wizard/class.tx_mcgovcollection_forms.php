<?php
class tx_mcgovcollection_forms extends tslib_pibase {
	private $field;
	private $url; // url for the action-attribute
	private $row; // table-row for this form
	private $subparts = array(); // Array of all Subparts of the template
	private $template; // File mit dem Template
	private $content; // Rendered Content
	public $extKey = 'mc_govcollection'; // Extension-Key
	public $scriptRelPath = 'wizard/class.tx_mcgovcollection_forms.php';
	public $prefixId = 'mc_govcollection_forms';
	private $av_field_types = array('text', 'select', 'textarea', 'checkbox', 'radio', 'email', 'date');
	private $av_layout_types = array('grouptitle', 'spacer');
	private $formconfig;
	
	function __construct($table, $uid, $field, $url) {
		parent::__construct();
		
		$this->template = t3lib_div::getUrl(t3lib_extMgm::extPath($this->extKey).'wizard/template/mc_renderer_forms.html');
		$this->url = $url;
		$this->field = $field;
		$this->row = $this->pi_getRecord($table, $uid);
		$this->pi_loadLL();
		
		// Labels durch Werte aus der Locallang.xml-Datei ersetzen
		$this->template = preg_replace_callback("/#LLL#(.*?)#LLL#/", array($this, 'translation_callback'), $this->template);
		
		$this->subparts['form'] = t3lib_parsehtml::getSubpart($this->template, '###FORM_VIEW###');
		$this->subparts['formfield'] = t3lib_parsehtml::getSubpart($this->template, '###FORM_FIELD###');
		foreach($this->av_field_types as $type) {
			$this->subparts['field'][$type] = t3lib_parsehtml::getSubpart($this->template, '###'.strtoupper($type).'_FIELD###');
		}
		foreach($this->av_layout_types as $type) {
			$this->subparts['field'][$type] = t3lib_parsehtml::getSubpart($this->template, '###'.strtoupper($type).'_FIELD###');
		}		
		
		$tempcss = '<link rel="stylesheet" type="text/css" href="'.t3lib_extMgm::siteRelPath($this->extKey).'wizard/template/format.css" media="all" />';
		$GLOBALS['TSFE']->additionalHeaderData[$this->prefixId] .= $tempcss;

		$tempjs = '<script type="text/javascript" src="'.t3lib_extMgm::siteRelPath($this->extKey).'lib/jquery-1.4.3.js"></script>';
		$tempjs .= '<script type="text/javascript" src="'.t3lib_extMgm::siteRelPath($this->extKey).'lib/jquery.validate.js"></script>';
		$tempjs .= '<script type="text/javascript" src="'.t3lib_extMgm::siteRelPath($this->extKey).'lib/messages_de.js"></script>';
		$tempjs .= '<script type="text/javascript" src="'.t3lib_extMgm::siteRelPath($this->extKey).'lib/methods_de.js"></script>';
		$GLOBALS['TSFE']->additionalHeaderData[$this->prefixId] .= $tempjs;
		
		// Get the Config for the Form
		$this->formconfig = t3lib_div::xml2array($this->row[$this->field]);
		
		if(isset($this->piVars['form'][$uid])) {
			$data = $this->piVars['form'][$uid];
			
			// Merge the data with the actual Form config
			foreach($data as $id => $value) {
				$this->formconfig[$id]['data'] = $value;
			}
		}
	}
	
	/**
	 * Helper-Functino for translation purposes
	 * @param unknown_type $matches
	 */
	private function translation_callback($matches) {
		return $this->pi_getLL($matches[1], 'no translation found');
	}
	
	public function dataAvailable() {
		return isset($this->piVars['form'][$this->row['uid']]);
	}
	
	public function getDataArray() {
		$res = array();
		foreach($this->formconfig as $field) {
			// TODO readable Data for select, radio, checkbox
			switch($field['type']) {
				case select: case radio:
					$options = explode(LF, $field['options']);
					$res[$field['label']] = $options[$field['data']];
					break;
				case checkbox:
					$options = explode(LF, $field['options']);
					foreach($field['data'] as $id => $value) {
						$field['data'][$id] = $options[$id];
					}
					$res[$field['label']] = implode(', ', $field['data']);
					break;
				default:
					$res[$field['label']] = $field['data'];
					break;
			}
		}
		return $res;
	}
	
	public function getDataXML() {
		return t3lib_div::array2xml_cs($this->formconfig, 'Form');
	}
	
	private function renderForm() {
		$ma['###URL###'] = $this->url.'&no_cache=1';

		$ma['###FORM###'] = '';
		foreach($this->formconfig as $id => $field) {
			$ffma = array();
			
			switch($field['type']) {
				case 'text': 
				case 'email':
				case 'date':
					$ffma['###FIELD_SPEZ###'] = '<input type="text" name="###NAME###" value="###VALUE###" />';
					break;
				case 'select':
					$select = '';
					$select .= '<select name="###NAME###">';
					foreach(explode(LF, $field['options']) as $selectid => $option) {
						if(isset($this->piVars['form'][$this->row['uid']][$id]) && strcmp($this->piVars['form'][$this->row['uid']][$id],$option)==0) {
							$select .= '<option value="'.$selectid.'" selected="selected">'.$option.'</option>';
						} else {
							$select .= '<option value="'.$selectid.'">'.$option.'</option>';
						}
					}
					$select .= '</select>';
					$ffma['###FIELD_SPEZ###'] = $select;
					break;
				case 'textarea':
					$ffma['###FIELD_SPEZ###'] = '<textarea type="text" name="###NAME###" rows="'.$field['rows'].'">###VALUE###</textarea>';
					break;
				case 'checkbox':
					$checkbox = '';
					foreach(explode(LF, $field['options']) as $checkid => $option) {
						$checkbox .= '<div style="clear:both;"><input type="checkbox" '.(isset($this->piVars['form'][$this->row['uid']][$id][$checkid])?'checked="true" ':'').'name="###NAME###['.$checkid.']" /> '.$option.'</div>';
					}
					$ffma['###FIELD_SPEZ###'] = $checkbox;
					break;
				case 'radio':
					$radio = '';
					foreach(explode(LF, $field['options']) as $radioid => $option) {
						$radio .= '<div style="clear:both;"><input type="radio" '.($this->piVars['form'][$this->row['uid']][$id]==$radioid?'checked="true" ':'').'name="###NAME###" value="'.$radioid.'"/> '.$option.'</div>';
					}
					$ffma['###FIELD_SPEZ###'] = $radio;
					break;
				default:
					$ffma['###FIELD_SPEZ###'] = '';
					break;
			}

			// Standard-Values
			$ffma['###NAME###'] = $this->prefixId.'[form]['.$this->row['uid'].']['.$id.']';
			$ffma['###LABEL###'] = $field['label'];
			$ffma['###REQUIRED###'] = strcmp($field['required'],'true')==0?'*':'';
			$ffma['###VALUE###'] = $field['default'];
			$ffma['###QUICKHELP###'] = '';
			if(isset($this->piVars['form'][$this->row['uid']][$id])) {
				$ffma['###VALUE###'] = $this->piVars['form'][$this->row['uid']][$id];
			}

			if(strlen($this->subparts['field'][$field['type']]) > 0) {
				$ma['###FORM###'] .= t3lib_parsehtml::substituteMarkerArray($this->subparts['field'][$field['type']], $ffma);
			} else {
				$ma['###FORM###'] .= t3lib_parsehtml::substituteMarkerArray($this->subparts['formfield'], $ffma);
			}
		}
		
		$this->content = t3lib_parsehtml::substituteMarkerArray($this->subparts['form'], $ma);
		
	}
	
	private function genJsScript() {
		$validationrules = array();
		foreach($this->formconfig as $id => $field) {
			$fieldrules = array();
			
			switch($field['type']) {
				case 'date':
					$fieldrules[] = 'date';
					break;
				case 'email':
					$fieldrules[] = 'email';
					break;
				default:
					break;
			}

			// Standard-Values
			$name = $this->prefixId.'[form]['.$this->row['uid'].']['.$id.']';
			
			// Rules for validation
			if(strcmp($field['required'],'true')==0) {
				$validationrules['rules'][$name][] = 'required';
			}
			foreach($fieldrules as $rule) {
				$validationrules['rules'][$name][] = $rule;
			}
			$validationrules['message'][$name] = $field['help'];
		}

		$tempjs = '<script type="text/javascript">$(document).ready(function(){
					    $("#mcrenderforms_form").validate({					    	rules: {';
		foreach($validationrules['rules'] as $name => $rule) {
			$tempjs .= '"'.$name.'": {'.implode(':true,',$rule).':true},';
		}					    	
		$tempjs .=     	'},
					errorClass : "mcrenderforms_field_error",
					validClass : "mcrenderforms_field_valid",
					messages: {';
		foreach($validationrules['message'] as $name => $message) {
			if(strlen($message)>0) {
				$tempjs .= '"'.$name.'": {';
				foreach($validationrules['rules'][$name] as $rule) {
					$tempjs .= $rule.':"'.$message.'",';
				}
				$tempjs .= '},';
			}
		}
		$tempjs .= '}
					    });
					  });
				  </script>';
		$GLOBALS['TSFE']->additionalHeaderData[$this->prefixId] .= $tempjs;
	}
	
	function getForm() {
		$this->renderForm();
		$this->genJsScript();
		
		return $this->content;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mc_govcollection/wizard/class.tx_mcgovcollection_forms.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mc_govcollection/wizard/class.tx_mcgovcollection_forms.php']);
}
?>