
Select 
'Ventas' As Documento, Cast(Encabezado.Fecha As Varchar(10)) As 'Fecha', Rtrim(Ltrim(Clientes.Razon_Social)) As 'Cliente'
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
, Case 
    When DocumentoWeb = 1 Then 'Venta_Electronica'
	Else 'Venta_Tradicional' 
  End As 'Origen_Venta'

/* Determina si el pedido fue tomado por el asesor, el cliente, etc */
, Case 
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
      And Encabezado.Mes = '202010'