<?php

namespace App\Libs;

class CreateImage
{
	public function __construct() {}
	
	public function text2Image(
		String $font = '', String $text, int $fontsize = 16, bool $border = true, int $border_rectingle = 0, bool $resize = true, bool $height_fix = true, int $width = 70, int $height = 70, 
		array $bgcolor = ['red' => 255, 'grn' => 255, 'blu' => 255, 'alp' => 0], array $fcolor = ['red' => 0, 'grn' => 0, 'blu' => 0], array $bdcolor = ['red' => 255, 'grn' => 0, 'blu' => 0]
	)
	{
		$font = $font == '' ? resource_path() . "/fonts/KCCDodamdodam.ttf" : resource_path() . "/fonts/" . $font;
		
		$text = str_replace('<br/>', "\n", $text);
		$strsize = imageftbbox($fontsize, 0, $font, $text, array("linespacing" => 0.5));
		
		$string_width = $strsize[4];
		$string_height = $strsize[5];

		if($resize == true) {
			if($height_fix == true) {
				$width = $string_width > $string_height ? $string_width : $string_height;
				$width += 20;
				$height= $width;
			} else {
				$width = $string_width + 20;
				$height = abs($string_height) + 20;
			}
		}
		
		$image = imagecreate($width, $height);
		$background_color = imagecolorallocatealpha($image, $bgcolor['red'], $bgcolor['grn'], $bgcolor['blu'], $bgcolor['alp']);
		$font_color = imagecolorallocate($image, $fcolor['red'], $fcolor['grn'], $fcolor['blu']);
		$border_color = imagecolorallocate($image, $bdcolor['red'], $bdcolor['grn'], $bdcolor['blu']);
		
		if($border == true) {
			/*
			$radius = 0;
			if($border_rectingle == true) {
				$radius = 90;
			}
			*/
			$radius = $border_rectingle;
			
			$this->imageroundedrectangle($image, 0, 0, $width - 1, $height - 1, $radius, $border_color);
			$this->imageroundedrectangle($image, 1, 1, $width - 2, $height - 2, $radius, $border_color);
			$this->imageroundedrectangle($image, 2, 2, $width - 3, $height - 3, $radius, $border_color);
		}
		
		$line_count = substr_count($text, "\n");
		$locate_x = $width / 2 - $string_width / 2;
		$locate_y = ($height / (2 + $line_count)) - ($string_height / (2 + $line_count));
		
		imageTTFText($image, $fontsize, 0, $locate_x, $locate_y, $font_color, $font, $text);
		ob_start();
		imagepng($image);
		$textImage = ob_get_contents();
		ob_end_clean();
		imagedestroy($image);
		
		return $textImage;
	}
	
	private function imageroundedrectangle(&$img, $x1, $y1, $x2, $y2, $r, $color)
	{
		$r = min($r, floor(min(($x2-$x1)/2, ($y2-$y1)/2)));
		// top border
		imageline($img, $x1+$r, $y1, $x2-$r, $y1, $color);
		// right border
		imageline($img, $x2, $y1+$r, $x2, $y2-$r, $color);
		// bottom border
		imageline($img, $x1+$r, $y2, $x2-$r, $y2, $color);
		// left border
		imageline($img, $x1, $y1+$r, $x1, $y2-$r, $color);

		// top-left arc
		imagearc($img, $x1+$r, $y1+$r, $r*2, $r*2, 180, 270, $color);
		// top-right arc
		imagearc($img, $x2-$r, $y1+$r, $r*2, $r*2, 270, 0, $color);
		// bottom-right arc
		imagearc($img, $x2-$r, $y2-$r, $r*2, $r*2, 0, 90, $color);
		// bottom-left arc
		imagearc($img, $x1+$r, $y2-$r, $r*2, $r*2, 90, 180, $color);

		return true;
	}
}