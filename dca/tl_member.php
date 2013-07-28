<?php

/**
 * @copyright  Helmut Schottmüller 2013
 * @author     Helmut Schottmüller <https://github.com/hschottm/xtmembers_membership>
 * @package    Backend
 * @license    LGPL
 */

array_insert($GLOBALS['TL_DCA']['tl_member']['list']['operations'], 1, array(
	'fees' => array
	(
		'label'               => &$GLOBALS['TL_LANG']['tl_member']['membership_fees'],
		'href'                => 'table=tl_member_fees',
		'icon'                => 'system/modules/xtmembers_membership/assets/fees.png'
	)
));

if (is_array($GLOBALS['TL_DCA']['tl_member']['config']['ctable']))
{
	array_push($GLOBALS['TL_DCA']['tl_member']['config']['ctable'], 'tl_member_fees');
}
else
{
	$GLOBALS['TL_DCA']['tl_member']['config']['ctable'] = array('tl_member_fees');
}


$GLOBALS['TL_DCA']['tl_member']['palettes']['default'] = str_replace('{groups_legend},','{legend_membership},membership_since,membership_until,membership_fee;{groups_legend}', $GLOBALS['TL_DCA']['tl_member']['palettes']['default']);

$GLOBALS['TL_DCA']['tl_member']['fields']['membership_fee'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_member']['membership_fee'],
	'exclude'                 => true,
	'search'                  => true,
	'inputType'               => 'text',
	'load_callback'           => array(array('tl_member_membership', 'formatCurrency')),
	'eval'                    => array('feEditable' => true,'feViewable' => true,'feGroup' => 'personal','tl_class' => 'w50','rgxp' => 'currency'),
	'sql'                     => "text NULL"
);

$GLOBALS['TL_DCA']['tl_member']['fields']['membership_since'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_member']['membership_since'],
	'exclude'                 => true,
	'search'                  => true,
	'inputType'               => 'text',
	'eval'                    => array('feEditable' => true,'feViewable' => true,'feGroup' => 'personal','tl_class' => 'w50 wizard','datepicker' => true,'rgxp' => 'date'),
	'sql'                     => "text NULL"
);

$GLOBALS['TL_DCA']['tl_member']['fields']['membership_until'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_member']['membership_until'],
	'exclude'                 => true,
	'search'                  => true,
	'inputType'               => 'text',
	'eval'                    => array('feEditable' => true,'feViewable' => true,'feGroup' => 'personal','tl_class' => 'w50 wizard','datepicker' => true,'rgxp' => 'date'),
	'sql'                     => "text NULL"
);

/**
 * Class tl_member_membership
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Helmut Schottmüller 2013
 * @author     Helmut Schottmüller <https://github.com/hschottm/xtmembers_membership>
 * @package    Controller
 */
class tl_member_membership extends tl_member
{

	public function formatCurrency($value, $dc)
	{
		return sprintf("%.2f", $value);
	}
}

?>