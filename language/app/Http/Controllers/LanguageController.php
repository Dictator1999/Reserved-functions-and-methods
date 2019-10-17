<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Language;
use App\Languagekey;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LanguageController extends Controller
{
    public function index($id=null)
    {
        $language = Language::select('id','language','is_default')
                            ->orderBy('is_default', 'desc')
                            ->get();
                            //Change default value

        $keys = Languagekey::select("id","key")
                            ->orderBy("key",'asc')
                            ->get();
        
        if($id != null)
        {
          $tablePhrase = Language::find($id);
        }else
        {
          $tablePhrase = Language::where("is_default",'1')->first();
        }
        $phrases = (array) json_decode($tablePhrase->phrases);

    	$data = array();
    	$data = [
        	'title'      => "ISLM-Admin-Language",
        	'page_title' => "Language",
            'language'   => $language,//Change default value
            'tablePhrase'   => $tablePhrase,//Change default value
            'keys'       => $keys,
        ];

	    $data =  (object) $data;
	    return view('.language.index',compact(['data','phrases']));
    }

    public function create()
    {
    	$data = array();
    	$data = [
        	'title'      => "ISLM-Admin-Language",
        	'page_title' => "Add Language",
        ];

	    $data =  (object) $data;
	    return view('language.create',compact('data'));	
    }

    public function changeValue(Request $request)
    {
      $language = Language::find($request['id']);
      $data = [];
      foreach ($request->all() as $key => $value) {
        if(substr($key, '-3') == 'xyz'){
          $data[substr($key, "0","-3")] = $value;
        }
      }

      $language->code = $request['code'];
      $language->phrases = json_encode($data);
      $language->save(); 
      return back()->withSuccessMessage("Successfully change language value");     
    }

    public function changeDefaultLang(Request $request)
     {
        $language = Language::find($request['val']);
        if($language != '')
        {
            $updateOld = Language::where("is_default",'1')->first();
            $updateOld->is_default = '0';
            $language->is_default = '1';

            $updateOld->save();
            $language->save();
            $data = Language::select('id','language','is_default')
            ->orderBy('is_default', 'desc')
            ->get();

            foreach ($data as $key => $value) {
                echo '<option value="'.$value->id.'">'.$value->language.'</option>';
            }
        }
    }

    public function geKeytDatatable()
     {
    	if(request()->ajax()) {
	        return datatables()->of(Languagekey::query("*"))

	        ->editColumn("language",function(Languagekey $key){
	        	return '<input type="text" name='.$key->key."xyz".' class="form-control">';
	        })
	        ->rawColumns(['language'])
	        ->setRowAttr([
               'align' => 'center'
	        ])
	        ->make(true);
	    }    
    }

    public function keyValStore(Request $request)
    {
       $language = new Language();       
       ($request['default'] != '') ? $default = '1' : $default = '0';
       ($request['rtl'] != '') ? $rtl = '1' : $rtl = '0';

       $data = [];

       foreach ($request->all() as $key => $value) {
           if(substr($key, '-3') == 'xyz'){
             $data[substr($key, "0","-3")] = $value;
           }
       }
       if($default == "1"){
          $updateDefault = Language::where("is_default","1")->first();
          $updateDefault->is_default = "0";
          $updateDefault->save();
       }

       $language->is_default = $default;
       $language->language = $request['language'];
       $language->code = $request['code'];
       $language->is_rtl = $rtl;
       $language->phrases = json_encode($data);
       $language->save();
       return "success";

    }



    public function newkeysave(Request $request)
    {
    	$number = count($request['key']);

    	if($number >= 1)
    	{
    		for ($i=0; $i < $number; $i++) { 
    			if(trim($request['key'][$i]) != '')
    			{
           $languagekey = new Languagekey();
           $languagekey->key = $request['key'][$i];
           $languagekey->save();
         }
       }
       return "success";
     }else{
      return 'error';
    }
  }

    public function newkeyexischk(Request $request)
    {
    	if($request['val'] != '')
    	{
	    	$key = Languagekey::where("key",$request['val'])->first();
	    	if($key != null)
	    	{
	    		return '1';
	    	}else
	    	{
	    		return '0';
	    	}    		
    	}

    }

}
