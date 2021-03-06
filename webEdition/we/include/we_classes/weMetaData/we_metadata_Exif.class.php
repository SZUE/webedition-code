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
 * @package none
 * @license    http://www.gnu.org/copyleft/gpl.html  GPL
 */
// make accessable for others too, but use weMetaData_Exif::getUsedFields();

/**
 * @abstract implementation class of metadata reader for Exif data
 * @author Alexander Lindenstruth
 * @since 5.1.0.0 - 27.09.2007
 * @uses exif php exif functions, see link below for more information
 * @link http://de.php.net/manual/de/ref.exif.php reference manual for php exif functions
 */
class we_metadata_Exif extends we_metadata_metaData{

	const usedFields = 'Artist,ColorSpace,Copyright,DateTime,DateTimeOriginal,ExifImageLength,ExifImageWidth,ExifVersion,ExposureBiasValue,ExposureTime,FileDateTime,FileSize,FileType,Flash,FNumber,FocalLength,HostComputer,ImageDescription,Make,MeteringMode,MimeType,Model,Orientation,ResolutionUnit,Software,UserComment,XResolution,YResolution,YCbCrPositioning';

	public function __construct($filetype){
		$this->filetype = $filetype;
		$this->accesstypes = array("read");
	}

	public static function getUsedFields(){
		return explode(',', self::usedFields);
	}

	protected function checkDependencies(){
		return (is_callable("exif_read_data"));
	}

	protected function getInstMetaData($selection = ""){
		if(!$this->valid){
			return false;
		}
		if(is_array($selection)){
// fetch some
		} else {
// fetch all
			if(@exif_imagetype($this->datasource)){
				$metadata = @exif_read_data($this->datasource);
			} else {
				$this->valid = false;
				return false;
			}
		}

		foreach(explode(',', self::usedFields) as $value){
			if(isset($metadata[$value])){
				$this->metadata[$value] = $metadata[$value];
			}
		}

		return $this->metadata;
	}

}