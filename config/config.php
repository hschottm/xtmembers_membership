<?php

/**
 * @copyright  Helmut Schottmüller 2013
 * @author     Helmut Schottmüller <https://github.com/hschottm/xtmembers_membership>
 * @package    xtmembers_membership
 * @license    LGPL
 * @filesource
 */

$GLOBALS['TL_HOOKS']['addCustomRegexp'][] = array('Membership', 'customRgxpCurrency');

array_insert($GLOBALS['BE_MOD']['accounts']['member']['tables'], 1, 'tl_member_fees');

?>