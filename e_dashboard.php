<?php
	if (!defined('e107_INIT')) { exit; }


	class vstore_dashboard // include plugin-folder in the name.
	{
		private $title; // dynamic title.

		function chart()
		{

			$config = array(
				0 => array(
					'text'		=> $this->renderChart(),
					'caption'	=> e107::getParser()->toGlyph('fa-shopping-cart').' Vstore Open/Payed orders',
				),
			);

			return $config;
		}


		/**
		 * Render a chart displaying open and payed orders of the last week
		 *
		 * @return bool|string
		 */
		function renderChart()
		{
			$sql = e107::getDb();

			$week_start    = strtotime('1 week ago');
			$week_end      = time()+7200;

			$fields = 'SUM(IF(order_status="C", order_pay_amount, 0)) AS A, SUM(IF(order_status!="C", order_pay_amount, 0)) AS B, DATE(FROM_UNIXTIME(order_date)) AS `COL`';
			$where = 'order_date >= '.$week_start .' AND order_date <= '.$week_end;
			$groupby = 'GROUP BY DATE(FROM_UNIXTIME(order_date))';
			if(!$sql->select('vstore_orders', $fields, $where.' '.$groupby))
			{
				return 'No orders available for the last week.';
			}

			$dbdata = array();
			while($row = $sql->fetch())
			{

				$dbdata['COL'][] = $row['COL'];
				$dbdata['A'][] = floatval($row['A']);
				$dbdata['B'][] = floatval($row['B']);

			}

			$this->title = 'Payed & Open orders';
			$data = array();

			$data['labels'] = $dbdata['COL'];

			$data['datasets'][]	= array(
				'fillColor'			=> "rgba(220,220,220,0.5)",
				'strokeColor'		=> "rgba(220,220,220,1)",
				'pointColor '		=> "rgba(220,220,220,1)",
				'pointStrokeColor'	=> "#fff",
				'data'				=> $dbdata['A'],
				'title'				=> 'Open'
			);

			$data['datasets'][]	= array(
				'fillColor'			=> "rgba(151,187,205,0.5)",
				'strokeColor'		=> "rgba(151,187,205,1)",
				'pointColor '		=> "rgba(151,187,205,1)",
				'pointStrokeColor'	=> "#fff",
				'data'				=> $dbdata['B'],
				'title'				=> 'Payed'
			);



			$cht = e107::getChart();
			$cht->setType('line');
			$cht->setOptions(array(
				'annotateDisplay' => true,
				'annotateFontSize' => 8
			));

			$cht->setData($data);

			$text = $cht->render('vstore_canvas', '100%', '200px');

			$text .= "<div class='center'><small>
			<span style='color:rgba(220,220,220,1);' class='fa fa-shopping-cart'></span> Open&nbsp;&nbsp;
			<span style='color:rgba(151,187,205,1);' class='fa fa-shopping-cart'></span> Payed
			</small></div>";


			return $text;

		}

/*
		function status() // Status Panel in the admin area
		{

			$var[0]['icon'] 	= "<img src='".e_PLUGIN."vstore/images/vstore_16.png' alt='' />";
			$var[0]['title'] 	= "My Title";
			$var[0]['url']		= e_PLUGIN_ABS."vstore/vstore.php";
			$var[0]['total'] 	= 10;

			return $var;
		}
*/

		function latest() // Latest panel in the admin area.
		{
			$count = e107::getDb()->count('vstore_orders', '(*)', 'FIND_IN_SET(order_status, "N,P,H")');
			$var[0]['icon'] 	= "<img src='".e_PLUGIN."vstore/images/vstore_16.png' alt='' />";
			$var[0]['title'] 	= "Open orders";
			$var[0]['url']		= e_PLUGIN_ABS."vstore/admin_config.php?searchquery=&filter_options=order_status__open&mode=orders&action=list";
			$var[0]['total'] 	= $count;

			return $var;
		}


	}
