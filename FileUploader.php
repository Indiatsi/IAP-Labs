<?php
class FileUploader {
	private static $target_directory = "uploads/";
	private static $size_limit = 51200;
	private $upload_ok = false;
	private $file_original_name;
	private $file_type;
	private $file_size;
	private $final_file_name;

	public function set_original_name($name){
		$this->file_original_name = $name;
	}

	public function get_original_name(){
		return $this->file_original_name;
	}

	public function set_file_type($type){
		$this->file_type = $type;
	}

	public function get_file_type(){
		return $this->file_type;
	}

	public function set_file_size($size){
		$this->file_size = $size;
	}

	public function get_file_size(){
		return $this->file_size;
	}

	public function set_final_name($name){
		$this->final_file_name = $name;
	}

	public function get_final_name(){
		return $this->final_file_name;
	}

	public function upload_file(){
		$upload_ok = $this->file_was_selected();
		if(!$upload_ok) return array('ok'=>false,'msg'=>'<div class="toast" data-delay="5000"><div class="toast-body bg-danger text-light"><strong>Error: </strong>Please Select a File to Upload!</div></div>');

		$this->set_original_name($_FILES['fileToUpload']['name']);
		$this->set_final_name($_FILES['fileToUpload']['name']);
		$this->set_file_type($_FILES['fileToUpload']['type']);
		$this->set_file_size($_FILES['fileToUpload']['size']);
		
		$upload_ok = !$this->file_already_exists();
		if(!$upload_ok) return array('ok'=>false,'msg'=>'<div class="toast" data-delay="5000"><div class="toast-body bg-danger text-light"><strong>Error: </strong>File to Upload Already Exists!</div></div>');
		
		$upload_ok = $this->file_size_is_correct();
		if(!$upload_ok) return array('ok'=>false,'msg'=>'<div class="toast" data-delay="5000"><div class="toast-body bg-danger text-light"><strong>Error: </strong>File Size Exceeds 50kb!</div></div>');

		$upload_ok = $this->file_type_is_correct();
		if(!$upload_ok) return array('ok'=>false,'msg'=>'<div class="toast" data-delay="5000"><div class="toast-body bg-danger text-light"><strong>Error: </strong>File Type Must be png or jpeg Format!</div></div>');
		return array('ok'=>true,'file'=>FileUploader::$target_directory.$this->final_file_name);
	}
	public function file_already_exists(){
		return file_exists(FileUploader::$target_directory.$this->final_file_name);
	}

	public function save_file_path_to($path){
		FileUploader::$target_directory = $path;
	}

	public function delete_file(){
		if(file_already_exists()){
			unlink(FileUploader::$target_directory.$this->final_file_name) or die("Unable to Delete File.");
		}
		return true;
	}

	public function file_type_is_correct(){
		$accepted_types = array('image/png','image/jpeg');
		return in_array($this->file_type, $accepted_types);
	}

	public function file_size_is_correct(){
		return $this->file_size < FileUploader::$size_limit;
	}

	public function file_was_selected(){
		return (sizeof($_FILES)===1 && isset($_FILES['fileToUpload']));
	}
}
?>