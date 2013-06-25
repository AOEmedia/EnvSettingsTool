<?php

/**
 * Cli output
 *
 * @author Fabrizio Branca
 */
class Est_CliOutput {

	private static $foreground_colors = array(
		'black' 		=> '0;30',
		'dark_gray' 	=> '1;30',
		'blue' 			=> '0;34',
		'light_blue' 	=> '1;34',
		'green' 		=> '0;32',
		'light_green' 	=> '1;32',
		'cyan' 			=> '0;36',
		'light_cyan' 	=> '1;36',
		'red' 			=> '0;31',
		'light_red' 	=> '1;31',
		'purple' 		=> '0;35',
		'light_purple' 	=> '1;35',
		'brown' 		=> '0;33',
		'yellow' 		=> '1;33',
		'light_gray' 	=> '0;37',
		'white' 		=> '1;37'
	);

	private static $background_colors = array(
		'black' 		=> '40',
		'red' 			=> '41',
		'green' 		=> '42',
		'yellow' 		=> '43',
		'blue' 			=> '44',
		'magenta' 		=> '45',
		'cyan' 			=> '46',
		'light_gray' 	=> '47'
	);

	/**
	 * @var bool disable coloring completely
	 */
	public static $active = true;


	/**
	 * Get colored string
	 *
	 * @param string $string
	 * @param string $foreground_color
	 * @param string $background_color
	 * @return string colored string
	 * @throws Exception
	 */
	public static function getColoredString($string, $foreground_color = null, $background_color = null) {

		if (!self::$active) {
			return $string;
		}

		if (is_null($foreground_color) && is_null($background_color)) {
			return $string;
		}

		$colored_string = "";

		// Check if given foreground color found
		if (!is_null($foreground_color)) {
			if (isset(self::$foreground_colors[$foreground_color])) {
				$colored_string .= "\033[" . self::$foreground_colors[$foreground_color] . "m";
			} else {
				throw new Exception(sprintf('Foreground color "%s" not found', $foreground_color));
			}
		}

		if (!is_null($background_color)) {
			// Check if given background color found
			if (isset(self::$background_colors[$background_color])) {
				$colored_string .= "\033[" . self::$background_colors[$background_color] . "m";
			} else {
				throw new Exception(sprintf('Background color "%s" not found', $background_color));
			}
		}

		// Add string and end coloring
		$colored_string .=  $string . "\033[0m";

		return $colored_string;
	}

	/**
	 * Clears the "screen" by outputting some special characters
	 *
	 * @param 	void
	 * @return 	void
	 */
	public static function clear() {
		// passthru('clear');
		$clear = array(27, 91, 72, 27, 91, 50, 74, 0);
		$output = '';
		foreach ($clear as $char) { $output .= chr($char); }
		echo $output;
	}

	/**
	 * Home
	 *
	 * @param 	void
	 * @return 	void
	 */
	public static function home() {
		// passthru('clear');
		$clear = array(27, 91, 72);
		$output = '';
		foreach ($clear as $char) { $output .= chr($char); }
		echo $output;
	}

}
