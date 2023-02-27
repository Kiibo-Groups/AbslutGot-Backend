<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
<table>
  <thead>
    <tr>
      <td><b>ID</b></td>
      <td><b>Fecha</b></td>
      <td><b>Ciudad</b></td>
      <td><b>Usuario</b></td>
      <td><b>Email</b></td>
      <td><b>Telefono</b></td>
      <td><b>Comercio</b></td>
      <td><b>Entrega</b></td>
      <td><b>Dirección</b></td>
      <td><b>Status</b></td>
      <td><b>Tipo de envio</b></td>
      <td><b>Repartidor</b></td>
      <td><b>C/Productos</b></td>
      <td><b>Comisión del restaurante</b></td>
      <td><b>Monto de comision</b></td>
      <td><b>Valor del Cupon</b></td>
      <td><b>Costo con Cupon aplicado</b></td>
      <td><b>Costo de envio</b></td>
      <td><b>Comision de envio</b></td>
      <td><b>Cantidad de comision de envio</b></td>
      <td><b>Monto total</b></td>
      <td><b>Tipo de pago</b></td>
      <td><b>Status del pago</b></td>
    </tr>
  </thead>
  <tbody>
    @php($total = [])
    @php($com = [])
    @foreach($data as $row)
    
    @php($com[] = $user->getCom($row['id'],$row['amount']))

    <tr>
      <td>#{{ $row['id'] }}</td>
      <td>{{ $row['date'] }}</td>
      <td>{{ $row['city'] }}</td>
      <td>{{ $row['user'] }}</td>
      <td>{{ $row['email'] }}</td>
      <td>{{ $row['phone'] }}</td>
      <td>{{ $row['store'] }}</td>
      <td>{{ $row['type'] }}</td>
      <td>{{ $row['addr'] }}</td>
      <td>{{ $row['status'] }}</td>
      <td>{{ $row['type_staff'] }}</td>
      <td>{{ $row['name_staff'] }}</td>
      <td>{{ $row['tot_prods'] }}</td>
      <td>{{ $row['commision'] }}</td>
      <td>{{ $row['comm_amount'] }}</td>
      <td>{{ $row['cupon_value'] }}</td>
      <td>{{ $row['cupon_amount'] }}</td>
      <td>{{ $row['amount_send'] }}</td>
      <td>{{ $row['costs_ship'] }}</td>
      <td>{{ $row['amount_ship'] }}</td>
      <td>{{ $row['amount'] }}</td>
      <td>{{ $row['payment'] }}</td>
      <td>{{ $row['pay_status'] }}</td>
    </tr>
@endforeach   
  </tbody>
</table>


<table>
  <thead>
    <tr>
      <td><b>Cantidad de Productos</b></td>
    </tr>
  </thead>
</table>


<table>
  <thead>
    <tr>
      <td><b>ID Pedido</b></td>
      <td><b>N/ Producto</b></td>
      <td><b>C/ Producto</b></td>
      <td><b>$/ Producto</b></td>
    </tr>
  </thead>
  <tbody>
@foreach($data as $row)
@foreach($row['productos'] as $productos)
@php($total[] = $productos->price)
<tr>
  <td>#{{ $row['id'] }}</td>
  <td>{{ $productos->name }}</td>
  <td>{{ $productos->qty }}</td>
  <td>${{ $productos->price }}</td>
</tr>
@endforeach 
@endforeach 

    <tr>
      <td>&nbsp;</td>
      <td >&nbsp;<b>Montos</b></td>
      <td >&nbsp;<b>{{ count($total) }}</b></td>
      <td >&nbsp;<b>${{ array_sum($total) }}</b></td>
      <td >&nbsp;<b>{{ array_sum($com) }}</b></td>
    </tr>
</tbody>
</table>
 
</body>
</html>