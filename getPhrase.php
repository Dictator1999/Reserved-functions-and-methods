<?php
/**
 * Keep in mind to insert Language model and language.sql file
 *@param word key name
*/
function getPhrase($key)
{
  if(is_array($key)){

    $language = App\Language::where("is_default",'1')->first();
    $phrases = (object) json_decode($language->phrases);
    $data = "";

    for($i=0;$i<count($key);$i++){
      $key[$i] = strtolower($key[$i]);
      $theKey = $key[$i];
      if(isset($phrases->$theKey)){
        $data .= $theKey." ";
      }else{
        $data .= $key[$i]."! ";
      }
    }
    return $data;
  }else{
    $key = strtolower($key);
    $language = App\Language::where("is_default",'1')->first();
    $phrases = (object) json_decode($language->phrases);
    if(isset($phrases->$key)){
        return $phrases->$key;
    }else{
        return $key."!";
    }    
  }
}