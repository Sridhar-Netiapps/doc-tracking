<p>Dear Branch Team,</p>
<p>New Pending documents syncing completed in the document tracker and the count is mentioned below. </p>
<table style="border: 2px solid black; border-collapse: collapse; text-align: left;">
    <tr style="border-bottom: 1px solid black;">
        <th style="border-right: 1px solid black;">MB Loan Documents</th>
        <td style="padding: 5px">{{ $data['loan']}} </td>
    </tr>
    <tr style="border-bottom: 1px solid black;">
        <th style="border-right: 1px solid black;">Gold Loan Documents</th>
        <td style="padding: 5px">{{ $data['goldloan']}} </td>
    </tr>
    <tr style="border-bottom: 1px solid black;" >
        <th style="border-right: 1px solid black;">Liabilities Documents</th>
        <td style="padding: 5px">{{ $data['aof']}} </td>
    </tr>
    <tr style="border-bottom: 1px solid black;">
        <th style="border-right: 1px solid black;">DTR Files</th>
        <td style="padding: 5px">{{ $data['dtrf']}} </td>
    </tr>
</table>
<p><a href={{ url('/') }}>Click Here</a></p>
<p>Thank you.</p>