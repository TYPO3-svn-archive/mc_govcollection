<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

t3lib_extMgm::addUserTSConfig('
	options.saveDocNew.tx_mcgovcollection_news=1
	options.saveDocNew.tx_mcgovcollection_contact=1
	options.saveDocNew.tx_mcgovcollection_contact=1
');

t3lib_extMgm::addPageTSConfig('
	RTE.config.tx_mcgovcollection_news.bodytext {
		hidePStyleItems = H1, H4, H5, H6
		proc.exitHTMLparser_db=1
		proc.exitHTMLparser_db {
			keepNonMatchedTags=1
			tags.font.allowedAttribs= color
			tags.font.rmTagIfNoAttrib = 1
			tags.font.nesting = global
		}
	}
	RTE.config.tx_mcgovcollection_contact.description {
		hidePStyleItems = H1, H4, H5, H6
		proc.exitHTMLparser_db=1
		proc.exitHTMLparser_db {
			keepNonMatchedTags=1
			tags.font.allowedAttribs= color
			tags.font.rmTagIfNoAttrib = 1
			tags.font.nesting = global
		}
	}
	RTE.config.tx_mcgovcollection_form.description {
		hidePStyleItems = H1, H4, H5, H6
		proc.exitHTMLparser_db=1
		proc.exitHTMLparser_db {
			keepNonMatchedTags=1
			tags.font.allowedAttribs= color
			tags.font.rmTagIfNoAttrib = 1
			tags.font.nesting = global
		}
	}
	RTE.config.tx_mcgovcollection_file.description {
		hidePStyleItems = H1, H4, H5, H6
		proc.exitHTMLparser_db=1
		proc.exitHTMLparser_db {
			keepNonMatchedTags=1
			tags.font.allowedAttribs= color
			tags.font.rmTagIfNoAttrib = 1
			tags.font.nesting = global
		}
	}
');

t3lib_extMgm::addPItoST43($_EXTKEY, 'fe_plugins/news/class.tx_mcgovcollection_news.php', '_news', 'list_type', 1);
t3lib_extMgm::addPItoST43($_EXTKEY, 'fe_plugins/contact/class.tx_mcgovcollection_contact.php', '_contact', 'list_type', 1);
t3lib_extMgm::addPItoST43($_EXTKEY, 'fe_plugins/form/class.tx_mcgovcollection_form.php', '_form', 'list_type', 1);
t3lib_extMgm::addPItoST43($_EXTKEY, 'fe_plugins/file/class.tx_mcgovcollection_file.php', '_file', 'list_type', 1);
?>