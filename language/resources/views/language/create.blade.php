@extends('administration.owner.layout.app')
@section("title")
{{ $data->title }}
@endsection

@section("page_title")
{{ $data->page_title }}
@endsection

@section("link")
<li><a href="{{ route('language') }}" class="btn bg-grad-info">{{ getPhrase('language') }}</a></li>
@endsection

@section('content')

<div class="row">
  <div class="col-md-3">
    <div class="card bg-light">
      <div class="card-title text-center bg-info py-2 text-light text-capitalize">{{ getPhrase("add_new_key") }}</div>
      <div class="card-body pt-3">
        <form id="newKeyForm">
          {{ csrf_field() }}
          <div id="key_div">
            <div class="uk-margin">
              <div class="uk-inline">
                <a class="uk-form-icon uk-form-icon-flip cursor-pointer" uk-icon="icon: lock"></a>
                <input class="key_name uk-input" type="text" name="key[]" placeholder="Key Name">
              </div>
            </div>                    
          </div>

          <ul class="list-inline">                    
            <li><span class="btn bg-grad-danger cursor-pointer" id="newKey">{{ getPhrase('new') }}</span></li>
            <li>
              <button class="btn bg-grad-info" id="submit_btn">{{ getPhrase("save") }}</button>
            </li>
          </ul>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-9">
    <form id="lang_form">
      {{ csrf_field() }}
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <input type="text" name="language" class="form-control" placeholder="Language">
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <input type="text" name="code" class="form-control" placeholder="Code">
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <ul class="list-inline">
              <li>
                <label>
                  <input value="1" type="checkbox" name="default">Default                          
                </label>
              </li>
              <li>
                <label>
                  <input value="1" type="checkbox" name="rtl">RTL                          
                </label>
              </li>
            </ul>                    
          </div>
        </div>
      </div>
      <table class="table table-bordered table-striped" id="laravel_datatable" style="width: 100%;">
       <thead>
        <tr class="text-center">
         <th>Key</th>
         <th>Language</th>
       </tr>
     </thead>
   </table>
   <div class="form-group text-center">
    <button id="SaveLanguage" class="btn bg-grad-info btn-lg text-capitalize">{{ getPhrase("save") }}</button>
  </div>
</form>
</div>
</div>

@section("script")
  <script>
  $(document).ready( function () {

    // New key module start
    // New key module start
    // New key module start
    var i = 1;
    $("#newKey").click(function(){
      i++;
       $("#key_div").append('<div class="uk-margin keybox'+i+' newKeyBox"><div class="uk-inline"><a class="remove_btn uk-form-icon uk-form-icon-flip cursor-pointer" uk-icon="icon: close" id="'+i+'"></a><input class="key_name uk-input" type="text" name="key[]" placeholder="Key Name"></div></div>');
    });
    $(document).on('click','.remove_btn',function(){
         var btn_id = $(this).attr("id");
         $(".keybox"+btn_id+"").remove();
    });

    $(document).on('focusout','.key_name',function(){
      var key_name = $(this);
      var val = $(this).val();
      var token = $('input[name="_token"]').val();
      if(val != ''){
        $.ajax({
           url:"{{ route('language.keyexitchk') }}",
           method:"POST",
           data:{_token:token,val:val},
           success:function(result)
           {
            if(result == 1)
            {
              $(key_name).css("border","1px solid #DC3545");
            }else
            {
              $(key_name).css("border","1px solid #28A745");
              $("#msg").html(" ");
            }
           }
        });        
      }
    });

    $("#submit_btn").click(function(){
        event.preventDefault();
        $.ajax({
            url:"{{ route('language.keysave') }}",
            method:"POST",
            data:$("#newKeyForm").serialize(),
            beforeSend: function() {
              $("#submit_btn").buttonLoader('start');
            },
            success:function(result)
            {
              $("#submit_btn").buttonLoader('stop');
              $(".newKeyBox").hide();
              sweetAlert(result);
            }
        });
    });


    // New key module end
    // New key module end
    // New key module end

    $("#SaveLanguage").click(function(event) {
      event.preventDefault();
      $.ajax({
        url: "{{ route('key.list.store') }}",
        method: 'POST',
        data: $("#lang_form").serialize(),
        beforeSend:function(){
          $("#SaveLanguage").buttonLoader('start');
        },
        success:function(result) {
          $("#SaveLanguage").buttonLoader('stop');
          sweetAlert(result);
          var table = $('#laravel_datatable').DataTable();
          table.ajax.reload();
        }
      });
    });


     $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('#laravel_datatable').DataTable({
           processing: true,
           serverSide: true,
           "paginate": false,
           "language": {
                 "infoEmpty": "No entries to show"
            },
           ajax: "{{ route('key.datatable.list') }}",
           columns: [
                    {data: 'key', name: 'key', orderable: true,searchable: true},
                    {data: 'language', name: 'language', orderable: false, searchable: false},
                 ]
        });  

  });

  </script>
@endsection
@endsection
