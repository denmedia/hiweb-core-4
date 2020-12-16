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
	 * @version 1.3
	 * @package hiweb
	 */
	class Client{

		/**
		 * @return Client
		 */
		static function get_instance(): Client {
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


		public function get_os2(): string {
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
		public function get_id_OsIp(): string {
			return md5( self::get_ip().'-'.self::get_os2() );
		}


		/**
		 * @return bool
		 */
		public function is_webBot(): bool {
			return (
				isset($_SERVER['HTTP_USER_AGENT'])
				&& preg_match('/bot|crawl|slurp|spider|mediapartners|chrome-lighthouse|gtmetrix/i', $_SERVER['HTTP_USER_AGENT'])
			);
		}


        /**
         * @param bool $iPad_is_desktop
         * @return bool
         * @version 1.1
         */
		public function is_mobile($iPad_is_desktop = true): bool {
            $is_mobile = function_exists('wp_is_mobile') ? wp_is_mobile() : preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$_SERVER['HTTP_USER_AGENT'])||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($_SERVER['HTTP_USER_AGENT'],0,4));
            return $is_mobile && ( !$iPad_is_desktop || preg_match('~Mozilla/5\.0\s?\(iPad;~i', $_SERVER['HTTP_USER_AGENT']) == 0);
		}


        /**
         * Return TRUE if browser (client) is support WebP image format
         * @return bool
         */
		public function is_support_WebP(): bool {
		    return (strpos( $_SERVER['HTTP_ACCEPT'], 'image/webp' ) !== false || strpos( $_SERVER['HTTP_USER_AGENT'], ' Chrome/' ) !== false);
        }

	}