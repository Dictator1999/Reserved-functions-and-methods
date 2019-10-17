@extends('administration.owner.layout.app')
@section("title")
{{ $data->title }}
@endsection

@section("page_title")
{{ $data->page_title }}
@endsection

@section("link")
  <li><a href="{{ route('language.create') }}" class="btn bg-grad-info">{{ getPhrase("add_new_language") }}</a></li>
@endsection

@section('content')

<div class="row">
  <div class="col-md-12">
    <ul class="list-inline m-0 mb-2">
      <li>Default:
        <select class="default_lan form-control w-auto d-inline" id="default_lan">
          @foreach ($data->language as $value)
          <option value="{{ $value->id }}">{{ $value->language }}</option>
          @endforeach
        </select>
      </li>
      <li>{{ $data->tablePhrase->language }}</li>
      <li><input type="text" id="code" class="form-control w-auto d-inline" value="{{ $data->tablePhrase->code }}"></li>
    </ul>
  </div>
</div>
{{ csrf_field() }}
<div class="row">
  <div class="col-md-2">
    <div class="card">
      <div class="card-header bg-info text-light text-center">
        <h4 class="m-0 text-light">Language</h4>
      </div>
      <ul class="nav flex-column mt-0">
        @foreach ($data->language as $value)
        <li class="nav-item @if ($value->is_default == 1)
          bg-light
          @endif">
          <a class="tab-link nav-link" href="{{ route('language',$value->id) }}">{{ $value->language }}</a>
        </li>
        @endforeach
      </ul>
    </div>
  </div>
  <div class="col-md-10">
    <form action="{{ route('language.val.change') }}" method="post">
      {{ csrf_field() }}
      <input type="hidden" value="{{ $data->tablePhrase->id }}" name="id">
      <input type="hidden" id="hiddenCode" name="code" value="{{ $data->tablePhrase->code }}" name="code">
      <table class="table table-bordered table-striped" id="myTable" style="width: 100%;">
       <thead>
        <tr class="text-center">
         <th>Key</th>
         <th>Language</th>
       </tr>
     </thead>
     <tbody>
      <?php $i = 1;?>
      @foreach ($data->keys as $value)
      <?php $i++; ?>
      <tr>
        <?php $test = $value->key; ?>
        <td>{{ $value->key }}</td>
        <td>
          @if (array_key_exists($test,$phrases))
          <span class="{{ $i.'rolePhrase' }}">{{ $phrases[$test] }}</span>
          <input id="{{ $i.'role' }}" type="text" name="{{ $value->key.'xyz' }}" value="{{ $phrases[$test] }}" class="form-control w-auto d-none">
          @else
          <span id="{{ $i.'roleEmpty' }}" class="btn btn-sm btn-danger">empty</span>
          <input id="{{ $i.'role' }}" type="text" name="{{ $value->key.'xyz' }}" class="form-control w-auto d-none">
          @endif 
          <i data-role="{{ $i.'role' }}" class="edit fa fa-edit float-right cursor-pointer p-1 text-muted"></i>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <div class="form-group text-center">
    <button type="submit" class="btn bg-grad-info btn-lg text-capitalize">{{ getPhrase("change") }}</button>
  </div>
</form>
</div>
</div>

@section("script")
<script>
  $(document).ready( function () {

    $("#default_lan").change(function(event) {
      var val = $('#default_lan').children("option:selected").val();
      var token = $('input[name="_token"]').val();
      $.ajax({
        url: '{{ route('language.default.cng') }}',
        method: 'POST',
        data: {_token:token,val:val},
        beforeSend:function(){
          $("#default_lan").html("<option>Loading...</option>");
          $("#default_lan").attr("disabled",'1');
        },
        success:function (result) {
          $("#default_lan").html(result);
          $("#default_lan").removeAttr('disabled');
        },
        error:function()
        {
          toastr.warning('Something went wrong');
        },
        complete:function()
        {
          toastr.success('Successfully changed');
          var table = $('#laravel_datatable').DataTable();
          table.ajax.reload();
        }

      });
    });

    $(".edit").click(function(){
       var currentInfo = $(this).attr("data-role");
       $("."+currentInfo+"Phrase").hide();
       $("#"+currentInfo+"Empty").hide();//where have no value but key
       $(this).addClass('d-none');
       $(this).parent().addClass("p-1");
       $("#"+currentInfo+"").removeClass('d-none');
       $('.edit_close').addClass('d-inline');
    });

    $("#code").keyup(function(event) {
      var code = $(this).val();
      $("#hiddenCode").val(code);
    });

  });

</script>
@endsection
@endsection
