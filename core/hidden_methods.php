<?php

	namespace hiweb\core;


	use hiweb\components\console\Console;
	use hiweb\components\Console\ConsoleFactory;


	trait hidden_methods{

		public static function __callStatic( $name, $arguments ){
			//console::debug_warn( 'Попытка вызова несуществующего статического метода', $name );
			if( method_exists( __CLASS__, $name ) ){
				if(!is_array($arguments)) $arguments = [$arguments];
				return call_user_func_array( [ __CLASS__, $name ], $arguments );
			} else
				ConsoleFactory::add( 'Static method Is Not Exists', 'warn', __METHOD__, [ $name, $arguments ], true );
		}


		public function __call( $name, $arguments ){
			if( method_exists( $this, $name ) ){
				if(!is_array($arguments)) $arguments = [$arguments];
				return call_user_func_array([$this, $name], $arguments);
			} else {
				ConsoleFactory::add( 'Method Is Not Exists', 'warn', __METHOD__, [ $name, $arguments ], true );
			}
		}


		public function __get( $name ){
			ConsoleFactory::add( 'Property Is Not Exists', 'warn', __METHOD__, [ $name ], true );
		}


		public function __set( $name, $value ){
			ConsoleFactory::add( 'Try det Not Exists Property is fail', 'warn', __METHOD__, [ $name ], true );
		}


	}