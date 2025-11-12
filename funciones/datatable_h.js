$(document).ready( function () {
    var table = $('.datatabla').DataTable(
            {
			language: {
				search: "_INPUT_",
				searchPlaceholder: "Buscar...",

				"decimal":        "",
				"emptyTable":     "No hay datos disponibles",
				"info":           "Mostrando _START_ al _END_ de _TOTAL_ registros",
				"infoEmpty":      "Mostrando 0 al 0 de 0 entradas",
				"infoFiltered":   "(filtrado desde _MAX_ total registros)",
				"infoPostFix":    "",
				"thousands":      ",",
				"lengthMenu":     "Mostrar _MENU_ registros",
				"loadingRecords": "Cargando...",
				"processing":     "",
				"search":         "Buscar:",
				"zeroRecords":    "No se encontraron registros",
				"paginate": {
					"first":      "Primero",
					"last":       "Ultimo",
					"next":       "Siguiente",
					"previous":   "Anterior"
							},
				"aria": {
					"sortAscending":  ": activar el orden ascendente",
					"sortDescending": ": activar el orden descendente"
						}
					},
				responsive: "true",
				order: "",
				dom: 'Brtlp',  
				buttons: {
						dom: {
							button: {
								className: 'btn btn-success',
								pageSize: 'LEGAL',
								orientation: 'landscape'
							}
						},
							buttons: [
								{
									extend:    'excelHtml5',
									text:      '<i class="fas fa-file-excel"></i> ',
									titleAttr: 'Exportar a Excel',
									className: 'btn btn-success',
									pageSize: 'LEGAL',
									orientation: 'landscape'
								},
								{
									extend:    'pdfHtml5',
									text:      '<i class="fas fa-file-pdf"></i> ',
									titleAttr: 'Exportar a PDF',
									className: 'btn btn-danger',
									pageSize: 'LEGAL',
									orientation: 'landscape'
								},
								{
									extend:    'print',
									text:      '<i class="fa fa-print"></i> ',
									titleAttr: 'Imprimir',
									className: 'btn btn-info',
									pageSize: 'LEGAL',
									orientation: 'landscape'
								}
							]
						}	
            });
	//-------------
    $('#obuscar').on( 'keyup', function () {
    table.search( this.value ).draw();
} );
} );   