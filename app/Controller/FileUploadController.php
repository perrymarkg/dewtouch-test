<?php

// FileUpload seems to come from Mac
ini_set("auto_detect_line_endings", "1");

class FileUploadController extends AppController {

	protected $uploadData;

	public function index() {

		$this->set('title', __('File Upload Answer'));

		try {
			$this->doUpload();
		} catch (\Exception $e) {
			$this->setError('Error: ' . $e->getMessage());
		}

		$file_uploads = $this->FileUpload->find('all');
		$this->set(compact('file_uploads'));
	}

	/**
	 * Proceed with upload
	 *
	 * @return void
	 */
	private function doUpload()
	{
		if (empty($this->data)) {
			return;
		}
		$fileRequest = $this->data['FileUpload']['file'];
		$tmp = $fileRequest['tmp_name'];
		
		$this->validateFile($fileRequest);
		$this->parseCSV($tmp);
		$this->doSave();

		$this->setSuccess('File uploaded successfuly!');
	}

	/**
	 * Save the uploadData to the database
	 *
	 * @return void
	 */
	private function doSave()
	{
		$this->FileUpload->create();
		$this->FileUpload->saveAll($this->uploadData);
	}

	/**
	 * Convert the uploaded csv file to array and assign it to uploadData
	 * variable.
	 * 
	 * This always assumes the file contains the correct header labels [id, name]
	 * 
	 * @param [string] $file
	 * @return void
	 */
	private function parseCSV($file)
	{
		$handle = fopen($file, 'r'); 
		$head = array_map('strtolower', fgetcsv($handle, 4096, ',', '"'));

		$ctr = 0;
		$data = array();
		while (($row = fgetcsv($handle, 4096, ",", '"')) !== FALSE) 
		{
			$modelData = array();
			$modelData['FileUpload'] = array_combine($head, $row);;
			$data[] = $modelData;
		}
		// App\Controller\AppController
		$this->uploadData = $this->sanitizeData($data);
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