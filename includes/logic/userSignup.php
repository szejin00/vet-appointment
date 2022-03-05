<?php include(INCLUDE_PATH . "/logic/common_functions.php"); ?>
<?php
// variable declaration
$username = "";
$email  = "";
$role_id = 2;
$errors  = [];
// SIGN UP USER
if (isset($_POST['signup_btn'])) {
	// validate form values
	$errors = validateUser($_POST, ['signup_btn']);

	// receive all input values from the form. No need to escape... bind_param takes care of escaping
	$username = $_POST['username'];
	$email = $_POST['email'];
	$password = password_hash($_POST['password'], PASSWORD_DEFAULT); //encrypt the password before saving in the database
	$created_at = date('Y-m-d H:i:s');

	// if no errors, proceed with signup
	if (count($errors) === 0) {
		// insert user into database
		$query = "INSERT INTO users SET username=?, role_id=2, email=?, password=?, created_at=?";
		$stmt = $mysqli->prepare($query);
		$stmt->bind_param('ssss', $username, $email, $password, $created_at);
		$result = $stmt->execute();


		if ($result) {
			$user_id = $stmt->insert_id;
			$stmt->close();
			$ewallet = "INSERT INTO ewallet SET user_id=?, total=0";
			$new = $mysqli->prepare($ewallet);
			$new->bind_param('i', $user_id);
			$new->execute();
			$new->close();
			loginById($user_id); // log user in
		} else {
			$_SESSION['error_msg'] = "Database error: Could not register user";
		}


	}
}

if (isset($_POST['login_btn'])) {
	// validate form values
	$errors = validateUser($_POST, ['login_btn']);
	$username = $_POST['username'];
	$password = $_POST['password']; // don't escape passwords.

	if (empty($errors)) {
		$sql = "SELECT * FROM users WHERE username=? OR email=? LIMIT 1";
		$user = getSingleRecord($sql, 'ss', [$username, $username]);

		if (!empty($user)) { // if user was found
			if (password_verify($password, $user['password'])) { // if password matches
				// log user in
				loginById($user['id']);
			} else { // if password does not match
				$_SESSION['error_msg'] = "Wrong credentials";
			}
		} else { // if no user found
			$_SESSION['error_msg'] = "Wrong credentials";
		}
	}
}
