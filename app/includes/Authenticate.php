<?php
// authentication class
class Authenticate
{

	// database handle
	private $dbh;

	// constructor assigns connection
	public function __construct(PDO $dbh)
	{
		$this->dbh = $dbh;
		session_regenerate_id(true);
	}

	// loges user in
	public function login($email, $password)
	{
		/*
			note: never give away too much
			information when invalid credentials
			are provided.
		*/

		if (!$this->validEmail($email)) {
			echo 'Invalid Email or Password';
			return;
		}

		$stmt = $this->dbh->prepare(
									 'SELECT `id`, `email`, `password`, `salt`, `fname`, `lname`
									  FROM `users`
									  WHERE email = :email'
									);

		$stmt->execute(array(':email' => $email));

		if ($stmt->rowCount() > 0) {
			$result = $stmt->fetch(PDO::FETCH_ASSOC);

			// check for correct password
			if (!$this->passwordVerify($password, $result['password'], $result['salt'])) {
				echo 'Invalid Email or Password';
				return;
			}

			// populate session data if password is correct
			$_SESSION['id'] = $result['id'];
			$_SESSION['email'] = $result['email'];
			$_SESSION['fname'] = $result['fname'];
			$_SESSION['lname'] = $result['lname'];
			$_SESSION['islogedin'] = true;
			header('Location: account.php');
			die();

		} else {
			echo 'Invalid Email or Password';
			return;
		}

	}

	// validate email
	private function validEmail($email)
	{
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return false;
		}
		return true;
	}

	// log out user
	public function logout()
	{
		$this->sessionDestroy();
	}

	// destroy session data
	public function sessionDestroy()
	{
		$_SESSION = array();

		if (ini_get("session.use_cookies")) {
    		$params = session_get_cookie_params();
    		setcookie(session_name(), '', time() - 42000,
        		$params["path"], $params["domain"],
        		$params["secure"], $params["httponly"]
    			);
		}
		
		session_destroy();
	}

	// creates new user and stores data in database
	public function createAccount($email, $password, $fname, $lname)
	{
		if (!$this->validEmail($email)) {
			echo 'Invalid email.';
			return;
		} elseif ($this->emailExists($email)) {
			echo 'Email already in use, please use another.';
			return;
		}
		
		$pass_plus_salt = $this->passwordHash($password);
		$pass = $pass_plus_salt[0];
		$salt = $pass_plus_salt[1];

		$stmt = $this->dbh->prepare(
							 		'INSERT INTO `users`
							  		(email, password, salt, fname, lname, usertype, active)
							  		VALUES
							 		 (:email, :password, :salt, :fname, :lname, :usertype, :active)'
								   );
		$stmt->execute(array(

			':email' 		=> $email,
			':password'		=> $pass,
			':salt'			=> $salt,
			':fname'		=> $fname,
			':lname'		=> $lname,
			':usertype'		=> 'regular',
			':active'		=> 1
		));

		echo 'Sign up complete.';

	}

	// delete account
	public function deleteAccount($user_id)
	{
		$stmt = $this->dbh->prepare('DELETE FROM `users` WHERE `id` = :id');
		if ($stmt->execute(array(':id' => $user_id))) {
			$this->sessionDestroy();
			return true;
		}
		return false;
	}

	// deactivae account
	public function deactivateAccount()
	{
		$stmt = $this->dbh->prepare('UPDATE `users` SET `active` = 0 WHERE id = :id');
		$stmt->execute(array(':id' => $_SESSION['id']));

		if ($stmt->rowCount() > 0) {
			echo 'Account deactivated';
		}
	}

	// activate account
	public function activateAccount()
	{
		$stmt = $this->dbh->prepare('UPDATE `users` SET `active` = 1 WHERE id = :id');
		$stmt->execute(array(':id' => $_SESSION['id']));

		if ($stmt->rowCount() > 0) {
			echo 'Account activated';
		}
	}

	// see if email is taken
	private function emailExists($email)
	{
		$stmt = $this->dbh->prepare('SELECT `email` FROM `users` WHERE `email` = :email');
		$stmt->execute(array(':email' => $email));

		if ($stmt->rowCount() > 0) {
			return true;
		}
		return false;
	}

	// hash password with salt
	private function passwordHash($password)
	{
		$salt = bin2hex(openssl_random_pseudo_bytes(8));
		$pass = crypt($password, $salt);

		return array($pass, $salt);
	}

	// verify password
	private function passwordVerify($plain, $encrypted, $salt)
	{
		if (crypt($plain, $salt) === $encrypted) {
			return true;
		}
		return false;
	}

	// change/edit name
	public function changeName($fname, $lname, $user_id)
	{
		$stmt = $this->dbh->prepare('UPDATE `users` SET fname = :fname, lname = :lname WHERE id = :id');
		$result = $stmt->execute(array(':fname' => $fname, ':lname' => $lname, ':id' => $_SESSION['id']));

		if ($result) {
			$_SESSION['fname'] = $fname;
			$_SESSION['lname'] = $lname;

			return array('fname' => $fname, 'lname' => $lname);
		}
		return false;
	}

	// 	change/edit password
	public function changePassword($pword)
	{
		$pass = $this->passwordHash($pword);
		$password = $pass[0];
		$salt = $pass[1];
		$stmt = $this->dbh->prepare('UPDATE `users` SET `password` = :password, `salt` = :salt WHERE id = :id');
		$result = $stmt->execute(array(':password' => $password, ':salt' => $salt, ':id' => $_SESSION['id']));

		if ($result) {
			return true;
		}
		return false;
	}

}