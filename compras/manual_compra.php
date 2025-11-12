<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-primary">
            <h1 class="card-title text-white font-weight-bold" style="font-size: 1.8rem;">Manual del Módulo de Compras</h1>
        </div>
        <div class="card-body">
            <h4>Introducción</h4>
            <p>El módulo de Compras gestiona todo el proceso de adquisiciones de bienes y servicios de la institución. A continuación, se detalla el funcionamiento de cada opción para guiar al usuario en la realización de los procesos.</p>
            <hr>

            <h4>1. Compromiso</h4>
            <p>Esta sección es el punto de partida para cualquier adquisición. Aquí se inicia el proceso y se asegura la disponibilidad de fondos.</p>
            <ul>
                <li>
                    <strong>Presupuesto:</strong>
                    <ol>
                        <li><strong>Verificar Disponibilidad:</strong> Antes de crear una orden, acceda aquí para consultar si la partida presupuestaria a la que se imputará el gasto tiene fondos suficientes.</li>
                        <li><strong>Apartar Fondos:</strong> El sistema permite "apartar" el monto estimado de la compra. Esto descuenta el valor de la disponibilidad, evitando que esos fondos se usen en otro compromiso.</li>
                        <li><strong>Paso Crítico:</strong> Este es un paso obligatorio. Sin un compromiso presupuestario, no podrá generar una orden de compra.</li>
                    </ol>
                </li>
                <li>
                    <strong>Compra y/o Servicio:</strong>
                    <ol>
                        <li><strong>Generar Orden:</strong> Una vez apartado el presupuesto, utilice esta opción para crear la Orden de Compra o de Servicio.</li>
                        <li><strong>Asociar Proveedor:</strong> Busque y seleccione el proveedor al que se le realizará la compra.</li>
                        <li><strong>Detallar Productos/Servicios:</strong> Ingrese los artículos o servicios, especificando cantidades, descripciones y precios unitarios. El sistema calculará los totales.</li>
                        <li><strong>Guardar y Procesar:</strong> Al guardar, la orden quedará en estado "Generada" y lista para pasar al siguiente nivel de aprobación.</li>
                    </ol>
                </li>
            </ul>
            <hr>

            <h4>2. Modificaciones</h4>
            <p>Permite realizar ajustes a las órdenes de compra que ya han sido generadas pero que aún no han sido procesadas por completo.</p>
            <ul>
                <li>
                    <strong>Orden:</strong>
                    <ol>
                        <li><strong>Buscar la Orden:</strong> Localice la orden que necesita modificar.</li>
                        <li><strong>Realizar Cambios:</strong> Puede ajustar cantidades, corregir descripciones o actualizar precios.</li>
                        <li><strong>Importante:</strong> Solo podrá modificar órdenes que se encuentren en un estado que lo permita (ej. "Generada" o "Devuelta para corrección"). Órdenes ya "Pagadas" no pueden ser modificadas.</li>
                    </ol>
                </li>
                <li>
                    <strong>Firmas:</strong>
                    <ol>
                        <li><strong>Gestión de Firmas:</strong> Esta sección permite configurar qué usuarios (Directores, Jefes de área) están autorizados para firmar y aprobar las órdenes en las distintas fases del proceso.</li>
                    </ol>
                </li>
            </ul>
            <hr>

            <h4>3. Consultas y Reportes</h4>
            <p>Herramientas para el seguimiento, la transparencia y la rendición de cuentas del proceso de compras.</p>
            <ul>
                <li>
                    <strong>Consultas:</strong>
                    <ol>
                        <li><strong>Búsqueda Rápida:</strong> Ideal para encontrar una orden específica. Puede filtrar por número de orden, RIF del proveedor, fecha de creación o estado actual.</li>
                        <li><strong>Ver Detalles:</strong> Permite visualizar toda la información de una orden sin posibilidad de editarla.</li>
                    </ol>
                </li>
                <li>
                    <strong>Reportes:</strong>
                    <ol>
                        <li><strong>Generar Informes:</strong> Cree reportes en formato PDF o Excel. Puede generar un listado de órdenes por rango de fecha, por proveedor, por partida presupuestaria, etc.</li>
                        <li><strong>Análisis y Auditoría:</strong> Estos reportes son fundamentales para la toma de decisiones, el control de gestión y las auditorías internas o externas.</li>
                    </ol>
                </li>
            </ul>
            <hr>

            <h4>4. Anulaciones</h4>
            <p>Proceso para invalidar de forma definitiva una orden de compra.</p>
            <ul>
                <li>
                    <strong>Anular:</strong>
                    <ol>
                        <li><strong>Seleccionar Orden:</strong> Busque la orden que desea anular.</li>
                        <li><strong>Justificar Anulación:</strong> El sistema le pedirá que ingrese un motivo para la anulación (ej. "Proveedor no disponible", "Error en solicitud").</li>
                        <li><strong>Acción Irreversible:</strong> Al confirmar, la orden queda en estado "Anulada". El compromiso presupuestario asociado se libera, y los fondos vuelven a estar disponibles en la partida.</li>
                    </ol>
                </li>
            </ul>
            <hr>

            <h4>5. Reversar</h4>
            <p>Permite devolver una orden a un paso anterior en el flujo de aprobación.</p>
            <ul>
                <li>
                    <strong>Reversar:</strong>
                    <ol>
                        <li><strong>Caso de Uso:</strong> Es útil cuando una orden fue aprobada por error o necesita una corrección antes de continuar. Por ejemplo, si una orden ya está "Aprobada por Administración" pero se detecta un error, se puede reversar al estado "Generada".</li>
                        <li><strong>Seleccionar y Reversar:</strong> Busque la orden y seleccione la opción de reversar. La orden volverá a su estado anterior para que se realicen los ajustes necesarios.</li>
                    </ol>
                </li>
            </ul>
            <hr>

            <h4>6. Eliminar</h4>
            <p>Opción de uso restringido para borrar una orden del sistema.</p>
            <ul>
                <li>
                    <strong>Eliminar:</strong>
                    <ol>
                        <li><strong>Uso Excepcional:</strong> Esta función solo debe utilizarse para órdenes creadas por error y que no han tenido ningún tipo de procesamiento.</li>
                        <li><strong>Eliminación Física:</strong> A diferencia de anular, esta acción borra el registro de la base de datos. Generalmente, solo usuarios con permisos de administrador tienen acceso a esta función.</li>
                    </ol>
                </li>
            </ul>
        </div>
        <div class="card-footer">
            <p>Para más información, contacte al departamento de Tecnología.</p>
        </div>
    </div>
</div>