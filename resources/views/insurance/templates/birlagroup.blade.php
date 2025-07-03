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
      padding: 5px;
    }
    .pageheader{
      height: 100px;
      background-color: #C91228;
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
     color: #fff;

    }
    .companydesc{
      font-weight: normal;
      font-size: 15px;
      color: #fff;
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
      padding: 20px;
    }
    .formname{
     font-weight: bolder;
     font-size: 18px;
     color: #000; 
    }
    .label-font{
      font-size: 13px;
    }

    .contentrows {
      display: flex;
      justify-content: flex-start;
      margin:auto;
      margin-top: 10px;
    }
    .policy{
     padding-right: 30px;
     padding-left: 5px;
     font-size: 13px;
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
      margin-right: 30px;
      width: 20px;
    }
    .policyunderlinefull {
      border-bottom: 2px solid black;
      display: inline-block;
      width: 350px;
    }
    .signunderline {
      border-bottom: 2px solid black;
      display: inline-block;
      margin-right: 30px;
      width: 100px;
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
      font-size: 12px;
    }

    .footer-bg{
      background-color: #6A4542;
      padding: 5px;
      justify-content: start;
      
    }
    .footer-text{
      margin-left: 10px;
      font-size: 22px;
      color: #fff;
     
    }

    .page-break {
      page-break-before: always;
    }
    .doc-header{
      background-color: #D58D87;
      
    }
    .doc-text{
      font-size: 12px;
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
  
</style>
<body>
  
  <div class="pagelayout">
    <div class="pageheader">
      <div class="companyheader">
       <!--  <div class="lhs">
          <label class="companyname">LIFE INSURANCE</label>
          <div><label class="companydesc">Aditya Birla Sun Life Insurance</label></div>
          <div><label class="companydesc">Company Limited</label></div>
        </div>
       <div class="companylogo"><img class="logo" src="{{ $base64 }}"> </div>  -->
       <table class="twocol-table-noborder">
         <tr>
           <td class="col-50">
             <div class="margintop">
                <label class="companyname">LIFE INSURANCE</label>
                <div><label class="companydesc">Aditya Birla Sun Life Insurance</label></div>
                <div><label class="companydesc">Company Limited</label></div>
              </div>
           </td>
           <td class="col-50 rightalign"><img class="logo" src="{{ $base64 }}"> </td>
         </tr>
       </table>
      </div>
    </div>
    
    <div class="contentmain">
      <div class="formname">GROUP DEATH CLAIM FORM - AFFINITY</div>
      <span>(To be completed by the Group Policyholder)</span>
      
      <div class="contentrows">
        <label class="label-font"> Group Policy No.:<strong class="policy">{{$data->policy_number}}</strong></label>
        <label class="label-font"> Member Id:<strong class="policy"></strong></label>
        <label class="label-font"> Client ID:<strong class="policy">{{$data->cust_id}}</strong></label>
        <label class="label-font"> Claim Amount:<strong class="policy"></strong></label>
      </div>

      <div class="contentrows">
        <label class="label-font"> Name of Group Policyholder:<strong class="policy"></strong></label>
        <label class="label-font"> Gender:<strong class="policy">Male</strong></label>
      </div>

      <div class="contentrows">
        <label class="label-font"> Full Name of deceased Member:<strong class="policy"></strong></label>
      </div>

      <div class="contentrows">
        <label class="label-font"> Date of Birth:<strong class="policy"></strong></label>
        <label class="label-font"> Date of Joining Policy:<strong class="policy"></strong></label>
        <label class="label-font"> Date of last attended duties:<strong class="policy"></strong></label>
      </div>

      <div class="contentrows">
        <label class="label-font"> Date of Death:<strong class="policy"></strong></label>
        <label class="label-font"> Time of Death:<strong class="policy"></strong></label>
        <label class="label-font"> A.M/P.M</label>
      </div>

      <div class="contentrows">
        <label class="label-font"> Cause of Death:<strong class="policy">{{$data->cause_od_death}}</strong></label>
        <label class="label-font"> Age as on Date of Death:<strong class="smallpad"></strong></label>
        <label>Years</label>
        <strong class="smallpad policyunderine" ></strong>
        <label>Month(s)</label>
      </div>

      <div class="contentrows">
        <strong>In case of accidental death:</strong>
        <label class="label-font"> Date of Accident:<strong class="policy"></strong> </label>
        <label class="label-font"> Nature of Accident: (Road/Rail/Air/Other (specify) </label><span class="smallpad policyunderline"></span> 
      </div>

      <div class="contentrows">
        <label class="label-font">Outstanding Loan amount as on Date of Death (as per CAS):</label>
        <span class="smallpad policyunderlinefull"></span> 
      </div>

      <div class="contentrows">
         <label class="label-font"> Upon admissibility of Claim, the Payment is to be made in favour of - <strong class="policy"></strong></label>
        
      </div>

       <div class="contentrows">
         <label class="label-font"> (Tick whichever is applicable and fill in the bank details) <input type="checkbox" name="">Group Policyholder <input type="checkbox" name="">Beneficiary </label>
      </div>
      
       <div class="contentrows">
        <label class="label-font">(Please note that any claim amount in excess of the outstanding loan as above will be settled in the favour of the beneficiary in accordance with the
        Terms &amp; Conditions of Policy Contract)</label>
      </div>

     
      <div>
        <table class="twocol-table">
          <tr>
            <td class="col-50 content-label">If Payment to be made in favor of Beneficiary then please provide the
               below details:</td>
            <td class="col-50 content-label">If Payment to be made in favor of Group Policyholder (GPH) then
               please provide the below details</td>   
          </tr>

          <tr>
            <td>
              <div>Beneficiary’s Name:<strong class="policy">JAYADA BANU</strong></div>
              <div>Bank Name:<strong class="policy"></strong></div>
              <div>Relationship to the deceased:<strong class="policy">spouse</strong></div>
              <div>Account Type:<strong class="policy"></strong></div>
              <div>Account No.:<strong class="policy"></strong></div>
              <div>IFSC Code:<strong class="policy"></strong></div>
              <div>Contact No.:<strong class="policy"></strong></div>
              <div>Email Id:<strong class="policy"></strong></div>
              
            </td>
            
            <td>
              <div>GPH Name:<strong class="policy"></strong></div>
              <div>Bank Name:<strong class="policy"></strong></div>
              <div>Account Type:<strong class="policy"></strong></div>
              <div>Account No.:<strong class="policy"></strong></div>
              <div>IFSC Code:<strong class="policy"></strong></div>
              <div>Contact No.:<strong class="policy"></strong></div>
              <div>Email Id:<strong class="policy"></strong></div>
              
            </td>
          </tr>
          
         
        </table>

      </div>
      <strong class="font_declaration">Declaration by Group Policyholder</strong>
      <span class="font_declaration">We agree to save and hold Aditya Birla Sun Life Insurance Company Limited (ABSLI) harmless and indemnified against any and/or all losses, claims, liabilities, legal
      proceedings (Including attorney fees’), expenses, or damages suffered by or taken against ABSLI arising on account of any error or misrepresentation in the information
      furnished for Electronic Fund Transfer which may be instituted, preferred, claimed or made against ABSLI, its successors or assigns by any person or persons making a
      claim to the said Policy benefits. We hereby declare that the particulars given above are true and correct. We undertake to indemnify Aditya Birla Sun Life Insurance
      Company Limited (ABSLI) the loss suffered, if any, due to wrong statement or information given in connection with this claim.</span><br>
      <span class="font_declaration">
      We agree that from this statement and all other papers and declarations in connection with this claim called by Aditya Birla Sun Life Insurance Company Limited (ABSLI) shall constitute Proof of death and may be used in any court of law.</span>
      <span class="font_declaration">
      We agree that payment of claim amount shall constitute discharge of liability of ABSLI.</span><br>
      <span class="font_declaration">
      We agree that submission of this form will not be construed as acceptance of the claim by ABSLI. ABSLI reserves the right to call upon additional documents.</span>
      
       
      <div class="contentrows">
        <label class="label-font">Name and Designation of the Authorized Person:</label>
        <span class="policyunderlinefull"></span> 
      </div>
      <p></p>
      
      <div class="contentrows">
        <label class="label-font">Signature of Authorized Person:</label>
        <span class="smallpad signunderline"></span> 
        <label class="label-font">Seal /Stamp of Group Policyholder:</label>
        <span class="signunderline"></span> 
      </div>

      <div class="contentrows">
        <label class="label-font">Date: </label>
        <span class="smallpad">
          <input type="text" name="" class="datebox" placeholder="D">
          <input type="text" name="" class="datebox" placeholder="D">
          <input type="text" name="" class="datebox" placeholder="M">
          <input type="text" name="" class="datebox" placeholder="M">
          <input type="text" name="" class="datebox" placeholder="Y">
          <input type="text" name="" class="datebox" placeholder="Y">
          <input type="text" name="" class="datebox" placeholder="Y">
          <input type="text" name="" class="datebox" placeholder="Y">
        </span> 
        <label class="label-font">Place:</label>
        <span class="signunderline"></span> 
      </div>
     
     <hr/>

     </div>
  <div class="page-break"></div>

  <div class="pagelayout">
    <div class="contentmain">

     <strong class="font_declaration">Declaration by Claimant</strong>
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
      <span class="font_declaration">I agree that payment of claim amount shall constitute discharge of liability of ABSLI.</span>
      <div class="contentrows">
         <label class="label-font">Date:</label>
        <span class="smallpad">
          <input type="text" name="" class="datebox" placeholder="D">
          <input type="text" name="" class="datebox" placeholder="D">
          <input type="text" name="" class="datebox" placeholder="M">
          <input type="text" name="" class="datebox" placeholder="M">
          <input type="text" name="" class="datebox" placeholder="Y">
          <input type="text" name="" class="datebox" placeholder="Y">
          <input type="text" name="" class="datebox" placeholder="Y">
          <input type="text" name="" class="datebox" placeholder="Y">
        </span> 
          <label class="label-font">Signed at:</label>
          <span class="signunderline"></span> 
          <label class="label-font">Signature of Claimant:</label>
          <span class="signunderline"></span> 
      </div>

    </div>
    <div class="footer-bg">
     <lable class="footer-text">Aditya Birla Sun Life Insurance Company Limited | adityabirlasunlifeinsurance.com</lable>     
   </div>
 </div>

  <div class="page-break"></div>

  <div class="pagelayout">

 
    <div class="doc-header">
     <lable class="doc-text">Mandatory Documents required to be submitted along with claim intimation</lable>     
   </div>
   
   <div class="contentmain">
   <div class="label-font">a) Copy of Death Certificate issued by Municipal Authority / Gram Panchyat duly attested by the Group Policyholder.</div>
   <div class="label-font">b) Death Claim Form.</div>
   <div class="label-font">c) Bank statement/Printed Cancel Cheque Copy.</div>
   <div class="label-font">d) KYC of Beneficiary.</div>
   <div class="label-font">e) Credit Account Statement.</div>
   <div class="label-font">f) Loan Account Statement.</div>
   <div class="label-font">g) Madical Attendant&#39;s Certificate.</div>
  
  <br>
   <div><strong>In case of Unnatural death</strong> </div>
   <div class="label-font">a) Copies of FIR</div>
   <div class="label-font">b) Post Mortem Report</div>
   <div class="label-font">c) Police Inquest Report attested by the Group Policy Holder would be required to be submitted.</div>
   <div class="label-font">d) News Paper Cutting, if any</div>
   
   <br>
   <div> <strong>ABSLI reserves the right to call for any addition requirements/Information to process the Claim.</strong></div>

   </div>

  </div>
</body>
</html>