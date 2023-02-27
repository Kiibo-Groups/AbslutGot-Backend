<p align="center" style="font-size: 20px">Report Between {{ $from }} To {{ $to }}</p>

<table width="100%" cellspacing="0" cellpadding="0" border="1">

<tr>
<td>&nbsp;<b>Order ID</b></td>
<td>&nbsp;<b>Order Date</b></td>
<td>&nbsp;<b>User</b></td>
<td>&nbsp;<b>Store</b></td>
<td>&nbsp;<b>Total Amount</b></td>
<td>&nbsp;<b>Commission</b></td>
</tr>

@php($total = [])
@php($com = [])
@foreach($data as $row)
@php($total[] = $row['amount'])
@php($com[] = $user->getCom($row['id'],$row['amount']))
<tr>
<td width="17%">&nbsp;#{{ $row['id'] }}</td>
<td width="17%">&nbsp;{{ $row['date'] }}</td>
<td width="17%">&nbsp;{{ $row['user'] }}</td>
<td width="17%">&nbsp;{{ $row['store'] }}</td>
<td width="17%">&nbsp;{{ $row['amount'] }}</td>
<td width="15%">&nbsp;{{ $user->getCom($row['id'],$row['amount']) }}</td>
</tr>

@endforeach	

<tr>
<td width="17%">&nbsp;</td>
<td width="17%">&nbsp;<b>Total Orders</b></td>
<td width="17%">&nbsp;<b>{{ count($total) }}</b></td>
<td width="17%">&nbsp;<b>Total</b></td>
<td width="17%">&nbsp;<b>{{ array_sum($total) }}</b></td>
<td width="15%">&nbsp;<b>{{ array_sum($com) }}</b></td>
</tr>

</table>