Use FactuXX
Go

-- Tablas de Ventas
Select 
'Ventas' As Documento, Encabezado.Fecha As 'Fecha_Hora', Rtrim(Ltrim(Clientes.Razon_Social)) As 'Cliente'
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

, 0 As 'Recaudo'
, 0 As 'Saldos_Inv'
, 0 As 'Costo_Inv'

, Round((Select IsNull(Sum(Costo_Mercancia.Costo_Mercancia * Cantidad), 0) From Costo_Mercancia_2000 Costo_Mercancia Where Costo_Mercancia.Id = Detalle.Id),0) As 'CMV'

, Case When Round(((((Detalle.Valor * Detalle.Cantidad) - ((Detalle.Valor * Detalle.Cantidad) * ((Detalle.Descuento + Detalle.DescuentoProducto + Detalle.DescuentoComercial)/100))))),0) <> 0 Then
	 Round(((((Detalle.Valor * Detalle.Cantidad) - ((Detalle.Valor * Detalle.Cantidad) * ((Detalle.Descuento + Detalle.DescuentoProducto + Detalle.DescuentoComercial)/100))))) - (Select IsNull(Sum(Costo_Mercancia.Costo_Mercancia * Cantidad), 0) From Costo_Mercancia_2000 Costo_Mercancia Where Costo_Mercancia.Id = Detalle.Id),0) 
  Else 0 End As 'Rentabilidad'

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


Union All



-- TABLAS DE DEVOLUCIONES 

-- Tablas de Devolucion en Ventas
Select Distinct
'Dev_Ventas' As Documento, Encabezado.Fecha As 'Fecha_Hora', Rtrim(Ltrim(Clientes.Razon_Social)) As 'Cliente'
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
, 'Dev_Ventas' As 'Origen_Venta'

/* Determina si el pedido fue tomado por el asesor, el cliente, etc */
, Case 
    When Detalle.OrigenPedido = 0 Then 'Asesor iVentas'
	When Detalle.OrigenPedido = 1 Then 'Cliente en la Web'
	When Detalle.OrigenPedido = 2 Then 'Asesor en la web'
	When Detalle.OrigenPedido = 3 Then 'Factura Manual'
  End As 'Origen_Pedido'

, (Detalle.Cantidad * -1) As 'Total_Unidades'
, Round((((Detalle.Valor * Detalle.Cantidad) * (Detalle.Descuento/100)) * -1),0) As 'Dcto_Referencia'
, Case When Detalle.TipoDescuento = 1 Then Round((((Detalle.Valor * Detalle.Cantidad) * (Detalle.DescuentoProducto/100)) * -1),0) Else 0 End As 'Dcto_Web'
, Case When Detalle.TipoDescuento = 2 Then Round((((Detalle.Valor * Detalle.Cantidad) * (Detalle.DescuentoProducto/100)) * -1),0) Else 0 End As 'Dcto_Promo'
, Round((((Detalle.Valor * Detalle.Cantidad) * (Detalle.DescuentoComercial/100)) * -1),0) As 'Dcto_Comercial'
, Round((((Detalle.Valor * Detalle.Cantidad) * ((Detalle.Descuento + Detalle.DescuentoProducto + Detalle.DescuentoComercial)/100)) * -1),0) As 'Total_Dcto'
, Round(((((Detalle.Valor * Detalle.Cantidad) - ((Detalle.Valor * Detalle.Cantidad) * ((Detalle.Descuento + Detalle.DescuentoProducto + Detalle.DescuentoComercial)/100)))) * -1),0) As 'Vta_menos_Dcto'

, 0 As 'Recaudo'
, 0 As 'Saldos_Inv'
, 0 As 'Costo_Inv'

, Round((Select IsNull(Sum(Costo_Mercancia.Costo_Mercancia * Cantidad), 0)  From Costo_Mercancia_2000 Costo_Mercancia Where Costo_Mercancia.Id = Detalle.Id) * -1,0) As 'CMV'

, Case When Round(((((Detalle.Valor * Detalle.Cantidad) - ((Detalle.Valor * Detalle.Cantidad) * ((Detalle.Descuento + Detalle.DescuentoProducto + Detalle.DescuentoComercial)/100))))),0) <> 0 Then
	 Round((((((Detalle.Valor * Detalle.Cantidad) - ((Detalle.Valor * Detalle.Cantidad) * ((Detalle.Descuento + Detalle.DescuentoProducto + Detalle.DescuentoComercial)/100))))) - (Select IsNull(Sum(Costo_Mercancia.Costo_Mercancia * Cantidad), 0) From Costo_Mercancia_2000 Costo_Mercancia Where Costo_Mercancia.Id = Detalle.Id)) * -1,0) 
Else 0 End As 'Rentabilidad'

From Dev_Fac2_2000 Detalle
Inner Join Dev_Fac1_2000 Encabezado  On Detalle.Numero = Encabezado.Numero
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










Union All


-- TABLAS DE CAJA

-- Tablas de Recibos de Caja
Select Distinct
'Recaudo' As Documento, Encabezado.Fecha As 'Fecha_Hora', Rtrim(Ltrim(Clientes.Razon_Social)) As 'Cliente'
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
, (Encabezado.Valor) As 'Recaudo'
, 0 As 'Saldos_Inv'
, 0 As 'Costo_Inv'
, 0 As 'CMV'
, 0 As 'Rentabilidad'

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
      And (Encabezado.Codigo_Plantilla = '' Or Encabezado.Codigo_Plantilla = 'Null')
	  And Encabezado.Espera = 0 /* And Detalle.Anulado = 0 */
      And Encabezado.Mes = '202010'

Union All








-- TABLAS DE NOTA DEBITO

-- Tablas de Notas D�bito
Select Distinct
'Notas D�bito' As Documento, Encabezado.Fecha As 'Fecha_Hora', Rtrim(Ltrim(Clientes.Razon_Social)) As 'Cliente'
, Rtrim(Ltrim(Vendedor.Nombre))  As 'Vendedor', 'Notas D�bito' As 'Bodega', 'Notas D�bito' As 'Departamento'
, Convert(Varchar(2), Clientes.Precio_de_Venta) As 'Lista_Precio'
, 'Notas D�bito' As 'Grupo_inv', 'Notas D�bito' As 'SubGrupo', Encabezado.Numero, 'Notas D�bito' As 'Referencia', 'Notas D�bito' As 'Ref_Color'
, 'Notas D�bito' As 'Producto', Rtrim(Ltrim(DimSectorEconomico.Descripcion)) As 'Sector_Economico', 'Notas D�bito' As 'Zona'
, Rtrim(Ltrim(DimDistribuidor.Descripcion)) As 'Canal_Distribucion', 'Notas D�bito' As 'Color'
, Rtrim(Ltrim(DimRegiones.CodRegion)) + '-' + Rtrim(Ltrim(DimRegiones.NombreRegion)) As 'Nbre_Region'
, 'Notas D�bito' As 'Marca_Pdto', 'Notas D�bito' As 'Origen_Venta', 'Notas D�bito' As 'Origen_Pedido'

, 0 As 'Total_Unidades'
, 0 As 'Dcto_Referencia'
, 0 As 'Dcto_Web'
, 0 As 'Dcto_Promo'
, 0 As 'Dcto_Comercial'
, 0 As 'Total_Dcto'
, 0 As 'Vta_menos_Dcto'
, Detalle.Valor As 'Recaudo'
, 0 As 'Saldos_Inv'
, 0 As 'Costo_Inv'
, 0 As 'CMV'
, 0 As 'Rentabilidad'

From Nd_CxC1_2000 Encabezado
Inner Join Nd_CxC2_2000 Detalle      On Detalle.Numero = Encabezado.Numero
Inner Join Clientes                  On Clientes.Nit   = Encabezado.Nit
Inner Join Vendedor                  On Vendedor.Vendedor = Clientes.Vendedor
Inner Join Zonas                     On Zonas.Zona = Encabezado.Zona
Left Outer Join dimEstado            On clientes.departamento = dimestado.codestado         
Left Outer Join dimciudad            On clientes.codciudad = dimciudad.codciudad
Left Outer Join DimSectorEconomico   On Zonas.Sector = DimSectorEconomico.Sector
Left Outer Join DimDistribuidor      On DimDistribuidor.Categoria = DimSectorEconomico.Categoria
Left Outer Join DimRegiones          On DimRegiones.CodRegion = DimSectorEconomico.CodRegion

Where Encabezado.Anulado = 0 And Detalle.Anulado = 0 And Encabezado.Espera = 0
      And Encabezado.Mes = '202010'

Union All















-- TABLAS NOTAS CREDITO

-- Tablas de Notas Cr�dito
Select Distinct
'Notas Cr�dito' As Documento, Encabezado.Fecha As 'Fecha_Hora', Rtrim(Ltrim(Clientes.Razon_Social)) As 'Cliente'
, Rtrim(Ltrim(Vendedor.Nombre))  As 'Vendedor', 'Notas Cr�dito' As 'Bodega', 'Notas Cr�dito' As 'Departamento'
, Convert(Varchar(2), Clientes.Precio_de_Venta) As 'Lista_Precio'
, 'Notas Cr�dito' As 'Grupo_inv', 'Notas Cr�dito' As 'SubGrupo', Encabezado.Numero, 'Notas Cr�dito' As 'Referencia', 'Notas Cr�dito' As 'Ref_Color'
, 'Notas Cr�dito' As 'Producto', Rtrim(Ltrim(DimSectorEconomico.Descripcion)) As 'Sector_Economico', 'Notas Cr�dito' As 'Zona'
, Rtrim(Ltrim(DimDistribuidor.Descripcion)) As 'Canal_Distribucion', 'Notas Cr�dito' As 'Color'
, Rtrim(Ltrim(DimRegiones.CodRegion)) + '-' + Rtrim(Ltrim(DimRegiones.NombreRegion)) As 'Nbre_Region'
, 'Notas Cr�dito' As 'Marca_Pdto', 'Notas Cr�dito' As 'Origen_Venta', 'Notas Cr�dito' As 'Origen_Pedido'

, 0 As 'Total_Unidades'
, 0 As 'Dcto_Referencia'
, 0 As 'Dcto_Web'
, 0 As 'Dcto_Promo'
, 0 As 'Dcto_Comercial'
, 0 As 'Total_Dcto'
, 0 As 'Vta_menos_Dcto'
, (Detalle.Valor * -1) As 'Recaudo'
, 0 As 'Saldos_Inv'
, 0 As 'Costo_Inv'
, 0 As 'CMV'
, 0 As 'Rentabilidad'

From Nc_CxC1_2000 Encabezado
Inner Join Nc_CxC2_2000 Detalle      On Detalle.Numero = Encabezado.Numero
Inner Join Clientes                  On Clientes.Nit   = Encabezado.Nit
Inner Join Vendedor                  On Vendedor.Vendedor = Clientes.Vendedor
Inner Join Zonas                     On Zonas.Zona = Encabezado.Zona
Left Outer Join dimEstado            On clientes.departamento = dimestado.codestado         
Left Outer Join dimciudad            On clientes.codciudad = dimciudad.codciudad
Left Outer Join DimSectorEconomico   On Zonas.Sector = DimSectorEconomico.Sector
Left Outer Join DimDistribuidor      On DimDistribuidor.Categoria = DimSectorEconomico.Categoria
Left Outer Join DimRegiones          On DimRegiones.CodRegion = DimSectorEconomico.CodRegion

Where Encabezado.Anulado = 0 And Detalle.Anulado = 0 And Encabezado.Espera = 0
      And Encabezado.Mes = '202010'

Union All













-- TABLAS DE SALDOS DE BODEGA

-- Tablas de Saldos Bodega (Tomar el Saldo del Inventario)
Select Distinct
'Saldos_Bod' As Documento

, Case
	When SubString(Saldos_Bod.Mes,5,2) = '02' Then Cast(SubString(Saldos_Bod.Mes,1,4) + '-' + SubString(Saldos_Bod.Mes,5,2) + '-' + '28' As Date)
	When SubString(Saldos_Bod.Mes,5,2) In ('04','06','09','11') Then Cast(SubString(Saldos_Bod.Mes,1,4) + '-' + SubString(Saldos_Bod.Mes,5,2) + '-' + '30' As Date)
	When SubString(Saldos_Bod.Mes,5,2) In ('01','03','05','07','08','10','12') Then Cast(SubString(Saldos_Bod.Mes,1,4) + '-' + SubString(Saldos_Bod.Mes,5,2) + '-' + '31' As Date)
  End As 'Fecha_Hora'

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
, 0 As 'Recaudo'

, (Saldos_Bod.Saldo_Inicial + Saldos_Bod.Compras + Saldos_Bod.Entradas_Especiales - Saldos_Bod.Ventas - Saldos_Bod.Salidas_Especiales) As 'Saldos_Inv'
, Round((Saldos_Bod.Saldo_Inicial + Saldos_Bod.Compras + Saldos_Bod.Entradas_Especiales - Saldos_Bod.Ventas - Saldos_Bod.Salidas_Especiales) * Saldos_Bod.Costo_Ponderado_Final,0) As 'Costo_Inv'
, 0 As 'CMV'
, 0 As 'Rentabilidad'

From SaldosBodega_2000 Saldos_Bod
Inner Join Bodegas        On Bodegas.Bodega = Saldos_Bod.Bodega
Inner Join Kardex         On Kardex.Referencia = Saldos_Bod.Referencia
Left Outer Join Grupos    On Grupos.Grupo = Kardex.Grupo
Left Outer Join SubGrupos On (SubGrupos.Grupo + SubGrupos.SubGrupo) = (Kardex.Grupo + Kardex.SubGrupo)
Left Outer Join Departamentos On Departamentos.Departamento = SubGrupos.Departamento
Left Outer Join DimMarcaProductos On DimMarcaProductos.CodMarca = Kardex.CodMarca

Where SubString(Saldos_Bod.Mes,1,4) Between (Year(GetDate())  - 1) And Year(GetDate())