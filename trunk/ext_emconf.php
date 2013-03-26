<?php

########################################################################
# Extension Manager/Repository config file for ext "multicolumn".
#
# Auto generated 26-03-2013 12:50
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'multicolumn',
	'description' => 'The Multicolumn extension expands TYPO3 with a new content element called Multicolumn. With the Multicolumn content element it has never been easier to do multicolumn layouts with TYPO3',
	'category' => 'fe',
	'author' => 'Michael Birchler, Snowflake Productions Gmgh',
	'author_email' => 'mbirchler@snowflake.ch',
	'shy' => '',
	'dependencies' => '',
	'conflicts' => 'templavoila',
	'module' => '',
	'state' => 'stable',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 1,
	'priority' => 'bottom',
	'lockType' => '',
	'author_company' => 'Snowflake Productions Gmbh',
	'version' => '2.1.14',
	'constraints' => array(
		'depends' => array(
			'php' => '5.3.2-0.0.0',
			'typo3' => '4.5.0-6.1.999',
		),
		'conflicts' => array(
			'templavoila' => '',
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:65:{s:9:"ChangeLog";s:4:"317d";s:16:"ext_autoload.php";s:4:"54be";s:21:"ext_conf_template.txt";s:4:"71f2";s:12:"ext_icon.gif";s:4:"925c";s:17:"ext_localconf.php";s:4:"65ea";s:14:"ext_tables.php";s:4:"354c";s:14:"ext_tables.sql";s:4:"ad91";s:15:"flexform_ds.xml";s:4:"e808";s:13:"locallang.xml";s:4:"337f";s:26:"locallang_csh_flexform.xml";s:4:"7fd9";s:16:"locallang_db.xml";s:4:"cdde";s:12:"tsconfig.txt";s:4:"7d0c";s:26:"tt_content_multicolumn.gif";s:4:"849c";s:14:"doc/manual.sxw";s:4:"a391";s:44:"hooks/class.tx_multicolumn_alt_clickmenu.php";s:4:"7734";s:41:"hooks/class.tx_multicolumn_cms_layout.php";s:4:"0c66";s:38:"hooks/class.tx_multicolumn_db_list.php";s:4:"04a3";s:43:"hooks/class.tx_multicolumn_t3lib_befunc.php";s:4:"064c";s:38:"hooks/class.tx_multicolumn_tcemain.php";s:4:"8684";s:50:"hooks/class.tx_multicolumn_tt_content_drawItem.php";s:4:"75c7";s:46:"hooks/class.tx_multicolumn_wizardItemsHook.php";s:4:"e04a";s:31:"lib/class.tx_multicolumn_db.php";s:4:"e728";s:32:"lib/class.tx_multicolumn_div.php";s:4:"e936";s:41:"lib/class.tx_multicolumn_emconfhelper.php";s:4:"b740";s:37:"lib/class.tx_multicolumn_flexform.php";s:4:"11cf";s:36:"lib/class.tx_multicolumn_pi_base.php";s:4:"9dc7";s:37:"lib/class.tx_multicolumn_tce_eval.php";s:4:"6bb6";s:36:"lib/class.tx_multicolumn_tceform.php";s:4:"78ad";s:14:"pi1/ce_wiz.gif";s:4:"1863";s:32:"pi1/class.tx_multicolumn_pi1.php";s:4:"c397";s:17:"pi1/locallang.xml";s:4:"2284";s:24:"pi1/static/defaultTS.txt";s:4:"5bab";s:20:"pi1/static/setup.txt";s:4:"f1c5";s:46:"pi_sitemap/class.tx_multicolumn_pi_sitemap.php";s:4:"d7e7";s:27:"pi_sitemap/static/setup.txt";s:4:"fdda";s:20:"res/flashmessage.css";s:4:"22d8";s:24:"res/backend/style-v6.css";s:4:"ec25";s:21:"res/backend/style.css";s:4:"9ec6";s:25:"res/effects/locallang.xml";s:4:"0a65";s:46:"res/effects/easyAccordion/easyAccordionInit.js";s:4:"03ca";s:49:"res/effects/easyAccordion/jquery.easyAccordion.js";s:4:"4da1";s:35:"res/effects/easyAccordion/style.css";s:4:"ce01";s:43:"res/effects/roundabout/jquery.roundabout.js";s:4:"4094";s:51:"res/effects/roundabout/multicolumnImplementation.js";s:4:"54ed";s:37:"res/effects/roundabout/roundabout.css";s:4:"fc6c";s:33:"res/effects/roundabout/shadow.png";s:4:"5a86";s:34:"res/effects/roundabout/sprites.png";s:4:"c9b2";s:36:"res/effects/simpleTabs/simpleTabs.js";s:4:"6bd3";s:32:"res/effects/simpleTabs/style.css";s:4:"47d4";s:47:"res/effects/sudoSlider/jquery.sudoSlider.min.js";s:4:"5ec2";s:32:"res/effects/sudoSlider/style.css";s:4:"0c38";s:45:"res/effects/sudoSlider/sudoSliderEffectbox.js";s:4:"c1ac";s:42:"res/effects/sudoSlider/images/btn_next.gif";s:4:"a1d5";s:42:"res/effects/sudoSlider/images/btn_prev.gif";s:4:"7301";s:32:"res/effects/vAccordion/style.css";s:4:"814a";s:36:"res/effects/vAccordion/vAccordion.js";s:4:"d558";s:24:"res/javascript/jQuery.js";s:4:"4bab";s:16:"res/layout/1.gif";s:4:"e38a";s:17:"res/layout/10.gif";s:4:"7286";s:16:"res/layout/2.gif";s:4:"c1a4";s:16:"res/layout/3.gif";s:4:"5f77";s:27:"res/layout/effectSlider.gif";s:4:"c982";s:24:"res/layout/locallang.xml";s:4:"f2be";s:45:"res/layout/makeEqualElementBoxColumnHeight.js";s:4:"4e78";s:39:"res/layout/makeEqualElementBoxHeight.js";s:4:"9926";}',
	'suggests' => array(
	),
);

?>