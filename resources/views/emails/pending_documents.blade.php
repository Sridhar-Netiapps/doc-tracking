<p>Dear Branch ({{ $data['branch_code'] }}),</p>
<p>Pls find the details of pending documents added to the document tracker for your action. Pls arrange to dispatch them immediately.</p>

<table border="1" cellpadding="5">
    <tr><th>Document Type</th><th>Document Count</th></tr>
    <tr><td>MB Loan</td><td>{{ $data['loan'] }}</td></tr>
    <tr><td>Gold Loan</td><td>{{ $data['goldloan'] }}</td></tr>
    <tr><td>Liabilities</td><td>{{ $data['aof'] }}</td></tr>
    <tr><td>DTR Files</td><td>{{ $data['dtrf'] }}</td></tr>
</table>

<p>Pls click on below link to dispatch</p>
<p><a href={{ url('/')}}>Click Here</a></p>