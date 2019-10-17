<?php
/**
* Make a unique slug if exis to db than increment a number at the end of the slug.
* @param  encode_base64() text
*/
function image($data)
{
  $image_array_1 = explode(";", $data);
  $image_array_2 = explode(",", $image_array_1[1]);
  $data = base64_decode($image_array_2[1]);
  $imageName = uniqid().time().".png";
  $temp = tmpfile();
  fwrite($temp, $data);
  $tempPath = stream_get_meta_data($temp)['uri'];
  $image = new \Illuminate\Http\UploadedFile($tempPath, $imageName, null, null, true);

  $ImageObj = "Intervention\Image\Facades\Image";
  $directory = "folderName/$imageName";

  $ImageObj::make($image)->resize(100, null, function ($constraint) {
    $constraint->aspectRatio();
  })->save($directory);

   fclose($temp);
  return $imageName;
}