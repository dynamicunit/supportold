<?php
class token{
	public static function generate(){
		return session::put(config::get('session/token_name'),md5(uniqid()));
	}
	
	public static function check($token){
		$tokenname=config::get('session/token_name');
		if (session::exists($tokenname) && $token === Session::get($tokenname)){
			session::delete($tokenname);
			return true;
		}
		return false;
	}
}
?>
