<?php
/**
* Short text
* @param string|image name or directory
*/ 
function unlinkImg($imgName)
{
  $userPath = "FolderName";
  if(file_exists($userPath.$imgName))
  {
     unlink($userPath.$imgName);
  }
}