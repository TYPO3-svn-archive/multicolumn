<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 snowflake productions GmbH
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

class tx_multicolumn_pi1  extends tslib_pibase {
	public $prefixId      = 'tx_multicolumn_pi1';        // Same as class name
	public $scriptRelPath = 'pi1/class.tx_multicolumn_pi1.php';    // Path to this script relative to the extension dir.
	public $extKey        = 'multicolumn';    // The extension key.
	public $pi_checkCHash = true;
	
	/**
	 * Local cObj
	 *
	 * @var		tslib_cObj
	 */	
	protected $localCobj;
	
	/**
	 * Instance of tx_multicolumn_flexform
	 *
	 * @var		tx_multicolumn_flexform
	 */
	protected $flex;
	
	/**
	 * Layout configuration array from ts / flexform
	 *
	 * @var		array
	 */
	protected $layoutConfiguration;
	
	/**
	 * Layout configuration array from ts / flexform with option split
	 *
	 * @var		array
	 */	
	protected $layoutConfigurationSplited;
	
	/**
	 * multicolumn uid
	 *
	 * @var		integer
	 */		
	protected $multicolumnContainerUid;
	
	/**
	 * The main method of the PlugIn
	 *
	 * @param    string        $content: The PlugIn content
	 * @param    array        $conf: The PlugIn configuration
	 * @return    The content that is displayed on the website
	 */
	public function main($content,$conf)    {
		$this->init($content, $conf);
			// typoscript is not included
		if(!$this->conf['includeFromStatic'])  return $this->showFlashMessage($this->llPrefixed['lll:error.typoscript.title'], $this->llPrefixed['lll:error.typoscript.message']);

		if($this->layoutConfiguration['columns']) {
			$listItemData = $this->buildColumnData();
				//append config from column 0 for global config container width
			$listData = $listItemData[0];
			$listData['content'] = $this->renderListItems('column', $listItemData, $this->llPrefixed);

			return $this->renderItem('columnContainer', $listData);
		}
	}
	
	
	/**
	 * Initalizes the plugin.
	 *
	 * @param	String		$content: Content sent to plugin
	 * @param	String[]	$conf: Typoscript configuration array
	 */
	protected function init($content, $conf) {
		$this->content = $content;
		$this->conf = $conf;
		$this->pi_loadLL();
		$this->localCobj = t3lib_div::makeInstance('tslib_cObj');
		require_once(PATH_tx_multicolumn . 'lib/class.tx_multicolumn_flexform.php');

		$this->llPrefixed = tx_multicolumn_div::prefixArray($this->LOCAL_LANG[$this->LLkey], 'lll:');
		$this->pi_setPiVarDefaults();
		
			// Check if sys_language_contentOL is set and take $this->cObj->data['_LOCALIZED_UID']
		if ($GLOBALS['TSFE']->sys_language_contentOL && $GLOBALS['TSFE']->sys_language_uid && $this->cObj->data['_LOCALIZED_UID']) {
			$this->multicolumnContainerUid = $this->cObj->data['_LOCALIZED_UID'];
			// take default uid from cObj->data
		} else {
			$this->multicolumnContainerUid = $this->cObj->data['uid'];
		}

		$this->flex = t3lib_div::makeInstance('tx_multicolumn_flexform', $this->cObj->data['pi_flexform']);
		$this->layoutConfiguration = tx_multicolumn_div::getLayoutConfiguration(null, $this->flex);

			//include layout css
		if($this->layoutConfiguration['layoutCss']) {
			$this->addCssFile($this->layoutConfiguration['layoutCss']);
		}
			// do option split
		$this->layoutConfigurationSplited = $GLOBALS['TSFE']->tmpl->splitConfArray($this->layoutConfiguration, $this->layoutConfiguration['columns']);
		

	
	}
	
	
	/**
	 * Gets the data for each column
	 *
	 * @return	array			column data
	 */	
	protected function buildColumnData() {
		$numberOfColumns = $this->layoutConfiguration['columns'];
		$columnContent = array();
		$disableImageShrink = $this->layoutConfiguration['disableImageShrink'] ? true : false;
			// store current max width
		$maxWidthBefore = $GLOBALS['TSFE']->register['maxImageWidth'];

		$columnNumber = 0;
		while ($columnNumber < $numberOfColumns) {
			$multicolumnColPos = tx_multicolumn_div::colPosStart + $columnNumber;
			
			$splitedColumnConf = $this->layoutConfigurationSplited[$columnNumber];
			$conf = array_merge($this->layoutConfiguration, $splitedColumnConf);

			$colPosData = array('colPos' => $this->cObj->data['colPos']);
			$colPosMaxImageWidth = intval($this->renderItem('columnWidth', $colPosData));

			$columnData = $conf;
			$columnData['columnWidth'] = $conf['columnWidth'] ? $conf['columnWidth'] : round(100/$numberOfColumns);

				// evaluate columnWidth in pixels
			if($conf['containerMeasure'] == 'px' && $conf['containerWidth']) {
				$columnData['columnWidthPixel'] = round($conf['containerWidth']/$numberOfColumns);
				
				// if columnWidth and column measure is set
			} else if($conf['columnMeasure'] == 'px' && $conf['columnWidth']) {
				$columnData['columnWidthPixel'] = $conf['columnWidth'];
				
				// if container width is set in percent (default 100%)
			} else if ($colPosMaxImageWidth) {
				$columnData['columnWidthPixel'] = tx_multicolumn_div::calculateMaxColumnWidth($columnData['columnWidth'], $colPosMaxImageWidth, $numberOfColumns);
			}

				// calculate total column padding width
			if($columnData['columnPadding']) {
				$columnData['columnPaddingTotalWidthPixel'] = tx_multicolumn_div::getPaddingTotalWidth($columnData['columnPadding']);
			}
			
			$columnData['colPos'] = $multicolumnColPos;
			$contentElements = tx_multicolumn_db::getContentElementsFromContainer($columnData['colPos'], $this->cObj->data['pid'], $this->multicolumnContainerUid, $this->cObj->data['sys_language_uid']);

			if($contentElements) {
					// do auto scale if requested
				$maxImageWidth = $disableImageShrink ? null : ($columnData['columnWidthPixel'] ? ($columnData['columnWidthPixel'] - $columnData['columnPaddingTotalWidthPixel']) : null);

				$GLOBALS['TSFE']->register['maxImageWidth'] = $maxImageWidth;
				$columnData['content'] = $this->renderListItems('columnItem', $contentElements, $this->llPrefixed);
			}

			$columnContent[] = $columnData;
			$columnNumber ++;
		}
		
			// restore maxWidth
		$GLOBALS['TSFE']->register['maxImageWidth'] = $maxWidthBefore;
		
		return $columnContent;
	}
	
	/**
	 * Render an array with data element with $confName
	 *
	 * @param	String		$confName		Path to typoscript to render each element with
	 * @param	Array		$recordsArray	Array which contains elements (array) for typoscript rendering
	 * @param	Array		$appendData		Additinal data
	 * @return	String		All items rendered as a string
	 */
	public function renderListItems($confName, array $recordsArray, array $appendData = array(), $debug = false) {
		$arrayLength= count($recordsArray);
		$rowNr	= 1;
		$index = 0;
		$content = null;

		foreach($recordsArray as $data) {
			// first run?
			if($rowNr == 1)
				$data['isFirst'] = $confName.'First listItemFirst';

			// last run
			if($rowNr == $arrayLength)
				$data['isLast'] = $confName.'Last listItemLast';

			// push recordNumber to $data array
			$data['recordNumber'] = $rowNr;
			$data['index'] = $rowNr-1;

			// push arrayLength to $data array
			$data['arrayLength'] = $arrayLength;

			// Add odd or even to the cObjData array.
			$data['oddeven'] = $rowNr % 2 ? $confName.'Odd listItemOdd' : $confName.'Even listItemEven';

			$data = array_merge($data, $appendData);

			// Render
			$this->localCobj->data = $data;
			$content .= $this->localCobj->cObjGetSingle($this->conf[$confName], $this->conf[$confName.'.']);

			$rowNr ++;
		}

		return $content;
	}
	
	/**
	 * Render an array with trough cObjGetSingle
	 *
	 * @param	String		$confName Path to typoscript to render each element with
	 * @param	Array		$recordsArray	Array which contains elements (array) for typoscript rendering
	 * @return	String		All items rendered as a string
	 */	
	protected function renderItem($confName, array $data) {
		$this->localCobj->data = $data;
		return $this->localCobj->cObjGetSingle($this->conf[$confName], $this->conf[$confName.'.']);
	}
	
	/**
	 * Adds a css file
	 */	
	protected function addCssFile($cssFile) {
		$cssFileResolved = $GLOBALS['TSFE']->tmpl->getFileName($cssFile);
		if($cssFileResolved) $GLOBALS['TSFE']->getPageRenderer()->addCssFile($cssFileResolved);
	}
	
	/**
	 * Displays a flash message
	 *
	 * @param	string		$title flash message title
	 * @param	string		$message flash message message
	 *
	 * @retun	string		html content of flash message
	 */		
	protected function showFlashMessage($title, $message, $type = t3lib_FlashMessage::ERROR) {
			// get relative path
		$relPath = str_replace(t3lib_div::getIndpEnv('TYPO3_REQUEST_HOST'), null, t3lib_div::getIndpEnv('TYPO3_SITE_URL'));
			// add error csss
		$GLOBALS['TSFE']->getPageRenderer()->addCssFile($relPath . 'typo3conf/ext/multicolumn/res/flashmessage.css', 'stylesheet','screen');
		$flashMessage = t3lib_div::makeInstance('t3lib_FlashMessage', $message, $title, $type);
		return $flashMessage->render();
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/multicolumn/pi1/class.tx_multicolumn_pi1.php'])    {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/multicolumn/pi1/class.tx_multicolumn_pi1.php']);
}
?>