<?
/***************************************************
** GetURLImageSize( $URLData ) determines the
** dimensions of local file/remote URL/pic-data-string pictures.
** returns array with ( $width,$height,$type )
**
** Thanks to: Oyvind Hallsteinsen aka Gosub / ELq - gosub@elq.org
** for the original size determining code
**
** PHP Hack by Filipe Laborde-Basto Oct 21/2000
** FREELY DISTRIBUTABLE -- use at your sole discretion! :) Enjoy.
** (Not to be sold in commercial packages though, keep it free!)
** Feel free to contact me at fil@rezox.com (http://www.rezox.com)
**
** Revised 7 Apr 2002 by Fil Laborde. Now multi-functional:
** can read dimensions from URL, file, or string
** Idea for getting dimensions from strings by
** modified code sent in by James Heinrich 
**
***************************************************/

define(GIF_SIG, "\x47\x49\x46");
define(JPG_SIG, "\xFF\xD8\xFF");
define(PNG_SIG, "\x89\x50\x4E\x47\x0D\x0A\x1A\x0A");
define(JPG_SOF, "\xC0");		/* Start Of Frame N 1100xxxx -- higher 4 bits are SOF, lower 4 are frame # */
define(JPG_EOI, "\xD9");		 /* End Of Image (end of datastream) */
define(JPG_SOS, "\xDA"); /* Start Of Scan - image data start */
define(RD_BUF, 512);			  /* amount of data to initially read */


function GetURLImageSize( $URLData ){
	/* initialize variables */
	$Width = 0; $Height = 0; $Type = 0;
	$imgPos = 0; $imgData = '';
  
	/* if a filename or URL */
	if( (strlen($URLData)<200 && is_file($URLData)) ||
		 substr(strtolower($URLData),0,7)=='http://' ){
		$FP = @fopen($URLData,'rb');
		if( $FP )
			$imgData = fread( $FP,RD_BUF );
	} else { /* is a string */
		$imgData = $URLData;
	};
	if( $imgData ){
		if( substr($imgData,0,3)==GIF_SIG ){
			$dim = unpack ("v2dim",substr($imgData,6,4) );
			$Width = $dim["dim1"]; $Height = $dim["dim2"];
			$Type = 1;
		} elseif( substr($imgData,0,8)==PNG_SIG ){
			$dim = unpack ("N2dim",substr($imgData,16,8) );
			$Width = $dim["dim1"]; $Height = $dim["dim2"];	  
			$Type = 3;
		} elseif( substr($imgData,0,3)==JPG_SIG ){
			$imgPos = 2; $Type = 2;
			/************ Scan through JPG Chunk **************/		  
			while($imgPos < strlen($imgData)) {
				/* synchronize to the marker 0xFF */
				$imgPos=strpos(&$imgData,0xFF,$imgPos)+1;
				/* find first non-0xFF character */
				while( $imgData[$imgPos]=="\xFF" ){ $imgPos++; };
				$ChunkType = $imgData[$imgPos++];
			  
				/* find dimensions of block */
				if( ( $ChunkType&"\xF0" )==JPG_SOF ){
				  /* Grab width/height from SOF segment (these are acceptable chunk types) */
				  $dim =unpack ("n2dim",substr($imgData,$imgPos+3,4) );
				  $Height=$dim["dim1"]; $Width=$dim["dim2"];
				  break; /* found it, exit loop */
				 
				/* End loop in case we find one of these markers */
				} elseif( $ChunkType==JPG_EOI || $ChunkType==JPG_SOS ){
				  if( $FP ) fclose ($FP); /* close file */
				  return FALSE;
			  
				/* Another type of chunk -- skip it! */
				} else {
				  $ChunkSize = (ord($imgData[$imgPos++])<<8)+ord($imgData[$imgPos++])-2;
				  /* if the skip is more than what we've read in, read more */
				  if( $FP && strlen($imgData)<($ChunkSize+$imgPos+16) ){ /* if file/URL && next chunk not in memory, go read it */
						$imgData .= fread( $FP,$ChunkSize+2*RD_BUF );
				  };
				  $imgPos += $ChunkSize;
				}; //endif check marker type
			}; //endif loop through JPG chunks
		}; //endif chk for valid file types
		if( $FP ) fclose ($FP); /* close file */
	};
	return array( $Width,$Height,$Type );
}; // end function
?>
