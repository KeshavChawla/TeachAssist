<?php 

class Network{
	private $handle;

	public function __construct($cookiejar){
		$this->handle=curl_init();
		curl_setopt($this->handle, CURLOPT_VERBOSE, TRUE);
		// curl_setopt($this->handle, CURLOPT_HEADER, 1);
		curl_setopt($this->handle, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($this->handle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($this->handle, CURLOPT_COOKIEJAR, $cookiejar.'.cookie'); //there's probably a security hole in here somewhere
		curl_setopt($this->handle, CURLOPT_COOKIEFILE, $cookiejar.'.cookie'); //but teachassist seems to cover it, just reauth every time
	}  
	private function curl($method, $url, $params=array()){
		$params=http_build_query($params);
		if($method=='post'){
			curl_setopt($this->handle, CURLOPT_URL, $url);
			curl_setopt($this->handle, CURLOPT_POST, 1);
			curl_setopt($this->handle, CURLOPT_POSTFIELDS, $params);
		}else{
			curl_setopt($this->handle, CURLOPT_URL, $url.$params);
			curl_setopt($this->handle, CURLOPT_POST, 0);
			curl_setopt($this->handle, CURLOPT_POSTFIELDS, null);
		}
		return curl_exec($this->handle);
	}
	public function get($url, $params=array()){
		return $this->curl('get', $url, $params);
	}
	public function post($url, $params=array()){
		return $this->curl('post', $url, $params);
	}

	private function getCurlInfo($param){
		return curl_getinfo($this->handle, $param) ?: NULL;
	}
	public function getLastCurlUrl(){
		return $this->getCurlInfo(CURLINFO_EFFECTIVE_URL);
	}

}

?>