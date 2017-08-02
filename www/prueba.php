<?php
    $select_carreras = null;
    $select_facultades = null;
?>
<!-- html -->
	<!-- body -->
		<!-- div wrapper -->
			<link rel="stylesheet" type="text/css" href="plugins/datatables/css/dataTables.bootstrap.css"/>
			<link rel="stylesheet" type="text/css" href="plugins/datepicker/css/bootstrap-datepicker3.css"/>
			<link rel="stylesheet" type="text/css" href="plugins/bootstrap-toggle/css/bootstrap-toggle.min.css"/>
			
			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1>
						Edición de Datos Planificación Curricular
					</h1>
					
					<ol class="breadcrumb">
						<li><a href="index.php"><i class="fa fa-home"></i> Inicio</a></li>
						<li class="active">Edición de Datos Planificación Curricular</li>
					</ol>
				</section>
				
				<!-- Main content -->
				<section class="content">
					<!-- Default box -->
					<div class="box">
						<div class="box-body">
							<table class="table table-bordered table-hover" width="100%">
								<thead>
									<tr>
										<th>Rut</th>
										<th>Apellido Paterno</th>
										<th>Apellido Materno</th>
										<th>Nombres</th>
										<th>Boton</th>
									</tr>
								</thead>
								<tbody class="text-center">
								</tbody>
								<tfoot>
									<tr>
										<th colspan="7"><a id="add" class="pull-right btn btn-primary"><i class="fa fa-lg fa-plus-square btn-icon"></i> Agregar</a></th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</section>
			</div>
			<script src="bower_components/jquery/dist/jquery.min.js"></script>
			<script type="text/javascript" src="plugins/datatables/js/jquery.dataTables.js"></script>
			<script type="text/javascript" src="plugins/datatables/js/dataTables.bootstrap.js"></script>
			<script type="text/javascript" src="plugins/datepicker/js/bootstrap-datepicker.js"></script>
			<script type="text/javascript" src="plugins/bootstrap-toggle/js/bootstrap-toggle.min.js"></script>
			<script type="text/javascript" src="plugins/validator/jquery.validate.js"></script>
			<script>
				$.ajax({
					type: 'POST',
					url: 'php/getPlanificacionCurricular.php',
					dataType: 'JSON',
					data: { getData: true },
					error: function(jqXHR, textStatus, errorThrown){
						console.log(jqXHR, textStatus, errorThrown);
						alert("ERROR Error al conectar con el servidor. error");
					},
					success: function(data){
						table = $(".table").dataTable({
							autoWidth: true,
							data: data,
							columns: [
								{ data: "facultad" },
								{ data: "carrera" },
								{ data: "year" },
								{ data: "revision" },
								{ data: "fecha_ingreso" },
								{ data: "comentario" },
								{ data: null, orderable: false, searchable: false }
							],
							createdRow: function(row, data, index){
								if(data.acciones !== undefined){
									$('.select2', row).select2();
									$('#year', row).datepicker({
										autoclose: true,
										todayHighlight: true,
										minViewMode: "years",
										format: "yyyy",
										language: "es",
										toggleActive: true
									});
									$('#revision', row).bootstrapToggle();
									$('#ingreso', row).datepicker({
										autoclose: true,
										todayHighlight: true,
										daysOfWeekDisabled: [0, 6],
										format: "dd/mm/yyyy",
										language: "es",
										toggleActive: true,
										weekStart: 1
									});
									$('td', row).eq(6).html(data.acciones);
									$('.delete', row).click(function(){
										var row = this.closest('tr');
										swal({
											title: "ADVERTENCIA",
											text: "Realmente desea eliminar la fila seleccionada?",
											type: "warning",
											showCancelButton: true,
											confirmButtonText: "Si",
											cancelButtonText: "No"
										}, function (confirm){
											if(confirm){
												table.api().row(row).remove().draw(false);
											}
										});
									});
									$('.save', row).click(function(){
										if(!validator.checkAll($(this.closest("tr")))){
											swal("ERROR", "Debe ingresar todos los datos requeridos.", "error");
										} else {
											var row = this.closest('tr');
											swalWarningAjaxFunction(
												"Realmente desea ingresar los datos al sistema?",
												{
													guardar: true,
													facultad: $('#facultad', row).val(),
													carrera: $('#carrera', row).val(),
													year: $('#year', row).val(),
													revision: $('#revision', row).is(":checked"),
													ingreso: $('#ingreso', row).val(),
													comentario: $('#comentario', row).val()
												},
												"php/ingresarPlanificacionCurricular.php",
												function(data){
													if(data.error){
														swal("ERROR", data.msg, "error");
													} else {
														swal("ÉXITO", data.msg, "success");
														table.api().row(row).data(data.datos).draw(false);
													}
												},
												"A cancelado el ingreso."
											);
										}
									});
								}
							},
							drawCallback: function( settings ) {
								var rows = this.api().rows({order: 'current', page: 'current'}).nodes();
								for(var i = 0; i < rows.length; i++){
									var row = rows[i], data = this.api().row(row).data();
									
									if(data.id !== undefined){
										if(data.acciones === undefined){
											$('td', row).eq(3).html(data.revision == 1 ? "Si" : "No");
											if(moment(data.fecha_ingreso).isValid())
												$('td', row).eq(4).html(moment(data.fecha_ingreso).format("DD/MM/YYYY"));
											$('td', row).eq(6).html(
												"<a href='#' class='edit btn btn-sm btn-info'><i class='fa fa-lg fa-edit'></i></a>" +
												"<a href='#' class='delete btn btn-sm btn-danger'><i class='fa fa-lg fa-trash-o'></i></a>"
											);
											$('.edit', row).click(function(){
												var row = this.closest('tr'), data = table.api().row(row).data();
												
												table.api().row(row).data({
													backup: data,
													id: data.id,
													facultad: '<?php echo $select_facultades; ?>',
													carrera: '<?php echo $select_carreras; ?>',
													year: "<input type='text' id='year' name='year' maxlength='4' value='" + data.year + "' required='required' class='col-md-12' readonly>",
													revision: "<input id='revision' name='revision'" + (data.revision == 1 ? " checked" : "") + " type='checkbox' class='form-control' data-toggle='toggle' data-on='Si' data-off='No'>",
													fecha_ingreso: "<input type='text' id='ingreso' name='ingreso' required='required' class='col-md-12' readonly>",
													comentario: "<input type='text' id='comentario' name='comentario' value='" + data.comentario + "' maxlength='300' required='required' class='col-md-12'>",
													acciones: "<a href='#' class='save btn btn-sm btn-success'><i class='fa fa-lg fa-save'></i></a>" +
															  "<a href='#' class='cancel btn btn-sm btn-danger'><i class='fa fa-lg fa-remove'></i></a>"
												}).draw(false);
												
												$('#facultad', row).val(data.id_facultad);
												$('#carrera', row).val(data.codigo_carrera);
												$('.select2', row).select2();
												$('#year', row).datepicker({
													autoclose: true,
													todayHighlight: true,
													minViewMode: "years",
													format: "yyyy",
													language: "es",
													toggleActive: true
												});
												$('#revision', row).bootstrapToggle();
												$('#ingreso', row).datepicker({
													autoclose: true,
													todayHighlight: true,
													daysOfWeekDisabled: [0, 6],
													format: "dd/mm/yyyy",
													language: "es",
													toggleActive: true,
													weekStart: 1
												});
												if(moment(data.fecha_ingreso).isValid())
													$('#ingreso', row).datepicker('update', moment(data.fecha_ingreso).format("DD/MM/YYYY"));
											});
											$('.delete', row).click(function(){
												var row = this.closest('tr'), data = table.api().row(row).data();
												swalWarningAjaxFunction(
													"Realmente desea eliminar los datos seleccionados del sistema?",
													{ deleteData: true, id: data.id },
													"php/eliminarPlanificacionCurricular.php",
													function(data){
														if(data.error){
															swal("ERROR", data.msg, "error");
														} else {
															swal("ÉXITO", data.msg, "success");
															table.api().row(row).remove().draw(false);
														}
													},
													"A cancelado la eliminación."
												);
											});
										} else {
											$('td', row).eq(6).html(data.acciones);
											$('.save', row).click(function(){
												if(!validator.checkAll($(this.closest("tr")))){
													swal("ERROR", "Debe ingresar todos los datos requeridos.", "error");
												} else {
													var row = this.closest('tr'), data = table.api().row(row).data();
													swalWarningAjaxFunction(
														"Realmente desea actualizar los datos al sistema?",
														{
															actualizar: true,
															id: data.id,
															facultad: $('#facultad', row).val(),
															carrera: $('#carrera', row).val(),
															year: $('#year', row).val(),
															revision: $('#revision', row).is(":checked"),
															ingreso: $('#ingreso', row).val(),
															comentario: $('#comentario', row).val()
														},
														"php/actualizarPlanificacionCurricular.php",
														function(data){
															if(data.error){
																swal("ERROR", data.msg, "error");
															} else {
																swal("ÉXITO", data.msg, "success");
																table.api().row(row).data(data.datos).draw(false);
															}
														},
														"A cancelado la actualización."
													);
												}
											});
											$('.cancel', row).click(function(){
												var row = this.closest('tr'), data = table.api().row(row).data();
												table.api().row(row).data(data.backup).draw(false);
											});
										}
									}
								}
							}
						});
					}
				});
				
				$("#add").click(function(){
					table.api().row.add({
						facultad: '<?php echo $select_facultades; ?>',
						carrera: '<?php echo $select_carreras; ?>',
						year: "<input type='text' id='year' name='year' maxlength='4' required='required' class='col-md-12' readonly>",
						revision: "<input id='revision' name='revision' type='checkbox' class='form-control' data-toggle='toggle' data-on='Si' data-off='No'>",
						fecha_ingreso: "<input type='text' id='ingreso' name='ingreso' required='required' class='col-md-12' readonly>",
						comentario: "<input type='text' id='comentario' name='comentario' maxlength='300' required='required' class='col-md-12'>",
						acciones: "<a href='#' class='save btn btn-sm btn-success'><i class='fa fa-lg fa-save'></i></a>" +
								  "<a href='#' class='delete btn btn-sm btn-danger'><i class='fa fa-lg fa-trash-o'></i></a>"
					}).draw(false);
				});
			</script>
		<!-- div wrapper -->
	<!-- body -->
