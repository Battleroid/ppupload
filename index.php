<?php

$self = basename(__FILE__);

function upload_file($image, $timed=false) {
	// adjust $target based on where you decide to keep files
	$notallowed = array('application/x-sh');
	$target = ($timed) ? 'view/timed' : 'view';
	$prot = $_SERVER['SERVER_PROTOCOL'];

	// get file information
	$info = pathinfo($image['name']);
	$name = uniqid() . "." . $info['extension'];
	while (file_exists($target . "/" . $name)) {
		$name = uniqid() . "." . $info['extension'];
	}
	$path = $target . "/" . $name;

	// check type
	if (in_array($image['type'], $notallowed)) {
		header($prot . ' 422 Wrong File Type', true, 422);
		return "Wrong file type.";
	}

	// path will need to be adjusted if you use something else
	$link = ($timed) ? "http://" . $_SERVER['HTTP_HOST'] . "/timed/" . $name : "http://" . $_SERVER['HTTP_HOST'] . "/" . $path;

	// save
	if (move_uploaded_file($image['tmp_name'], $path)) {
		// uncomment to enable redirection instead
		// return header('Location: ' . $link, true, 200);
		header($prot . ' ', true, 200);
		return $link;
	} else {
		header($prot . ' 500 Internal Server Error', true, 500);
		return "Something went wrong.";
	}
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_FILES['image'])) {
	$timed = (!empty($_POST['timed']) && $_POST['timed'] == '1') ? true : false;
	$result = upload_file($_FILES['image'], $timed);
}

if (isset($result)) {
	echo $result;
	die();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>ppupload</title>
</head>
<body>
	<form action="<?php echo $self; ?>" method="post" enctype="multipart/form-data">
		<input type="file" name="image">
		<label for="timed">Self Destructing?</label>
		<input type="checkbox" name="timed" value="1">
		<input type="submit" value="Upload">
	</form>
</body>
</html>
