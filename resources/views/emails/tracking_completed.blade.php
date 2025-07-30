<p>Dear Branch ({{ $data['branch_code'] }}),</p>
<p>Your dispatch ref no: {{ $data['dispatch_no'] }} AWB/POD No: {{ $data['awb_pod'] }} dated {{ $data['dispatch_date'] }} tracking is completed.</p>
<p>Pls click on the link below to check the status.</p>
<a href="{{ url('/home') }}">(doctrack)</a>
<p>If any document is “Rejected” pls arrange to redispatch.</p>
<p>Also check “Received with Query” cases in the document tracker and ensure queries are cleared.</p>
<p>Please reach out to Regional Operations Team for any clarifications.</p>