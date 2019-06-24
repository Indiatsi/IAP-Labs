<?php
include 'Crud.php';
include 'Authenticator.php';
include 'DBConnector.php';
include 'FileUploader.php';
class User implements Crud,Authenticator{
	private $dbConn;
	private $user_id;
	private $first_name;
	private $last_name;
	private $city_name;
	private $username;
	private $password;

    function __construct() {
        $argv=func_get_args();
        switch (func_num_args()) {
            case 5:
                $this->construct1($argv[0], $argv[1], $argv[2], $argv[3], $argv[4], $argv[5], $argv[6]);
                break;
            default:
                break;
        }
    }

	public function construct1($first_name,$last_name,$city_name,$username,$password){
		$this->first_name = $first_name;
		$this->last_name = $last_name;
		$this->city_name = $city_name;
		$this->username = $username;
		$this->password = $password;
        $this->password = $password;
		$this->dbConn = new DBConnector();
	}

    public static function create()
    {
        // $instance = new self();
        // return $instance;

        $reflection = new ReflectionClass(__CLASS__);
        $instance = $reflection->newInstanceWithoutConstructor();

        return $instance;
    }

	public static function login_constructor($username,$password){
		$instance = new self(null,null,null,$username,$password);
		return $instance;
	}

	public static function null_constructor(){
		$instance = new self(null,null,null,null,null,null);
		return $instance;
	}

	public function setUsername($username){
		$this->username = $username;
	}

	public function getUsername(){
		return $this->username;
	}

	public function setPassword($password){
		$this->password = $password;
	}

	public function hashPassword(){
		$this->password = password_hash($this->password, PASSWORD_DEFAULT);
	}

	public function isPasswordCorrect(){
		if(!$this->username || !$this->password) return false;
		$sql = 'SELECT * FROM `user` WHERE `username` = "'.$this->username.'"';
		$res = mysqli_query($this->dbConn->conn, $sql) or die('ERROR: '.mysqli_error($this->dbConn->conn));
		if($res->num_rows>0){
			while($row = $res->fetch_assoc()){
				if(password_verify($this->password, $row['password'])){
					return true;
				}
			}
		}
		return false;
	}

	public function login(){
		if($this->isPasswordCorrect()){
			$_SESSION['username'] = ucwords(trim(strtolower($this->username)));
			header('Location:private_page.php');
		}else{
			return false;
		}
	}

	public function logout(){
		if(isset($_SESSION['username'])){
			unset($_SESSION['username']);	
		}
		return true;
	}

	public function getPassword(){
		return $this->password;
	}

	public function setUserId($user_id){
		$this->user_id = $user_id;
	}

	public function getUserId(){
		return $this->user_id;
	}

	public function save(){
		$this->hashPassword();
		$file_uploader = new FileUploader;
		$file = $file_uploader->upload_file();
		if(!$file['ok']){
			$this->createFormErrorSessions($file['msg']);
			return false;
		}
		$sql = 'INSERT into `user` (`first_name`,`last_name`,`user_city`,`username`,`password`,`profile`) VALUES ("'.$this->first_name.'","'.$this->last_name.'","'.$this->city_name.'","'.$this->username.'","'.$this->password.'","'.$file['file'].'")';
		$res = mysqli_query($this->dbConn->conn,$sql) or die('ERROR: '.mysqli_error($this->dbConn->conn));
		if($res){
			$this->createFormErrorSessions('<div class="toast" data-delay="3000"><div class="toast-body bg-success text-light"><strong>Success: </strong>Save Operation was Successful!</div></div>');
			return true;
		}else{
			$file_uploader->delete_file();
			$this->createFormErrorSessions('<div class="toast" data-delay="3000"><div class="toast-body bg-danger text-light"><strong>Error: </strong>An Error Occurred!</div></div>');
			return false;
		}
	}

	public function isUserExist(){
		$sql = 'SELECT * FROM `user` WHERE `username` = "'.$this->username.'"';
		$res = mysqli_query($this->dbConn->conn, $sql) or die('ERROR: '.mysqli_error($this->dbConn->conn));
		if($res->num_rows>0){
			return true;
		}
		return false;
	}

	public function readAll(){
        $this->dbConn = new DBConnector();

		$sql = "SELECT * from `user`";
		$res = mysqli_query($this->dbConn->conn,$sql);
		return $res->num_rows>0 ? $res:false;
	}

    public function readUserApiKey($user){
        $this->dbConn = new DBConnector();

        $res = mysqli_query($this->dbConn->conn, "SELECT api_key FROM api_keys WHERE user_id = '$user'") or die("Error " . mysqli_error($this->dbConn->conn));

        $this->dbConn->closeDatabase();

        if (mysqli_num_rows($res)) {
            return mysqli_fetch_array($res)['api_key'];
        }

        return false;
    }

	public function readUnique(){
		return null;
	}
	public function search(){
		return null;
	}
	public function update(){
		return null;
	}
	public function removeOne(){
		return null;
	}
	public function removeAll(){
		return null;
	}
	public function validateForm(){
		$data=[$this->first_name,$this->last_name,$this->city_name,$this->username];
		foreach($data as $single){
			if(strlen(trim($single))<1){
				return array('ok'=>false,'msg'=>'<div class="toast" data-delay="5000"><div class="toast-body bg-danger text-light"><strong>Error: </strong>Ensure You Have Filled all Fields!</div></div>');
			}
			if(preg_match('/[^a-z0-9 \'-]/i',$single)){
				return array('ok'=>false,'msg'=>'<div class="toast" data-delay="5000"><div class="toast-body bg-danger text-light"><strong>Error: </strong>Only Alphanumeric Characters are Required!</div></div>');
			}
		}
		if($this->isUserExist()){
			return array('ok'=>false,'msg'=>'<div class="toast" data-delay="5000"><div class="toast-body bg-danger text-light"><strong>Error: </strong>Username already registered!</div></div>');
		}
		if(!$this->password || strlen($this->password)<8 || !(preg_match('/[a-z]/',$this->password) && preg_match('/[A-Z]/',$this->password) && preg_match('/[0-9]/',$this->password))){
			return array('ok'=>false,'msg'=>'<div class="toast" data-delay="5000"><div class="toast-body bg-danger text-light"><strong>Error: </strong>Password must contain Lowercase, Uppercase and Numeric Characters and must be atleast 8 characters long!</div></div>');
		}
		return array('ok'=>true);
	}
	public function createFormErrorSessions($data){
		$_SESSION['form_errors'] = $data;
	}
}
?>