<?php
/*
 * e107 website system
 *
 * Copyright (C) 2008-2014 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 * 
 * Chatbox e_search addon 
 */
 

if (!defined('e107_INIT')) { exit; }

// v2 e_search addon. 
// Removes the need for search_parser.php, search_advanced.php and in most cases search language files. 

class vstore_search extends e_search // include plugin-folder in the name.
{
		
	function config()
	{
		$sql = e107::getDb();
		
		$catList = array();
		
		$catList[] = array('id' => 'all', 'title' => LAN_SEARCH_51);
		
		if ($sql ->select("vstore_cat", "cat_id, cat_name")) 
		{
			while($row = $sql->fetch()) 
			{
				$catList[] = array('id' => $row['cat_id'], 'title' => $row['cat_name']);
			}
		}
		
		
		$matchList = array(
					array('id' => 0, 'title' => LAN_SEARCH_53),
					array('id' => 1, 'title' => LAN_SEARCH_54)
		);

			
		$search = array(
			'name'			=> 'Vstore',
			'table'			=> 'vstore_items AS i LEFT JOIN #vstore_cat AS c ON i.item_cat = c.cat_id',

			'advanced' 		=> array(
								'cat'	=> array('type'	=> 'dropdown', 		'text' => LAN_SEARCH_63, 'list'=>$catList),
								'match'	=> array('type'	=> 'dropdown',		'text' =>  LAN_SEARCH_52, 'list'=>$matchList)
							),
							
			'return_fields'	=> array('i.item_id', 'i.item_name', 'i.item_code', 'i.item_desc', 'i.item_keywords', 'i.item_pic', 'i.item_cat', 'c.cat_name'), 
			'search_fields'	=> array('i.item_name' => '1.2', 'i.item_desc' => '0.6', 'i.item_code' => '0.6', 'i.item_keywords' => '0.6', 'c.cat_name' => '1.2'), // fields and their weights. 
	
			'order'			=> array('i.item_name' => 'DESC'),
			'refpage'		=> 'vstore.php'
		);


		return $search;
	}



	/* Compile Database data for output */
	function compile($row)
	{
		$tp = e107::getParser();
		
		$res = array();

		// Build url to product / item
		$link = e107::url('vstore', 'product', array(
				'cat_sef' => eHelper::title2sef($row['cat_name'], 'dashl'), 
				'item_id' => eHelper::title2sef($row['item_id'], 'dashl'), 
				'item_sef' => eHelper::title2sef($row['item_name'], 'dashl')));

		$res['link'] 		= $link;
		$res['pre_title'] 	= $tp->toHTML($row['cat_name'],false,'TITLE')." | ";
		$res['title'] 		= $row['item_name'];
		$res['summary'] 	= $row['news_desc'];
		$res['detail'] 		= ''; //LAN_SEARCH_3.$tp->toDate($row['news_datestamp'], "long");
		$res['image']		= $row['item_pic'];
		
		return $res;
		
	}



	/**
	 * Optional - Advanced Where
	 * @param $parm - data returned from $parm (ie. advanced fields included. in this case 'date' and 'author' )
	 */
	function where($parm=null)
	{
		$tp = e107::getParser();
	
		$time = time();
		
		// search only in active items and categories
		$qry = 'i.item_active = 1 AND c.cat_class IN ('.USERCLASS_LIST.') AND';
		
		if (isset($parm['cat']) && $parm['cat'] != 'all') {
			$qry .= " c.cat_id='".intval($parm['cat'])."' AND";
		}

		return $qry;
	}
	

}
