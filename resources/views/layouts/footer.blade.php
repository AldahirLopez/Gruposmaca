<!-- ======= Footer ======= -->
<footer id="footer" class="footer">
    <div class="copyright">
        &copy; Copyright <strong><span>Grupo SMACA</span></strong>. All Rights Reserved
    </div>
    <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
    </div>
</footer><!-- End Footer -->

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS Files -->
<script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/vendor/chart.js/chart.umd.js') }}"></script>
<script src="{{ asset('assets/vendor/echarts/echarts.min.js') }}"></script>
<script src="{{ asset('assets/vendor/quill/quill.js') }}"></script>
<script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
<script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>

<!-- Template Main JS File -->
<script src="{{ asset('assets/js/main.js') }}"></script>

<!-- Script para manejar el envío del formulario -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.enviarCotizacion').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const precio = document.getElementById(`precio${id}`).value;
                const iva = document.getElementById(`iva${id}`).value;

                if (precio && iva) {
                    const nomenclatura = this.getAttribute('data-nomenclatura');
                    const nombre_estacion = this.getAttribute('data-nombre-estacion');
                    const direccion_estacion = this.getAttribute('data-direccion-estacion');
                    const estado_estacion = this.getAttribute('data-estado-estacion');

                    const url = `{{ route('pdf.cotizacion', ['nomenclatura' => ':nomenclatura', 'nombre_estacion' => ':nombre_estacion', 'direccion_estacion' => ':direccion_estacion', 'estado_estacion' => ':estado_estacion']) }}`
                        .replace(':nomenclatura', nomenclatura)
                        .replace(':nombre_estacion', nombre_estacion)
                        .replace(':direccion_estacion', direccion_estacion)
                        .replace(':estado_estacion', estado_estacion);
                    const finalUrl = `${url}&precio=${precio}&iva=${iva}`;

                    window.open(finalUrl, '_blank');
                } else {
                    alert('Por favor, complete todos los campos.');
                }
            });
        });
    });
</script>