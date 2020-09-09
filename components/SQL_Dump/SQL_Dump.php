<?php
	
	namespace hiweb\components\SQL_Dump;
	
	
	class SQL_Dump{
		
		static function full_dump_to_file($dump_path){
			
			ini_set( 'display_errors', 1 );
			ini_set( 'display_startup_errors', 1 );
			error_reporting( E_ALL );
			
			$database = DB_NAME;
			$user = DB_USER;
			$pass = DB_PASSWORD;
			$host = DB_HOST;
			$dir = $dump_path; //WP_CONTENT_DIR . '/dump.sql';
			
			echo "<h3>Backing up database to `<code>{$dir}</code>`</h3>";
			
			exec( "mysqldump --user={$user} --password={$pass} --host={$host} {$database} --result-file={$dir} 2>&1", $output );
			
			var_dump( $output );
			die;
		}
		
	}