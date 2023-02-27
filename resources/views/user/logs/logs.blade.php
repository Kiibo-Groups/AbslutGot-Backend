<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Logs</title>
</head>
<body>
<table>
  <thead>
    <tr>
      <td><b>ID</b></td>
      <td><b>Fecha</b></td>
      <td><b>Usuario</b></td>
      <td><b>Comercio</b></td>
      <td><b>Actividad</b></td>
    </tr>
  </thead>
  <tbody>
    @foreach($data as $row)
    <tr>
      <td>#{{ $row['id'] }}</td>
      <td>{{ $row['date'] }}</td>
      <td>{{ $row['user'] }}</td>
      <td>{{ $row['store'] }}</td>
      <td>{{ $row['log'] }}</td>
    </tr>
@endforeach   
  </tbody>
</table>
</body>
</html>