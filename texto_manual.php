<script language="JavaScript">
	//---------------------
	function manual_compras() {
		$('#principal').html('<iframe src="https://scribehow.com/embed/Modulo_Compras__Y_IrTM8ESqqwpieshtDQ0g" width="640" height="640" allowfullscreen frameborder="0"></iframe>');
	}
	//---------------------
	function compras_1() {
		$('#principal').html('<iframe src="https://scribehow.com/embed/Generar_Presupuesto__QNiRCr5VTHafvFc6z95NRA" width="640" height="640" allowfullscreen frameborder="0"></iframe>');
	}
	//---------------------
	function compras_2() {
		$('#principal').html('<iframe src="https://scribehow.com/embed/Generar_Compra_yo_Servicio__cvJ9b3cHQpq6m3_yMM_aVg" width="640" height="640" allowfullscreen frameborder="0"></iframe>');
	}
	//---------------------
	function compras_3() {
		$('#principal').html('<iframe src="https://scribehow.com/embed/Modificar_Ordenes_de_Compra_y_Servicio__qfbyrPsvTEmxT9at97gQww" width="640" height="640" allowfullscreen frameborder="0"></iframe>');
	}

	//-------------------------
	function tecnologia() {
		setTimeout(function() {
			$('#ayuda').html('<a data-toggle="tooltip" title="INDICE MÓDULO TECNOLOGÍA" class="nav-item nav-link" href="#" onClick="manual_tecnologia();"><i class="fa-regular fa-circle-question fa-2x fa-fade"></i></a>');
		}, 1000);
		oculta_menus();
		$('#tecnologia').show();
	}

	//-------------------------
	function proveedor() {
		setTimeout(function() {
			$('#ayuda').html('<a data-toggle="tooltip" title="INDICE PROVEEDORES" class="nav-item nav-link" href="#" onClick="manual_proveedor();"><i class="fa-regular fa-circle-question fa-2x fa-fade"></i></a>');
		}, 1000);
		oculta_menus();
		$("#principal").load("proveedores/proveedor.php");
	}

	//-------------------------
	function viaticos() {
		setTimeout(function() {
			$('#ayuda').html('<a data-toggle="tooltip" title="INDICE MÓDULO VIATICOS" class="nav-item nav-link" href="#" onClick="manual_viatico();"><i class="fa-regular fa-circle-question fa-2x fa-fade"></i></a>');
		}, 1000);
		oculta_menus();
		$('#viaticos').show();
	}

	function dacs() {
		setTimeout(function() {
			$('#ayuda').html('<a onClick="manual_dacs();"><i class="fa-regular fa-circle-question fa-2x fa-fade"></i></a>');
		}, 1000);
		oculta_menus();

		$('#dacs').show();
	}

	function seguridad() {
		setTimeout(function() {
			$('#ayuda').html('<a data-toggle="tooltip" title="INDICE MÓDULO SEGURIDAD" class="nav-item nav-link" href="#" onClick="manual_seguridad();"><i class="fa-regular fa-circle-question fa-2x fa-fade"></i></a>');
		}, 1000);
		oculta_menus();
		$('#seguridad').show();
	}

	function poa() {
		setTimeout(function() {
			$('#ayuda').html('<a data-toggle="tooltip" title="INDICE MÓDULO POAI" class="nav-item nav-link" href="#" onClick="manual_poa();"><i class="fa-regular fa-circle-question fa-2x fa-fade"></i></a>');
		}, 1000);
		oculta_menus();
		$('#poa').show();
	}

	function correspondencia() {
		setTimeout(function() {
			$('#ayuda').html('<a data-toggle="tooltip" title="INDICE MÓDULO CORRESPONDENCIA" class="nav-item nav-link" href="#" onClick="manual_correspondencia();"><i class="fa-regular fa-circle-question fa-2x fa-fade"></i></a>');
		}, 1000);
		oculta_menus();
		$('#correspondencia').show();
	}

	function contabilidad() {
		setTimeout(function() {
			$('#ayuda').html('<a data-toggle="tooltip" title="INDICE MÓDULO CONTABILIDAD" class="nav-item nav-link" href="#" onClick="manual_contabilidad();"><i class="fa-regular fa-circle-question fa-2x fa-fade"></i></a>');
		}, 1000);
		oculta_menus();
		$('#contabilidad').show();
	}

	function presupuesto() {
		setTimeout(function() {
			$('#ayuda').html('<a data-toggle="tooltip" title="INDICE MÓDULO PRESUPUESTO" class="nav-item nav-link" href="#" onClick="manual_presupuesto();"><i class="fa-regular fa-circle-question fa-2x fa-fade"></i></a>');
		}, 1000);
		oculta_menus();
		$('#presupuesto').show();
	}

	function personal() {
		setTimeout(function() {
			$('#ayuda').html('<a data-toggle="tooltip" title="INDICE MÓDULO TALENTO HUMANO" class="nav-item nav-link" href="#" onClick="manual_personal();"><i class="fa-regular fa-circle-question fa-2x fa-fade"></i></a>');
		}, 1000);
		oculta_menus();
		$('#personal').show();
	}

	function admon() {
		setTimeout(function() {
			$('#ayuda').html('<a data-toggle="tooltip" title="INDICE MÓDULO ORDENACION DE PAGOS" class="nav-item nav-link" href="#" onClick="manual_admon();"><i class="fa-regular fa-circle-question fa-2x fa-fade"></i></a>');
		}, 1000);
		oculta_menus();
		$('#admon').show();
	}

	function compra() {
		oculta_menus();
		$('#compra').show();
	}

	function bienes() {
		setTimeout(function() {
			$('#ayuda').html('<a data-toggle="tooltip" title="INDICE MÓDULO BIENES NACIONALES" class="nav-item nav-link" href="#" onClick="manual_admon();"><i class="fa-regular fa-circle-question fa-2x fa-fade"></i></a>');
		}, 1000);
		oculta_menus();
		$('#bienes').show();
	}

	function archivo() {
		setTimeout(function() {
			$('#ayuda').html('<a data-toggle="tooltip" title="INDICE MÓDULO ARCHIVO" class="nav-item nav-link" href="#" onClick="manual_archivo();"><i class="fa-regular fa-circle-question fa-2x fa-fade"></i></a>');
		}, 1000);
		oculta_menus();
		$('#archivo').show();
	}

	function almacen() {
		setTimeout(function() {
			$('#ayuda').html('<a data-toggle="tooltip" title="INDICE MÓDULO ALMACEN" class="nav-item nav-link" href="#" onClick="manual_admon();"><i class="fa-regular fa-circle-question fa-2x fa-fade"></i></a>');
		}, 1000);
		oculta_menus();
		$('#almacen').show();
	}
</script>