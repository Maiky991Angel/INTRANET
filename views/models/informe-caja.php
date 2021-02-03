<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="fila mb-2">
                <div class="col-sm-6">


                    <h1>Saldos Bodegas</h1>


                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Saldos Bodegas </h3>
            </div>


            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped table-responsive display nowrap">
                    <thead>
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
                    
                    include ("model/conexion.php");

                    $sql = "SELECT top 1

                    'Recaudo' As Documento, Cast(Encabezado.Fecha As Varchar(11)) As 'fecha', Rtrim(Ltrim(Clientes.Razon_Social)) As 'Cliente'
                    , Rtrim(Ltrim(Vendedor.Nombre))  As 'Vendedor', 'Recaudo' As 'Bodega', 'Recaudo' As 'Departamento'
                    , Convert(Varchar(2), Clientes.Precio_de_Venta) As 'Lista_Precio'
                    , 'Recaudo' As 'Grupo_inv', 'Recaudo' As 'SubGrupo', Encabezado.Numero, 'Recaudo' As 'Referencia', 'Recaudo' As 'Ref_Color'
                    , 'Recaudo' As 'Producto', Rtrim(Ltrim(DimSectorEconomico.Descripcion)) As 'Sector_Economico', 'Recaudo' As 'Zona'
                    , Rtrim(Ltrim(DimDistribuidor.Descripcion)) As 'Canal_Distribucion', 'Recaudo' As 'Color'
                    , Rtrim(Ltrim(DimRegiones.CodRegion)) + '-' + Rtrim(Ltrim(DimRegiones.NombreRegion)) As 'Nbre_Region'
                    , 'Recaudo' As 'Marca_Pdto', 'Recaudo' As 'Origen_Venta', 'Recaudo' As 'Origen_Pedido'
                    , 0 As 'Total_Unidades'
                    , 0 As 'Dcto_Referencia'
                    , 0 As 'Dcto_Web'
                    , 0 As 'Dcto_Promo'
                    , 0 As 'Dcto_Comercial'
                    , 0 As 'Total_Dcto'
                    , 0 As 'Vta_menos_Dcto'

                    From Recibos1_2000 Encabezado
                    /* Inner Join Recibos2_2000 Detalle     On Detalle.Numero = Encabezado.Numero */
                    Inner Join Clientes                  On Clientes.Nit   = Encabezado.Nit
                    Inner Join Vendedor                  On Vendedor.Vendedor = Clientes.Vendedor
                    Inner Join Zonas                     On Zonas.Zona = Encabezado.Zona
                    Left Outer Join dimEstado            On clientes.departamento = dimestado.codestado         
                    Left Outer Join dimciudad            On clientes.codciudad = dimciudad.codciudad
                    Left Outer Join DimSectorEconomico   On Zonas.Sector = DimSectorEconomico.Sector
                    Left Outer Join DimDistribuidor      On DimDistribuidor.Categoria = DimSectorEconomico.Categoria
                    Left Outer Join DimRegiones          On DimRegiones.CodRegion = DimSectorEconomico.CodRegion

                    Where SubString(Encabezado.CuentaContable,1,2) = '11' And Encabezado.Anulado = 0 
                        And (Encabezado.Codigo_Plantilla = '' Or Encabezado.Codigo_Plantilla = 'Null')";

                     $consultar=sqlsrv_query($conn, $sql);
                     while ($fila=sqlsrv_fetch_array($consultar)){
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
         
                             ?>
                    </tbody>
                    <!-- <tfoot>

                        <tr>
                            <th>Documento</th>
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
                            <th>Recaudo</th>
                            <th>Saldos_Inv</th>
                            <th>Costo_Inv</th>
                            <th>CMV</th>
                            <th>Rentabilidad </th>
                        </tr>


                    </tfoot> -->
                </table>
            </div>
        </div>
</div>
</section>
</div>
</div>