<?php
	function debug($var){
		if(Conf::$debug >0){
			$debug = debug_backtrace();

			echo '<p><a href="#"><strong>'.$debug[0]['file'].'</strong> l.'.$debug[0]['line'].'</a></p>';
			echo '<ol>';
			foreach ($debug as $key => $value) {
				if($key >0){
					echo "<li><strong>".$value['file']."</strong> l.".$value['line']."</li>";
				}
			}
			echo "</ol>";
			echo "<pre>";
			print_r($var);
			echo "</pre>";
		}
		
	}

?>