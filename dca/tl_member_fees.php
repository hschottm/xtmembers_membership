<?php

/**
 * @copyright  Helmut Schottmüller 2013
 * @author     Helmut Schottmüller <https://github.com/hschottm/xtmembers_membership>
 * @package    Backend
 * @license    LGPL
 */

class tl_member_fees extends Backend
{
	public function formatCurrency($value, $dc)
	{
		return sprintf("%.2f", $value);
	}

	public function getLabel($row, $label)
	{
		$status = $GLOBALS['TL_LANG']['tl_member_fees'][$row['status']];
		if (0 == strlen($status)) $status = $GLOBALS['TL_LANG']['tl_member_fees']['outstanding'];
		return sprintf('%s - %s', $row['year'], $status);
	}

	public function onLoad($dc = null)
	{
		if (is_object($dc))
		{
			$arrMember = $this->Database->prepare("SELECT * FROM tl_member WHERE id = ?")
				->execute($this->Input->get('id'))->row();
			$from = $arrMember['membership_since'];
			$until = $arrMember['membership_until'];
			if (!strlen($from)) return;
			$yearfrom = date('Y', $from);
			$yearuntil = date('Y', $until);
			if ($yearuntil < $yearfrom)
			{
				$until = time();
				$yearuntil = date('Y', $until);
			}
			$arrYear = $this->Database->prepare("SELECT * FROM tl_member_fees WHERE pid = ? AND year = ?")
				->execute($arrMember['id'], $yearuntil)->row();
			if (!is_array($arrYear))
			{
				$min = $yearfrom;
				$minYear = $this->Database->prepare("SELECT MIN(year) minyear FROM tl_member_fees WHERE pid = ?")
					->execute($arrMember['id'])->row();
				if (is_array($minYear) && strlen($minYear['minyear']))
				{
					$min = $minYear['minyear']+1;
				}
				for ($i = $min; $i <= $yearuntil; $i++)
				{
					$this->Database->prepare("INSERT INTO tl_member_fees (tstamp, pid, sorting, year, fee, status) VALUES (?, ?, ?, ?, ?, ?)")
						->execute(time(), $arrMember['id'], $i, $i, $arrMember['membership_fee'], '');
				}
			}
		}
	}
}

/**
 * Table tl_literature_category
 */
$GLOBALS['TL_DCA']['tl_member_fees'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ptable'                      => 'tl_member',
		'ctable'                      => array(),
		'switchToEdit'                => false,
		'closed'                      => true,
		'enableVersioning'            => true,
		'onload_callback' => array
		(
			array('tl_member_fees', 'onLoad')
		),
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',
				'pid' => 'index'
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 1,
			'fields'                  => array('year'),
			'flag'                    => 12,
			'panelLayout'             => 'search,limit'
		),
		'label' => array
		(
			'fields'                  => array('year'),
			'format'                  => '%d',
			'label_callback'          => array('tl_member_fees', 'getLabel')
		),
		'global_operations' => array
		(
			'all' => array
				(
					'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
					'href'                => 'act=select',
					'class'               => 'header_edit_all',
					'attributes'          => 'onclick="Backend.getScrollOffset();"'
				)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_member_fees']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_member_fees']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => 'year,status,fee,payed'
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'pid' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'sorting' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'year' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_member_fees']['year'],
			'search'                  => true,
			'sorting'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>4, 'rgxp' => 'digit', 'tl_class' => 'w50', 'readonly' => true),
			'sql'                     => "varchar(4) NOT NULL default ''"
		),
		'fee' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_member_fees']['fee'],
			'search'                  => true,
			'sorting'                 => true,
			'inputType'               => 'text',
			'load_callback'           => array(array('tl_member_fees', 'formatCurrency')),
			'eval'                    => array('tl_class' => 'w50','rgxp' => 'currency', 'mandatory' => true),
			'sql'                     => "varchar(32) NOT NULL default '0'"
		),
		'payed' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_member_fees']['already_payed'],
			'search'                  => true,
			'sorting'                 => true,
			'inputType'               => 'text',
			'load_callback'           => array(array('tl_member_fees', 'formatCurrency')),
			'eval'                    => array('tl_class' => 'w50','rgxp' => 'currency', 'mandatory' => true),
			'sql'                     => "varchar(32) NOT NULL default '0'"
		),
		'status' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_member_fees']['status'],
			'search'                  => true,
			'sorting'                 => true,
			'inputType'               => 'select',
			'options'                 => array('payed', 'postponed', 'freed'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_member_fees'],
			'eval'                    => array('includeBlankOption'=>true, 'feEditable'=>true, 'feViewable'=>true, 'feGroup'=>'personal', 'tl_class'=>'w50'),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
	)
);

?>