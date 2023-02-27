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
      <td><b>id</b></td>
      <td><b>status</b></td>
      <td><b>name</b></td>
      <td><b>email</b></td>
      <td><b>Telefono</b></td>
      <td><b>Codigo Referido</b></td>
    </tr>
  </thead>
  
  <tbody>
    @foreach($data as $row)
        <tr>
            <td>{{$row['id']}}</td>
            <td>{{$row['status']}}</td>
            <td>{{$row['name']}} </td>
            <td>{{$row['email']}}</td>
            <td>{{$row['Telefono']}}</td>
            <td>{{$row['refered']}}</td>
        </tr>
    @endforeach
  </tbody>
</table> 
</body>
</html>