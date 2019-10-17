<?php
/**
* Call default image if called image source not avalavle inside folder.
* @param  encode_base64() text
*/
 function img($img = null)
 {
    if($img == null)
    {
      return asset('img/empty-image.png');
    }else{
      return asset('fileLocation'.$img);
    }

    return "Empty";
 }