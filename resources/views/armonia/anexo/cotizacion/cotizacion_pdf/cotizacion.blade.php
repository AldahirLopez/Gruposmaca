<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container { 
            max-width: 916px;
            margin: 0 auto;
            font-size: 13px;
            overflow: hidden;
        }

        .header {
            margin-bottom: 20px;
        }

        .header img {
            float: left;
            /* Alinear la imagen a la izquierda */
            width: 230px;
            margin-right: 20px;

        }

        .header .header-text {
            flex: 1;
            text-align: right;
            line-height: 1;
        }

        .header h1 {
            color: black;
            font-size: 18px;
            margin: 0;
        }

        .header_tabla {
            margin-top: 50px;
        }

        .header_tabla h1 {
            color: black;
            font-size: 15px;
            margin: 0;
        }

        .header::after {
            content: "";
            /* Limpiar flotación */
            display: table;
            clear: both;
        }

        .content {
            margin-top: 20px;
            text-align: right;
            line-height: 1.2;
        }

        .content p {
            margin: 5px 0;
        }

        .justified {
            text-align: justify;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-weight: bold;
        }

        .highlight {
            color: black;
        }

        .line {
            border-top: 3px solid #008000;
            margin: 0px 0;
        }

        p {
            margin-bottom: 0;
            margin-block-start: 0;
            margin-block-end: 0;
        }

        .tabla-propuesta {
            width: 100%;
            margin-top: 20px;
        }

        .tabla-propuesta th,
        .tabla-propuesta td {
            border: 1px solid #999;
            padding: 8px;
            text-align: center;
        }

        .tabla-propuesta th {
            background-color: #c5c3c3;
            font-weight: bold;
        }

        .tabla-propuesta .concepto {
            text-align: left;
        }

        .tabla-propuesta tfoot td {
            text-align: right;
            font-weight: bold;
        }

        .nota {
            text-align: left;
            margin-top: 10px;
            font-size: 0.9em;
        }

        .alcance {
            align-items: center;
            background-color: green;
            color: white;
            padding: 10px;
            text-align: center;
            font-size: 13px;
        }

        .alcance p {
            margin: 0;
        }

        .terms {
            padding-top: 5px;
        }

        .terms p {
            color: black;
            font-size: 13px;
            margin: 10px;
            line-height: 2;
        }

        .infor {
            padding-top: 5px;
        }

        .infor h2 {
            color: black;
            font-size: 18px;
            margin: 0;
        }

        .infor p {
            color: black;
            font-size: 13px;
            margin: 10px;
            line-height: 1.8;
        }

        .infor ol li {
            line-height: 1.5;
        }

        .firm {
            text-align: center;
        }

        .firm p {
            line-height: 1;
        }

        /* Estilos específicos para impresión */
        @media print {
            .alcance {
                background-color: #0dbd0d !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                /* Soporte para navegadores WebKit */
                print-color-adjust: exact;
                /* Soporte para navegadores estándar */
            }

            .tabla-propuesta th {
                background-color: #f2f2f2 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="img-logo">
                <img src="{{ public_path('assets/img/logoarmonia.png') }}">
            </div>
            <div class="header-text">
                <h1>ARMONÍA Y CONTRASTE AMBIENTAL, S.A. DE C.V. </h1>
                <p class="highlight"><strong>Unidad de Inspección</strong></p>
                <p style="text-align: right;"><strong>Materia:</strong> Controles Volumétricos de Hidrocarburos y
                    Petrolíferos</p>
                <p>con base en los Anexos 30 y 31 de la Miscelánea Fiscal</p>
                <p>Publicada el 27 de diciembre de 2021 - actualizada el 9 de marzo de 2022</p>
                <p>Publicada el 27 de diciembre de 2022 y actualizada el 12 de enero de 2023</p>
            </div>
        </div>

        <div class="content">
            <p><strong>Acreditación No.</strong> UICV-011</p>
            <p><strong>Actualización técnica</strong> 2023/04/10</p>
        </div>
        <div class="line"></div>
        <div class="footer">
            <p style="font-size: 14px; margin-top: 5px;">San Jacinto Amilpas, Oaxaca, {{ \Carbon\Carbon::now()->locale('es')->isoFormat('dddd D [de] MMMM [de] YYYY') }}.</p>
        </div>


        <div class="header_tabla">
            <h1 style="margin-top: 5px;"> {{ $nombre_estacion }} </h1>
            <h1 style="margin-top: 5px; letter-spacing: 3px;">PRESENTE.</h1>
        </div>
        <div class="content">
            <p class="justified" style="font-size: 16px;">Es de nuestro interés presentarles por este medio, la
                propuesta económica para realizar
                el servicio
                de Inspección en cumplimiento a los Anexos 30 y 31 de la Resolución Miscelánea Fiscal, en la
                estación de servicio ubicada en {{ $direccion_estacion }}, consistiendo en lo siguiente:
            </p>
        </div>

        <h2 style="text-align: center; font-weight: bold; text-decoration: underline; font-size: 16px; margin-top: 30px;">
            Propuesta económica</h2>
        <table class="tabla-propuesta">
            <thead>
                <tr>
                    <th>CANTIDAD</th>
                    <th>UNIDAD</th>
                    <th>CONCEPTO</th>
                    <th>COSTO UNITARIO</th>
                    <th>IMPORTE</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1.00</td>
                    <td>Servicio</td>
                    <td class="concepto">Inspeccionar la correcta operación y funcionamiento de los equipos y programas
                        informáticos para llevar los controles volumétricos de hidrocarburos y petrolíferos de
                        conformidad en los Anexo 30 y 31 de la Resolución Miscelánea Fiscal.</td>
                    <td>${{ number_format($costo, 0, ',', ',') }}</td>
                    <td>${{ number_format($costo, 0, ',', ',') }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" style="text-align: right;">Subtotal</td>
                    <td>${{ number_format($costo, 0, ',', ',') }}</td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align: right;">I.V.A.</td>
                    <td>${{ number_format($iva, 0, ',', ',') }}</td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align: right;">Total</td>
                    <td>${{ number_format($iva+$costo, 0, ',', ',') }}</td>

                </tr>
                <tr>
                    <td colspan="5" class="nota" style="text-align: left;">Nota: El costo es por estación de servicio y
                        ya incluye viáticos.</td>
                </tr>
            </tfoot>
        </table>
        <div class="alcance" style="margin-top: 70px;">
            <p>ARMONÍA Y CONTRASTE AMBIENTAL S.A. DE C.V. - AC4160422EA7 - arcaom2016@gmail.com Tel. 951 1321956</p>
            <p>Riberas del Río Atoyac No. 3025, Col. Jardines de la primavera, San Jacinto Amilpas, Oax. C.P. 68285</p>
        </div>
    </div>
    <div class="container">
        <div class="header">
            <img src="{{ public_path('assets/img/logoarmonia.png') }}">
            <div class="header-text">
                <h1>ARMONÍA Y CONTRASTE AMBIENTAL, S.A. DE C.V.</h1>
                <p class="highlight"><strong>Unidad de Inspección</strong></p>
                <p><strong>Materia:</strong> <span class="justified">Controles Volumétricos de Hidrocarburos y
                        Petrolíferos con base en los
                        Anexos 30 y 31 de la Miscelánea Fiscal</span></p>
                <p>Publicada el 27 de diciembre de 2021 - actualizada el 9 de marzo de 2022</p>
                <p>Publicada el 27 de diciembre de 2022 y actualizada el 12 de enero de 2023</p>
            </div>
        </div>

        <div class="content">
            <p><strong>Acreditación No.</strong> UICV-011</p>
            <p><strong>Actualización técnica</strong> 2023/04/10</p>
        </div>
        <div class="line"></div>
        <p style="font-size: 15px; margin-top: 30px;">Alcance:</p>
        <div class="infor">
            <p>La inspección se realizará a los sistemas de medición y a los programas informáticos a que se refiere el
                Anexo 30 de la Resolución Miscelánea Fiscal.</p>

            <p>La Inspección de los sistemas de medición comprende 3 etapas:</p>
            <ol class="justified">
                <li>Se recopila, analiza y evalúa la información documental existente.</li>
                <li>Se realiza un levantamiento de información en sitio verificando y complementando la información
                    documental revisada.</li>
                <li>Se procesa toda la información recopilada de manera documental y en sitio, para identificar
                    hallazgos y generar conclusiones y recomendaciones.</li>
            </ol>

            <p>Inspección de los Programas Informáticos</p>
            <ol class="justified">
                <li>Confirmar que los programas informáticos para llevar controles volumétricos están acorde a los
                    requerimientos de funcionalidad y seguridad establecidos en los apartados 30.6.1 y 30.6.2 del Anexo
                    30.</li>
                <li>Realizar pruebas de consulta y pruebas de generación de informe, corroborando los resultados
                    obtenidos con la información visualizada directamente en las tablas de la base de datos.</li>
                <li>Realizar una prueba simulando la interrupción de la comunicación de algún elemento del control
                    volumétrico, siempre y cuando existan condiciones que no comprometan la operación.</li>
            </ol>

            <p>Concluida la inspección se emitirá un certificado (dictamen) en donde el resultado indique:</p>
            <ul class="justified">
                <li>La correcta operación y funcionamiento de los equipos y programas informáticos para llevar los
                    controles volumétricos; o bien,</li>
                <li>Describa las observaciones encontradas en los equipos y programas informáticos para llevar los
                    controles volumétricos, así como las recomendaciones aplicables.</li>
            </ul>
        </div>

        <div class="alcance" style="margin-top: 120px;">
            <p>ARMONÍA Y CONTRASTE AMBIENTAL S.A. DE C.V. - AC4160422EA7 - arcaom2016@gmail.com Tel. 951 1321956</p>
            <p>Riberas del Río Atoyac No. 3025, Col. Jardines de la primavera, San Jacinto Amilpas, Oax. C.P. 68285</p>
        </div>

    </div>

    <div class="container">
        <div class="header">
            <img src="{{ public_path('assets/img/logoarmonia.png') }}">
            <div class="header-text">
                <h1>ARMONÍA Y CONTRASTE AMBIENTAL, S.A. DE C.V.</h1>
                <p class="highlight"><strong>Unidad de Inspección</strong></p>
                <p><strong>Materia:</strong> <span class="justified">Controles Volumétricos de Hidrocarburos y
                        Petrolíferos con base en los
                        Anexos 30 y 31 de la Miscelánea Fiscal</span></p>
                <p>Publicada el 27 de diciembre de 2021 - actualizada el 9 de marzo de 2022</p>
                <p>Publicada el 27 de diciembre de 2022 y actualizada el 12 de enero de 2023</p>
            </div>
        </div>

        <div class="content">
            <p><strong>Acreditación No.</strong> UICV-011</p>
            <p><strong>Actualización técnica</strong> 2023/04/10</p>
        </div>
        <div class="line"></div>

        <div class="terms">
            <p>Términos y condiciones de pago</p>
            <ul class="justified">
                <li>En caso de aceptar la propuesta, se procederá a la elaboración de la orden de trabajo
                    correspondiente y se enviará el contrato de servicio para la firma de ambas partes;
                    requiriendo un anticipo del 50% del costo total de servicio.</li>
                <li>El 50% restante se cubrirá contra entrega del Dictamen</li>
            </ul>

            <p style="margin-top: 55px;">Confidencialidad:</p>
            <ul class="justified">
                <li>Como Unidad de Inspección solicitaremos acceso a información técnica, procedimientos,
                    programas, documentos e información relacionada con la materia que se está
                    evaluando.</li>
            </ul>

            <p style="margin-top: 55px;">Sin otro particular, quedamos a sus órdenes.</p>

            <div class="firm" style="margin-top: 55px;">
                <p>ATENTAMENTE</p>
                <p>Ing. Jorge López Benítez</p>
                <p>Armonía y Contraste Ambiental S.A. de C.V.</p>
            </div>


        </div>

        <div class="alcance" style="margin-top: 230px;">
            <p>ARMONÍA Y CONTRASTE AMBIENTAL S.A. DE C.V. - AC4160422EA7 - arcaom2016@gmail.com Tel. 951 1321956</p>
            <p>Riberas del Río Atoyac No. 3025, Col. Jardines de la primavera, San Jacinto Amilpas, Oax. C.P. 68285</p>
        </div>

    </div>
</body>

</html>