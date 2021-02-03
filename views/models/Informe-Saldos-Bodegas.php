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
                    
                    'Saldos_Bod' As Documento

                    , 'Saldos_Bod' As 'Cliente'
                    , 'Saldos_Bod' As 'Vendedor', Rtrim(Ltrim(Saldos_Bod.Bodega)) + ' - ' + Rtrim(Ltrim(Bodegas.Nombre)) As 'Bodega'
                    , Rtrim(Ltrim(Departamentos.Descripcion)) As 'Departamento', 'Saldos_Bod' As 'Lista_Precio'
                    , Rtrim(Ltrim(Grupos.Descripcion)) As 'Grupo_inv'
                    , Rtrim(Ltrim(SubGrupos.Descripcion)) As 'SubGrupo', 'Saldos_Bod' As 'Numero'
                    , Case When Kardex.AplicaColor = 0 Then Rtrim(Ltrim(Kardex.Referencia)) 
                    Else Rtrim(Ltrim(Kardex.CodigoBase)) End As 'Referencia'
                    , Rtrim(Ltrim(Kardex.Referencia)) As 'Ref_Color', Rtrim(Ltrim(Kardex.Nombre_Producto)) As 'Producto'
                    , 'Saldos_Bod' As 'Sector_Economico', 'Saldos_Bod' As 'Zona', 'Saldos_Bod' As 'Canal_Distribucion'
                    , Rtrim(Ltrim(Kardex.NombreColor)) As 'Color', 'Saldos_Bod' As 'Nbre_Region'
                    , Rtrim(Ltrim(Kardex.CodMarca)) + '-' + Rtrim(Ltrim(DimMarcaProductos.Descripcion)) As 'Marca_Pdto'
                    , 'Saldos_Bod' As 'Origen_Venta', 'Saldos_Bod' As 'Origen_Pedido'

                    , 0 As 'Total_Unidades'
                    , 0 As 'Dcto_Referencia'
                    , 0 As 'Dcto_Web'
                    , 0 As 'Dcto_Promo'
                    , 0 As 'Dcto_Comercial'
                    , 0 As 'Total_Dcto'
                    , 0 As 'Vta_menos_Dcto'


                    From SaldosBodega_2000 Saldos_Bod
                    Inner Join Bodegas        On Bodegas.Bodega = Saldos_Bod.Bodega
                    Inner Join Kardex         On Kardex.Referencia = Saldos_Bod.Referencia
                    Left Outer Join Grupos    On Grupos.Grupo = Kardex.Grupo
                    Left Outer Join SubGrupos On (SubGrupos.Grupo + SubGrupos.SubGrupo) = (Kardex.Grupo + Kardex.SubGrupo)
                    Left Outer Join Departamentos On Departamentos.Departamento = SubGrupos.Departamento
                    Left Outer Join DimMarcaProductos On DimMarcaProductos.CodMarca = Kardex.CodMarca

                    Where SubString(Saldos_Bod.Mes,1,4) Between (Year(GetDate())  - 1) And Year(GetDate())";

                     $consultar=sqlsrv_query($conn, $sql);
                     while ($fila=sqlsrv_fetch_array($consultar)){
                          echo'
                         
                          
                                <tr>
                                 <td>'.$fila['Documento'].'</td>
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