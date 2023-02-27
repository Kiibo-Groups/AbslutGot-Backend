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
      <td><b>#</b></td>
      <td><b>ID</b></td>
      <td><b>Repartidor</b></td>
      <td><b>Email</b></td>
      <td><b>Comercio</b></td>
      <td><b>% Plataforma</b></td>
      <td><b>Tipo de Comisión</b></td>
      <td><b>% Repartidor</b></td>
      <td><b>Total</b></td>
    </tr>
  </thead>
  
  <tbody>
    @php($total = [])
    @php($id = 0)
        @foreach($data as $row)
            @php($total[] = $row['total'])
            <tr>
                <td>{{ count($total) }}</td>
                <td>{{$row['id']}}</td>
                <td>{{$row['name']}} &nbsp;(<small>{{$row['rfc']}}</small>)</td>
                <td>{{$row['email']}}</td>
                <td>{{$row['store']}} &nbsp;(<small>{{$row['store_rfc']}}</small>)</td>
                <td>{{$row['platform_porcent']}}</td>
                <td>{{$row['type_staff_porcent']}}</td>
                <td>{{$row['staff_porcent']}}</td>
                <td>{{$row['total']}}</td>
            </tr>
        @endforeach
  </tbody>
</table> 
</body>
</html>