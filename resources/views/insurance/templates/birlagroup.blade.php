<!DOCTYPE html>
<html>
<head>
  <title>Birla Group Insurance</title>
</head>
<style type="text/css" nonce="wUDPhZ1Z60inspnMCukimCi">
   @page {
      margin: 0cm; /* removes all default page margins */
    }

    .pagelayout {
      width: 780px;  
      margin: 0px auto;      /* Center horizontally with some vertical spacing */
      background-color: #fff; /* Optional: for contrast */
      padding: 10px;
    }
    .pageheader{
      height: 90px;
      background-color: #fff;
    }
   
    .companyheader {   
      justify-content: space-between;
      margin:auto;
    }
    .lhs{
       padding: 20px 20px;
    }
    .companyname{
     font-weight: bolder;
     font-size: 18px;
     color: #C91228;

    }
    .companydesc{
      font-weight: bolder;
      font-size: 18px;
      color: #C91228;
    }
    .companylogo{
      margin-top: 10px;
      padding: 10px ;
      text-align: right;

    }
    .logo{
       height: 80px;
    }

    .contentmain{
      padding: 5px;
    }
    .formname{
     font-weight: bolder;
     font-size: 15px;
     color: #000; 
    }
    .label-font1{
      font-size: 13px;
      font-weight: bold;
    }
    .label-font{
      font-size: 15px;
    }

    .contentrows {
      display: flex;
      justify-content: flex-start;
      margin:auto;
      margin-top: 4px;
    }
    .policy{
     padding-right: 30px;
     padding-left: 5px;
     font-size: 12px;
    }
    .smallpad{
      padding-left: 10px;
      padding-right: 10px;
    }
    .policyunderine{
      border-bottom: 2px solid black;
    }
    .policyunderline {
      border-bottom: 2px solid black;
      display: inline-block;
      margin-right: 20px;
      width: 50px;
    }
    .policyunderlinefull {
      border-bottom: 2px solid black;
      display: inline-block;
      width: 300px;
    }
    .signunderline {
      border-bottom: 2px solid black;
      display: inline-block;
      margin-right: 30px;
      width: 130px;
    }
    .twocol-table {
      width: 100%;
      border-collapse: collapse;
     
    }

    .twocol-table td, .twocol-table th {
      border: 1px solid black;
      padding: 6px;
      vertical-align: top;
    }
     .twocol-table-noborder {
      width: 100%;
      border-collapse: collapse;
     
    }
    .twocol-table-noborder td, .twocol-table-noborder th {
    
      vertical-align: top;
      margin-top: 40px;
      padding: 20px;
    }
    .rightalign{
      text-align: right;
    }


    .col-50 {
      width: 50%;
    }
    .font_declaration{
      font-size: 15px;
      margin-left: 10px;
      margin-right: 10px;

    }

    .page-footer{
      align-items: bottom;
    }

    .footer-bg{
      background-color: #fff;
      padding: 1px;
      justify-content: start;
      
    }
    .footer-text{
      margin-left: 10px;
      font-size: 11px;
      color: #000;
     
    }

    .page-break {
      page-break-before: always;
    }
    .doc-header{
      background-color: #FAD691;
      margin: 10px;
      
    }
    .doc-text{
      font-size: 15px;
      margin-left: 10px;
    }
     .declarationfillin{
      display: inline-block;
      border-bottom: 2px solid black;
      width: 100px;
    }
    .datebox{
      border: 1px solid black;
      width: 10px;
      height: 10px;
      padding: 3px;
    }
    .col-12{
      width: 12.5%;
    }
    .marginTop{
      margin-top: 7px;
    }

    .tableunderline {
      border-bottom: 1px solid black;
      display: inline-block;
      width: 90%;
    }
    .bg-span{
      background-color: #fff;
      padding: 5px;
    }

  .policy-box {
    display: inline-block;
    width: 15px;
    height: 15px;
    line-height: 15px;
    text-align: center;
    border: 1px solid #000;
    font-weight: 600;
    border-radius: 0; 
    padding: 2px;  /* optional: makes them join like a strip */
    margin: 7px 0px 0px 0px;          /* removes space */
  }

  .text-placeholder{
    color: #BFC6C4;
  }

  .footer-fixed {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    text-align: center;
    padding: 10px 0;
    background: #fff;
}



    

  
</style>
<body>
  
  <div class="pagelayout">
    <div class="pageheader">
      <div class="companyheader">
      
       <table class="twocol-table-noborder">
         <tr>
           <td class="col-50">
             <div class="margintop">
                <label class="companyname"></label>
                <div><label class="companydesc">Aditya Birla Sun Life </label></div>
                <div><label class="companydesc">Insurance Company Ltd</label></div>
              </div>
           </td>
           <td class="col-50 rightalign"><img class="logo" src="{{ $base64 }}"> </td>
         </tr>
       </table>
      </div>
    </div>
    
    <div class="contentmain">
      <div class="formname">GROUP DEATH CLAIM FORM - AFFINITY</div>
      <span class="label-font1">(To be completed by the Group Policyholder)</span>
      
      <div class="contentrows">
        <label class="label-font"> Group Policy No. : <strong class="policy ">@foreach(str_split($data->policy_number) as $char)<span class="policy-box">{{ $char }}</span>@endforeach</strong></label>
       

        <label class="label-font"> Member Id : <strong class="policy">@foreach(str_split($data->mp_no) as $char)<span class="policy-box">{{ $char }}</span>@endforeach</strong></label>
       <!--  <label class="label-font"> Client ID:<strong class="policy">{{$data->actual_id}}</strong></label>
        <label class="label-font"> Claim Amount:<strong class="policy">{{$data->claim_amount}}</strong></label> -->
      </div>

      <div class="contentrows">
        <label class="label-font"> Name of Group Policyholder : 
          <strong class="policy ">@foreach(str_split("Ujjivan Small Finance Bank") as $char)<span class="policy-box">{{ $char }}</span>@endforeach</strong></label>
       <!--  <strong class="label-font"> Gender:<strong class="policy">{{$data->gender}}</strong></strong>

        <strong class="label-font"> LAN:<strong class="policy">{{$data->load_acc_id}}</strong></strong> -->
      </div>

      <div class="contentrows">
        <label class="label-font"> Full Name of deceased Member : 
          <strong class="policy ">@foreach(str_split( $data->deceased_name ) as $char)<span class="policy-box">{{ $char }}</span>@endforeach</strong></label></label>
      </div>


      <div class="contentrows">
        <label class="label-font"> Date of Birth : 
          <strong class="policy ">@foreach(str_split(($data->dob !='')? date('dmY',strtotime($data->dob)): '') as $char)<span class="policy-box">{{ $char }}</span>@endforeach</strong>
        </label>
        <label class="label-font"> Date of Joining Policy : 
          <strong class="policy ">@foreach(str_split(($data->policy_covered_date !='')? date('dmY',strtotime($data->policy_covered_date)): '') as $char)<span class="policy-box">{{ $char }}</span>@endforeach</strong>
        </label>
       
      </div>

      <div class="contentrows">
         <label class="label-font"> Date of last attended duties : <strong class="policy text-placeholder">@foreach(str_split("DDMMYYYY") as $char)<span class="policy-box">{{ $char }}</span>@endforeach</strong></label>
        <label class="label-font"> Date of Death :<strong class="policy">
        @foreach(str_split(($data->policy_covered_date !='')? date('dmY',strtotime($data->date_of_death)): '') as $char)<span class="policy-box">{{ $char }}</span>@endforeach</strong></label>
        
      </div>

      <div class="contentrows">
        <label class="label-font"> Time of Death : <strong class="policy text-placeholder">@foreach(str_split("HHMM") as $char)<span class="policy-box">{{ $char }}</span>@endforeach</strong></strong></label>
        <label class="label-font"> A.M/P.M</label>
      </div>

      <div class="contentrows">
        <label class="label-font"> Cause of Death : <strong class="policy {{ empty($data->cause_of_death)?'policyunderline': ''}}">{{$data->cause_of_death}}</strong></label>
        <label class="label-font "> Age as on Date of Death : </label><strong class="policyunderline" >{{ $data->age}}</strong>
        <label>Years</label>
        <strong class="policyunderline" ></strong>
        <label>Month(s)</label>
      </div>

      <div class="contentrows">
        <strong class="label-font1">In case of accidental death:</strong>
        <label class="label-font"> Date of Accident:</label><strong class="policy text-placeholder">@foreach(str_split("DDMMYYYY") as $char)<span class="policy-box">{{ $char }}</span>@endforeach</strong></span>
        <
      </div>

       <div class="contentrows">
        
        <label class="label-font"> Nature of Accident: (Road/Rail/Air/Other (specify) </label><span class="policyunderlinefull policyunderline"></span> 
      </div>

      <div class="contentrows">
        <label class="label-font">Outstanding Loan amount as on Date of Death (as per CAS):</label>
        <span class="smallpad policyunderlinefull"></span> 
      </div>

      <div class="contentrows">
         <label class="label-font"> Upon admissibility of Claim, the Payment is to be made in favour of - <strong class="policy">{{$data->nominee_name}}</strong></label>
        
      </div>

       <div class="contentrows">
         <label class="label-font"> (Tick whichever is applicable and fill in the bank details) <input type="checkbox" name="">Group Policyholder <input type="checkbox" name="">Beneficiary </label>
      </div>
      
       <div class="contentrows">
        <label class="label-font">(Please note that any claim amount in excess of the outstanding loan as above will be settled in the favour of the beneficiary in accordance with the
        Terms &amp; Conditions of Policy Contract)</label>
      </div>

     <br>
      <div class="contentrows">
        <table class="twocol-table">
          <tr>
            <td class="col-50 content-label">If Payment to be made in favor of <strong>Beneficiary</strong>  then please provide the
               below details:</td>
            <td class="col-50 content-label">If Payment to be made in favor of <strong>Group Policyholder Holder</strong>  (GPH) then
               please provide the below details</td>   
          </tr>

          <tr>
            <td>
              <div class="label-font tableunderline contentrows"> 
                <span class="bg-span">Beneficiary’s Name:</span> 
                <strong class="policy">{{$data->nominee_name}}</strong>
              </div>
             
              <div class="label-font tableunderline contentrows"> 
                <span class="bg-span">Bank Name:</span>
                <strong class="policy">{{$data->nominee->bank_name}}</strong>
             </div>

              <div class="label-font tableunderline contentrows">
                <span class="bg-span">Relationship to the deceased:</span>
                <strong class="policy">{{$data->relationship}}</strong>
              </div>

              <div class="label-font tableunderline contentrows">
                <span class="bg-span">Account Type:</span>
                <strong class="policy"></strong>
              </div>
              <div class="label-font tableunderline contentrows">
                <span class="bg-span">Account No.:</span>
                <strong class="policy">{{$data->nominee->acc_number}}</strong>
              </div>
              <div class="label-font tableunderline contentrows">
                <span class="bg-span">IFSC Code:</span>
                <strong class="policy">{{$data->nominee->ifsc}}</strong>
              </div>
              <div class="label-font tableunderline contentrows">
                <span class="bg-span">Contact No.:</span> 
                <strong class="policy">{{$data->nominee->nominee_number}}</strong>
              </div>
              <div class="label-font tableunderline contentrows">
                <span class="bg-span">Email Id:</span>
                <strong class="policy"></strong>
              </div>
              
            </td>
            
            <td>
              <div class="label-font tableunderline contentrows">
                <span class="bg-span">GPH Name:</span>
                <strong class="policy">{{ ($data->deceased == 'Customer')? 'Ujjivan Small Finance Bank':''}}</strong></div>
              <div class="label-font tableunderline contentrows">
                <span class="bg-span">Bank Name:</span>
                <strong class="policy">{{ ($data->deceased == 'Customer')? ' Ujjivan ABSLI Insurance ':''}}</strong></div>
              <div class="label-font tableunderline contentrows">
                <span class="bg-span">Account Type:</span>
                <strong class="policy">{{ ($data->deceased == 'Customer')? ' CURRENT ACCOUNT ':''}}</strong>
              </div>
              <div class="label-font tableunderline contentrows">
                <span class="bg-span">Account No.:</span>
                <strong class="policy">{{ ($data->deceased == 'Customer')? '10001025261001':''}}</strong>
              </div>
              <div class="label-font tableunderline contentrows">
                <span class="bg-span">IFSC Code:</span>
                <strong class="policy">{{ ($data->deceased == 'Customer')? 'UJVN0099999':''}}</strong>
              </div>
              <div class="label-font tableunderline contentrows">
                <span class="bg-span">Contact No.:</span>
                <strong class="policy"></strong>
              </div>
              <div class="label-font tableunderline contentrows">
                <span class="bg-span">Email Id:</span>
                <strong class="policy"></strong>
              </div>
              
            </td>
          </tr>
          
         
        </table>

      </div>
      <br>
      <strong class="">Declaration by Group Policyholder</strong><br>
      <span class="font_declaration">We agree to save and hold Aditya Birla Sun Life Insurance Company Limited (ABSLI) harmless and indemnified 
against any and/or all losses, claims, liabilities, legal proceedings (Including attorney fees’), expenses, or damages 
suffered by or taken against ABSLI arising on account of any error or misrepresentation in the information 
furnished for Electronic Fund Transfer which may be instituted, preferred, claimed or made against ABSLI, its 
successors or assigns by any person or persons making a claim to the said Policy benefits.</span><br>
      <span class="font_declaration">
      We hereby declare that the particulars given above are true and correct. We undertake to indemnify Aditya Birla 
Sun Life Insurance Company Limited (ABSLI) the loss suffered, if any, due to wrong statement or information 
given in connection with this claim.</span>
      <span class="font_declaration">
      We agree that from this statement and all other papers and declarations in connection with this claim called by Aditya 
Birla Sun Life Insurance Company Limited (ABSLI) shall constitute Proof of death and may be used in any court of law.</span><br>
      <span class="font_declaration">
      We agree that payment of claim amount shall constitute discharge of liability of ABSLI.</span>
      <span class="font_declaration">
      We agree that submission of this form will not be construed as acceptance of the claim by ABSLI. ABSLI reserves 
the right to call upon additional documents.
</span>
  
 </div>
  <div class="page-break"></div>

  <div class="pagelayout">   
  <div class="contentmain">  
       
      <div class="contentrows">
        <label class="label-font">Name and Designation of the Authorized Person:</label>
        <span class="policyunderlinefull"></span> 
      </div>
     
      <div class="contentrows">
        <label class="label-font">Signature of Authorized Person:</label>
        <span class="smallpad signunderline"></span> 
        <label class="label-font">Seal /Stamp of Group Policyholder:</label>
        <span class="signunderline"></span> 
      </div>

      <div class="contentrows marginTop">
        <label class="label-font">Date 
          <strong class="policy text-placeholder">@foreach(str_split("DDMMYYYY") as $char)<span class="policy-box">{{ $char }}</span>@endforeach</strong>
        </label>
         
        <label class="label-font">Place:</label>
        <span class="policyunderlinefull"></span> 
      </div>
     
  
    <div class="contentrows"></div>
     <strong class="contentrows">Declaration by Claimant</strong><br>
      <span class="font_declaration">I hereby notify the Aditya Birla Sun Life Insurance Company Limited (ABSLI) that Mr./Ms./Master<strong class="declarationfillin"></strong> whose life was insured by the said company,
      under group policy no. <strong class="declarationfillin"></strong>is no more and I hereby declare that the said person is the Life Insured described above and that the aforesaid answers and statements
      made by me are true and correct. I agree that furnishing of this form, or any forms supplemental thereto, shall not constitute nor be considered an admission of claim by
      Aditya Birla Sun Life Insurance Company Limited (ABSLI) that there was any assurance in force on the life in question or of its liability thereunder, nor a waiver of any of its
      rights or defence. I hereby authorize any physician, hospital, clinic, insurance company or other organization, institution or person that has any record of the deceased or his
      health, to give to Aditya Birla Sun Life Insurance Company Limited (ABSLI), any and all information about the deceased with reference to his health and medical history and
      any hospitalization, advice, diagnosis, treatment, disease or ailment. I further authorize the Employers (past and present) of the Life Insured to furnish to Aditya Birla Sun
      Life Insurance Company Limited (ABSLI), details of the leave availed of by the Life Insured during the last three years of his service together with copies of the leave
      applications and medical certificates, if any, submitted by the Life Insured in support of such applications and details of reimbursement of medical expenses. I also consent
      to a personal investigation.</span><br>
      <span class="font_declaration contentrows">I agree that payment of claim amount shall constitute discharge of liability of ABSLI.</span>
      <div class="contentrows marginTop">
         <label class="label-font">Date <strong class="policy text-placeholder">@foreach(str_split("DDMMYYYY") as $char)<span class="policy-box">{{ $char }}</span>@endforeach</strong> </label>
        
          <label class="label-font">Signed at:</label>
          <span class="signunderline"></span> 
          <label class="label-font">Signature of Claimant:</label>
          <span class="signunderline"></span> 
      </div>

    </div>
   
  
    <div class="doc-header">
     <lable class="doc-text">Mandatory Documents required to be submitted along with claim intimation</lable>     
   </div>
   
   <div class="contentmain">
   <div class="label-font">a) Copy of Death Certificate issued by Municipal Authority / Gram Panchyat duly attested by the Group Policyholder.</div>
   <div class="label-font contentrows">b) Death Claim Form.</div>
   <div class="label-font contentrows">c) Bank statement/Printed Cancel Cheque Copy.</div>
   <div class="label-font contentrows">d) KYC of Beneficiary.</div>
   <div class="label-font contentrows">e) Credit Account Statement.</div>
   <div class="label-font contentrows">f) Loan Account Statement.</div>
   <div class="label-font contentrows">g) Madical Attendant&#39;s Certificate.</div>
  
  <br>
   <div><strong>In case of Unnatural death</strong> </div>
   <div class="label-font contentrows">a) Copies of FIR</div>
   <div class="label-font contentrows">b) Post Mortem Report</div>
   <div class="label-font contentrows">c) Police Inquest Report attested by the Group Policy Holder would be required to be submitted.</div>
   <div class="label-font contentrows">d) News Paper Cutting, if any</div>
   
   <br>
   <div> 
    <strong>ABSLI reserves the right to call for any addition requirements/Information to process the Claim.</strong>
   </div>
   </div>
  
  

   <div class="footer-fixed">
     <hr/>
     <table class="twocol-table-noborder">
         <tr>
           <td class="col-70">
             <div class="margintop">
                <label class="companyname"></label>
                <div><label class="footer-text">“The Trade Logo “Aditya Birla Capital” Displayed Above Is Owned By ADITYA BIRLA MANAGEMENT 
CORPORATION PRIVATE LIMITED (Trademark Owner) And Used By ADITYA BIRLA SUN LIFE INSURANCE 
COMPANY LIMITED (ABSLI) under the License.”</label></div>
                <div><label class="footer-text">Aditya Birla Sun Life Insurance Company Limited Registered Office: One World Centre, Tower 1, 16th Floor, 
Jupiter Mill Compound, 841, Senapati Bapat Marg, Elphinstone Road, Mumbai - 400 013. IRDAI Reg No.109 | 
CIN: U99999MH2000PLC128110 Toll free no. 1-800-270-7000 https://lifeinsurance.adityabirlacapital.com</label></div>
              </div>
           </td>
           <td class="col-30 rightalign"><img class="logo" src="{{ $base64 }}"> </td>
         </tr>
       </table>
</div>

   </div>

   

  </div>
</body>
</html>