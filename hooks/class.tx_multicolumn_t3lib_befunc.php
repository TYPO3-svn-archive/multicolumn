<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 snowflake productions GmbH
*  All rights reserved
*
*  This script is part of the Typo3 project. The Typo3 project is
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

class tx_multicolumn_t3lib_befunc {
	/**
	 * Copy children of a localized multicolumn container
	 *
	 * @param	array		$dataStructArray	Flexform datastruct
	 * @param	array		$conf: 	tca
	 * @param	array		$row (reference) The record uid currently processing data for, [integer] or [string] (like 'NEW...')
	 * @param	array		$fieldArray: (reference) The field array of a record
	 */	
	public function getFlexFormDS_postProcessDS(&$dataStructArray, $conf, $row, $table, $fieldName) {
		if($table == 'tt_content' && $row['CType'] == 'multicolumn') {
			require_once(PATH_tx_multicolumn . 'lib/class.tx_multicolumn_flexform.php');
			$flex = t3lib_div::makeInstance('tx_multicolumn_flexform', $row['pi_flexform']);
			$layout = $flex->getFlexValue ('preSetLayout', 'layoutKey');
			
			if($layout == 'effectBox.') {
				unset($dataStructArray['sheets']['advancedLayout']);
			} else {
				unset($dataStructArray['sheets']['effectBox']);
			}
		}


		//unset($dataStructArray['sheets']['advancedLayout']);

	}
}
?>