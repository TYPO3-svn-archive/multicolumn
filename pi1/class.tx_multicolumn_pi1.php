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
	 * Is effect box
	 *
	 * @var		integer
	 */		
	protected $isEffectBox;
	
	/**
	 * Effect configuration array from ts / flexform
	 *
	 * @var		array
	 */
	protected $effectConfiguration;
	
	/**
	 * maxWidth before
	 *
	 * @var		integer
	 */	
	protected $TSFEmaxWidthBefore;
	
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
		
		$content = $this->layoutConfiguration['columns'] ? $this->renderMulticolumnView() : $this->renderEffectBoxView();
		return $content;
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
		$this->isEffectBox = ($this->flex->getFlexValue ('preSetLayout', 'layoutKey') == 'effectBox.') ? true : false;
			// store current max width
		$this->TSFEmaxWidthBefore = isset($GLOBALS['TSFE']->register['maxImageWidth']) ? $GLOBALS['TSFE']->register['maxImageWidth'] : null;
		
			// effect view
		if($this->isEffectBox) {
			$this->effectConfiguration = tx_multicolumn_div::getEffectConfiguration(null, $this->flex);
			if(!empty($this->effectConfiguration['options'])) {
				$name = 'mullticolumnEffectBox_' . $this->cObj->data['uid'];
				$code = 'var ' . $name . ' ={' . $this->effectConfiguration['options'] . '};';
				$GLOBALS['TSFE']->getPageRenderer()->addJsInlineCode($name, $code);
			}
				// js files
			if(is_array($this->effectConfiguration['jsFiles.'])) {
				$this->includeCssJsFiles($this->effectConfiguration['jsFiles.']);
			}
				// css files
			if(is_array($this->effectConfiguration['cssFiles.'])) {
				$this->includeCssJsFiles($this->effectConfiguration['cssFiles.']);
			}
			
			// default multicolumn view
		} else {
			$this->layoutConfiguration = tx_multicolumn_div::getLayoutConfiguration(null, $this->flex);
	
				//include layout css
			if($this->layoutConfiguration['layoutCss']) {
				$this->addCssFile($this->layoutConfiguration['layoutCss']);
			}
				// do option split
			$this->layoutConfigurationSplited = $GLOBALS['TSFE']->tmpl->splitConfArray($this->layoutConfiguration, $this->layoutConfiguration['columns']);	
		}	
	}
	
	protected function renderMulticolumnView() {
		$listData = array();
		$listItemData = $this->buildColumnData();
				//append config from column 0 for global config container width
		$listData = $listItemData[0];
		$listData['content'] = $this->renderListItems('column', $listItemData, $this->llPrefixed);

		return $this->renderItem('columnContainer', $listData);
	}
	
	protected function renderEffectBoxView() {
		$listData = array();
		$listData = $this->cObj->data;
		
		$columnWidth = !empty($this->effectConfiguration['effectBoxWidth']) ? $this->effectConfiguration['effectBoxWidth'] : $this->renderColumnWidth();
		$GLOBALS['TSFE']->register['maxImageWidth'] = !empty($columnWidth) ? $columnWidth : $GLOBALS['TSFE']->register['maxImageWidth'] ;

		$contentElements = tx_multicolumn_db::getContentElementsFromContainer($columnData['colPos'], $this->cObj->data['pid'], $this->multicolumnContainerUid, $this->cObj->data['sys_language_uid']);
		if(is_array($contentElements)) {
			$listeItemsArray = array (
				'effect' => $this->effectConfiguration['effect']
				,'columnWidth' => $columnWidth ? ('width:' . $columnWidth . 'px;') : null
			);
			$listeItemsArray = t3lib_div::array_merge($listeItemsArray, $this->llPrefixed);
			$listItemContent = $this->renderListItems('effectBoxItems', $contentElements, $listeItemsArray);
		}

		$listData['columnWidth'] = $columnWidth;
		$listData['effect'] = $this->effectConfiguration['effect'];
		$listData['effectBoxClass'] = $this->effectConfiguration['effectBoxClass'];
		$listData['effectBoxItems'] = $listItemContent;
		
		$content = $this->renderItem('effectBox', $listData);
		$GLOBALS['TSFE']->register['maxImageWidth'] = $this->TSFEmaxWidthBefore;
		
		return $content;
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
		
		$columnNumber = 0;
		while ($columnNumber < $numberOfColumns) {
			$multicolumnColPos = tx_multicolumn_div::colPosStart + $columnNumber;
			
			$splitedColumnConf = $this->layoutConfigurationSplited[$columnNumber];
			$conf = array_merge($this->layoutConfiguration, $splitedColumnConf);

			$colPosMaxImageWidth = $this->renderColumnWidth();

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
				$maxImageWidth = $disableImageShrink ? null : (isset($columnData['columnWidthPixel']) ? ($columnData['columnWidthPixel'] - $columnData['columnPaddingTotalWidthPixel']) : null);

				$GLOBALS['TSFE']->register['maxImageWidth'] = $maxImageWidth;
				$columnData['content'] = $this->renderListItems('columnItem', $contentElements, $this->llPrefixed);
			}

			$columnContent[] = $columnData;
			$columnNumber ++;
		}
		
			// restore maxWidth
		$GLOBALS['TSFE']->register['maxImageWidth'] = $this->TSFEmaxWidthBefore;
		
		return $columnContent;
	}
	
	/**
	 * Evaluates the maxwidth of current column
	 *
	 * @param	String		$confName		Path to typoscript to render each element with
	 * @param	Array		$recordsArray	Array which contains elements (array) for typoscript rendering
	 * @param	Array		$appendData		Additinal data
	 * @return	String		All items rendered as a string
	 */	
	protected function renderColumnWidth () {
		$colPosData = array('colPos' => $this->cObj->data['colPos']);
		return intval($this->renderItem('columnWidth', $colPosData));
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
	 * Includes a css or js file
	 *
	 * @param	include files
	 */	
	protected function includeCssJsFiles(array $files) {
		foreach($files as $fileKey=>$file) {
			$mediaTypeSplit = strrchr($file, '.');

			$hookRequestParams = array(
				'includeFile' => array(
					$fileKey => $file,
					$fileKey . '.' => $files[$fileKey . '.']  	       
				),
				'mediaType' => str_replace('.', null, $mediaTypeSplit)
			);

			if(!$this->hookRequest('addJsCssFile', $hookRequestParams)) {
				$resolved = $GLOBALS['TSFE']->tmpl->getFileName($file);
				if($resolved) {
					($mediaTypeSplit ==  '.js') ? $GLOBALS['TSFE']->getPageRenderer()->addJsFooterFile($resolved) : $GLOBALS['TSFE']->getPageRenderer()->addCssFile($resolved);
				}
			}			
		}
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
	
	/**
	 * Returns an object reference to the hook object if any
	 *
	 * @param	string		Name of the function you want to call / hook key
	 * @param	array		Request params
	 * @return	integer		Hook objects found
	 */
	protected function hookRequest($functionName, array $hookRequestParams) {
		global $TYPO3_CONF_VARS;
		$hooked = 0;
		
			// Hook: menuConfig_preProcessModMenu
		if (is_array($TYPO3_CONF_VARS['EXTCONF']['multicolumn']['pi1_hooks'][$functionName])) {
			foreach($TYPO3_CONF_VARS['EXTCONF']['multicolumn']['pi1_hooks'][$functionName] as $classRef) {
				$hookObj = t3lib_div::getUserObj($classRef);
				if (method_exists ($hookObj, $functionName)) {
					$hookObj->$functionName($this, $hookRequestParams);
					$hooked ++;
				}
			}
		}

		return $hooked;
	}
}

if (defined('TYPO3_MODE') && isset($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/multicolumn/pi1/class.tx_multicolumn_pi1.php']))    {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/multicolumn/pi1/class.tx_multicolumn_pi1.php']);
}
?>