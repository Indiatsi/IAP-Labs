<?php
session_start();
include 'User.php';
$user=null;
if(isset($_POST['btn-save'])){
	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	$city = $_POST['city_name'];
	$username = $_POST['username'];
	$password = $_POST['password'];

	$user = new User(ucwords(trim(strtolower($first_name))),ucwords(trim(strtolower($last_name))),strtoupper(trim($city)),ucwords(trim(strtolower($username))),$password);

	$validation_response = $user->validateForm();
	if(!$validation_response['ok']){
		$user->createFormErrorSessions($validation_response['msg']);
		echo "<script>location.replace('lab1.php')</script>";
		return;
	}

	$res = $user->save();
	echo "<script>location.replace('lab1.php')</script>";
	return;
}

$user = ($user===null) ? User::null_constructor() : $user;
$data = $user->readAll();
$user->logout();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>IAP Lab 2</title>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" type="image/png" href="https://res.cloudinary.com/dkgtd3pil/image/upload/v1554896611/other_data/pngtree_color_internet_programming_icon_internet_png_image_621325_icon.png">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/kt-2.5.0/r-2.2.2/datatables.min.css"/>
    <style type="text/css">
        .close{
            outline: none !important;
        }
        img{
            width: 100%;
            height: 315px;
        }
    </style>

    <script type="text/javascript" src="jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="timezone.js"></script>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/kt-2.5.0/r-2.2.2/datatables.min.js"></script>
	<!--End of Dependencies. Comment out the above lines to remove any styling and front-end validation used.-->

	<script src="validate.js"></script>

</head>
<body class="container-fluid p-0 mt-3" style="background: whitesmoke;min-width: 293px;">

	<!--Div to display information messages in materialize-css toast format-->
	<div id="info" style="display: inline-block;position: absolute;top: 5%;right: 5%;z-index: 100"></div>


	<!--Start of Assignment Work-->

	<!--Display Information i.e error or success messages. This works like the codeigniter flashdata.-->
	<?php
		if(array_key_exists('form_errors', $_SESSION)){ 
			echo '<div style="display: inline-block;position: absolute;top: 5%;right: 5%;z-index: 99">'.$_SESSION['form_errors'].'</div>';
			$_SESSION = array();
		}
	?>

	<div class="mt-3 row w-100 m-0">
		<div class="col-sm-12 col-md-5 col-lg-4 mb-4">
			<form method="post" onsubmit="return validateForm()" enctype="multipart/form-data">
				<table class="table table-borderless" align="center">
					<tr>
						<td align="center"><input type="text" name="username" class="form-control" placeholder="Username" required></td>
					</tr>
					<tr>
						<td align="center"><input type="text" name="first_name" class="form-control" placeholder="First Name" required></td>
					</tr>
					<tr>
						<td align="center"><input type="text" name="last_name" class="form-control" placeholder="Last Name" required></td>
					</tr>
					<tr>
						<td align="center"><input type="text" name="city_name" class="form-control" placeholder="City" required></td>
					</tr>
					<tr>
						<td align="center">
							<div>
							    <input type="file" class="custom-file-input" data-original="Upload a Profile Photo" id="customFile" name="fileToUpload" required>
							    Upload a Profile Photo
							</div>
						</td>
					</tr>
					<tr>
						<td align="center">
							<div class="input-group">
								<input type="password" name="password" class="form-control" placeholder="Password" required>
								<div class="input-group-append">
							</div>
						</td>
					</tr>
					<tr>
						<td align="center"><div class="row"><div class="col-12 col-sm-6 mb-3"><a href="login.php" class="btn btn-info btn-block"><strong>LOGIN</strong></a></div> <div class="col-12 col-sm-6"><button type="submit" class="btn btn-success btn-block" name="btn-save"><strong>SAVE</strong></button></div></td>
					</tr>
				</table>
			</form>
		</div>
		<div class="col-sm-12 col-md-7 col-lg-8">
			<!--Display Records in the Database-->
			<?php if($data){?>
				<table align="center" class="table table-borderless table-hover table-striped w-100" id="data">
					<thead class="table-secondary">
						<tr>
							<th>Username</th>
							<th>First Name</th>
							<th>Last Name</th>
							<th>City</th>
						</tr>
					</thead>
					<tbody>
						<?php while($row = $data->fetch_assoc()){?>
							<tr>
								<td><?=$row['username']?></td>
								<td><?=$row['first_name']?></td>
								<td><?=$row['last_name']?></td>
								<td><?=$row['user_city']?></td>
							</tr>
						<?php }?>
					</tbody>
				</table>
			<?php }else{?>
				<!--Display this if Database is Empty-->
				<div class="container">
					<div class="text-center text-muted" style="background: transparent;"><h3>No records to show here!</h3></div>
				</div>
			<?php }?>
		</div>
	</div>
	<!--End of Assignment work :)-->
</body>
</html>