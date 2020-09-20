@extends('layouts.template')
@section('title','CRUD-Builder')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>{{__('app.crud_builder')}}</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">{{__('app.home')}}</a></li>
            <li class="breadcrumb-item active">{{__('app.crud_builder')}}</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <section class="content">
      <div class="container-fluid">
          <div class="row">
            <!-- #############################################CRUD BUILDER####################################### -->
              <div class="col-md-12">
                  <section class="content">
                      <div class="card col-lg-12 p-0">
                          <div class="card-header">
                              <h1 class="card-title">{{__('app.crud_builder')}}</h1>
                          </div>
                          <div class="card-body">
                              <form id="form">
                                  <input type="hidden" name="_token" id="token" value="{!! csrf_token() !!}"/>
                                  <div class="row">
                                        <div class="form-group col-md-4">
                                            <label for="txtModelName">{{__('app.model_name')}}<span class="required" data-toggle="tooltip" data-placement="top" title="Tooltip on top">*</span></label>
                                            <input type="text" class="form-control" required id="txtModelName" placeholder="{{__('app.enter_model_name')}}">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="drdCommandType">{{__('app.command_type')}}</label>
                                            <select id="drdCommandType" class="form-control form-control-md width-100">
                                                <option value="infyom:scaffold">{{__('app.scaffold_generator')}}</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="txtCustomTblName">{{__('app.custom_table_name')}}</label>
                                            <input type="text" class="form-control" id="txtCustomTblName" placeholder="{{__('app.enter_table_name')}}">
                                        </div>
                                        <div class="form-group col-md-5">
                                          <label for="txtModelName">{{__('app.options')}}</label>
                                            <div class="form-inline form-group transparent-border">
                                                <div class="checkbox chk-align ml-3">
                                                    <label>
                                                        <input type="checkbox" class="flat-red " id="chkDelete"><span
                                                            class="chk-label-margin mx-1">  {{__('app.soft_delete')}} </span>
                                                    </label>
                                                </div>
                                                <div class="checkbox chk-align ml-3" id="chDataTable">
                                                    <label>
                                                        <input type="checkbox" class="flat-red " id="chkDataTable" checked>
                                                        <span class="chk-label-margin mx-1">{{__('app.databases')}}</span>
                                                    </label>
                                                </div>
                                                <div class="checkbox chk-align" id="chForceMigrate">
                                                    <label>
                                                        <input type="hidden" class="flat-red " id="chkMigration" checked>
                                                        <input type="checkbox" class="flat-red" id="chkForceMigrate" checked>
                                                        <span class="chk-label-margin">{{__('app.migrate')}}</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                          <label for="txtPrefix">{{__('app.prefix')}}</label>
                                          <input type="text" class="form-control" id="txtPrefix" placeholder="Enter prefix">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="txtPaginate">{{__('app.paginate')}}</label>
                                            <input type="number" class="form-control" value="10" id="txtPaginate" placeholder="">
                                        </div>
                                        <div class="form-group col-md-12">
                                            <div class="form-control transparent-border padding-left-0">
                                                <label>{{__('app.fields')}}</label>
                                            </div>
                                        </div>

                                        <div class="table-responsive col-md-12">
                                            <table class="table table-striped table-bordered" id="table">
                                                <thead class="no-border">
                                                <tr>
                                                    <th>{{__('app.field_name')}}</th>
                                                    <th>{{__('app.db_type')}}</th>
                                                    <th>{{__('app.validation')}}</th>
                                                    <th>{{__('app.html_type')}}</th>
                                                    <th class="px-width-68">{{__('app.primary')}}</th>
                                                    <th class="px-width-80">{{__('app.is_foreign')}}</th>
                                                    <th class="px-width-87">{{__('app.searchable')}}</th>
                                                    <th class="px-width-63">{{__('app.fillable')}}</th>
                                                    <th class="px-width-65">{{__('app.in_form')}}</th>
                                                    <th class="px-width-67">{{__('app.in_index')}}</th>
                                                    <th></th>
                                                </tr>
                                                </thead>
                                                <tbody id="container" class="no-border-x no-border-y ui-sortable">

                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="form-inline col-md-6 mt-2">
                                            <div class="form-group mx-1 col-md-12 transparent-border">
                                                <button type="button" class="btn btn-success btn-green col-md-4" id="btnAdd"> {{__('app.add_field')}}
                                                </button>
                                            </div>
                                        </div>

                                  <div class="form-inline col-md-12 mt-5">
                                          <div class="form-group col-md-6 transparent-border padding-left-10">
                                            <button type="submit" class="btn  btn-primary btn-blue col-md-12" id="btnGenerate">{{__('app.generate')}}
                                            </button>
                                          </div>
                                          <div class="form-group col-md-6 transparent-border padding-left-10">
                                              <button type="button" class="btn btn-light col-md-12 " id="btnReset" data-toggle="modal"
                                              data-target="#confirm-delete">{{__('app.reset')}}
                                            </button>
                                          </div>
                                  </div>


                                  <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog"
                                       aria-labelledby="myModalLabel" aria-hidden="true">
                                      <div class="modal-dialog">
                                          <div class="modal-content">

                                              <div class="modal-header">
                                                  <button type="button" class="close" data-dismiss="modal"
                                                          aria-hidden="true">&times;</button>
                                                  <h4 class="modal-title" id="myModalLabel">{{__('app.confirm_reset')}}</h4>
                                              </div>

                                              <div class="modal-body">
                                                  <p>{{__('app.confirm_reset_message')}}</p>

                                                  <p class="debug-url"></p>
                                              </div>

                                              <div class="modal-footer">
                                                  <button type="button" class="btn  btn-default" data-dismiss="modal">{{__('app.no')}}
                                                  </button>
                                                  <a id="btnModelReset" class="btn  btn-danger btn-ok" data-dismiss="modal">{{__('app.yes')}}</a>
                                              </div>
                                          </div>
                                      </div>
                                  </div>

                                </div>
                              </form>

                          </div>
                      </div>
                  </section>
              </div>
              <!-- #############################################CRUD BUILDER####################################### -->

              <!-- #############################################CRUD ROLLBACK####################################### -->
              <div class="col-md-12">
                  <section class="content">
                      <div id="rollbackInfo display-none"></div>
                      <div class="card card-primary col-lg-12 p-0">
                          <div class="card-header">
                              <h1 class="card-title">{{__('app.rollback')}}</h1>
                          </div>
                          <div class="card-body">
                              <form id="rollbackForm">
                                  <input type="hidden" name="_token" id="rbToken" value="{!! csrf_token() !!}"/>
                                  <div class="row">
                                      <div class="form-group col-md-4">
                                          <label for="txtRBModelName">{{__('app.model_name')}}<span class="required">*</span></label>
                                          <input type="text" class="form-control" required id="txtRBModelName" placeholder="{{__('app.enter_model_name')}}">
                                      </div>
                                      <div class="form-group col-md-4">
                                          <label for="drdRBCommandType">{{__('app.command_type')}}</label>
                                          <select id="drdRBCommandType" class="form-control width-100">
                                              <option value="scaffold" selected>{{__('app.scaffold_generator')}}</option>
                                          </select>
                                      </div>
                                      <div class="form-group col-md-4">
                                          <label for="txtRBPrefix">{{__('app.prefix')}}</label>
                                          <input type="text" class="form-control" id="txtRBPrefix" placeholder="{{__('app.enter_prefix')}}">
                                      </div>
                                      <div class="form-inline col-md-12 text-right padding-15">
                                      <div class="form-group transparent-border padding-left-10">
                                          <button type="submit" class="btn  btn-primary btn-blue" id="btnRollback">{{__('app.rollback')}}
                                          </button>
                                      </div>
                                  </div>
                                  </div>
                              </form>
                          </div>
                      </div>
                  </section>
              </div>
              <!-- #############################################CRUD ROLLBACK####################################### -->
          </div>
      </div>
</section>
</div>
@endsection
 <!-- </body> -->
@section('script')
<script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>
<script src="{{asset('plugins/select2/js/select2.min.js')}}"></script>

<script>
"use strict";
$("select").select2({width: '100%'});
var fieldIdArr = [];
    $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '10%' // optional
    });

    $("#drdCommandType").on("change", function () {
        if ($(this).val() == "infyom:scaffold") {
            $('#chSwag').hide();
            $('#chTest').hide();
        }
        else {
            $('#chSwag').show();
            $('#chTest').show();
        }
    });

    $("#chkForceMigrate").on("ifChanged", function () {
        if ($(this).prop('checked') == true) {
            $('#chkMigration').iCheck("check", true);
            $('#chkMigration').iCheck("disable", true);
        } else {
            $('#chkMigration').iCheck("enable", true);
        }
    });
    var modelUrl = "{{URL::to('/model-check')}}";
    function check(model)
    {

      $.ajax({
          url: modelUrl,
          type: 'POST',
          dataType: 'json',
          contentType: 'application/json',
          data: JSON.stringify(model),
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function (result) {
              if (result.status == false) {
                  swal({
                      icon: 'error',
                      text: result.message,
                  });
                  $('#btnGenerate').prop('disabled', true);
              }
              else {
                  $('#btnGenerate').prop('disabled', false);
              }
          },
      });
    }

    $(document).ready(function () {
        var htmlStr = '<tr class="item display-table-row"></tr>';
        var commonComponent = $(htmlStr).filter("tr").load('{{ route('field-template') }}');

        $('#txtModelName').on('keyup',()=>{
          $('#txtCustomTblName').val($('#txtModelName').val().toLowerCase().replace(/\s/g, "_") + 's')
            if($('#txtModelName').val() == ''){
                $('#txtCustomTblName').val('')
            }
          var modelName = $('#txtModelName').val($('#txtModelName').val().charAt(0).toUpperCase() + $('#txtModelName').val().slice(1))
          check($('#txtModelName').val())

        });

        $("#btnAdd").on("click", function () {
            var item = $(commonComponent).clone();
            initializeCheckbox(item);
            $("#container").append(item);
        });

        $("#btnTimeStamps").on("click", function () {
            var item_created_at = $(commonComponent).clone();
            $(item_created_at).find('.txtFieldName').val("created_at");
            renderTimeStampData(item_created_at);
            initializeCheckbox(item_created_at);
            $("#container").append(item_created_at);

            var item_updated_at = $(commonComponent).clone();
            $(item_updated_at).find('.txtFieldName').val("updated_at");
            renderTimeStampData(item_updated_at);
            initializeCheckbox(item_updated_at);
            $("#container").append(item_updated_at);
        });

        $("#btnPrimary").on("click", function () {
            var item = $(commonComponent).clone();
            renderPrimaryData(item);
            initializeCheckbox(item);
            $("#container").append(item);
        });

        $("#btnModelReset").on("click", function () {
            $("#container").html("");
            $('input:text').val("");
            $('input:checkbox').iCheck('uncheck');
        });


        $("#form").on("submit", function () {
            var fieldArr = [];
            var relationFieldArr = [];
            $('.item').each(function () {
                var htmlType = $(this).find('.drdHtmlType');
                var htmlValue = "";
                if ($(htmlType).val() == "select" || $(htmlType).val() == "radio") {
                    htmlValue = $(this).find('.drdHtmlType').val() + ',' + $(this).find('.txtHtmlValue').val();
                }
                else {
                    htmlValue = $(this).find('.drdHtmlType').val();
                }

                fieldArr.push({
                    name: $(this).find('.txtFieldName').val(),
                    dbType: $(this).find('.txtdbType').val(),
                    htmlType: htmlValue,
                    validations: $(this).find('.txtValidation').val(),
                    foreignTable: $(this).find('.txtForeignTable').val(),
                    isForeign: $(this).find('.chkForeign').prop('checked'),
                    searchable: $(this).find('.chkSearchable').prop('checked'),
                    fillable: $(this).find('.chkFillable').prop('checked'),
                    primary: $(this).find('.chkPrimary').prop('checked'),
                    inForm: $(this).find('.chkInForm').prop('checked'),
                    inIndex: $(this).find('.chkInIndex').prop('checked')
                });
            });

            // Insertion of Primary column ['id']
            fieldArr.unshift({
              name:'id',
              dbType:"increments",
              htmlType:"number",
              validations:"",
              foreignTable:"",
              isForeign:false,
              searchable:false,
              fillable:false,
              primary:true,
              inForm:false,
              inIndex:false
            });

            var data = {
                modelName: $('#txtModelName').val(),
                commandType: $('#drdCommandType').val(),
                tableName: $('#txtCustomTblName').val(),
                migrate: $('#chkMigration').prop('checked'),
                options: {
                    softDelete: $('#chkDelete').prop('checked'),
                    save: $('#chkSave').prop('checked'),
                    prefix: $('#txtPrefix').val(),
                    paginate: $('#txtPaginate').val(),
                    forceMigrate: $('#chkForceMigrate').prop('checked'),
                },
                addOns: {
                    swagger: $('#chkSwagger').prop('checked'),
                    tests: $('#chkTestCases').prop('checked'),
                    datatables: $('#chkDataTable').prop('checked')
                },
                fields: fieldArr,
                relations: relationFieldArr
            };

            data['_token'] = $('#token').val();
            $('#btnGenerate').prop('disabled', true);
            $.ajax({
                url: '{{ route('generator-builder/generate') }}',
               // type: "POST",
                method: "POST",
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify(data),
                success: function (result) {
                    // Sweet Alert notification for success
                    swal({
                          text: result.message,
                          title: "success",
                          icon: "success",
                          button: "Ok",
                      }).then((result) => {
                            location.reload();
                            $('#btnGenerate').prop('disabled', false);
                            $('#btnReset').prop('disabled', false);
                    });
                },
                error: function (result) {
                    var result = JSON.parse(JSON.stringify(result));
                    var errorMessage = '';
                    if (result.hasOwnProperty('responseJSON') && result.responseJSON.hasOwnProperty('message')) {
                        errorMessage = result.responseJSON.message;
                    }
                    // Sweet Alert notification for failed
                    swal({
                      text: errorMessage,
                      title: "error",
                      icon: "error",
                      button: "Ok",
                    }).then((result) => {
                          location.reload();
                          $('#btnGenerate').prop('disabled', false);
                          $('#btnReset').prop('disabled', false);
                    });

                }
            });

            return false;
        });

        $('#rollbackForm').on("submit", function (e) {
            e.preventDefault();

            var data = {
                modelName: $('#txtRBModelName').val(),
                commandType: $('#drdRBCommandType').val(),
                prefix: $('#txtRBPrefix').val(),
                _token: $('#rbToken').val()
            };

            $.ajax({
                url: '{{ route('generator-builder/rollback') }}',
                method: "POST",
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify(data),
                success: function (result) {
                    swal({
                          text: result.message,
                          title: "success",
                          icon: "success",
                          button: "Ok",
                      }).then((result) => {
                              location.reload();
                    });

                },
                error: function (result) {
                    var result = JSON.parse(JSON.stringify(result));
                    var errorMessage = '';
                    if (result.hasOwnProperty('responseJSON') && result.responseJSON.hasOwnProperty('message')) {
                        errorMessage = result.responseJSON.message;
                    }

                      swal({
                        text: errorMessage,
                        title: "error",
                        icon: "error",
                        button: "Ok",
                      });
                      $('#btnReset').prop('disabled', false);
                }
            });
        });

        $('#schemaFile').change(function () {

            var ext = $(this).val().split('.').pop().toLowerCase();
            if (ext != 'json') {
                $("#schemaInfo").html("");
                $("#schemaInfo").append('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Schema file must be json</strong></div>');
                $("#schemaInfo").show();
                $(this).replaceWith($(this).val('').clone(true));
                setTimeout(function () {
                    $('div.alert').fadeOut('fast');
                }, 3000);
            }
        });

        function renderPrimaryData(el) {

            $('.chkPrimary').iCheck(getiCheckSelection(false));

            $(el).find('.txtFieldName').val("id");
            $(el).find('.txtdbType').val("increments");
            $(el).find('.chkSearchable').attr('checked', false);
            $(el).find('.chkFillable').attr('checked', false);
            $(el).find('.chkPrimary').attr('checked', true);
            $(el).find('.chkInForm').attr('checked', false);
            $(el).find('.chkInIndex').attr('checked', false);
            $(el).find('.drdHtmlType').val('number').trigger('change');
        }

        function renderTimeStampData(el) {

            $(el).find('.txtdbType').val("timestamp");
            $(el).find('.chkSearchable').attr('checked', false);
            $(el).find('.chkFillable').attr('checked', false);
            $(el).find('.chkPrimary').attr('checked', false);
            $(el).find('.chkInForm').attr('checked', false);
            $(el).find('.chkInIndex').attr('checked', false);
            $(el).find('.drdHtmlType').val('date').trigger('change');
        }

    });

    function initializeCheckbox(el) {

        $(el).find('input')
        $(el).find("select").select2({width: '100%'});

        $(el).find(".chkPrimary").on("ifClicked", function () {
            $('.chkPrimary').each(function () {
                $(this).iCheck('uncheck');
            });
        });

        $(el).find(".chkForeign").on("ifChanged", function () {

            if ($(this).prop('checked') == true) {
                $(el).find('.foreignTable').show();
            } else {
                $(el).find('.foreignTable').hide();
            }
        });

        $(el).find(".chkPrimary").on("ifChanged", function () {
            if ($(this).prop('checked') == true) {
                $(el).find(".chkSearchable").iCheck('uncheck');
                $(el).find(".chkFillable").iCheck('uncheck');
                $(el).find(".chkInForm").iCheck('uncheck');
            }
        });

        var htmlType = $(el).find('.drdHtmlType');

        $(htmlType).select2().on('change', function () {
            if ($(htmlType).val() == "select" || $(htmlType).val() == "radio")
                $(el).find('.htmlValue').show();
            else
                $(el).find('.htmlValue').hide();
        });
    }

function getiCheckSelection(value) {
    if (value == true)
        return 'checked';
    else
        return 'uncheck';
}
function removeItem(e) {
    e.parentNode.parentNode.parentNode.removeChild(e.parentNode.parentNode);
}

</script>

@endsection
<!-- </html> -->
