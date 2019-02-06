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
			$sql = e107::getDb();

			// Define colors for the chart
			$colors = array(
				'A' => '255,0,0',	// Red
				'B' => '0,255,0',	// Blue
				'C' => '0,0,255',	// Green
				'D' => '50,50,50',	// Gray
				'E' => '255,255,0', // Yellow
				'F' => '0,255,255',	// Cyan
				'G' => '255,0,255',	// Magenta
				'H' => '255,215,0',	// Gold
				'I' => '0,206,209', // Dark Turquoise
				'J' => '148,0,211',	// Dark Violet
			);

			// Define the chart types
			$opt_types = array(
				'amount' => 'Payed & Open orders',
			);
			


			$sc = e107::getScParser()->getScObject('vstore_shortcodes', 'vstore', false);

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

			// Get data for the top summary boxes
			$count_open = (int) $sql->retrieve('vstore_orders', 'count(order_id)', 'order_status != "C"');
			$count_orders_7 = (int) $sql->retrieve('vstore_orders', 'count(order_id)', sprintf('order_date >= %d', $date_7));
			$gross_7 = (double) $sql->retrieve('vstore_orders', 'SUM(order_pay_amount)', sprintf('order_date >= %d', $date_7));
			$gross_31 = (double) $sql->retrieve('vstore_orders', 'SUM(order_pay_amount)', sprintf('order_date >= %d', $date_31));



			$fields = '';
			// Make sure a chart type is defined (default = amount)
			$posted['chart_type'] = varset($posted['chart_type'], 'amount');

			$legend = array();

			// Configure chart type depending settings
			if ($posted['chart_type'] == 'amount')
			{
				// The data fields (must be named A, B, C, and so on)
				$fields = 'SUM(IF(order_status="C", order_pay_amount, 0)) AS A, SUM(IF(order_status!="C", order_pay_amount, 0)) AS B'; 
				// The legend references the fieldnames and their "readable name"
				$legend = array('A' => 'Payed', 'B' => 'Open');
				// Type of chart to display
				$chart_type = 'line';
				// Set y axis caption (value unit)
				$yAxis = $sc->getCurrencySymbol();
			}


			// Init labels and other vars
			$data = array('labels' => array());
			$start = $posted['chart_start'];
			$diff = $posted['chart_end'] - $posted['chart_start'];

			// define WHERE clause for sql query (date range)
			$where = 'order_date >= '.$posted['chart_start'] . ' AND order_date <= ' . ($posted['chart_end'] + (24 * 60 * 60));

			// Define the labels
			// Define GROUP BY and group column depending on the date range to display
			// Define the x axis caption
			if ($diff <= (7 * 24 * 60 * 60)) // <= 7 days (show days)
			{
				do
				{
					$data['labels'][]  = date('d', $start);
					$start = mktime(0,0,0, date('m', $start), date('d', $start)+1, date('Y', $start));
				} while($start < $posted['chart_end']);
				$data['labels'][]  = date('d', $start);
				$fields .= ', DAY(FROM_UNIXTIME(order_date)) AS `COL`';
				$groupby = ' GROUP BY DAY(FROM_UNIXTIME(order_date))';
				$xAxis = 'Day';
			}
			elseif ($diff <= (31 * 24 * 60 * 60)) // <= 31 days (show weeks)
			{
				do
				{
					$data['labels'][] = date('W', $start);
					$start = mktime(0,0,0, date('m', $start), date('d', $start)+7, date('Y', $start));
				} while($start < $posted['chart_end']);
				$data['labels'][] = date('W', $start);
				$fields .= ', WEEK(FROM_UNIXTIME(order_date), 1) AS COL';
				$groupby = ' GROUP BY WEEK(FROM_UNIXTIME(order_date), 1)';
				$xAxis = 'Week';
			}
			elseif ($diff <= (365 * 24 * 60 * 60)) // <= 1 year (show month)
			{
				do
				{
					$data['labels'][]  = date('n/Y', $start);
					$start = mktime(0,0,0, date('m', $start)+1, date('d', $start), date('Y', $start));
				} while($start < $posted['chart_end']);
				$data['labels'][]  = date('n/Y', $start);
				$fields .= ', CONCAT(MONTH(FROM_UNIXTIME(order_date)), "/", YEAR(FROM_UNIXTIME(order_date))) AS COL';
				$groupby = ' GROUP BY MONTH(FROM_UNIXTIME(order_date)), YEAR(FROM_UNIXTIME(order_date)) ORDER BY YEAR(FROM_UNIXTIME(order_date)), MONTH(FROM_UNIXTIME(order_date))';
				$xAxis = 'Month';
			}
			else // > 1 year (show years)
			{
				do
				{
					$data['labels'][]  = date('Y', $start);
					$start = mktime(0,0,0, date('m', $start), date('d', $start), date('Y', $start)+1);
				} while($start < $posted['chart_end']);
				$data['labels'][]  = date('Y', $start);
				$fields .= ', YEAR(FROM_UNIXTIME(order_date)) AS COL';
				$groupby = ' GROUP BY YEAR(FROM_UNIXTIME(order_date))';
				$xAxis = 'Year';
			}

			// Make sure the labels array contains unique values
			$data['labels'] = array_unique($data['labels']);

			// Fetch data from the database
			$dbdata = $sql->retrieve('vstore_orders', $fields, $where.' '.$groupby, true);

			// If data found in database
			if ($dbdata)
			{

				// Prepare dataset arary
				foreach(array_keys($legend) as $col)
				{
					$sets[$col] = array();
				}
				
				$i = 0;
				foreach ($dbdata as $value) {
					$k = $data['labels'][$i];
					if ($value['COL'] != $k)
					{
						// if this "column" is empty in the database
						// fill dataset with null values
						do
						{
							foreach(array_keys($legend) as $col)
							{
								$sets[$col][] = null;
							}
							$i++;
							$k = $data['labels'][$i];
						} while(!empty($k) && $value['COL'] != $k);
					}

					if ($value['COL'] == $k)
					{
						// add data for this colum to dataset
						foreach(array_keys($legend) as $col)
						{
							$sets[$col][] = $value[$col];
						}
					}

					$i++;
				}

				// If the number of labels and number of datasets do not match
				if (count($data['labels']) > count($sets['A']))
				{
					// Fill up the dataset with null values
					for($x = count($sets[0]); $x < count($data['labels']); $x++)
					{
						foreach(array_keys($legend) as $col)
						{
							$sets[$col][] = null;
						}
					}
				}

				// Define the chart dataset: colors, data
				foreach(array_keys($legend) as $col)
				{
					$data['datasets'][]	= array(
						'fillColor' 		=> "rgba(".$colors[$col].",0.3)",
						'strokeColor'  		=>  "rgba(".$colors[$col].",1)",
						'pointColor '  		=>  "#fff",
						'pointStrokeColor'  =>  "rgba(".$colors[$col].",1)",
						'data'				=> $sets[$col]	
					);
		
				}
		
				// CHART

				// Define chart options
				$options = array(
					'canvasBorders' => false,
					'bezierCurve' => false,
					'inGraphDataShow' => true,
					'pointDotRadius' => 5,
					'yAxisLabel' => $yAxis,
					'xAxisLabel' => $xAxis,
				);

				// Define chart and render it
				$cht = e107::getChart();
				$cht->setType(varset($chart_type, 'line'));
				$cht->setOptions($options);
				$cht->setData($data,'canvas');
				$chart = $cht->render('canvas');
				

				// Create a legend
				$chart .= '
				<div class="dwell text-center">
				';
				foreach(array_keys($legend) as $col)
				{
					$chart .= '
					<span>&nbsp;
						<i class="fa fa-line-chart" style="color: rgb('.$colors[$col].')"></i> '.$legend[$col].'&nbsp;
					</span>
					';
				}
				$chart .= '
				</div>
				';

				// Make sure the chart uses the whole space (width)
				e107::css('inline','canvas.e-graph {  width: 100% !important;  max-width: 100% !important;  height: auto !important; 	}');
			}
			else
			{
				$chart = e107::getMessage()->addWarning('No data for chart available!')->render();
			}


			// render page
			$text = $this->open('vstore-statistics','post', null, array('class'=>'form')) . '
			<div>
				<div class="row">
					<div class="panel panel-default col-6 col-xs-6 col-md-3">
						<a href="admin_config.php?filter_options=order_status__open&mode=orders&action=list">
						<div class="panel-body">
							Open orders
							<h4>'.number_format($count_open).'</h4>
						</div>
						</a>
					</div>				
					<div class="panel panel-default col-6 col-xs-6 col-md-3">
					<a href="admin_config.php?filter_options=datestamp__order_date__week&mode=orders&action=list">
						<div class="panel-body">
							Orders last 7 days
							<h4>'.number_format($count_orders_7).'</h4>
						</div>
					</a>
					</div>				
					<div class="panel panel-default col-6 col-xs-6 col-md-3">
					<a href="admin_config.php?filter_options=datestamp__order_date__week&mode=orders&action=list">
						<div class="panel-body">
							Gross sales last 7 days
							<h4>'.$sc->getCurrencySymbol() . number_format($gross_7, 2).'</h4>
						</div>
					</a>
					</div>				
					<div class="panel panel-default col-6 col-xs-6 col-md-3">
					<a href="admin_config.php?filter_options=datestamp__order_date__month&mode=orders&action=list">
						<div class="panel-body">
							Gross sales last 31 days
							<h4>'.$sc->getCurrencySymbol() . number_format($gross_31, 2).'</h4>
						</div>
					</a>
					</div>				
				</div>				

				<div class="row" style="margin-top: 10px;">
					<div class="col-sm-3">Type:</div>
					<div class="col-sm-9">'.$this->select('chart_type', $opt_types, $posted['chart_type']).'</div>
				</div>
				<div class="row">
					<div class="col-sm-3">Range:</div>
					<div class="col-sm-9">'
					.' '.$this->button('plus1', 'Yesterday', 'button', '', array('class' => 'btn-default vstore-range', 'data-value' => '1'))
					.' '.$this->button('plus7', 'Week', 'button', '', array('class' => 'btn-default vstore-range', 'data-value' => '7'))
					.' '.$this->button('plus31', 'Month', 'button', '', array('class' => 'btn-default vstore-range', 'data-value' => '31'))
					.' '.$this->button('plus365', 'Year', 'button', '', array('class' => 'btn-default vstore-range', 'data-value' => '365'))
					.'</div>
				</div>
				<div class="row">
					<div class="col-sm-3">From:</div>
					<div class="col-sm-9">'.$this->datepicker('chart_start', $posted['chart_start']).'</div>
				</div>
				<div class="row">
					<div class="col-sm-3">To:</div>
					<div class="col-sm-9">'.$this->datepicker('chart_end', $posted['chart_end']).'</div>
				</div>

				<div class="row" style="margin-top: 10px;">
					<div class="text-center">'.$this->button('chart_update', '<i class="fa fa-refresh" aria-hidden="true"></i> Update').'</div>
				</div>

				<div class="row vstore-chart" style="margin-top: 10px;">
					'.$chart.'
				</div>
			</div>
			'.$this->close();

			e107::js('footer-inline', '

			$(".vstore-range").click(function(e){
				e.preventDefault();
				var multiplier = parseInt($(this).data("value"), 10);
				if (!isNaN(multiplier))
				{
					var from = new Date('.strtotime(date('Y-m-d')).' * 1000);
					var to = new Date('.strtotime(date('Y-m-d')).' * 1000);
					if (multiplier > 0)
					{
						switch (multiplier)
						{
							case 1:
								from.setDate(from.getDate()-1);
								break;
							case 7:
								from.setDate(from.getDate()-7);
								break;
							case 31:
								from.setMonth(from.getMonth()-1);
								break;
							case 365:
								from.setFullYear(from.getFullYear()-1);
								break;
						}

						$("#chart-start").val(from.getTime() / 1000);
						$("#chart-end").val(to.getTime() / 1000);
						var format = $("#e-datepicker-chart-start").data("date-format");
						format = format.replace("yyyy", "yy");
						$("#e-datepicker-chart-start").val($.datepicker.formatDate(format, from));
						$("#e-datepicker-chart-end").val($.datepicker.formatDate(format, to));
					}
				}
			});

			');

			return $text;
		}
}

class vstore_statistics_form_ui extends e_admin_form_ui
{
}

?>

