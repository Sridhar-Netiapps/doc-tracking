<!DOCTYPE html>
<html>
<head>
	<title>Bajaj Allianz</title>
</head>
<style type="text/css"  nonce="wUDPhZ1Z60inspnMCukimCi">
 @page {
      margin: 0cm; /* removes all default page margins */
    }
    .pagelayout {
      width: 780px;  
      margin: 0px auto;      /* Center horizontally with some vertical spacing */
      background-color: #fff; /* Optional: for contrast */
      padding: 5px;
    }
    .contentlayout{
      border: 1px solid black;
      margin: 10px;
    }
    .innercontent{
      padding : 10px 20px 20px 0px;	
    }
    .headerlogo{
      text-align: right;
      height: 100px;
    }
    .logo{
    	width: 200px;
    	height: 150px;
    }
    .headerdiv{
      align-items: center;
      
    }
    .headername{
      text-align: center;
      font-size: 14px;
      font-weight: bold;
      text-decoration: underline 2px solid black;
      }
    .highlights{
       margin: 10px 40px 20px 80px;
       text-align: start;
       font-size: 12px;
    }
    .label-font{
    	font-size: 12px;
    }	
    .margintop{
    	margin-top: 30px;
    }
     .nomineename{
    	width: 80px;
    	display: inline-block;
    	padding: 0px 2px 0px 2px;
    	text-align: center;
    }
    .marginleft{
    	margin-left: 10px;
    	padding: 0px 5px 0px 5px;
    }
    .maincontent{
    	padding: 2px;
    }
    .twocol-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
      margin-bottom: 10px;
     
    }
    .twocol-table td, .twocol-table th {
      border: 1px solid black;
      padding: 10px;
      vertical-align: top;
    }

    .twocol-table-nopadding {
      width: 100%;
      border-collapse: collapse; 
    }

    .twocol-table-nopadding td, .twocol-table-nopadding th {
      border: 1px solid black;
      padding: 4px;
      vertical-align: top;
    }

     .twocol-table-noborder {
      width: 100%;
      border-collapse: collapse; 
    }

    .twocol-table-noborder td, .twocol-table-noborder th {
     
      padding: 4px;
      vertical-align: top;
    }

    .col-50 {
      width: 50%;
    }
    .col-30 {
      width: 30%;
    }
    .col-20 {
      width: 20%;
    }
   
    .bankdetails{
    	border: 1px solid black;
    	padding: 2px;
    	text-align: center;
    }
    .declaration{
    	font-size: 10px;
    	font-style: italic;
    	margin: 10px;
    }

    .stampspace{
      text-align: right;
      margin-right: 100px
    }
    .stamp{
      display: inline-block;
      height: 90px;
      width: 70px;
      border: 1px solid black;
      text-align: center;
    }
    .page-break {
	  page-break-before: always;
	}
	.certificate-content{
		margin-top: 30px;
		padding: 30px;
	}
	.fillablespace{
		display: inline-block;
		width: 100px;
		border-bottom: 1px solid black;
	}
	.linespace{
		margin-top: 10px;

</style>
<body>
<div class="pagelayout">
<div class="contentlayout">
	<div class="innercontent">
	  <div class="headerlogo">
		<img class="logo" src="{{ $base64 }}" alt="logo">
	  </div>

	  <div class="headername"><span>CLAIM INTIMATION &amp; DISCHARGE FORM</span> </div>

	  <div class="highlights">
	  	<div>&bull;&nbsp;&nbsp;The company retains right to call for further evidence needed to process the claim and to entertain or repudiate the claim.</div>
	  	<div>&bull;&nbsp;&nbsp;Acceptance of forms does not amount to admission of claim.</div>
	  	<div class="margintop">I/ we  <strong class="label-font nomineename">«NOMINEE»</strong>(Names of Claimant/ Beneficiary) hereby intimate the death of life assured with the following details:</div>
	  </div>
      
      <div class="maincontent">
	  <div class="label-font">
	  	<strong>1. PARTICULARS OF INSURED:</strong> 
	  </div>
      
      <div class="marginleft">
	      <table class="twocol-table">
	      	<tr>
	      		<td class="col-50">
	      			<label class="label-font">Master Policy No: <strong class="label-font">{{$data->policy_number}}</strong></label>
	      		</td>
	      		<td class="col-50">
	      			<label class="label-font">Master Policyholders Name: <strong class="label-font">UJJIVAN SMALL FINANCE BANK</strong></label>
	      		</td>
	      	</tr>

	      	<tr>
	      		<td class="col-50">
	      			<label class="label-font">Members Name: <strong class="label-font"></strong></label>
	      		</td>
	      		<td class="col-50">
	      			<label class="label-font">Membership No: <strong class="label-font"></strong></label>
	      		</td>
	      	</tr>

	      	<tr>
	      		<td class="col-50">
	      			<label class="label-font">Age: <strong class="label-font"></strong></label>
	      		</td>
	      		<td class="col-50">
	      			<label class="label-font">Sex: <strong class="label-font"></strong></label>
	      		</td>
	      	</tr>

	      	<tr>
	      		<td class="col-50">
	      			<label class="label-font">Loan Account Number: <strong class="label-font"></strong></label>
	      		</td>
	      		<td class="col-50">
	      			<label class="label-font">Sum Assured: <strong class="label-font"></strong></label>
	      		</td>
	      	</tr>

	      	<tr>
	      		<td class="col-50">
	      			<label class="label-font">Outstanding Loan Amount: Rs. <strong class="label-font"></strong></label>
	      		</td>
	      		<td class="col-50">
	      			<label class="label-font">Balance Payable Claimant / Beneficiary (if any): Rs. <strong class="label-font"></strong></label>
	      		</td>
	      	</tr>
	      </table>
      </div>

      <div class="label-font"><strong>2. DETAILS OF CLAIM:</strong></div>

      <div class="marginleft">
      	<table class="twocol-table">
      		<tr>
      			<td class="col-30">
      				<label class="label-font">Date of Event giving rise to claim<strong class="label-font"></strong></label>
      			</td>
      			<td class="col-20"><strong class="label-font"></strong></td>
      			<td class="col-30">
      				<label class="label-font">Cause of Event giving rise to claim<strong class="label-font"></strong></label>
      			</td>
      			<td class="col-20"><strong class="label-font"></strong></td>
      		</tr>
      	</table>
      </div>

      <div class="label-font"><strong>3. PARTICULARS OF CLAIMANT/ BENEFICIARY:</strong></div>

      <div class="marginleft">
      	<table class="twocol-table">
      		<tr>
      			<td class="col-50">
      				<label class="label-font">Name :<strong class="label-font"></strong></label>
      			</td>
      			<td class="col-50">
      				<label class="label-font">Relationship with Insured Member:<strong class="label-font"></strong></label>
      			</td>
      		</tr>

      		<tr>
      			<td class="col-50">
      				<label class="label-font">Address :<strong class="label-font"></strong></label>
      			</td>
      			<td class="col-50">
      				<strong class="label-font"></strong>
      			</td>
      		</tr>

      		<tr>
      			<td class="col-50">
      				<label class="label-font">Details of Identity Proof submitted :<strong class="label-font"></strong></label>
      			</td>
      			<td class="col-50">
      				<label class="label-font">PAN/ Form-60<strong class="label-font"></strong></label>
      			</td>
      		</tr>

      		<tr>
      			<td class="col-50">
      				<label class="label-font">Contact Number:<strong class="label-font"></strong></label>
      			</td>
      			<td class="col-50">
      				<strong class="label-font"></strong>
      			</td>
      		</tr>
      	</table>
      </div>

      <div class="marginleft">
      	<div class="bankdetails"><strong class="label-font">Claimant’s Bank Details</strong></div>
      	<table class="twocol-table-nopadding">
      		<tr>
      			<td class="col-20">
      				<label class="label-font">Bank Name<strong class="label-font"></strong></label>
      			</td>
      			<td class="col-30"><strong class="label-font"></strong></td>
      			<td class="col-20">
      				<label class="label-font">Bank Branch<strong class="label-font"></strong></label>
      			</td>
      			<td class="col-30"> <strong class="label-font"></strong></td>
      		</tr>

      		<tr>
      			<td class="col-20">
      				<label class="label-font">IFSC Code<strong class="label-font"></strong></label>
      			</td>
      			<td class="col-30"><strong class="label-font"></strong></td>
      			<td class="col-20">
      				<label class="label-font">Account Number<strong class="label-font"></strong></label>
      			</td>
      			<td class="col-30"><strong class="label-font"></strong></td>
      		</tr>
      	</table>
      </div>

      <div class="headername margintop"><span>CONSENT AND DECLARATION FROM CLAIMANT / BENEFICIARY</span> </div>

      <div class="declaration">I/we hereby confirm that the details provided above are true and complete. I/we further provide consent to Bajaj Allianz Life Insurance Company
		Limited for applying the benefits under this policy first towards the outstanding loan amount as mentioned above, by paying it directly to the Master
		Policyholder. Any remaining balance after appropriation towards outstanding loan amount shall be paid to me/us. I certify that this consent is given
		in consideration of a loan obtained by the life assured from the Master Policyholder. I/we further certify that the loan outstanding amount confirmed
		by the Master Policyholder shall be final and binding. I/we declare that the receipt of benefits by the Master Policyholder and/or by me/us shall be a
		valid and sufficient discharge of Bajaj Allianz Life Insurance Company Limited’s liabilities concerning the life cover provided to the Life Assured.</div>
      
      <div class="stampspace">
		<span class="stamp">Revenue Stamp</span>
	  </div>

	  <table class="twocol-table-noborder">
	  	<tr>
	  		<td class="col-50"><label class="label-font marginleft">Date:-</label><strong class="label-font"></strong></td>
	  		<td class="col-50 label-font">Signature by the Claimant/ Beneficiary on the Revenue Stamp</td>
	  	</tr>
	  </table>
      
     </div>
	</div>
</div>
</div>

<div class="page-break"></div>

<div class="pagelayout">
<div class="contentlayout">
	<div class="certificate-content">
	  <div class="headername"><span class="margintop">Certificate of Master Policyholder</span> </div>

	  <div class="margintop">
	  	 I, <strong class="label-font fillablespace"></strong>currently posted as <strong class="label-font fillablespace"></strong>(Designation) with <strong class="label-font">UJJIVAN SMALL FINANCE BANK</strong> do
         hereby certify
	  </div>
	  
	  	<p class="label-font">1. Insured Member is the same person who has been registered as the Member in the Membership Register maintained by
	  	<strong>UJJIVAN SMALL FINANCE BANK</strong> for the purposes of group insurance scheme administered under Master Policy No.</p> 
	  
	  	<p class="label-font ">2. As per the membership register, Claimant/ Beneficiary who has executed this Claim Discharge Form is the same person who has been nominated by insured member.</p>
	
	  	<p class="label-font ">3. As on date of death, outstanding loan against the membership number <strong class="fillablespace"></strong> is Rs. <strong class="fillablespace"></strong> .</p>
	 

	  <div class="margintop">
	  	<label>Signature :</label>
	  </div>

	  <div class="margintop">
	  	<label>Name &amp; Designation :</label>
	  </div>
    </div>
</div>
</div>
</body>

</html>