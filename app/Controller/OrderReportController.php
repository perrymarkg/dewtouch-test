<?php
	class OrderReportController extends AppController{

		public function index(){

			$this->setFlash('Multidimensional Array.');

			$this->loadModel('Order');
			$orders = $this->Order->find('all',array('conditions'=>array('Order.valid'=>1),'recursive'=>2));
		
			// debug($orders);exit;

			$this->loadModel('Portion');
			$portions = $this->Portion->find('all',array('conditions'=>array('Portion.valid'=>1),'recursive'=>2));
			// debug($portions);exit;

			$this->willDisplayArray($orders);
			
			// To Do - write your own array in this format
			$order_reports = array(
				"Order 1" => array(
					"Ingredient A" => 1.00,
					"Ingredient B" => 12.00,
					"Ingredient C" => 3.00,
					"Ingredient E" => 9.00,
					"Ingredient F" => 5.00,
					"Ingredient G" => 24.00,
					"Ingredient I" => 22.00,
				),
				"Order 2" => array(
					"Ingredient A" => 13.00,
					"Ingredient D" => 6.00,
					"Ingredient F" => 14.00,
					"Ingredient H" => 2.00,
				),
				"Order 3" => array(
					"Ingredient B" => 9.00,
					"Ingredient C" => 9.00,
					"Ingredient D" => 2.00,
					"Ingredient E" => 15.00,
					"Ingredient F" => 5.00,
				),
				"Order 4" => array(
					"Ingredient A" => 6.00,
					"Ingredient B" => 54.00,
					"Ingredient E" => 79.00,
					"Ingredient F" => 7.00,
					"Ingredient H" => 21.00,
					"Ingredient I" => 2.00,
				),
				"Order 5" => array(
					"Ingredient A" => 19.00,
					"Ingredient C" => 6.00,
					"Ingredient F" => 31.00,
					"Ingredient H" => 3.00,
				),
				"Order 6" => array(
					"Ingredient B" => 15.00,
					"Ingredient E" => 3.00,
				),
				"Order 7" => array(
					"Ingredient A" => 29.00,
					"Ingredient B" => 27.00,
					"Ingredient E" => 45.00,
					"Ingredient G" => 12.00,
					"Ingredient I" => 11.00,
				),
				"Order 8" => array(
					"Ingredient B" => 16.00,
					"Ingredient E" => 36.00,
					"Ingredient G" => 52.00,
					"Ingredient H" => 24.00,
					"Ingredient I" => 80.00,
				),
				"Order 9" => array(
					"Ingredient B" => 8.00,
					"Ingredient E" => 32.00,
					"Ingredient G" => 15.00,
					"Ingredient H" => 35.00,
					"Ingredient I" => 2.00,
					"Ingredient J" => 1.00,
				),
				"Order 10" => array(
					"Ingredient E" => 27.00,
					"Ingredient J" => 2.00,
				),
			);
			
			

			// ...

			$this->set('order_reports',$order_reports);

			$this->set('title',__('Orders Report'));
		}

		public function Question(){

			$this->setFlash('Multidimensional Array.');

			$this->loadModel('Order');
			$orders = $this->Order->find('all',array('conditions'=>array('Order.valid'=>1),'recursive'=>2));

			// debug($orders);exit;

			$this->set('orders',$orders);

			$this->loadModel('Portion');
			$portions = $this->Portion->find('all',array('conditions'=>array('Portion.valid'=>1),'recursive'=>2));
				
			// debug($portions);exit;

			$this->set('portions',$portions);

			$this->set('title',__('Question - Orders Report'));
		}

		/**
		 * Display orders array. Check view source for formatted output.
		 *
		 * @param [type] $orders
		 * @return void
		 */
		public function willDisplayArray($orders)
		{
			if(isset($this->request->query['array'])){
				$this->autoRender = false;
				$html ='';
				$html .= '$order_reports = array(' . "\r\n";
				foreach ($orders as $order) {
					
					$results = $this->getOrder($order['Order']['id']);
							$html .= "\t\t" . '"' . $order['Order']['name'] . '"' . " => array(\n";
						foreach ($results as $result) {
							
							
							$html .= "\t\t\t" . '"' . $result['ingredients']['ingredient'] . '"' . ' => ';
							$html .= $result[0]['final_total'] . ",\r\n";
						}
							$html .= "\t\t" . '),' . "\r\n";
				}
				$html .=  ')' . "\r\n";
				echo $html;
				return false;
			}
		}

		/**
		 * Quick get order data
		 *
		 * @param int $order_id
		 * @return void
		 */
		public function getOrder($order_id)
		{
			$query = "SELECT 
						order_details.order_id,
						order_details.item_id,
						order_details.quantity, 
						items.name, 
						portions.id AS portion_id,
						ingredients.*, 
						(ingredients.value * order_details.quantity) AS c_total, 
						SUM((ingredients.value * order_details.quantity)) AS final_total
					FROM `order_details`
					LEFT JOIN items ON order_details.item_id = items.id
					LEFT JOIN portions ON portions.item_id = items.id 
					LEFT JOIN (
						SELECT 
							portion_details.id as portion_details_id, 
							portion_details.portion_id, 
							portion_details.value, 
							portion_details.part_id, 
							portion_details.valid, parts.name AS ingredient 
						FROM `portion_details` 
						LEFT JOIN parts ON parts.id = portion_details.part_id
						) AS ingredients ON portions.id = ingredients.portion_id
					WHERE order_details.order_id = {$order_id} AND order_details.valid = 1 
					GROUP BY ingredients.ingredient
					ORDER BY ingredients.ingredient ASC";
			$db = ConnectionManager::getDataSource("default"); // name of your database connection
			return $db->fetchAll($query);
		}

	}