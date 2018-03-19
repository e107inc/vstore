<?php
/**
 * Adminarea module statistics
 */
class vstore_statistics_ui extends e_admin_ui
{

		protected $pluginTitle		= 'Vstore';
		protected $pluginName		= 'vstore';


		public function init()
		{
			$this->getRequest()->setAction('custom');
		}

		public function customPage()
		{
			$ns = e107::getRender();
			$sql = e107::getDb();
			$frm = e107::getForm();
			$dt = e107::getDate();

			require_once(e_PLUGIN.'vstore/vstore.class.php');
			$sc = new vstore_plugin_shortcodes();

			$posted = $this->getPosted();

			$date_0 = gmmktime(0,0,0, date('m'), date('d'), date('Y'));
			$date_7 = gmmktime(0,0,0, date('m'), date('d')-7, date('Y'));
			$date_31 = gmmktime(0,0,0, date('m'), date('d')-31, date('Y'));

			// correct start & end date
			if (isset($posted['chart_start']))
			{
				$h = date("H", $posted['chart_start']);
				$add = ($h > 0 ? 24-$h : 0) * 60 * 60;
				$posted['chart_start'] = strtotime(gmdate('Y-m-d', $posted['chart_start']+$add));
			}
			else
			{
				$posted['chart_start'] = $date_7;
			}
			
			if (isset($posted['chart_end']))
			{
				$h = date("H", $posted['chart_end']);
				$add = ($h > 0 ? 24-$h : 0) * 60 * 60;
				$posted['chart_end'] = strtotime(gmdate('Y-m-d', $posted['chart_end']+$add));
			}
			else
			{
				$posted['chart_end'] = $date_0;
			}

			if ($posted['chart_start'] > $posted['chart_end'])
			{
				$h = $posted['chart_start'];
				$posted['chart_start'] = $posted['chart_end'];
				$posted['chart_end'] = $h;
			}
			unset($h);


			$count_open = (int) $sql->retrieve('vstore_orders', 'count(order_id)', 'order_status != "C"');
			$count_orders_7 = (int) $sql->retrieve('vstore_orders', 'count(order_id)', sprintf('order_date >= %d', $date_7));
			$gross_7 = (double) $sql->retrieve('vstore_orders', 'SUM(order_pay_amount)', sprintf('order_status = "C" AND order_date >= %d', $date_7));
			$gross_31 = (double) $sql->retrieve('vstore_orders', 'SUM(order_pay_amount)', sprintf('order_status = "C" AND order_date >= %d', $date_31));

			$opt_types = array(
				'amount' => 'Payed & Open orders',
			);

			$fields = '';
			$posted['chart_type'] = varset($posted['chart_type'], 'amount');

			if ($posted['chart_type'] == 'amount')
			{
				$fields = 'SUM(IF(order_status="C", order_pay_amount, 0)) AS A, SUM(IF(order_status!="C", order_pay_amount, 0)) AS B'; 
				$legend = array('A' => 'Payed', 'B' => 'Open');
			}


			$data = array('labels' => array());
			$start = $posted['chart_start'];
			$diff = $posted['chart_end'] - $posted['chart_start'];

			$where = 'order_date >= '.$posted['chart_start'] . ' AND order_date <= ' . ($posted['chart_end'] + (24 * 60 * 60));


			if ($diff <= (7 * 24 * 60 * 60)) // <= 7 days
			{
				do
				{
					$data['labels'][]  = date('d', $start);
					$start = mktime(0,0,0, date('m', $start), date('d', $start)+1, date('Y', $start));
				} while($start < $posted['chart_end']);
				$data['labels'][]  = date('d', $start);
				$fields .= ', DAY(FROM_UNIXTIME(order_date)) AS C';
				$groupby = ' GROUP BY DATE(FROM_UNIXTIME(order_date))';
				$xAxis = 'Day';
			}
			elseif ($diff <= (31 * 24 * 60 * 60)) // <= 31 days
			{
				do
				{
					$data['labels'][] = date('W', $start);
					$start = mktime(0,0,0, date('m', $start), date('d', $start)+7, date('Y', $start));
				} while($start < $posted['chart_end']);
				$data['labels'][] = date('W', $start);
				$fields .= ', WEEK(FROM_UNIXTIME(order_date), 1) AS C';
				$groupby = ' GROUP BY WEEK(FROM_UNIXTIME(order_date), 1)';
				$xAxis = 'Week';
			}
			elseif ($diff <= (365 * 24 * 60 * 60)) // <= 1 year
			{
				do
				{
					$data['labels'][]  = date('n', $start);
					$start = mktime(0,0,0, date('m', $start)+1, date('d', $start), date('Y', $start));
				} while($start < $posted['chart_end']);
				$data['labels'][]  = date('n', $start);
				$fields .= ', MONTH(FROM_UNIXTIME(order_date)) AS C';
				$groupby = ' GROUP BY MONTH(FROM_UNIXTIME(order_date))';
				$xAxis = 'Month';
			}
			else // > 1 year
			{
				do
				{
					$data['labels'][]  = date('Y', $start);
					$start = mktime(0,0,0, date('m', $start), date('d', $start), date('Y', $start)+1);
				} while($start < $posted['chart_end']);
				$data['labels'][]  = date('Y', $start);
				$fields .= ', YEAR(FROM_UNIXTIME(order_date)) AS C';
				$groupby = ' GROUP BY YEAR(FROM_UNIXTIME(order_date))';
				$xAxis = 'Year';
			}

			$data['labels'] = array_unique($data['labels']);

			$dbdata = $sql->retrieve('vstore_orders', $fields, $where.' '.$groupby, true);

			$sets = array('A' => array(), 'B' => array());

			if ($dbdata)
			{
				$i = 0;
				foreach ($dbdata as $value) {
					$k = $data['labels'][$i];
					if ($value['C'] > $k)
					{
						do
						{
							$sets['A'][] = null;
							$sets['B'][] = null;
							$i++;
							$k = $data['labels'][$i];
						} while(!empty($k) && $value['C'] != $k);
					}

					if ($value['C'] == $k)
					{
						$sets['A'][] = $value['A'];
						$sets['B'][] = $value['B'];
					}

					$i++;
				}

				if (count($data['labels']) > count($sets[0]))
				{
					for($x = count($sets[0]); $x < count($data['labels']); $x++)
					{
						$sets['A'][] = null;
						$sets['B'][] = null;
					}
				}

				$colA = '88,255,88';
				$colB = '255,88,88';
			
				// CHART
				$cht = e107::getChart();
			
				$data['datasets'][]	= array(
									'fillColor' 		=> "rgba(".$colA.",0.3)",
									'strokeColor'  		=>  "rgba(".$colA.",1)",
									'pointColor '  		=>  "#fff",
									'pointStrokeColor'  =>  "rgba(".$colA.",1)",
									'data'				=> $sets['A']	
					
				);
				
				$data['datasets'][]	= array(
									'fillColor' 		=> "rgba(".$colB.",0.3)",
									'strokeColor'  		=>  "rgba(".$colB.",1)",
									'pointColor '  		=>  "#fff",
									'pointStrokeColor'  =>  "rgba(".$colB.",1)",
									'data'				=> $sets['B']		
				);
		
				$options = array(
					//'title' => $opt_types[$posted['chart_type']],
					'canvasBorders' => false,
					'bezierCurve' => false,
					'inGraphDataShow' => true,
					'pointDotRadius' => 5,
					// 'legendBorders' => true,
					'yAxisLabel' => $sc->getCurrencySymbol(),
					'xAxisLabel' => $xAxis,
				);
				$cht = e107::getChart();
				$cht->setType('line');
				$cht->setOptions($options);
				$cht->setData($data,'canvas');
				$chart = $cht->render('canvas');
				

				$chart .= '<div class="dwell text-center"><i class="fa fa-line-chart"  style="color: rgb('.$colB.')"></i> '.$legend['A'];
				$chart .= '&nbsp;&nbsp;&nbsp;<i class="fa fa-line-chart"  style="color: rgb('.$colA.')"></i> '.$legend['B'].'</div>';

				e107::css('inline','canvas.e-graph {  width: 100% !important;  max-width: 100% !important;  height: auto !important; 	}');
			}
			else
			{
				$chart = e107::getMessage()->addWarning('No data for chart awailable!')->render();
			}



			$text = $frm->open('vstore-statistics','post', null, array('class'=>'form')) . '
			<div>
				<div class="row">
					<div class="panel panel-default col-6 col-xs-6 col-md-3">
						<div class="panel-body">
							Open orders
							<h4>'.number_format($count_open).'</h4>
						</div>
					</div>				
					<div class="panel panel-default col-6 col-xs-6 col-md-3">
						<div class="panel-body">
							Orders last 7 days
							<h4>'.number_format($count_completed).'</h4>
						</div>
					</div>				
					<div class="panel panel-default col-6 col-xs-6 col-md-3">
						<div class="panel-body">
							Gross sales last 7 days
							<h4>'.$sc->getCurrencySymbol() . number_format($gross_7, 2).'</h4>
						</div>
					</div>				
					<div class="panel panel-default col-6 col-xs-6 col-md-3">
						<div class="panel-body">
							Gross sales last 31 days
							<h4>'.$sc->getCurrencySymbol() . number_format($gross_31, 2).'</h4>
						</div>
					</div>				
				</div>				

				<div class="row" style="margin-top: 10px;">
					<div class="col-sm-3">Type:</div>
					<div class="col-sm-9">'.$frm->select('chart_type', $opt_types, $posted['chart_type']).'</div>
				</div>
				<div class="row">
					<div class="col-sm-3">From:</div>
					<div class="col-sm-9">'.$frm->datepicker('chart_start', $posted['chart_start']).'</div>
				</div>
				<div class="row">
					<div class="col-sm-3">To:</div>
					<div class="col-sm-9">'.$frm->datepicker('chart_end', $posted['chart_end']).'</div>
				</div>

				<div class="row" style="margin-top: 10px;">
					<div class="text-center">'.$frm->button('chart_update', 'Update').'</div>
				</div>

				<div class="row chart">
					'.$chart.'
				</div>
			</div>
			'.$frm->close();

			return $ns->tablerender(null, $text);
		}
}

class vstore_statistics_form_ui extends e_admin_form_ui
{
}

?>

