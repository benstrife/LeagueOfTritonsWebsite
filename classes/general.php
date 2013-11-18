<?php 
class General{
	#Check if the user is logged in.
	public function logged_in() {
		return(isset($_SESSION['id'])) ? true : false;
	}
       
}

?>