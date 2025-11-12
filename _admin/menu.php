<nav class="navbar navbar-expand-lg navbar-dark" style="background-color:#0275d8">
    <a class="navbar-brand" href="../principal.php">
        <h2>CEBG</h2>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation"><span style="" class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
            <a data-toggle="tooltip" title="PROVEEDORES" class="nav-item nav-link" href="interno.php#/contribuyentes"><i class="fa-solid fa-truck-field fa-lg fa-fade ml-4" style="cursor:pointer; color:powderblue"></i></a>

            <!-- <a data-toggle="tooltip" title="USUARIOS" class="nav-item nav-link" href="interno.php#/usuarios"><i class="fa-solid fa-users fa-lg fa-fade" style="cursor:pointer; color:powderblue" ></i></a> -->
        </div>
    </div>
    <div class="navbar-nav" align="right">
        <h6 class="texto_blanco" onClick="cambio_clave();" data-toggle="modal" data-target="#modal_normal">
            <?php echo $_SESSION['USUARIO']; ?>
        </h6>
    </div>
    <div align="right"><button type="button" class="btn btn-outline-light btn-sm ml-5 mr-5 font-weight-bold" onClick="salir();"><i class="fas fa-door-open"></i></button></div>
</nav>