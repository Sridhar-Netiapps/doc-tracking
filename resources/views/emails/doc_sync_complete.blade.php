<p>Dear Branch Team,</p>
<p>New Pending documents syncing completed in the document tracker and the count is mentioned below.</p>
<table style="border: 2px">
    <tr>
        <th>MB Loan Documents</th>
        <td>{{ $data['loan']}} </td>
    </tr>
    <tr>
        <th>Gold Loan Documents</th>
        <td>{{ $data['goldloan']}} </td>
    </tr>
    <tr>
        <th>Liabilities Documents</th>
        <td>{{ $data['aof']}} </td>
    </tr>
    <tr>
        <th>DTR Files</th>
        <td>{{ $data['dtrf']}} </td>
    </tr>
</table>
<p><a href={{ url('/') }}>Click Here</a></p>
<p>Thank you.</p>