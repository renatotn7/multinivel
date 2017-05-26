<?php

function __autoload($className){
	 if(file_exists(BASEPATH.'libraries'.DIRECTORY_SEPARATOR.strtolower($className).EXT)){
		  require(BASEPATH.'libraries'.DIRECTORY_SEPARATOR.strtolower($className).EXT);
		 }else if(file_exists(BASEPATH.'libraries'.DIRECTORY_SEPARATOR.$className.EXT)){
			 require(BASEPATH.'libraries'.DIRECTORY_SEPARATOR.$className.EXT);
			 }
	
	}