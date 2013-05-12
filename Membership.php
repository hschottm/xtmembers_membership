<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Core
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

/**
 * Class Membership
 *
 * Helper class for tags
 * @copyright  Helmut Schottmüller 2013
 * @author     Helmut Schottmüller <https://github.com/hschottm/xtmembers_membership>
 * @package    Controller
 */
class Membership extends Backend
{

	public function customRgxpCurrency($strRegexp, $varValue, Widget $objWidget)
	{
		if ($strRegexp == 'currency')
		{
			if (!is_numeric($varValue) || $varValue < 0)
			{
				$objWidget->addError(sprintf($GLOBALS['TL_LANG']['ERR']['currency'], $objWidget->label));
			}
			return true;
		}
		return false;
	}

}

?>