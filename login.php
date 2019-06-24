<?php
session_start();
include 'User.php';
$user = null;
if(isset($_POST['btn-login'])){
	$username = $_POST['username'];
	$password = $_POST['password'];

	$user = User::login_constructor(trim(strtolower($username)),$password);

	if(!$user->isPasswordCorrect()){
		$user->createFormErrorSessions('<div class="toast" data-delay="5000"><div class="toast-body bg-danger text-light"><strong>Error: </strong>Invalid username or password!</div></div>');
		echo "<script>location.replace('login.php')</script>";
		return;
	}

	$res = $user->login();
	return;
}
$user = ($user===null) ? User::null_constructor() : $user;
$user->logout();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>IAP Lab 2</title>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
 
	<!--Load dependencies from CDNs. These are only for the purpose of front-end design (bootstrap) and validation (JQuery).-->
	<link rel="shortcut icon" type="image/png" href="https://res.cloudinary.com/dkgtd3pil/image/upload/v1554896611/other_data/pngtree_color_internet_programming_icon_internet_png_image_621325_icon.png">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/kt-2.5.0/r-2.2.2/datatables.min.css"/>

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

	<!--Headers to display developer information-->
	<div>
		<header class="d-block d-md-none">
			<div class="d-flex justify-content-end" style="position: relative;right: 1.5rem"><a data-toggle="collapse" class="text-info" href="#my-info">Developer Info</a></div>
			<div id="my-info" class="collapse">
				<div class="d-flex justify-content-around">
					<div class="text-muted text-center">101538</div>
					<div class="text-muted text-center">BICS Y3 S1 GB</div>
					<div class="text-muted text-center">IAP Lab 2</div>
				</div>
			</div>
		</header>
	</div>

	<!--Start of Assignment Work-->

	<!--Display Information i.e error or success messages. This works like the codeigniter flashdata.-->
	<?php
		if(array_key_exists('form_errors', $_SESSION)){ 
			echo '<div style="display: inline-block;position: absolute;top: 5%;right: 5%;z-index: 99">'.$_SESSION['form_errors'].'</div>';
			$_SESSION = array();
		}
	?>

	<div class="mb-4 mt-2 row w-100 m-0">
		<div class="col-sm-12 col-md-2 col-lg-4"></div>
		<div class="col-sm-12 col-md-8 col-lg-4">
			<form method="post" onsubmit="return validateLogin()">
				<table class="table table-borderless" align="center">
					<tr>
						<td align="center"><input type="text" name="username" class="form-control" placeholder="Username" required></td>
					</tr>
					<tr>
						<td align="center">
							<div class="input-group">
								<input type="password" name="password" class="form-control" placeholder="Password" required>
								<div class="input-group-append">
									<div class="input-group-text">
										<a href="" onclick="return viewPassword('a','[name=password]')" class="text-info"><i class="fas fa-eye"></i></a>
									</div>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td align="center"><div class="row"><div class="col-12 col-sm-6 mb-3"><a href="lab1.php" class="btn btn-info btn-block"><strong>SIGN UP</strong></a></div> <div class="col-12 col-sm-6"><button type="submit" class="btn btn-success btn-block" name="btn-login"><strong>LOGIN</strong></button></div></td>
					</tr>
				</table>
			</form>
		</div>
		<div class="col-sm-12 col-md-2 col-lg-4"></div>
	</div>
</body>
</html>