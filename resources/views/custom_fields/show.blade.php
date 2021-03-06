@extends('layouts/default')

{{-- Page title --}}
@section('title')
  {{ trans('admin/custom_fields/general.custom_fields') }}
@parent
@stop

@section('header_right')
    <a href="{{ route('admin.custom_fields.index') }}" class="btn btn-primary pull-right">
        {{ trans('general.back') }}</a>
@stop



@section('content')



  <div class="row">
    <div class="col-md-12">

      <div class="box box-default">

          <div class="box-header with-border">
            <h3 class="box-title">{{ $custom_fieldset->name }} {{ trans('admin/custom_fields/general.fieldset') }}</h3>
            <div class="box-tools pull-right">

            </div>
          </div><!-- /.box-header -->
         <div class="box-body">

           <table
           name="fieldsets"
           id="sort" class="table table-responsive todo-list">
               <thead>
                   <tr>
                     <th class="col-md-1"></th>
                     <th class="col-md-1">{{ trans('admin/custom_fields/general.order') }}</th>
                     <th class="col-md-3">{{ trans('admin/custom_fields/general.field_name') }}</th>
                     <th class="col-md-2">{{ trans('admin/custom_fields/general.field_format') }}</th>
                     <th class="col-md-2">{{ trans('admin/custom_fields/general.field_element') }}</th>
                     <th class="col-md-1">{{ trans('admin/custom_fields/general.encrypted') }}</th>
                     <th class="col-md-1">{{ trans('admin/custom_fields/general.required') }}</th>
                     <th class="col-md-1"></th>
                   </tr>
               </thead>
               <tfoot>
                   <tr>
                     <td colspan="5" class="text-right">
                       {{ Form::open(['route' => ["admin.custom_fields.associate",$custom_fieldset->id], 'class'=>'form-horizontal', 'id' => 'ordering']) }}
                       {{ Form::checkbox("required","on") }}
                       {{ trans('admin/custom_fields/general.required') }}
                       {{ Form::text("order",$maxid)}}
                       {{ Form::select("field_id",$custom_fields_list,"",["onchange" => "$('#ordering').submit()"]) }}
                       <span class="alert-msg"><?= $errors->first('field_id'); ?></span>

                     </td>
                   </tr>
               </tfoot>
               <tbody>
                 @foreach($custom_fieldset->fields as $field)
                 <tr class="cansort" data-index="{{ $field->pivot->custom_field_id }}" id="item_{{ $field->pivot->custom_field_id }}">
                   <td>
                     <!-- drag handle -->
                         <span class="handle">
                           <i class="fa fa-ellipsis-v"></i>
                           <i class="fa fa-ellipsis-v"></i>
                         </span>
                       </td>
                   <td class="index">{{$field->pivot->order}}</td>
                   <td>{{$field->name}}</td>
                   <td>{{$field->format}}</td>
                   <td>{{$field->element}}</td>
                     <td>{{ $field->field_encrypted=='1' ?  trans('general.yes') : trans('general.no') }}</td>
                   <td>{{$field->pivot->required ? "REQUIRED" : "OPTIONAL"}}</td>
                   <td><a href="{{ route('admin.custom_fields.disassociate', [$field,$custom_fieldset->id]) }}" class="btn btn-sm btn-danger">Remove</a></td>
                 </tr>
                 @endforeach
               </tbody>
         </table>

         </div>
      </div>
  </div>
</div>


</div>


@section('moar_scripts')
  <script>
  var fixHelperModified = function(e, tr) {
      var $originals = tr.children();
      var $helper = tr.clone();
      $helper.children().each(function(index) {
          $(this).width($originals.eq(index).width())
      });
      return $helper;
  },
      updateIndex = function(e, ui) {
          $('td.index', ui.item.parent()).each(function (i) {
              $(this).html(i + 1);
              $.ajax({
                method: "POST",
                url: "{{ route('api.customfields.order', $custom_fieldset->id)  }}",
                data: $("#sort tbody").sortable('serialize', {
                }),

                success: function(data) {
                    //console.log('ajax fired');
                    // do some stuff here


                }
      	    });
          });
      };

  $("#sort tbody").sortable({
      helper: fixHelperModified,
      stop: updateIndex
  }).disableSelection();
</script>

@stop
@stop
