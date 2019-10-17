<?php
/**
* Make a unique slug if exis to db than increment a number at the end of the slug.
* @param  modal name
* @param string|current text which be wanted to be make slug
* @param integer|user id
* @return text for example bangladesh-is-small-country-1
*/
function makeSlug($modal,$slug,$id=0)
{
  if($slug != ""){
    $slug = str_slug($slug);
  }else{
    $slug = "text";
    $slug = str_slug($slug);
  }
  
  $allSlugs = getRelatedSlugs($modal,$slug, $id);
  if (! $allSlugs->contains('slug', $slug)){
    return $slug;
  }
  for ($i = 1; $i <= 90000; $i++) {
    $newSlug = $slug.'-'.$i;
    if (! $allSlugs->contains('slug', $newSlug)) {
      return $newSlug;
    }
  }
  throw new \Exception('Can not create a unique slug');
}

function getRelatedSlugs($model,$slug, $id=0){
  $modelName = str_replace(".","","App\.$model");
  return $modelName::select('slug')->where('slug', 'like', $slug.'%')
  ->where('id', '<>', $id)
  ->get();
}