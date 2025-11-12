<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-primary">
            <h1 class="card-title text-white font-weight-bold" style="font-size: 2.2rem;">Manual del Módulo de Ordenación de Pagos</h1>
        </div>
        <div class="card-body">
            <h4>Introducción</h4>
            <p>Este módulo gestiona todo el flujo de pagos de la institución, desde la recepción de una solicitud de pago hasta la generación de la orden y su posterior procesamiento para el pago a proveedores o beneficiarios.</p>
            <hr>

            <h4>1. Compromiso</h4>
            <p>Esta sección inicia el proceso de pago a partir de una obligación adquirida (por ejemplo, una factura recibida).</p>
            <ul>
                <li>
                    <strong>Solicitud de Pago:</strong>
                    <ol>
                        <li><strong>Registro de la Solicitud:</strong> Aquí se registran las solicitudes de pago basadas en facturas u otros documentos de cobro. Se asocia la solicitud a un proveedor y se detallan los montos.</li>
                        <li><strong>Verificación Inicial:</strong> El sistema valida que la información esté completa antes de pasar a la siguiente fase.</li>
                    </ol>
                </li>
                <li>
                    <strong>Orden Financiera:</strong>
                    <ol>
                        <li><strong>Generación de la Orden:</strong> Una vez registrada la solicitud, se genera una Orden Financiera que formaliza la instrucción de pago dentro del sistema.</li>
                    </ol>
                </li>
            </ul>
            <hr>

            <h4>2. Orden de Pago</h4>
            <p>En esta fase se procesa la orden, se aplican las retenciones correspondientes y se prepara para el pago final.</p>
            <ul>
                <li>
                    <strong>Generar OP (Aprobar):</strong>
                    <ol>
                        <li><strong>Aprobación:</strong> Las órdenes financieras generadas son revisadas y aprobadas por los usuarios autorizados. Al aprobarse, se convierten formalmente en una Orden de Pago (OP).</li>
                    </ol>
                </li>
                <li>
                    <strong>OP (Retenciones):</strong>
                    <ol>
                        <li><strong>Cálculo de Retenciones:</strong> Sobre la Orden de Pago aprobada, el sistema calcula y aplica las retenciones de impuestos (IVA, ISLR, etc.) según la normativa vigente y los datos del proveedor.</li>
                        <li><strong>Ajuste del Monto a Pagar:</strong> El monto neto a pagar se actualiza automáticamente tras aplicar las retenciones.</li>
                    </ol>
                </li>
            </ul>
            <hr>

            <h4>3. Pago</h4>
            <p>Sección final donde se procesa el desembolso del dinero.</p>
            <ul>
                <li>
                    <strong>Generar Pago:</strong>
                    <ol>
                        <li><strong>Procesamiento del Pago:</strong> Aquí se agrupan las órdenes de pago listas y se genera el lote de pago, ya sea por transferencia bancaria, cheque u otro método.</li>
                    </ol>
                </li>
            </ul>
            <hr>

            <h4>4. Modificaciones y Anulaciones</h4>
            <p>Permite gestionar cambios, anulaciones y reversos en el flujo de pagos.</p>
            <ul>
                <li><strong>Modificar:</strong> Permite corregir datos en una orden de pago que aún no ha sido procesada por completo.</li>
                <li><strong>Anulaciones:</strong> Invalida una orden de pago, por ejemplo, si se detecta un error grave o la factura fue cancelada. Esta acción es definitiva.</li>
                <li><strong>Reversar:</strong> Devuelve una orden de pago a un estado anterior para su corrección. Por ejemplo, de "Con Retenciones" a "Aprobada" para ajustar un cálculo.</li>
            </ul>
            <hr>

            <h4>5. Consultas y Reportes</h4>
            <p>Herramientas para el seguimiento y la reportería del proceso de pagos.</p>
            <ul>
                <li><strong>Consultas:</strong> Permite buscar y visualizar el estado de cualquier orden de pago, solicitud o pago generado.</li>
                <li><strong>Reportes:</strong> Genera informes detallados, como listados de pagos por proveedor, reportes de retenciones, etc., que son cruciales para la contabilidad y el control fiscal.</li>
            </ul>
        </div>
        <div class="card-footer">
            <p>Para más información, contacte al departamento de Tecnología.</p>
        </div>
    </div>
</div>