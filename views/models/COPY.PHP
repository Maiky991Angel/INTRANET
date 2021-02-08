<?php
 include ("model/conexion.php");
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">


                    <h1>Informe de Venta</h1>


                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>


    <!-- Main content -->
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Reporte de Venta </h3>
            </div>
            <!-- 
            <div class="input-group">
                <div id="reportrange"
                    style="background: #fff; cursor: pointer; padding: 5px 8px; margin-left: 20px; margin-top: 25px; border: 1px solid #ccc; width: 300px">
                    <i class="fa fa-calendar"></i>&nbsp;
                    <span></span> <i class="fa fa-caret-down"></i>
                </div>
            </div> -->

            <form action="" method="GET" style=" padding: 5px 8px; margin-left: 5px; margin-top: 25px; width: 250px">

                <div class="col-auto">
                    <input type="text" name="busqueda" class="form-control" id="inputPassword2" placeholder="Buscar...">
                </div>

                <div class="col-auto" style="margin-top: 10px;">
                    <button type="submit" name="enviar" class="btn btn-primary mb-3">Enviar</button>
                </div>
            </form>



            <div class="card-body" style="margin-top: -30px;">
                <table id="example1" class="table table-bordered table-striped table-responsive display nowrap">
                    <thead>
            </div>
            <tr>
                <th>Documento</th>
                <th>fecha</th>
                <th>Cliente</th>
                <th>Vendedor</th>
                <th>Bodega</th>
                <th>Departamento</th>
                <th>lista de precio</th>
                <th>Grupo_inv</th>
                <th>SubGrupo</th>
                <th>Numero</th>
                <th>Referencia</th>
                <th>Ref_Color</th>
                <th>Producto</th>
                <th>Sector_Economico</th>
                <th>Zona</th>
                <th>Canal_Distribucion</th>
                <th>Color</th>
                <th>Nbre_Region</th>
                <th>Marca_Pdto</th>
                <th>Origen_Venta</th>
                <th>Origen_Pedido</th>
                <th>Total_Unidades</th>
                <th>Dcto_Referemcia</th>
                <th>Dcto_Web</th>
                <th>Dcto_Promo</th>
                <th>Dcto_Comercial</th>
                <th>Total_Dcto</th>
                <th>Vta_menos_Dcto</th>

            </tr>

            </thead>


            <tbody>
                <?php


           
                    if (isset($_GET['enviar'])) {
                        $busqueda = $_GET['busqueda'];

                     $sql = "SELECT  top 20

                      'Ventas' As Documento, Cast(Encabezado.Fecha As Varchar(11)) As 'fecha', Rtrim(Ltrim(Clientes.Razon_Social)) As 'Cliente'
                      , Rtrim(Ltrim(Vendedor.Nombre))  As 'Vendedor', Rtrim(Ltrim(Encabezado.Bodega)) + ' - ' + Rtrim(Ltrim(Bodegas.Nombre)) As 'Bodega'
                      , Rtrim(Ltrim(Departamentos.Descripcion)) As 'Departamento', Convert(Varchar(2), Clientes.Precio_de_Venta) As 'Lista_Precio'
                      , Rtrim(Ltrim(Grupos.Descripcion)) As 'Grupo_inv'
                      , Rtrim(Ltrim(SubGrupos.Descripcion)) As 'SubGrupo', Encabezado.Numero
                      , Case When Kardex.AplicaColor = 0 Then Rtrim(Ltrim(Kardex.Referencia)) 
                      Else Rtrim(Ltrim(Kardex.CodigoBase)) End As 'Referencia'
                      , Rtrim(Ltrim(Kardex.Referencia)) As 'Ref_Color', Rtrim(Ltrim(Kardex.Nombre_Producto)) As 'Producto'
                      , Rtrim(Ltrim(DimSectorEconomico.Descripcion)) As 'Sector_Economico', Rtrim(Ltrim(Zonas.Descripcion)) As 'Zona'
                      , Rtrim(Ltrim(DimDistribuidor.Descripcion)) As 'Canal_Distribucion', Rtrim(Ltrim(Kardex.NombreColor)) As 'Color' 
                      , Rtrim(Ltrim(DimRegiones.CodRegion)) + '-' + Rtrim(Ltrim(DimRegiones.NombreRegion)) As 'Nbre_Region'
                      , Rtrim(Ltrim(Kardex.CodMarca)) + '-' + Rtrim(Ltrim(DimMarcaProductos.Descripcion)) As 'Marca_Pdto' 
                    
                      /* Determina si el pedido fue tomado por iVentas o e-commerce */
                      ,Case 
                          When DocumentoWeb = 1 Then 'Venta_Electronica'
                          Else 'Venta_Tradicional' 
                      End As 'Origen_Venta'

                      /* Determina si el pedido fue tomado por el asesor, el cliente, etc */
                      ,Case    
                          When Detalle.OrigenPedido = 0 Then 'Asesor iVentas'
                          When Detalle.OrigenPedido = 1 Then 'Cliente en la Web'
                          When Detalle.OrigenPedido = 2 Then 'Asesor en la web'
                          When Detalle.OrigenPedido = 3 Then 'Factura Manual'
                      End As 'Origen_Pedido'
                      , Detalle.Cantidad As 'Total_Unidades'
                      , Round(((Detalle.Valor * Detalle.Cantidad) * (Detalle.Descuento/100)),0) As 'Dcto_Referencia'
                      , Case When Detalle.TipoDescuento = 1 Then Round(((Detalle.Valor * Detalle.Cantidad) * (Detalle.DescuentoProducto/100)),0) Else 0 End As 'Dcto_Web'
                      , Case When Detalle.TipoDescuento = 2 Then Round(((Detalle.Valor * Detalle.Cantidad) * (Detalle.DescuentoProducto/100)),0) Else 0 End As 'Dcto_Promo'
                      , Round(((Detalle.Valor * Detalle.Cantidad) * (Detalle.DescuentoComercial/100)),0) As 'Dcto_Comercial'
                      , Round(((Detalle.Valor * Detalle.Cantidad) * ((Detalle.Descuento + Detalle.DescuentoProducto + Detalle.DescuentoComercial)/100)),0) As 'Total_Dcto'
                      , Round(((((Detalle.Valor * Detalle.Cantidad) - ((Detalle.Valor * Detalle.Cantidad) * ((Detalle.Descuento + Detalle.DescuentoProducto + Detalle.DescuentoComercial)/100))))),0) As 'Vta_menos_Dcto'

                    
                      From Factura2_2000 Detalle
                      Inner Join Factura1_2000 Encabezado  On Detalle.Numero = Encabezado.Numero
                      Inner Join Bodegas                   On Bodegas.Bodega = Encabezado.Bodega
                      Inner Join Zonas                     On Zonas.Zona = Encabezado.Zona
                      Inner Join Clientes                  On Clientes.Nit   = Encabezado.Nit
                      Inner Join Vendedor                  On Vendedor.Vendedor = Clientes.Vendedor
                      Inner Join Kardex                    On Kardex.Referencia = Detalle.Referencia
                      Left Outer Join Grupos               On Grupos.Grupo = Kardex.Grupo
                      Left Outer Join SubGrupos            On (SubGrupos.Grupo + SubGrupos.SubGrupo) = (Kardex.Grupo + Kardex.SubGrupo)
                      Left Outer Join Departamentos        On Departamentos.Departamento = SubGrupos.Departamento
                      Left Outer Join DimSectorEconomico   On Zonas.Sector = DimSectorEconomico.Sector
                      Left Outer Join DimDistribuidor      On DimDistribuidor.Categoria = DimSectorEconomico.Categoria
                      Left Outer Join DimRegiones          On DimRegiones.CodRegion = DimSectorEconomico.CodRegion
                      Left Outer Join DimCiudad            On Zonas.CodCiudad = DimCiudad.CodCiudad
                      Left Outer Join DimMarcaProductos    On DimMarcaProductos.CodMarca = Kardex.CodMarca
                     
                      Where Encabezado.Anulado = 0 And Detalle.Anulado = 0 And Encabezado.Espera = 0
                            And Encabezado.nit LIKE '%busqueda%'";
var_dump($sql);
                    //$consult= sqlsrv_query($conn, " SELECT * FROM Factura1_2000  WHERE Encabezado.nit LIKE '%busqueda%' ");
                      $consult= sqlsrv_query($conn, $sql);
                      
                       

                       while ($fila=sqlsrv_fetch_array($consult))
                {

                        echo'
                      
                            <tr>
                             <td>'.$fila['Documento'].'</td>
                             <td>'.$fila['fecha'].'</td>
                             <td>'.$fila['Cliente'].'</td>
                             <td>'.$fila['Vendedor'].'</td>
                             <td>'.$fila['Bodega'].'</td>
                             <td>'.$fila['Departamento'].'</td>
                             <td>'.$fila['Lista_Precio'].'</td>
                             <td>'.$fila['SubGrupo'].'</td>
                             <td>'.$fila['Grupo_inv'].'</td>
                             <td>'.$fila['Numero'].'</td>
                             <td>'.$fila['Referencia'].'</td>
                             <td>'.$fila['Ref_Color'].'</td>
                             <td>'.$fila['Producto'].'</td>
                             <td>'.$fila['Sector_Economico'].'</td>
                             <td>'.$fila['Zona'].'</td>
                             <td>'.$fila['Color'].'</td>
                             <td>'.$fila['Nbre_Region'].'</td>
                             <td>'.$fila['Marca_Pdto'].'</td>
                             <td>'.$fila['Origen_Venta'].'</td>
                             <td>'.$fila['Origen_Pedido'].'</td>
                             <td>'.$fila['Total_Unidades'].'</td>
                             <td>'.$fila['Marca_Pdto'].'</td>
                             <td>'.$fila['Dcto_Referencia'].'</td>
                             <td>'.$fila['Dcto_Web'].'</td>
                             <td>'.$fila['Dcto_Promo'].'</td>
                             <td>'.$fila['Dcto_Comercial'].'</td>
                             <td>'.$fila['Total_Dcto'].'</td>
                             <td>'.$fila['Vta_menos_Dcto'].'</td>

                       
                             </tr>
                            
                                 
      
                                ';
     
     
                           }
                    }


                //      $sql = "SELECT  top 20

                //      'Ventas' As Documento, Cast(Encabezado.Fecha As Varchar(11)) As 'fecha', Rtrim(Ltrim(Clientes.Razon_Social)) As 'Cliente'
                //      , Rtrim(Ltrim(Vendedor.Nombre))  As 'Vendedor', Rtrim(Ltrim(Encabezado.Bodega)) + ' - ' + Rtrim(Ltrim(Bodegas.Nombre)) As 'Bodega'
                //      , Rtrim(Ltrim(Departamentos.Descripcion)) As 'Departamento', Convert(Varchar(2), Clientes.Precio_de_Venta) As 'Lista_Precio'
                //      , Rtrim(Ltrim(Grupos.Descripcion)) As 'Grupo_inv'
                //      , Rtrim(Ltrim(SubGrupos.Descripcion)) As 'SubGrupo', Encabezado.Numero
                //      , Case When Kardex.AplicaColor = 0 Then Rtrim(Ltrim(Kardex.Referencia)) 
                //      Else Rtrim(Ltrim(Kardex.CodigoBase)) End As 'Referencia'
                //      , Rtrim(Ltrim(Kardex.Referencia)) As 'Ref_Color', Rtrim(Ltrim(Kardex.Nombre_Producto)) As 'Producto'
                //      , Rtrim(Ltrim(DimSectorEconomico.Descripcion)) As 'Sector_Economico', Rtrim(Ltrim(Zonas.Descripcion)) As 'Zona'
                //      , Rtrim(Ltrim(DimDistribuidor.Descripcion)) As 'Canal_Distribucion', Rtrim(Ltrim(Kardex.NombreColor)) As 'Color' 
                //      , Rtrim(Ltrim(DimRegiones.CodRegion)) + '-' + Rtrim(Ltrim(DimRegiones.NombreRegion)) As 'Nbre_Region'
                //      , Rtrim(Ltrim(Kardex.CodMarca)) + '-' + Rtrim(Ltrim(DimMarcaProductos.Descripcion)) As 'Marca_Pdto' 
                    
                //      /* Determina si el pedido fue tomado por iVentas o e-commerce */
                //      ,Case 
                //          When DocumentoWeb = 1 Then 'Venta_Electronica'
                //          Else 'Venta_Tradicional' 
                //      End As 'Origen_Venta'

                //      /* Determina si el pedido fue tomado por el asesor, el cliente, etc */
                //      ,Case    
                //          When Detalle.OrigenPedido = 0 Then 'Asesor iVentas'
                //          When Detalle.OrigenPedido = 1 Then 'Cliente en la Web'
                //          When Detalle.OrigenPedido = 2 Then 'Asesor en la web'
                //          When Detalle.OrigenPedido = 3 Then 'Factura Manual'
                //      End As 'Origen_Pedido'
                //      , Detalle.Cantidad As 'Total_Unidades'
                //      , Round(((Detalle.Valor * Detalle.Cantidad) * (Detalle.Descuento/100)),0) As 'Dcto_Referencia'
                //      , Case When Detalle.TipoDescuento = 1 Then Round(((Detalle.Valor * Detalle.Cantidad) * (Detalle.DescuentoProducto/100)),0) Else 0 End As 'Dcto_Web'
                //      , Case When Detalle.TipoDescuento = 2 Then Round(((Detalle.Valor * Detalle.Cantidad) * (Detalle.DescuentoProducto/100)),0) Else 0 End As 'Dcto_Promo'
                //      , Round(((Detalle.Valor * Detalle.Cantidad) * (Detalle.DescuentoComercial/100)),0) As 'Dcto_Comercial'
                //      , Round(((Detalle.Valor * Detalle.Cantidad) * ((Detalle.Descuento + Detalle.DescuentoProducto + Detalle.DescuentoComercial)/100)),0) As 'Total_Dcto'
                //      , Round(((((Detalle.Valor * Detalle.Cantidad) - ((Detalle.Valor * Detalle.Cantidad) * ((Detalle.Descuento + Detalle.DescuentoProducto + Detalle.DescuentoComercial)/100))))),0) As 'Vta_menos_Dcto'

                    
                //      From Factura2_2000 Detalle
                //      Inner Join Factura1_2000 Encabezado  On Detalle.Numero = Encabezado.Numero
                //      Inner Join Bodegas                   On Bodegas.Bodega = Encabezado.Bodega
                //      Inner Join Zonas                     On Zonas.Zona = Encabezado.Zona
                //      Inner Join Clientes                  On Clientes.Nit   = Encabezado.Nit
                //      Inner Join Vendedor                  On Vendedor.Vendedor = Clientes.Vendedor
                //      Inner Join Kardex                    On Kardex.Referencia = Detalle.Referencia
                //      Left Outer Join Grupos               On Grupos.Grupo = Kardex.Grupo
                //      Left Outer Join SubGrupos            On (SubGrupos.Grupo + SubGrupos.SubGrupo) = (Kardex.Grupo + Kardex.SubGrupo)
                //      Left Outer Join Departamentos        On Departamentos.Departamento = SubGrupos.Departamento
                //      Left Outer Join DimSectorEconomico   On Zonas.Sector = DimSectorEconomico.Sector
                //      Left Outer Join DimDistribuidor      On DimDistribuidor.Categoria = DimSectorEconomico.Categoria
                //      Left Outer Join DimRegiones          On DimRegiones.CodRegion = DimSectorEconomico.CodRegion
                //      Left Outer Join DimCiudad            On Zonas.CodCiudad = DimCiudad.CodCiudad
                //      Left Outer Join DimMarcaProductos    On DimMarcaProductos.CodMarca = Kardex.CodMarca
                    
                //      Where Encabezado.Anulado = 0 And Detalle.Anulado = 0 And Encabezado.Espera = 0
                //      ";
                    
                //       $sql2 = "And Encabezado.nit = ''";
                // //  $concatenar = $sql + $sql2; 
                //       $consultar=sqlsrv_query($conn, $sql);
                //       while ($fila=sqlsrv_fetch_array($consultar))
                {

                        //    echo'
                          
                        //        <tr>
                        //         <td>'.$fila['Documento'].'</td>
                        //         <td>'.$fila['fecha'].'</td>
                        //         <td>'.$fila['Cliente'].'</td>
                        //         <td>'.$fila['Vendedor'].'</td>
                        //         <td>'.$fila['Bodega'].'</td>
                        //         <td>'.$fila['Departamento'].'</td>
                        //         <td>'.$fila['Lista_Precio'].'</td>
                        //         <td>'.$fila['SubGrupo'].'</td>
                        //         <td>'.$fila['Grupo_inv'].'</td>
                        //         <td>'.$fila['Numero'].'</td>
                        //         <td>'.$fila['Referencia'].'</td>
                        //         <td>'.$fila['Ref_Color'].'</td>
                        //         <td>'.$fila['Producto'].'</td>
                        //         <td>'.$fila['Sector_Economico'].'</td>
                        //         <td>'.$fila['Zona'].'</td>
                        //         <td>'.$fila['Color'].'</td>
                        //         <td>'.$fila['Nbre_Region'].'</td>
                        //         <td>'.$fila['Marca_Pdto'].'</td>
                        //         <td>'.$fila['Origen_Venta'].'</td>
                        //         <td>'.$fila['Origen_Pedido'].'</td>
                        //         <td>'.$fila['Total_Unidades'].'</td>
                        //         <td>'.$fila['Marca_Pdto'].'</td>
                        //         <td>'.$fila['Dcto_Referencia'].'</td>
                        //         <td>'.$fila['Dcto_Web'].'</td>
                        //         <td>'.$fila['Dcto_Promo'].'</td>
                        //         <td>'.$fila['Dcto_Comercial'].'</td>
                        //         <td>'.$fila['Total_Dcto'].'</td>
                        //         <td>'.$fila['Vta_menos_Dcto'].'</td>

                           
                        //         </tr>
                                
                                     
          
                        //            ';
         
         
                               }
         
                             ?>
            </tbody>

            </table>
        </div>
</div>
</div>
</section>
</div>
</div>