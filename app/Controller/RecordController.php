<?php

class RecordController extends AppController
{		
		var $paginate = array(
			'limit' => 5,
			'order' => array(
				'Record.id' => 'asc'
			)
		);

		public function index(){
			ini_set('memory_limit','256M');
			set_time_limit(0);
			
			$this->setFlash('Listing Record page too slow, try to optimize it.');
			
			$this->set('title',__('List Record'));
		}

		/**
		 * Check if ajax request and get the records
		 *
		 * @return void
		 */
		public function ajxGetRecords()
		{
			if (!$this->request->isAjax()) {
				$this->setFlash('Invalid request');
				return;
			}

			$this->autoRender = false;
			
			try {
				$data = $this->parseRequestData();
	
				$this->setPagination($data);
	
				$records = $this->paginate('Record');

				$total = $this->params['paging']['Record']['count'];
				echo $this->getPaginationData($data, $total, $records);
			} catch (\Exception $e) {
				echo $e->getMessage();
			}
			
		}

		/**
		 * Parse the request for datatables
		 *
		 * @return void
		 */
		private function parseRequestData()
		{
			$start = $this->request->data['iDisplayStart'];
			$limit = $this->request->data['iDisplayLength'];

			$data = [
				'sort_order' => $this->getSortOrder(),
				'limit' => $limit,
				'page' => ceil($start/$limit) + 1,
				'sEcho' => $this->request->data['sEcho'],
				'search' => $this->request->data['sSearch']
			];
			
			return $this->sanitizeData($data);
		}

		/**
		 * Check if column is name or id
		 *
		 * @param [int] $index
		 * @return string
		 */
		private function getColNameByIndex($index)
		{
			return $index ? 'name' : 'id';
		}

		/**
		 * Get the sort oder
		 *
		 * @return array
		 */
		private function getSortOrder()
		{
			$sortingCols = $this->request->data['iSortingCols'];

			if ($sortingCols > 0) {
				$order = [];
				for ($x = 0; $x < $sortingCols; $x++) {
					$colIndex = $this->request->data['iSortCol_' . $x];
					$colDir = $this->request->data['sSortDir_' . $x];
					$colName = $this->getColNameByIndex($colIndex);
					$order = array($colName => $colDir);
				}
			}

			return $order;
		}

		/**
		 * Set the pagination
		 *
		 * @param [array] $data
		 * @return void
		 */
		public function setPagination($data)
		{
			$this->paginate['order'] = $data['sort_order'];
			$this->paginate['limit'] = $data['limit'];
			$this->paginate['page'] = $data['page'];

			if (!empty($data['search'])) {
				$conditions = array(
					array(
						'OR' => array(
							'RECORD.id LIKE' => "%{$data['search']}%",
							'RECORD.name LIKE' => "%{$data['search']}%"
						)
					)
				);
				$this->paginate['conditions'] = $conditions;
			}
		}
		
		/**
		 * Build the pagination data to be used for datatables
		 *
		 * @param [array] $data
		 * @param [int] $total
		 * @param [array] $records
		 * @return stringZ
		 */
		public function getPaginationData($data, $total, $records)
		{
			$pagination = array(
				"sEcho" => $data['sEcho'],
				"iTotalRecords" => $total,
				"iTotalDisplayRecords" => $total,
				"aaData" => array()
			);

			if (!$total) {
				return json_encode($pagination);
			}

			foreach ($records as $record) {
				$pagination['aaData'][] = array(
					$record['Record']['id'],
					$record['Record']['name']
				);
			}

			return json_encode($pagination);
		}
		
// 		public function update(){
// 			ini_set('memory_limit','256M');
			
// 			$records = array();
// 			for($i=1; $i<= 1000; $i++){
// 				$record = array(
// 					'Record'=>array(
// 						'name'=>"Record $i"
// 					)			
// 				);
				
// 				for($j=1;$j<=rand(4,8);$j++){
// 					@$record['RecordItem'][] = array(
// 						'name'=>"Record Item $j"		
// 					);
// 				}
				
// 				$this->Record->saveAssociated($record);
// 			}
			
			
			
// 		}
	}