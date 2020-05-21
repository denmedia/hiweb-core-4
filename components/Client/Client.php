<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 15.03.2018
	 * Time: 10:08
	 */

	namespace hiweb\components\Client;


	/**
	 * Class client
	 * @version 1.1
	 * @package hiweb
	 */
	class Client{

		/**
		 * @return Client
		 */
		static function get_instance(){
			static $instance;
			if(!$instance instanceof Client) $instance = new Client();
			return $instance;
		}

		public function get_os(){

			$R = false;

			$os_array = [
				'/windows nt 10/i' => 'Windows 10',
				'/windows nt 6.3/i' => 'Windows 8.1',
				'/windows nt 6.2/i' => 'Windows 8',
				'/windows nt 6.1/i' => 'Windows 7',
				'/windows nt 6.0/i' => 'Windows Vista',
				'/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
				'/windows nt 5.1/i' => 'Windows XP',
				'/windows xp/i' => 'Windows XP',
				'/windows nt 5.0/i' => 'Windows 2000',
				'/windows me/i' => 'Windows ME',
				'/win98/i' => 'Windows 98',
				'/win95/i' => 'Windows 95',
				'/win16/i' => 'Windows 3.11',
				'/macintosh|mac os x/i' => 'Mac OS X',
				'/mac_powerpc/i' => 'Mac OS 9',
				'/linux/i' => 'Linux',
				'/ubuntu/i' => 'Ubuntu',
				'/iphone/i' => 'iPhone',
				'/ipod/i' => 'iPod',
				'/ipad/i' => 'iPad',
				'/android/i' => 'Android',
				'/blackberry/i' => 'BlackBerry',
				'/webos/i' => 'Mobile'
			];

			foreach( $os_array as $regex => $value ){
				if( preg_match( $regex, $_SERVER['HTTP_USER_AGENT'] ) ) $R = $value;
			}

			return $R;
		}


		public function get_browser(){

			$browser = false;

			$browser_array = [
				'/msie/i' => 'Internet Explorer',
				'/firefox/i' => 'Firefox',
				'/safari/i' => 'Safari',
				'/chrome/i' => 'Chrome',
				'/edge/i' => 'Edge',
				'/opera/i' => 'Opera',
				'/netscape/i' => 'Netscape',
				'/maxthon/i' => 'Maxthon',
				'/konqueror/i' => 'Konqueror',
				'/mobile/i' => 'Handheld Browser'
			];

			foreach( $browser_array as $regex => $value ){
				if( preg_match( $regex, $_SERVER['HTTP_USER_AGENT'] ) ) $browser = $value;
			}

			return $browser;
		}


		public function get_os2(){
			if( isset( $_SERVER ) ){
				$agent = $_SERVER['HTTP_USER_AGENT'];
			} else {
				global $HTTP_SERVER_VARS;
				if( isset( $HTTP_SERVER_VARS ) ){
					$agent = $HTTP_SERVER_VARS['HTTP_USER_AGENT'];
				} else {
					global $HTTP_USER_AGENT;
					$agent = $HTTP_USER_AGENT;
				}
			}
			$ros[] = [ 'Windows XP', 'Windows XP' ];
			$ros[] = [ 'Windows NT 5.1|Windows NT5.1)', 'Windows XP' ];
			$ros[] = [ 'Windows 2000', 'Windows 2000' ];
			$ros[] = [ 'Windows NT 5.0', 'Windows 2000' ];
			$ros[] = [ 'Windows NT 4.0|WinNT4.0', 'Windows NT' ];
			$ros[] = [ 'Windows NT 5.2', 'Windows Server 2003' ];
			$ros[] = [ 'Windows NT 6.0', 'Windows Vista' ];
			$ros[] = [ 'Windows NT 7.0', 'Windows 7' ];
			$ros[] = [ 'Windows CE', 'Windows CE' ];
			$ros[] = [ '(media center pc).([0-9]{1,2}\.[0-9]{1,2})', 'Windows Media Center' ];
			$ros[] = [ '(win)([0-9]{1,2}\.[0-9x]{1,2})', 'Windows' ];
			$ros[] = [ '(win)([0-9]{2})', 'Windows' ];
			$ros[] = [ '(windows)([0-9x]{2})', 'Windows' ];
			// Doesn't seem like these are necessary...not totally sure though..
			//$ros[] = array('(winnt)([0-9]{1,2}\.[0-9]{1,2}){0,1}', 'Windows NT');
			//$ros[] = array('(windows nt)(([0-9]{1,2}\.[0-9]{1,2}){0,1})', 'Windows NT'); // fix by bg
			$ros[] = [ 'Windows ME', 'Windows ME' ];
			$ros[] = [ 'Win 9x 4.90', 'Windows ME' ];
			$ros[] = [ 'Windows 98|Win98', 'Windows 98' ];
			$ros[] = [ 'Windows 95', 'Windows 95' ];
			$ros[] = [ '(windows)([0-9]{1,2}\.[0-9]{1,2})', 'Windows' ];
			$ros[] = [ 'win32', 'Windows' ];
			$ros[] = [ '(java)([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,2})', 'Java' ];
			$ros[] = [ '(Solaris)([0-9]{1,2}\.[0-9x]{1,2}){0,1}', 'Solaris' ];
			$ros[] = [ 'dos x86', 'DOS' ];
			$ros[] = [ 'unix', 'Unix' ];
			$ros[] = [ 'Mac OS X', 'Mac OS X' ];
			$ros[] = [ 'Mac_PowerPC', 'Macintosh PowerPC' ];
			$ros[] = [ '(mac|Macintosh)', 'Mac OS' ];
			$ros[] = [ '(sunos)([0-9]{1,2}\.[0-9]{1,2}){0,1}', 'SunOS' ];
			$ros[] = [ '(beos)([0-9]{1,2}\.[0-9]{1,2}){0,1}', 'BeOS' ];
			$ros[] = [ '(risc os)([0-9]{1,2}\.[0-9]{1,2})', 'RISC OS' ];
			$ros[] = [ 'os/2', 'OS/2' ];
			$ros[] = [ 'freebsd', 'FreeBSD' ];
			$ros[] = [ 'openbsd', 'OpenBSD' ];
			$ros[] = [ 'netbsd', 'NetBSD' ];
			$ros[] = [ 'irix', 'IRIX' ];
			$ros[] = [ 'plan9', 'Plan9' ];
			$ros[] = [ 'osf', 'OSF' ];
			$ros[] = [ 'aix', 'AIX' ];
			$ros[] = [ 'GNU Hurd', 'GNU Hurd' ];
			$ros[] = [ '(fedora)', 'Linux - Fedora' ];
			$ros[] = [ '(kubuntu)', 'Linux - Kubuntu' ];
			$ros[] = [ '(ubuntu)', 'Linux - Ubuntu' ];
			$ros[] = [ '(debian)', 'Linux - Debian' ];
			$ros[] = [ '(CentOS)', 'Linux - CentOS' ];
			$ros[] = [ '(Mandriva).([0-9]{1,3}(\.[0-9]{1,3})?(\.[0-9]{1,3})?)', 'Linux - Mandriva' ];
			$ros[] = [ '(SUSE).([0-9]{1,3}(\.[0-9]{1,3})?(\.[0-9]{1,3})?)', 'Linux - SUSE' ];
			$ros[] = [ '(Dropline)', 'Linux - Slackware (Dropline GNOME)' ];
			$ros[] = [ '(ASPLinux)', 'Linux - ASPLinux' ];
			$ros[] = [ '(Red Hat)', 'Linux - Red Hat' ];
			// Loads of Linux machines will be detected as unix.
			// Actually, all of the linux machines I've checked have the 'X11' in the User Agent.
			//$ros[] = array('X11', 'Unix');
			$ros[] = [ '(linux)', 'Linux' ];
			$ros[] = [ '(amigaos)([0-9]{1,2}\.[0-9]{1,2})', 'AmigaOS' ];
			$ros[] = [ 'amiga-aweb', 'AmigaOS' ];
			$ros[] = [ 'amiga', 'Amiga' ];
			$ros[] = [ 'AvantGo', 'PalmOS' ];
			//$ros[] = array('(Linux)([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,3}(rel\.[0-9]{1,2}){0,1}-([0-9]{1,2}) i([0-9]{1})86){1}', 'Linux');
			//$ros[] = array('(Linux)([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,3}(rel\.[0-9]{1,2}){0,1} i([0-9]{1}86)){1}', 'Linux');
			//$ros[] = array('(Linux)([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,3}(rel\.[0-9]{1,2}){0,1})', 'Linux');
			$ros[] = [ '[0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,3})', 'Linux' ];
			$ros[] = [ '(webtv)/([0-9]{1,2}\.[0-9]{1,2})', 'WebTV' ];
			$ros[] = [ 'Dreamcast', 'Dreamcast OS' ];
			$ros[] = [ 'GetRight', 'Windows' ];
			$ros[] = [ 'go!zilla', 'Windows' ];
			$ros[] = [ 'gozilla', 'Windows' ];
			$ros[] = [ 'gulliver', 'Windows' ];
			$ros[] = [ 'ia archiver', 'Windows' ];
			$ros[] = [ 'NetPositive', 'Windows' ];
			$ros[] = [ 'mass downloader', 'Windows' ];
			$ros[] = [ 'microsoft', 'Windows' ];
			$ros[] = [ 'offline explorer', 'Windows' ];
			$ros[] = [ 'teleport', 'Windows' ];
			$ros[] = [ 'web downloader', 'Windows' ];
			$ros[] = [ 'webcapture', 'Windows' ];
			$ros[] = [ 'webcollage', 'Windows' ];
			$ros[] = [ 'webcopier', 'Windows' ];
			$ros[] = [ 'webstripper', 'Windows' ];
			$ros[] = [ 'webzip', 'Windows' ];
			$ros[] = [ 'wget', 'Windows' ];
			$ros[] = [ 'Java', 'Unknown' ];
			$ros[] = [ 'flashget', 'Windows' ];
			// delete next line if the script show not the right OS
			//$ros[] = array('(PHP)/([0-9]{1,2}.[0-9]{1,2})', 'PHP');
			$ros[] = [ 'MS FrontPage', 'Windows' ];
			$ros[] = [ '(msproxy)/([0-9]{1,2}.[0-9]{1,2})', 'Windows' ];
			$ros[] = [ '(msie)([0-9]{1,2}.[0-9]{1,2})', 'Windows' ];
			$ros[] = [ 'libwww-perl', 'Unix' ];
			$ros[] = [ 'UP.Browser', 'Windows CE' ];
			$ros[] = [ 'NetAnts', 'Windows' ];
			$file = count( $ros );
			$os = '';
			for( $n = 0; $n < $file; $n ++ ){
				if( preg_match( '/' . $ros[ $n ][0] . '/i', $agent, $name ) ){
					$os = @$ros[ $n ][1] . ' ' . @$name[2];
					break;
				}
			}
			return trim( $os );
		}


		/**
		 * @return mixed
		 */
		public function get_ip(){
			if( !empty( $_SERVER['HTTP_CLIENT_IP'] ) )   //check ip from share internet
			{
				$ip = $_SERVER['HTTP_CLIENT_IP'];
			} elseif( !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) )   //to check ip is pass from proxy
			{
				$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			} else {
				$ip = $_SERVER['REMOTE_ADDR'];
			}
			return $ip;
		}


		/**
		 * @return string
		 */
		public function get_id_OsIp(){
			return md5( self::get_ip().'-'.self::get_os2() );
		}


		/**
		 * @return bool
		 */
		public function is_webBot(){
			return (
				isset($_SERVER['HTTP_USER_AGENT'])
				&& preg_match('/bot|crawl|slurp|spider|mediapartners/i', $_SERVER['HTTP_USER_AGENT'])
			);
		}
		
		
		public function is_mobile($iPad_is_desktop = true){
			return wp_is_mobile() && ( !$iPad_is_desktop || preg_match('~Mozilla/5\.0\s?\(iPad;~i', $_SERVER['HTTP_USER_AGENT']) == 0);
		}

	}