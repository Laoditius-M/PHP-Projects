<?php
$hn = 'localhost';
$un = 'root';
$pd = 'root';
$db = 'PHPPRACTICE';

$conn = new mysqli($hn,$un,$pd, $db);
if($conn -> connect_error){
	die("Error");
}

$fname = $lname = $email = $phoneNum = $password = $confirmPass = "";
$errFName = $errLName = $errEmail = $errPhoneNum = $errPassword = $errConPassword = "";
$validDetails = "";

if(isset($_POST['btnSignUp'])){
	if(isset($_POST['fName'])){
		$fname = get_post($conn,'fName');
	}
	if(isset($_POST['lName'])){
		$lname = get_post($conn,'lName');
	}
	if(isset($_POST['email'])){
		$email = get_post($conn,'email');
	}
	if(isset($_POST['phoneNum'])){
		$phoneNum = get_post($conn,'phoneNum');
	}
	if(isset($_POST['password'])){
		$password = get_post($conn,'password');
	}
	if(isset($_POST['confirmPass'])){
		$confirmPass = get_post($conn,'confirmPass');
	}
	
	$errFName = validate_fName($fname);
	$errLName = validate_lName($lname);
	$errEmail = validate_email($email);
	$errPhoneNum = validate_phoneNum($phoneNum);
	$errPassword = validate_password($password);
	$errConPassword = validate_password($confirmPass);
	
	$validDetails = $errFName.$errLName.$errEmail.$errPhoneNum.$errPassword.$errConPassword;
	echo "<!DOCTYPE html><html><head><title>PFM Signin</title>";
	if($validDetails=="")
	{
		if($password == $confirmPass){
		  $query1 = "SELECT email FROM users WHERE email LIKE '$email'";
		  $result1 = $conn->query($query1);
		  if(!$result1) die("Retrieval error");
		  $query2 = "SELECT password FROM users WHERE password LIKE '$password'";
		  $result2 = $conn->query($query2);
		  if(!$result2) die("Retrieval error");
		  $query3 = "SELECT phoneNum FROM users WHERE phoneNum LIKE '$phoneNum'";
		  $result3 = $conn->query($query3);
		  if(!$result3) die("Retrieval error");

		  $numRows1 = $result1->num_rows;
		  $numRows2 = $result2->num_rows;
		  $numRows3 = $result3->num_rows;
		  
		    if($numRows1 > 0) $errEmail = "Email Already exists";
		    if($numRows2 > 0) $errPassword = "Password already exists";
			if($numRows3 > 0) $errPhoneNum = "Phone Number already exists";
		 
		    
			
		    if($numRows1 == 0 && $numRows2 == 0 && $numRows3 == 0){
		       $query = "INSERT INTO users(firstName, lastName, email, phoneNum, password) 
		       VALUES('$fname','$lname','$email','$phoneNum', '$password')";
		       $result = $conn->query($query);
		
		       if(!$result)die("Insert Error");
		       else{
			   header("location:home.html");
			   exit;
		       }
		   }	  
		   
	    }else{
			$errPassword = "Passwords do not match";
			$errConPassword = "Passwords do not match";
		  }
	}
	
}



echo <<<_END
    <link rel="stylesheet" href="stylE.css">
</head>
<body>
    <section id="signupSect">
        <div class="container">
            <h1>Register</h1>
            <h4>Programmer From Mars</h4>
            <form method="POST">
                <div class="circle"></div>
                <div class="inputContainer">
                    <input type="text" value="$fname" class="input" placeholder="first name" name="fName">
					<p class="errMessage">$errFName</p>
                </div>
                <div class="inputContainer">
                    <input type="text" value="$lname" class="input" placeholder="last name" name="lName">
					<p class="errMessage">$errLName</p>
                </div>
                <div class="inputContainer">
                    <input type="text" value="$email" class="input" placeholder="email" name="email">
					<p class="errMessage">$errEmail</p>
                </div>
                <div class="inputContainer">
                    <input type="tel" value="$phoneNum" class="input" placeholder="phone number" name="phoneNum">
					<p class="errMessage">$errPhoneNum</p>
                </div>
                <div class="inputContainer">
                    <input type="password" class="input" placeholder="password" name="password">
					<p class="errMessage">$errPassword</p>
                </div>
                <div class="inputContainer">
                    <input type="password" class="input" placeholder="confirm password" name="confirmPass">
					<p class="errMessage">$errPassword</p>
                </div>
                <div class="inputContainer">
                    <input type="submit" class="signUpBtn transitionSpeed" value="Signup" name="btnSignUp">
                </div>
                <div class="memberBox">
                    <p>Already a member? <a href="#">Sign In</a></p>
                </div>
            </form>
        </div>
    </section>
</body>
</html>
_END;

function validate_fName($field){
	if($field == "") return "No first name was entered";
	else if(preg_match("/[^a-zA-Z]/",$field)){
		return "Only letters are allowed";
	}	
	return "";
}

function validate_lName($field){
	if($field == "") return "No last name was entered";
	else if(preg_match("/[^a-zA-Z]/",$field)){
		return "Only letters are allowed";
	}	
	return "";
}

function validate_email($field){
	if ($field == "")return "No Email was entered";
    else if (!((strpos($field, ".") > 0) &&
    (strpos($field, "@") > 0)) ||
     preg_match("/[^a-zA-Z0-9.@_-]/", $field))
    return "Email address is invalid";
 
    return "";
}

function validate_phoneNum($field){
	if($field == "") return "No Phone Number was entered";
    else if(preg_match("/[^0-9]/",$field)){
		return "Phone number is invalid";
	}	
	return "";
}

function validate_password($field){
	if($field == "") return "No Password was entered";
	else if(preg_match("/[^a-zA-z1-9@.]/",$field)){
		return "Only letters, numbers and @ are allowed";
	}	
	return "";
}

function get_post($conn, $field){
	return $conn->real_escape_string($_POST[$field]);
}
?>