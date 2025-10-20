<?php
class redirect{
	public static function to ($page = null){
		if (is_numeric($page)){
			switch ($page){
				case 404: 
					header('HTTP/1.0 404 Not Found');
					include 'includes/errors/404.php';
					exit();
					break;
			}
			
		}
		
		if ($page){
			header('Location: ' . $page);
			exit();
		}
		
	}
	
	public static function to_after_seconds ($page = null, $seconds = 10){
	    if ($page){
	        header( "refresh:5;url=wherever.php" );
	        header('Location: ' . $page);
	        exit();
	    }
	    
	}
}

?>

