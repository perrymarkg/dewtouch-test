<?php
/**
 * Typically for importing large datasets
 * the data must be split into chunks 
 * before importing and possibly should be handled via AJAX
 * to avoid hitting the timeout
 * 
 * due to time constraints I will be using ini_set('max_execution_time', 0) instead  
 * for this part of the test
 */
ini_set('max_execution_time', 0);

class MigrationImportController extends AppController
{
	public $paginate = array(
        'limit' => 10,
        'order' => array(
            'Transaction.id' => 'asc'
        )
	);
	
    public function index()
    {
		$this->setFlash('Please choose the file to import');
		$this->loadModel('Transaction');

		$this->doImport();

		$members = $this->paginate('Transaction');
		
		$this->set(compact('members'));
    }

    /**
	 * Proceed with import
	 *
	 * @return void
	 */
	private function doImport()
	{
		if (empty($this->data)) {
			return;
		}
		$fileRequest = $this->data['Migration']['file'];
		$tmp = $fileRequest['tmp_name'];
		
		$this->validateFile($fileRequest);
		$this->parseCSV($tmp);
		$this->doSave();

		$this->setSuccess('File uploaded successfuly!');
    }
    
    /**
	 * Convert the uploaded csv file to array and assign it to uploadData
	 * variable.
	 * 
	 * 
	 * @param [string] $file
	 * @return void
	 */
	private function parseCSV($file)
	{
		$handle = fopen($file, 'r'); 
		$head = array_map(array($this, 'to_'), fgetcsv($handle, 4096, ',', '"'));

		$ctr = 0;
		$data = array();
		while (($row = fgetcsv($handle, 4096, ",", '"')) !== FALSE) 
		{
			$row = array_combine($head, $row);
			$data[] = $this->parseRow($row);
		}
		// App\Controller\AppController
		$this->uploadData = $this->sanitizeData($data);
	}

	/**
	 * Parse row to model array
	 *
	 * @param array $row
	 * @return array
	 */
	private function parseRow($row)
	{
		$typeNo = $this->getTypeNo($row);
		$date = \DateTime::createFromFormat('d/m/Y', $row['date']);

		$modelData = 
			array(
				'member_data' => array(
					'Member' => array(
						'name' => $row['member_name'],
						'type' => $typeNo[0],
						'no' => $typeNo[1],
						'company' => !empty($row['member_company']) ? $row['member_company'] : NULL
					),
				),
				'transaction_data' => array(
					'Transaction' => array(
						'member_name' => $row['member_name'],
						'member_paytype' => $row['member_pay_type'],
						'member_company' => !empty($row['member_company']) ? $row['member_company'] : NULL,
						'date' => $date->format('Y-m-d'),
						'month' => $date->format('m'),
						'year' => $row['renewal_year'],
						'ref_no' => $row['ref_no'],
						'receipt_no' => $row['receipt_no'],
						'payment_method' => $row['payment_by'],
						'batch_no' => $row['batch_no'],
						'cheque_no' => !empty($row['cheque_no']) ? $row['cheque_no'] : NULL,
						'payment_type' => $row['payment_description'],
						'renewal_year' => $row['renewal_year'],
						'subtotal' => $row['subtotal'],
						'tax' => $row['totaltax'],
						'total' => $row['total'],
					),
				),
				'transaction_item_data' => array(
					'TransactionItem' => array(
						'description' => "Being Payment For: {$row['payment_description']} : {$row['renewal_year']}",
						'quantity' => 1,
						'unit_price' => $row['subtotal'],
						'sum' => $row['subtotal'],
						'table' => 'Member'
					)
				)

			);
		return $modelData;
	}
	/**
	 * Convert string to camel_case
	 *
	 * @param [string] $string
	 * @return string
	 */
	private function to_($string)
	{
		$string = strtolower($string);
		return str_replace(' ', '_', str_replace('.', '', $string));
	}

	/**
	 * Get The Type and No
	 *
	 * @param array $row
	 * @return array
	 */
	private function getTypeNo($row)
    {
        return explode(' ', $row['member_no']);
	}
	
 	private function doSave()
	{
		$this->loadModel('Member');
		$this->loadModel('TransactionItem');
		
		foreach ($this->uploadData as $data) {
			$member = $this->Member->save($data['member_data']);
			
			$data['transaction_data']
				['Transaction']
				['member_id'] = $this->Member->id;
			$this->Member
				->Transaction
				->save($data['transaction_data']);
			
			$data['transaction_item_data']
				['TransactionItem']
				['table_id'] = $this->Member->id;
			$data['transaction_item_data']
				['TransactionItem']
				['transaction_id'] = $this->Member->Transaction->id;
			$this->Member
				->Transaction
				->TransactionItem
				->save($data['transaction_item_data']);
			
			$this->Member->Transaction->TransactionItem->clear();
			$this->Member->Transaction->clear();
			$this->Member->clear();
		}
	}
    
    /**
	 * Check file type
	 *
	 * @param [array] $fileRequest
	 * @return void
	 */
	private function validateFile($fileRequest)
	{
		$allowed = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
		if (
			!is_file($fileRequest['tmp_name']) ||
			!in_array($fileRequest['type'], $allowed)
		) {
			throw new \Exception('Invalid file selected');
		}
	}

}