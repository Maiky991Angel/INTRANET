<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="views/dist/img/LOG.png">
    <title>JALTECH S.A.S</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="views/plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->

    <!-- Theme style -->
    <link rel="stylesheet" href="views/dist/css/adminlte.min.css">

    <!-- DATA TABLES -->

    <link rel="stylesheet" href="views/plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="views/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="views/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="views/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
</head>

<body class="hold-transition sidebar-mini sidebar-collapse layout-fixed">
    <div class="wrapper">

        <?php

            include "models/nav.php";


            include "models/aside.php";




            if (isset($_GET['ruta'])) {
                    
                if($_GET['ruta'] == "inicio"||
                    $_GET['ruta'] == "Informe-Ventas"|| 
                    $_GET['ruta'] == "Informe-caja"|| 
                    $_GET['ruta'] == "Informe-Saldos-Bodegas"|| 
                    $_GET['ruta'] == "Informe-Devoluciones"|| 
                    $_GET['ruta'] == "Informe-Pedido"){
                                            
                    include "models/".$_GET['ruta'].".php";


            }else {
                include "models/404.php";
                    }
            }else{
                include "models/inicio.php";

            }

            include "models/footer.php";
                
            ?>


    </div>

    -
    <!-- jQuery -->
    <script src="views/plugins/jquery/jquery.min.js"></script>

    <!-- ventas.js -->
    <script src="views/js/ventas.js"></script>
    <script src="views/js/plantilla.js"></script>
 
    

    <!-- Bootstrap 4 -->
    <script src="views/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- AdminLTE App -->
    <script src="views/dist/js/adminlte.min.js"></script>

    <!-- AdminLTE for demo purposes -->
    <script src="views/dist/js/demo.js"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <!-- DataTables  & Plugins -->
    <script src="views/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="views/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="views/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="views/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="views/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="views/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="views/plugins/jszip/jszip.min.js"></script>
    <script src="views/plugins/pdfmake/pdfmake.min.js"></script>
    <script src="views/plugins/pdfmake/vfs_fonts.js"></script>
    <script src="views/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="views/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="views/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <script>
    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": true,
            "buttons": ["csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        // $('#example2').DataTable({
        //     "paging": true,
        //     "lengthChange": false,
        //     "searching": false,
        //     "ordering": true,
        //     "info": true,
        //     "autoWidth": false,
        //     "responsive": true,
        // });
    });
    </script>

    <>



</body>

</html>