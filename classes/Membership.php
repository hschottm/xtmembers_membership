<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Core
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

namespace Contao;

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

	public function exportXls()
	{
		if (\Input::get('key') != 'export_xls')
		{
			$this->redirect(str_replace('&key=export_xls', '', \Environment::get('request')));
		}
		
		$this->loadLanguageFile('tl_member_fees');
		$this->loadLanguageFile('tl_member');
		$arrFees = $this->Database->prepare("SELECT tl_member_fees.*, tl_member.firstname, tl_member.lastname, tl_member.street, tl_member.postal, tl_member.city FROM tl_member_fees, tl_member WHERE tl_member_fees.pid = tl_member.id ORDER BY tl_member.lastname ASC, tl_member.firstname ASC, tl_member_fees.year DESC")
			->execute();
		if ($arrFees->numRows)
		{
			$xls = new \xlsexport();
			$sheet = utf8_decode($GLOBALS['TL_LANG']['tl_member_fees']['fees']);
			$xls->addworksheet($sheet);
			$row = 0;
			$col = 0;
			$xls->setcell(array("sheetname" => $sheet,"row" => $row, "col" => $col, "data" => utf8_decode($GLOBALS['TL_LANG']['tl_member']['lastname'][0]), "bgcolor" => "#C0C0C0", "color" => "#000000", "fontweight" => XLSFONT_BOLD));
			$col++;
			$xls->setcell(array("sheetname" => $sheet,"row" => $row, "col" => $col, "data" => utf8_decode($GLOBALS['TL_LANG']['tl_member']['firstname'][0]), "bgcolor" => "#C0C0C0", "color" => "#000000", "fontweight" => XLSFONT_BOLD));
			$col++;
			$xls->setcell(array("sheetname" => $sheet,"row" => $row, "col" => $col, "data" => utf8_decode($GLOBALS['TL_LANG']['tl_member']['street'][0]), "bgcolor" => "#C0C0C0", "color" => "#000000", "fontweight" => XLSFONT_BOLD));
			$col++;
			$xls->setcell(array("sheetname" => $sheet,"row" => $row, "col" => $col, "data" => utf8_decode($GLOBALS['TL_LANG']['tl_member']['postal'][0]), "bgcolor" => "#C0C0C0", "color" => "#000000", "fontweight" => XLSFONT_BOLD));
			$col++;
			$xls->setcell(array("sheetname" => $sheet,"row" => $row, "col" => $col, "data" => utf8_decode($GLOBALS['TL_LANG']['tl_member']['city'][0]), "bgcolor" => "#C0C0C0", "color" => "#000000", "fontweight" => XLSFONT_BOLD));
			$col++;
			$xls->setcell(array("sheetname" => $sheet,"row" => $row, "col" => $col, "data" => utf8_decode($GLOBALS['TL_LANG']['tl_member_fees']['year'][0]), "bgcolor" => "#C0C0C0", "color" => "#000000", "fontweight" => XLSFONT_BOLD));
			$col++;
			$xls->setcell(array("sheetname" => $sheet,"row" => $row, "col" => $col, "data" => utf8_decode($GLOBALS['TL_LANG']['tl_member_fees']['fee'][0]), "bgcolor" => "#C0C0C0", "color" => "#000000", "fontweight" => XLSFONT_BOLD));
			$col++;
			$xls->setcell(array("sheetname" => $sheet,"row" => $row, "col" => $col, "data" => utf8_decode($GLOBALS['TL_LANG']['tl_member_fees']['payed'][0]), "bgcolor" => "#C0C0C0", "color" => "#000000", "fontweight" => XLSFONT_BOLD));
			$col++;
			$xls->setcell(array("sheetname" => $sheet,"row" => $row, "col" => $col, "data" => utf8_decode($GLOBALS['TL_LANG']['tl_member_fees']['status'][0]), "bgcolor" => "#C0C0C0", "color" => "#000000", "fontweight" => XLSFONT_BOLD));
			$col++;

			while ($arrFees->next())
			{
				$row++;
				$data = $arrFees->row();
				$col = 0;
				$xls->setcell(array("sheetname" => $sheet,"row" => $row, "col" => $col, "data" => utf8_decode($data['lastname'])));
				$col++;
				$xls->setcell(array("sheetname" => $sheet,"row" => $row, "col" => $col, "data" => utf8_decode($data['firstname'])));
				$col++;
				$xls->setcell(array("sheetname" => $sheet,"row" => $row, "col" => $col, "data" => utf8_decode($data['street'])));
				$col++;
				$xls->setcell(array("sheetname" => $sheet,"row" => $row, "col" => $col, "data" => utf8_decode($data['postal'])));
				$col++;
				$xls->setcell(array("sheetname" => $sheet,"row" => $row, "col" => $col, "data" => utf8_decode($data['city'])));
				$col++;
				$xls->setcell(array("sheetname" => $sheet,"row" => $row, "col" => $col, "data" => $data['year']));
				$col++;
				$xls->setcell(array("sheetname" => $sheet,"row" => $row, "col" => $col, "data" => $data['fee'], "type" => CELL_FLOAT));
				$col++;
				$xls->setcell(array("sheetname" => $sheet,"row" => $row, "col" => $col, "data" => $data['payed'], "type" => CELL_FLOAT));
				$col++;
				$xls->setcell(array("sheetname" => $sheet,"row" => $row, "col" => $col, "data" => utf8_decode($GLOBALS['TL_LANG']['tl_member_fees'][$data['status']])));
			}

			$xls->sendFile($this->safefilename(htmlspecialchars_decode($GLOBALS['TL_LANG']['tl_member_fees']['fees'])) . ".xls");
			exit;
		}
		$this->redirect(\Environment::get('script') . '?do=' . \Input::get('do'));
	}

	protected function safefilename($filename) 
	{
		$search = array('/ß/','/ä/','/Ä/','/ö/','/Ö/','/ü/','/Ü/','([^[:alnum:]._])');
		$replace = array('ss','ae','Ae','oe','Oe','ue','Ue','_');
		return preg_replace($search,$replace,$filename);
	}
}

?>