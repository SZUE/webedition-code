<?php

/**
 * webEdition CMS
 *
 * $Rev$
 * $Author$
 * $Date$
 *
 * This source is part of webEdition CMS. webEdition CMS is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 * A copy is found in the textfile
 * webEdition/licenses/webEditionCMS/License.txt
 *
 * @category   webEdition
 * @package    webEdition_base
 * @license    http://www.gnu.org/copyleft/gpl.html  GPL
 */

/**
 * Class we_image_resize
 *
 * Provides functions for creating webEdition buttons.
 */
class we_image_edit{

	const IMAGE_CONTENT_TYPES = 'image/jpeg,image/pjpeg,image/gif,image/png,image/x-png';

	public static $GDIMAGE_TYPE = array('.gif' => 'gif', '.jpg' => 'jpg', '.jpeg' => 'jpg', '.png' => 'png');

	/*	 * ***********************************************************************
	 * FUNCTIONS
	 * *********************************************************************** */

	/**
	 * Returns values of the function phpinfo().
	 *
	 * @see        gd_info()
	 *
	 * @return     array
	 */
	function php_info(){
		static $_phpinfo = array();

		// Check if need to get the requested information
		if(empty($_phpinfo)){
			// Start output buffering
			ob_start();

			phpinfo();

			// Read output of the function phpinfo()
			$_returned_phpinfo = ob_get_contents();

			// Clean output buffer
			ob_end_clean();

			// Fill informations of PHP
			$_phpinfo = explode("\n", $_returned_phpinfo);
		}

		return $_phpinfo;
	}

	function supported_image_types(){
		$_output_formats = array();

		if(function_exists("ImageTypes")){
			$_imagetypes = ImageTypes();

			// Check output formats
			if($_imagetypes & IMG_GIF){
				$_output_formats[] = "gif";
			}

			if($_imagetypes & IMG_PNG){
				$_output_formats[] = "png";
			}

			if($_imagetypes & IMG_JPG){
				$_output_formats[] = "jpg";
			}
		}
		return $_output_formats;
	}

	function detect_image_type($filename = "", &$imagedata){

		// Check if we need to read the beginning of the image
		if(file_exists($filename)){
			$_fp_file = fopen($filename, "rb");

			$imagedata = fread($_fp_file, 4);
			fclose($_fp_file);
		}

		switch(substr($imagedata, 0, 3)){
			case "GIF":
				$_type = "gif";

				break;

			case "\xFF\xD8\xFF":
				$_type = "jpg";

				break;

			case "\x89" . "PN":
				$_type = "png";

				break;

			default:
				$_type = "";

				break;
		}

		return $_type;
	}

	function gd_info(){
		// Check if we need to emulate this function since it is built into PHP v4.3.0+ (with bundled GD2 library)
		if(!function_exists("gd_info")){
			static $_gdinfo = array();

			// Check if need to get the requested information
			if(empty($_gdinfo)){
				// Initialize array with default values
				$_gdinfo = array("GD Version" => "", "FreeType Support" => false, "FreeType Linkage" => "", "T1Lib Support" => false, "GIF Read Support" => false, "GIF Create Support" => false, "JPG Support" => false, "PNG Support" => false, "WBMP Support" => false, "XBM Support" => false);

				// Now we need to read the phpinfo() to detect the GD library support
				$_phpinfo = we_image_edit::php_info();

				foreach($_phpinfo as $_value){
					$_value = trim(strip_tags($_value));

					foreach($_gdinfo as $key => $value){
						if(strpos($_value, $key) === 0){
							$_new_value = trim(str_replace($key, "", $_value));
							$_gdinfo[$key] = $_new_value;
						}
					}
				}

				// Check if GD version information is present now
				if(empty($_gdinfo["GD Version"])){
					// Check if we can detect GD library by bypassing the function: phpinfo()
					if(function_exists("ImageTypes")){
						$_imagetypes = ImageTypes();

						// Check JPG support
						if($_imagetypes & IMG_JPG){
							$_gdinfo["JPG Support"] = true;
						}

						// Check PNG support
						if($_imagetypes & IMG_PNG){
							$_gdinfo["PNG Support"] = true;
						}

						// Check GIF support
						if($_imagetypes & IMG_GIF){
							$_gdinfo["GIF Create Support"] = true;
						}
					}

					// Detect capabilities of GIF support
					if(function_exists("ImageCreateFromGIF")){
						if($_tempfilename = tempnam(TEMP_PATH, "")){
							if($_fp_tempfile = @fopen($_tempfilename, 'wb')){
								fwrite($_fp_tempfile, base64_decode("R0lGODlhAQABAIAAAH//AP///ywAAAAAAQABAAACAUQAOw=="));
								fclose($_fp_tempfile);

								// GIF create support must be enabled if we're able to create a image
								$_gif_test = @imagecreatefromgif($_tempfilename);

								if($_gif_test){
									$_gdinfo["GIF Read Support"] = true;
								}
							}

							unlink($_tempfilename);
						}
					}

					// Detect version of GD library
					if(function_exists("ImageCreateTrueColor") && @imagecreatetruecolor(1, 1)){
						$_gdinfo["GD Version"] = "2.0.1 or higher (assumed)";
					} else if(function_exists("ImageCreate") && @imagecreate(1, 1)){
						$_gdinfo["GD Version"] = "1.6.0 or higher (assumed)";
					}
				}
			}

			return $_gdinfo;
		} else{
			return gd_info();
		}
	}

	function gd_version(){

		static $_gdversion = 0;

		// Check if need to get the requested information
		if(empty($_gdversion)){
			// Request information about GD libary
			$_gdinfo = we_image_edit::gd_info();

			// Define string to be searched
			$_searchstring = "bundled (";

			// Detect information string now
			if(substr($_gdinfo["GD Version"], 0, strlen($_searchstring)) == $_searchstring){
				$_gdversion = substr($_gdinfo["GD Version"], strlen($_searchstring), 3);
			} else{
				$_gdversion = substr($_gdinfo["GD Version"], 0, 3);
			}
		}

		return $_gdversion;
	}

	function ImageCreateFromStringReplacement(&$imagedata){
		// Serious bugs in the non-bundled versions of GD library cause PHP to segfault when calling ImageCreateFromString() - avoid if possible
		$_gdimg = false;

		switch(we_image_edit::detect_image_type("", $imagedata)){
			case "gif":
				$_image_create_from_string_replacement_function = "imagecreatefromgif";

				break;

			case "jpg":
				$_image_create_from_string_replacement_function = "ImageCreateFromJPEG";

				break;

			case "png":
				$_image_create_from_string_replacement_function = "ImageCreateFromPNG";

				break;

			default:

				break;
		}

		if($_tempfilename = tempnam(TEMP_PATH, "")){
			if($_fp_tempfile = @fopen($_tempfilename, 'wb')){
				fwrite($_fp_tempfile, $imagedata);
				fclose($_fp_tempfile);
				$imagedata = "";
				unset($imagedata);
				if(function_exists($_image_create_from_string_replacement_function)){
					$_gdimg = $_image_create_from_string_replacement_function($_tempfilename);
				}
			}
			unlink($_tempfilename);
		}

		return $_gdimg;
	}

	function ImageCreateFromFileReplacement($filename){
		$foo = "";
		switch(we_image_edit::detect_image_type($filename, $foo)){
			case "gif":
				$_image_create_from_string_replacement_function = "imagecreatefromgif";

				break;

			case "jpg":
				$_image_create_from_string_replacement_function = "ImageCreateFromJPEG";

				break;

			case "png":
				$_image_create_from_string_replacement_function = "ImageCreateFromPNG";

				break;

			default:
				return false;

				break;
		}

		if(function_exists($_image_create_from_string_replacement_function)){
			return $_image_create_from_string_replacement_function($filename);
		}
	}

	function calculate_image_size($origwidth, $origheight, $newwidth, $newheight, $keep_aspect_ratio = true, $maxsize = true, $fitinside = false){
		if(we_image_edit::should_not_resize($origwidth, $origheight, $newwidth, $newheight, $maxsize, $fitinside)){
			return array("width" => $origwidth, "height" => $origheight, "useorig" => 1);
		}

		$_outsize["width"] = 0;
		$_outsize["height"] = 0;

		// If width has been specified set it and compute new height based on source area aspect ratio
		if($newwidth){
			$_outsize["width"] = $newwidth;
			$_outsize["height"] = round($origheight * $newwidth / $origwidth);
		} else{
			// bugfix #2482: preserve aspect ratio for thumbnails with width=0 and height != 0
			$_outsize["width"] = round(($origwidth / $origheight) * $newheight);
			if($newheight){
				$_outsize["height"] = $newheight;
			} else{
				$_outsize["height"] = round($origheight * $newwidth / $origwidth);
			}
		}

		// If height has been specified set it.
		// If width has already been set and the new image is too tall, compute a new width based
		// on aspect ratio - otherwise, use height and compute new width
		if($newheight){
			if($_outsize["height"] > $newheight){
				$_outsize["width"] = round($origwidth * $newheight / $origheight);
				$_outsize["height"] = $newheight;
			}
		}

		// Check, if we must discard aspect ratio
		if(!$keep_aspect_ratio && ($newwidth) && ($newheight)){
			$_outsize["width"] = $newwidth;
			$_outsize["height"] = $newheight;
		}

		// Check, if it is supposed to fit inside
		if($fitinside && ($newwidth) && ($newheight)){
			$_outsize["width"] = $newwidth;
			$_outsize["height"] = $newheight;
		}

		return array("width" => $_outsize["width"], "height" => $_outsize["height"], "useorig" => 0);
	}

	function calculate_image_sizeFit($origwidth, $origheight, $newwidth, $newheight, $maxsize = true){
		if(we_image_edit::should_not_resize($origwidth, $origheight, $newwidth, $newheight, $maxsize, true)){
			return array("width" => $origwidth, "height" => $origheight, "useorig" => 1);
		}

		$_outsize["width"] = 0;
		$_outsize["height"] = 0;

		// If width has been specified set it and compute new height based on source area aspect ratio
		// here it is set
		$_outsize["width"] = $newwidth;
		$_outsize["height"] = round($origheight * $newwidth / $origwidth);



		// If width has already been set and the new image is too tall, compute a new width based
		// on aspect ratio - otherwise, use height and compute new width
		if($newheight){
			if($_outsize["height"] > $newheight){
				$_outsize["width"] = round($origwidth * $newheight / $origheight);
				$_outsize["height"] = $newheight;
			}
		}


		return array("width" => $_outsize["width"], "height" => $_outsize["height"], "useorig" => 0);
	}

	function should_not_resize($origwidth, $origheight, $newwidth, $newheight, $maxsize = false, $fitinside = false){
		return ($maxsize == false) && ($fitinside == false) && ($origwidth <= $newwidth) && ($origheight <= $newheight);
	}

	function getimagesize($filename){
		$foo = "";
		$type = we_image_edit::detect_image_type($filename, $foo);
		if(we_image_edit::is_imagetype_supported($type)){
			$_gdimg = we_image_edit::ImageCreateFromFileReplacement($filename);
			$ct = 0;
			switch($type){
				case "gif":
					$ct = 1;
					break;
				case "jpg":
					$ct = 2;
					break;
				case "png":
					$ct = 3;
					break;
			}
			$w = ImageSX($_gdimg);
			$h = ImageSY($_gdimg);
			return array($w, $h, $ct, 'width="' . $w . '" height="' . $h . '"');
		}
		return array();
	}

	function is_imagetype_supported($type){
		return in_array($type, we_image_edit::supported_image_types());
	}

	function is_imagetype_read_supported($type){
		$t = array("gif", "jpg", "png");

		$sit = we_image_edit::supported_image_types();
		$fn = "";

		for($i = 0; $i < count($t); $i++){
			if(!in_array($t[$i], $sit)){
				switch($t[$i]){
					case "gif":
					case "png":
						$fn = "ImageCreateFrom" . strtoupper($t[$i]);
						break;
					case "jpg":
						$fn = "ImageCreateFromJPEG";
						break;
				}
				if(function_exists($fn)){
					if(@$fn($_SERVER['DOCUMENT_ROOT'] . IMAGE_DIR . "foo." . $t[$i])){
						array_push($sit, $t[$i]);
					}
				}
			}
		}
		return in_array($type, $sit);
	}

	function edit_image($imagedata, $output_format = "jpg", $output_filename = "", $output_quality = 75, $width = "", $height = "", $keep_aspect_ratio = true, $interlace = true, $crop_x = 0, $crop_y = 0, $crop_width = -1, $crop_height = -1, $rotate_angle = 0, $fitinside = false){
		$_fromFile = false;

		$output_format = strtolower($output_format);
		if($output_format == "jpeg"){
			$output_format = "jpg";
		}

		if(strlen($imagedata) < 255 && @file_exists($imagedata)){
			$_fromFile = true;
		}

		// Output format is available
		if(in_array($output_format, we_image_edit::supported_image_types())){
			// Set quality for JPG images
			if($output_format == "jpg"){
				// Keep quality between 1 and 99
				$output_quality = max(1, min(99, (is_int($output_quality) ? $output_quality : 75)));
			}

			if($_fromFile){
				$_gdimg = we_image_edit::ImageCreateFromFileReplacement($imagedata);
			} else{
				$_gdimg = we_image_edit::ImageCreateFromStringReplacement($imagedata);
			}

			// Now we need to ensure that we could read the file
			if($_gdimg){
				// Detect dimension of image
				$_width = ImageSX($_gdimg);
				$_height = ImageSY($_gdimg);

				if(($rotate_angle != 0) && function_exists("ImageRotate")){
					$rotate_angle = floatval($rotate_angle);

					while($rotate_angle < 0) {
						$rotate_angle += 360;
					}

					$rotate_angle = $rotate_angle % 360;

					if($rotate_angle != 0){
						$_gdimg = ImageRotate($_gdimg, $rotate_angle, 0);
						$_width = ImageSX($_gdimg);
						$_height = ImageSY($_gdimg);
					}
				}

				$_outsize = we_image_edit::calculate_image_size($_width, $_height, $width, $height, $keep_aspect_ratio, true, $fitinside);

				// Decide, which functions to use (depends on version of GD library)
				if(we_image_edit::gd_version() >= 2.0){
					$_image_create_function = "imagecreatetruecolor";
				} else{
					$_image_create_function = "imagecreate";
				}

				if(function_exists('imagecopyresampled')){
					$_image_resize_function = "imagecopyresampled";
				} else{
					$_image_resize_function = "imagecopyresized";
				}

				if($_outsize["width"] == 0){
					$_outsize["width"] = 1;
				}
				if($_outsize["height"] == 0){
					$_outsize["height"] = 1;
				}

				// Now create the image
				$_output_gdimg = $_image_create_function($_outsize["width"], $_outsize["height"]); // this image is always black

				$GDInfo = we_image_edit::gd_info();
				// DEBIAN EDGE FIX => crashes at imagefill, so use old Method
				if($GDInfo["GD Version"] == "2.0 or higher" && !function_exists("imagerotate")){
					// set black to transparent!
					if($output_format == 'gif' || $output_format == 'png'){ // transparency with gifs
						imagecolortransparent($_output_gdimg, imagecolorallocate($_output_gdimg, 0, 0, 0)); // set this color to transparent - done
					}
				} else{

					// preserve transparency of png and gif images:
					if($output_format == "gif"){
						$colorTransparent = imagecolortransparent($_gdimg);
						imagepalettecopy($_gdimg, $_output_gdimg);
						imagefill($_output_gdimg, 0, 0, $colorTransparent);
						imagecolortransparent($_output_gdimg, $colorTransparent);
						imagetruecolortopalette($_output_gdimg, true, 256);
					} else if($output_format == "png"){
						imagealphablending($_output_gdimg, false);
						$transparent = imagecolorallocatealpha($_output_gdimg, 0, 0, 0, 127);
						$transparent = imagecolorallocatealpha($_output_gdimg, 255, 255, 255, 127);
						imagefill($_output_gdimg, 0, 0, $transparent);
						imagesavealpha($_output_gdimg, true);
					} else{

					}
				}
				// Resize image
				//if($_outsize["width"] == "1")
				if($fitinside && $keep_aspect_ratio && $width && $height){
					$wratio = $width / $_width;
					$hratio = $height / $_height;
					$ratio = max($width / $_width, $height / $_height);
					$h = $height / $ratio;

					$w = $width / $ratio;
					if($wratio < $hratio){
						$x = ($_width - $width / $ratio) / 2;
						$y = 0;
					} else{
						$x = 0;
						$y = ($_height - $height / $ratio) / 2;
					}
					$_image_resize_function($_output_gdimg, $_gdimg, 0, 0, $x, $y, $width, $height, $w, $h);
				} else{
					$_image_resize_function($_output_gdimg, $_gdimg, 0, 0, 0, 0, $_outsize["width"], $_outsize["height"], $_width, $_height);
				}

				// PHP 4.4.1 GDLIB-Bug/Safemode - Workarround
				if($output_filename != "" && file_exists($output_filename)){
					touch($output_filename);
				}

				if($interlace){
					ImageInterlace($_output_gdimg, 1);
				} else{
					ImageInterlace($_output_gdimg, 0);
				}

				switch($output_format){
					case 'jpg':
						// Output to a filename or directly
						if($output_filename != ""){
							$_gdimg = @imagejpeg($_output_gdimg, $output_filename, $output_quality);

							if($_gdimg){
								$_gdimg = basename($output_filename);
							}
						} else{
							if($_tempfilename = tempnam(TEMP_PATH, "")){
								@imagejpeg($_output_gdimg, $_tempfilename, $output_quality);
								$_fp_tempfile = fopen($_tempfilename, "rb");
								$_gdimg = "";
								while(!feof($_fp_tempfile)) {
									$_gdimg .= fread($_fp_tempfile, 8192);
								}
								fclose($_fp_tempfile);

								// As we read the temporary file we no longer need it
								//unlink($_tempfilename);
							}
						}

						break;

					case 'png':
					case 'gif':
						// Set output function
						$_image_out_function = 'image' . $output_format;
						// Output to a filename or directly
						if($output_filename){
							$_gdimg = @$_image_out_function($_output_gdimg, $output_filename);
							if($_gdimg){
								$_gdimg = basename($output_filename);
							}
						} else{
							if($_tempfilename = tempnam(TEMP_PATH, "")){
								@$_image_out_function($_output_gdimg, $_tempfilename);
								$_fp_tempfile = fopen($_tempfilename, "rb");
								$_gdimg = fread($_fp_tempfile, filesize($_tempfilename));
								fclose($_fp_tempfile);

								// As we read the temporary file we no longer need it
								unlink($_tempfilename);
							}
						}

						break;
				}

				ImageDestroy($_output_gdimg);
			}

			return isset($_gdimg) ? array($_gdimg, $_outsize["width"], $_outsize["height"]) : array(false, -1, -1);
		} else{
			return array(false, -1, -1);
		}
	}

	function ImageTrueColorToPalette2($image, $dither, $ncolors){
		$width = @imagesx($image);
		$height = @imagesy($image);
		$colors_handle = @imagecreatetruecolor($width, $height);
		@imagecopymerge($colors_handle, $image, 0, 0, 0, 0, $width, $height, 100);
		@imagetruecolortopalette($image, $dither, $ncolors);
		if(is_callable("imagecolormatch")){
			@imagecolormatch($colors_handle, $image);
		}
		@imagedestroy($colors_handle);
		return $image;
	}

	function createPreviewThumb($imgSrc, $imgID, $width, $height, $outputFormat = "jpg", $outputQuality = 75, $tmpName = ""){
		if(we_image_edit::gd_version() == 0){
			return IMAGE_DIR . "icons/doclist/image.gif";
		}
		if(substr($imgSrc, 0, strlen($_SERVER['DOCUMENT_ROOT'])) == $_SERVER['DOCUMENT_ROOT']){ // it is no src, it is a server path
			$imgSrc = substr($imgSrc, strlen($_SERVER['DOCUMENT_ROOT']));
		}
		if(substr($imgSrc, 0, 1) != "/"){
			$imgSrc = "/" . $imgSrc;
		}

		$_imgPath = $_SERVER['DOCUMENT_ROOT'] . $imgSrc;
		if(!($imagesize = getimagesize($_imgPath))){
			$imagesize = array(0, 0);
		}
		if($imagesize[0] > $width || $imagesize[1] > $height){
			$_previewDir = $_SERVER['DOCUMENT_ROOT'] . '/webEdition/preview/';
			if(!file_exists($_previewDir) || !is_dir($_previewDir)){
				we_util_File::createLocalFolder($_previewDir);
			}
			if($imgID){
				$_thumbSrc = '/webEdition/preview/' . $imgID . "_" . $width . "_" . $height . strtolower($outputFormat);
			} else{
				$_thumbSrc = TEMP_DIR . ($tmpName ? $tmpName : weFile::getUniqueId()) . "." . strtolower($outputFormat);
			}
			$_thumbPath = $_SERVER['DOCUMENT_ROOT'] . $_thumbSrc;

			$_thumbExists = file_exists($_thumbPath);

			$_imageCreationDate = filemtime($_imgPath);
			$_thumbCreationDate = $_thumbExists ? filemtime($_thumbPath) : 0;

			if(!$_thumbExists || ($_imageCreationDate > $_thumbCreationDate)){
				$thumb = we_image_edit::edit_image($_imgPath, $outputFormat, $_thumbPath, $outputQuality, $width, $height);
			}
			return $_thumbSrc;
		}

		return $imgSrc;
	}

	/**
	 * returns the HTML for a quality output select box
	 *
	 * @return string
	 * @param string $name
	 * @param string[optional] $sel
	 */
	static function qualitySelect($name = 'quality', $sel = 8){
		return '<select name="' . $name . '" class="weSelect" size="1">
<option value="0"' . (($sel == 0) ? ' selected' : '') . '>0 - ' . g_l('weClass', '[quality_low]') . '</option>
<option value="1"' . (($sel == 1) ? ' selected' : '') . '>1</option>
<option value="2"' . (($sel == 2) ? ' selected' : '') . '>2</option>
<option value="3"' . (($sel == 3) ? ' selected' : '') . '>3</option>
<option value="4"' . (($sel == 4) ? ' selected' : '') . '>4 - ' . g_l('weClass', '[quality_medium]') . '</option>
<option value="5"' . (($sel == 5) ? ' selected' : '') . '>5</option>
<option value="6"' . (($sel == 6) ? ' selected' : '') . '>6</option>
<option value="7"' . (($sel == 7) ? ' selected' : '') . '>7</option>
<option value="8"' . (($sel == 8) ? ' selected' : '') . '>8 - ' . g_l('weClass', '[quality_high]') . '</option>
<option value="9"' . (($sel == 9) ? ' selected' : '') . '>9</option>
<option value="10"' . (($sel == 10) ? ' selected' : '') . '>10 - ' . g_l('weClass', '[quality_maximum]') . '</option>
</select>
';
	}

}