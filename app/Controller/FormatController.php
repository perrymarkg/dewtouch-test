<?php
	class FormatController extends AppController{
		
		public function q1(){
			
			$this->setFlash('Question: Please change Pop Up to mouse over (soft click)');
			
			if (!empty($this->request->data['Type'])) {
				if (empty($this->request->data['Type']['type'])) {
					$this->setError('You did not select a value'); // App\Controller\AppController
					return;
				}

				// Showing how to pass data to view instead of flash
				$this->Session->delete('Message');
				$selected = $this->request->data['Type']['type'];
				$this->set(compact('selected'));
			}

						
// 			$this->set('title',__('Question: Please change Pop Up to mouse over (soft click)'));
		}
		
		public function q1_detail(){

			$this->setFlash('Question: Please change Pop Up to mouse over (soft click)');
				
			
			
// 			$this->set('title',__('Question: Please change Pop Up to mouse over (soft click)'));
		}
		
	}