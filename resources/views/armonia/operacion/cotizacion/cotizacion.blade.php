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
        .header .header-text p{
            margin-bottom: 0;
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

        

        .justified {
            text-align: justify;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            
        }

        .highlight {
            color: gray;
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
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            text:left;

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
        .firm p{
            line-height: 1;
        }

        

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
            text-align: center;
        }
        td {
            vertical-align: top;
        }
        .header {
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .concepto {
            height: 100px;
        }
        .cost-details {
            text-align: right;
            padding-right: 20px;
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
                <p class="highlight">Unidad de Inspección</p>
                <p class="highlight">Estaciones de Servicio</p>   
                <p class="highlight">Acreditación No.ES-003</p>
                <p class="highlight">Aprobación No. UN05-087/20</p>                
            </div>
        </div>

       
        <div class="line"></div>
        <div class="footer">
            <p>Oaxaca de Juárez, Oax.,  {{ \Carbon\Carbon::now()->locale('es')->isoFormat('dddd D [de] MMMM [de] YYYY') }}.</p>
        </div>


        <div class="header_tabla">
            <h1 style="margin-top: 5px;"> {{ $nombre_estacion }} </h1>
            <h1 style="margin-top: 5px; letter-spacing: 3px;">PRESENTE.</h1>
        </div>
        <div class="content">
            <p class="justified">Por este medio, le presentamos la propuesta económica para realizar la evaluación de la conformidad en las etapas de “Operación y Mantenimiento” en la estación de servicio ubicada en {{$direccion_estacion}}; conforme a la Norma Oficial Mexicana NOM-005-ASEA-2016, Diseño, Construcción, Operación y Mantenimiento de Estaciones de Servicio para el Almacenamiento y Expendio de Diésel y Gasolina.
            </p>
        </div>

        <h2 style="text-align: center; font-weight: bold; text-decoration: underline; font-size: 16px; margin-top: 30px;">
            Propuesta económica</h2>
            <table>
        <tr>
            <th>CANTIDAD</th>
            <th>CONCEPTO</th>
            <th>COSTO UNITARIO</th>
            <th>IMPORTE</th>
        </tr>
        <tr>
            <td>1.00</td>
            <td class="concepto">
                Evaluación de la conformidad etapas:<br>
                Operación y Mantenimiento.<br><br>
                <b><span class="nota">Nota: El costo ya incluye viáticos.</span></b>
            </td>
            <td class="totals">
                <b> ${{ number_format($costo, 0, ',', ',') }}</b><br><br>
                <b>Subtotal<br></b>
                <b>I.V.A.<br></b>
                <b>Total<br></b>
            </td>
            <td class="totals">
                <b>${{ number_format($costo, 0, ',', ',') }}<br><br></b>
                <b>${{ number_format($costo, 0, ',', ',') }}<br></b>
                <b>${{ number_format($iva, 0, ',', ',') }}<br></b>
                <b> ${{ number_format($iva+$costo, 0, ',', ',') }}<br></b>
            </td>
        </tr>
    </table>

   <b><p style="font-size: 15px; margin-top: 30px; text-decoration: underline; font-weight: bold;">Alcance</p></b>
        
   <ul>          
        <li>Los servicios de inspección en las etapas de Operación y Mantenimiento comprenden:
            <ul style="list-style-type: disc;"><br>
                <li>Evaluación documental; en cumplimiento a los numerales señalados en la Norma.</li><br>
                <li>Evaluación en campo; en cumplimiento a los numerales señalados en la Norma.</li><br>
            </ul>
        </li><br>

        <li>Una vez que el regulado (estación de servicio) apruebe con todos los puntos evaluados conforme a la Norma NOM-005-ASEA-2016, se emitirá su Dictamen técnico correspondiente.</li>
   </ul>


        <div class="alcance" style="margin-top: 70px;">
            <p>ARMONÍA Y CONTRASTE AMBIENTAL S.A. DE C.V. - AC4160422EA7 - arcaom2016@gmail.com Tel. 951 1321956</p>
            <p>Riberas del Río Atoyac No. 3025, Col. Jardines de la primavera, San Jacinto Amilpas, Oax. C.P. 68285</p>
        </div>
    </div>
    <div class="container">
            <div class="header">
                    <div class="img-logo"> 
                        <img src="{{ public_path('assets/img/logoarmonia.png') }}">
                    </div>
                    <div class="header-text">
                        <h1>ARMONÍA Y CONTRASTE AMBIENTAL, S.A. DE C.V. </h1>
                        <p class="highlight">Unidad de Inspección</p>
                        <p class="highlight">Estaciones de Servicio</p>   
                        <p class="highlight">Acreditación No.ES-003</p>
                        <p class="highlight">Aprobación No. UN05-087/20</p>                
                    </div>
            </div>

        <div class="line"></div>
       
    </div>

   

        <div class="terms">
            <b><p style="font-size: 15px; margin-top: 30px; text-decoration: underline; font-weight: bold;">Términos y condiciones de pago</p></b>
            
            <ul class="justified">          
                <li><p>En caso de aceptar la propuesta, se procederá a la elaboración de la orden de trabajo correspondiente y se enviará el contrato de servicio para la firma de ambas partes; requiriendo un anticipo del 50% del costo total de servicio.
                </p></li><br>
        
                <li><p>El 50% restante se cubrirá contra entrega del Dictamen técnico.</p></li><br>

                <li><p>La presente propuesta sólo contempla una visita en la estación de servicio. Si es necesario la realización de una segunda visita para concluir la evaluación, se requerirá cubrir únicamente los viáticos del verificador.</p></li>
            </ul>

            <b><p style="font-size: 15px; margin-top: 30px; text-decoration: underline; font-weight: bold;">Confidencialidad:</p></b>
            <ul class="justified">          
                    <li><p>Como Unidad de Inspección solicitaremos acceso a información técnica, procedimientos, programas, 
                        documentos e información relacionada con la etapa que se está evaluando.</p></li><br>

            </ul>

            
            <p style="margin-top: 20px;">Sin otro particular, quedamos a sus órdenes.</p>


            <div class="firm">
                <p>ATENTAMENTE</p>

                <div style="margin-top: 50px; margin-bottom:150px">
                    <p >Ing. Jorge López Benítez</p>
                    <p>Armonía y Contraste Ambiental S.A. de C.V.</p>
                </div>
                
            </div>


        </div>

        <div class="alcance" style="">
            <p>ARMONÍA Y CONTRASTE AMBIENTAL S.A. DE C.V. - AC4160422EA7 - arcaom2016@gmail.com Tel. 951 1321956</p>
            <p>Riberas del Río Atoyac No. 3025, Col. Jardines de la primavera, San Jacinto Amilpas, Oax. C.P. 68285</p>
        </div>

    </div>
</body>

</html>