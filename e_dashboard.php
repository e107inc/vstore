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
					'caption'	=> e107::getParser()->toGlyph('fa-shopping-cart').' '.LAN_VSTORE_CUSM_041.'',
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
				return LAN_VSTORE_GEN_034;
			}

			$dbdata = array();
			while($row = $sql->fetch())
			{

				$dbdata['COL'][] = $row['COL'];
				$dbdata['A'][] = floatval($row['A']);
				$dbdata['B'][] = floatval($row['B']);

			}

			$this->title = LAN_VSTORE_GEN_032;
			$data = array();

			$data['labels'] = $dbdata['COL'];

			$data['datasets'][]	= array(
				'fillColor'			=> "rgba(220,220,220,0.5)",
				'strokeColor'		=> "rgba(220,220,220,1)",
				'pointColor '		=> "rgba(220,220,220,1)",
				'pointStrokeColor'	=> "#fff",
				'data'				=> $dbdata['A'],
				'title'				=> ''.LAN_VSTORE_CUSM_034.''
			);

			$data['datasets'][]	= array(
				'fillColor'			=> "rgba(151,187,205,0.5)",
				'strokeColor'		=> "rgba(151,187,205,1)",
				'pointColor '		=> "rgba(151,187,205,1)",
				'pointStrokeColor'	=> "#fff",
				'data'				=> $dbdata['B'],
				'title'				=> ''.LAN_VSTORE_CUSM_033.''
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
			<span style='color:rgba(220,220,220,1);' class='fa fa-shopping-cart'></span> ".LAN_VSTORE_CUSM_034."&nbsp;&nbsp;
			<span style='color:rgba(151,187,205,1);' class='fa fa-shopping-cart'></span> ".LAN_VSTORE_CUSM_033."
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
			$var[0]['icon'] 	= LAN_VSTORE_DSTAT_001;
			$var[0]['url']		= e_PLUGIN_ABS."vstore/admin_config.php?searchquery=&filter_options=order_status__open&mode=orders&action=list";
			$var[0]['total'] 	= $count;

			return $var;
		}

		public function revenue()
		{
			$cht = e107::getChart()->setProvider('google');
			$sql = e107::getDb();
			$id = 'vstore'.ucfirst(__FUNCTION__);

			$width='100%';
			$height = 400;

			$amt = array();

			$month_start = strtotime('first day of -12 months');
			$month_end = strtotime("last day of this month"); // strtotime('+9 months');

			if(!$sql->gen("SELECT * FROM #vstore_orders WHERE order_pay_status = 'complete' AND  order_date BETWEEN ".$month_start." AND ".$month_end."  "))
			{
				return "<div class='alert alert-block alert-info'>".LAN_VSTORE_DSTAT_002."</div>";
			}

			$total = 0;

			while($row = $sql->fetch())
			{

				$month      = date('Y-n', $row['order_date']); //  'Y-n' for monthly, 'Y-n-j' for daily.
			//	$num        = (int) 1; // $row['invoice_total'];
				$value      =  $row['order_pay_amount']; // ($num - $tax);
				$type       = $row['order_pay_status'];

				if(!isset($amt[$month][$type]))
				{
					$amt[$month][$type] = 0;
					$amt[$month]['total'] = 0;
				}

				$amt[$month][$type] += $value; // increment 'complete' or other type.

				if($type === 'complete')
				{
					$amt[$month]['total'] += $value;
				}

				$total += $value;
			}

			$sum = $total;

			$data = array();
			$data[0] = array('Day', "Complete", /*'Tentative', 'To Reschedule', 'Discounts Given'*/);
			$data[0][] = array('type'=>'string', 'label'=>'Total', 'role'=>'annotation', 'p'=> array('html'=>true));


		//	$this->title = 'Revenue ('.$sum.')';

			$range = $this->dateRange($month_start, $month_end, 'first day of next month', 'Y-n');

		//	e107::getDebug()->log($range);
		//	e107::getDebug()->log($amt);

			$c = 0;
		//	foreach($amt as $k=>$v)
			foreach($range as $unix => $k)
			{
				$diz = date("M 'y", $unix);
				$total = (float) varset($amt[$k]['total']);
				$data[] = array(
						(string) $diz,
						(int) varset($amt[$k]['complete']),
					/*	(int) $amt[$k]['pending'],
						(int) $amt[$k]['reschedule'],
						(int) $amt[$k]['discount'],*/

						(string) round($total), // annotation

						);

				$ticks[] = $k;
				$c++;
			}

		//	e107::getDebug()->log($data);
			$label = " ".LAN_VSTORE_DSTAT_003."";
			$label .= date('M Y', $month_start)." - ".date('M Y', $month_end)." (".number_format($sum).")";

			$options = array(
				'chartArea'	=>array('left'=>80, 'right'=>40, 'width'=>'100%', 'top'=>60, 'bottom'=>130),
				'legend'	=> array('position'=> 'top', 'alignment'=>'center', 'textStyle' => array('fontSize' => 11, 'color' => '#ccc')),
				'vAxis'		=> array('title'=>null, /*'minValue'=>0, 'maxValue'=>10,*/ 'titleFontSize'=>16, 'titleTextStyle'=>array('color' => '#ccc'), 'gridlines'=>array('color'=>'#696969', 'count'=>5), 'minorGridlines'=>array('color'=>'transparent', 'count'=>0), 'format'=>'short', 'textStyle'=>array('color' => '#ccc') ),
				'hAxis'		=> array('title'=>$label, 'slantedText'=>true, 'slantedTextAngle'=>60, 'ticks'=>$ticks, 'titleFontSize'=>14, 'titleTextStyle'=>array('color' => '#ccc'), 'gridlines' => array('color'=>'transparent'), 'textStyle'=>array('color' => '#ccc') ),
				'colors'	=> array('#5CB85C', '#f89406', '#5bc0de',  '#ee5f5b', '#ffffff'),
				'animation'	=> array('duration'=>1000, 'easing' => 'out'),
				'areaOpacity'	=> 0.8,
				'isStacked' => true,
				'annotations'   => array('textStyle'=> array('color'=>'white', 'fontSize' => 11), 'format'=>'short', 'alwaysOutside' => true, 'stem' => array('color' => 'transparent')),
				'backgroundColor' => array('fill' => 'transparent' )
			);

			$cht->setType('column');
			$cht->setOptions($options);
			$cht->setData($data);



			return "<div>".$cht->render($id, $width, $height)."</div>";


		}


		public function orders()
		{
			$cht = e107::getChart()->setProvider('google');
			$sql = e107::getDb();
			$id = 'vstore'.ucfirst(__FUNCTION__);

			$width='100%';
			$height = 400;

			$amt = array();

			$month_start = strtotime('first day of this month');
			$month_end = strtotime("last day of this month"); // strtotime('+9 months');

			if(!$sql->gen("SELECT * FROM #vstore_orders WHERE order_date BETWEEN ".$month_start." AND ".$month_end."  "))
			{
				return "<div class='alert alert-block alert-info'>".LAN_VSTORE_DSTAT_002."</div>";
			}

			$total = 0;

			while($row = $sql->fetch())
			{

				$month      = date('Y-n-j', $row['order_date']); //  'Y-n' for monthly, 'Y-n-j' for daily.
				$num        = (int) 1; // $row['invoice_total'];
				$value      =  $row['order_pay_amount']; // ($num - $tax);
				$type       = $row['order_status'];

				if(!isset($amt[$month][$type]))
				{
					$amt[$month][$type] = 0;
					$amt[$month]['total'] = 0;
				}

				$amt[$month][$type] += $num; // increment 'complete' or other type.
				$amt[$month]['total'] += $num;

			/*	if($type === 'complete')
				{
					$amt[$month]['total'] += $num;
				}*/

				$total += $num;
			}


			$sum = $total;

			$data = array();
			$data[0] = array('Day', "".LAN_VSTORE_GEN_018."", "".LAN_VSTORE_GEN_025."", "".LAN_VSTORE_GEN_028."", "".LAN_VSTORE_GEN_027."", "".LAN_VSTORE_GEN_026."", "".LAN_VSTORE_GEN_029."");
	//		$data[0][] = array('type'=>'string', 'label'=>'Total', 'role'=>'annotation', 'p'=> array('html'=>true));

			// Create items for each day of the month.
			$range = $this->dateRange($month_start, $month_end, '+1 day', 'Y-n-j');

			$c = 0;

			foreach($range as $unix=>$k)
			{
				$diz = date("jS", $unix);

				$data[] = array(
						(string) $diz,
						(int) varset($amt[$k]['N']),
						(int) varset($amt[$k]['P']),
						(int) varset($amt[$k]['C']),
						(int) varset($amt[$k]['H']),
						(int) varset($amt[$k]['X']),
						(int) varset($amt[$k]['R']),

						/*
						(int) $amt[$k]['reschedule'],
						(int) $amt[$k]['discount'],*/

					//	(string) round($amt[$k]['total']), // annotation

						);

				$ticks[] = $k;
				$c++;
			}

		//	e107::getDebug()->log($data);
			$label = "".LAN_VSTORE_STAT_010." ";
		//	$label .= date('M Y', $month_start)." - ".date('M Y', $month_end)." (".number_format($sum).")";

			$options = array(
				'chartArea'	=>array('left'=>80, 'right'=>40, 'width'=>'100%', 'top'=>60, 'bottom'=>130),
				'legend'	=> array('position'=> 'top', 'alignment'=>'center', 'textStyle' => array('fontSize' => 11, 'color' => '#ccc')),
				'vAxis'		=> array('title'=>null, /*'minValue'=>0, 'maxValue'=>10,*/ 'titleFontSize'=>16, 'titleTextStyle'=>array('color' => '#ccc'), 'gridlines'=>array('color'=>'#696969', 'count'=>5), 'minorGridlines'=>array('color'=>'transparent', 'count'=>0), 'format'=>'short', 'textStyle'=>array('color' => '#ccc') ),
				'hAxis'		=> array('title'=>$label, 'slantedText'=>true, 'slantedTextAngle'=>60, 'ticks'=>$ticks, 'titleFontSize'=>14, 'titleTextStyle'=>array('color' => '#ccc'), 'gridlines' => array('color'=>'transparent'), 'textStyle'=>array('color' => '#ccc') ),
				'colors'	=> array(
					'#337ab7', // New
					'#5bc0de', // Processing
					'#5CB85C', // Completed
					'#f89406', // On Hold
					'#ee5f5b', // Cancelled
					'#555555' // Refunded
					),
				'animation'	=> array('duration'=>1000, 'easing' => 'out'),
				'areaOpacity'	=> 0.8,
				'isStacked' => true,
				'annotations'   => array('textStyle'=> array('color'=>'white', 'fontSize' => 11), 'format'=>'short', 'alwaysOutside' => true, 'stem' => array('color' => 'transparent')),
				'backgroundColor' => array('fill' => 'transparent' )
			);

			$cht->setType('column');
			$cht->setOptions($options);
			$cht->setData($data);



			return "<div>".$cht->render($id, $width, $height)."</div>";


		}




	private function dateRange( $first, $last, $step = '+1 day', $format = 'Y-n-j' )
	{

		$dates = array();
		$current = $first;
		//$last = strtotime( $last );

		while( $current <= $last )
		{
			$dates[$current] = date( $format, $current );
			$current = strtotime( $step, $current );
		}

		return $dates;
	}

}
