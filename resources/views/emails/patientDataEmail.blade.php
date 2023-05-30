<html lang="en" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office"><head>
  <meta charset="utf-8">
  <meta name="x-apple-disable-message-reformatting">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="format-detection" content="telephone=no, date=no, address=no, email=no">
  <!--[if mso]>
  <xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml>
  <style>
    td,th,div,p,a,h1,h2,h3,h4,h5,h6 {font-family: "Segoe UI", sans-serif; mso-line-height-rule: exactly;}
  </style>
<![endif]-->
  <title>NEURAL SCAN INFO</title>
  <link href="https://fonts.googleapis.com/css?family=Montserrat:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700" rel="stylesheet" media="screen">
  <style>
    .hover-underline:hover {
      text-decoration: underline !important;
    }

    @keyframes spin {
      to {
        transform: rotate(360deg);
      }
    }

    @keyframes ping {

      75%,
      100% {
        transform: scale(2);
        opacity: 0;
      }
    }

    @keyframes pulse {
      50% {
        opacity: .5;
      }
    }

    @keyframes bounce {

      0%,
      100% {
        transform: translateY(-25%);
        animation-timing-function: cubic-bezier(0.8, 0, 1, 1);
      }

      50% {
        transform: none;
        animation-timing-function: cubic-bezier(0, 0, 0.2, 1);
      }
    }

    @media (max-width: 600px) {
      .sm-leading-32 {
        line-height: 32px !important;
      }

      .sm-px-24 {
        padding-left: 24px !important;
        padding-right: 24px !important;
      }

      .sm-py-32 {
        padding-top: 32px !important;
        padding-bottom: 32px !important;
      }

      .sm-w-full {
        width: 100% !important;
      }
    }
  </style>
</head>

<body style="margin: 0; padding: 0; width: 100%; word-break: break-word; -webkit-font-smoothing: antialiased; --bg-opacity: 1; background-color: #eceff1; background-color: rgba(236, 239, 241, var(--bg-opacity));" data-new-gr-c-s-check-loaded="14.1110.0" data-gr-ext-installed="">
  <div style="display: none;"></div>
  <div role="article" aria-roledescription="email" aria-label="Verify Email Address" lang="en">
    <table style="font-family: Montserrat, -apple-system, 'Segoe UI', sans-serif; width: 100%;" width="100%" cellpadding="0" cellspacing="0" role="presentation">
      <tbody><tr>
        <td align="center" style="--bg-opacity: 1; background-color: #eceff1; background-color: rgba(236, 239, 241, var(--bg-opacity)); font-family: Montserrat, -apple-system, 'Segoe UI', sans-serif;" bgcolor="rgba(236, 239, 241, var(--bg-opacity))">
          <table class="sm-w-full" style="font-family: 'Montserrat',Arial,sans-serif; width: 600px;" width="600" cellpadding="0" cellspacing="0" role="presentation">
            <tbody><tr>
              <td class="sm-py-32 sm-px-24" style="font-family: Montserrat, -apple-system, 'Segoe UI', sans-serif; padding: 48px 20px 20px; text-align: center;" align="center">
                <a href="{{ env('APP_URL') }}">
                  <img src="{{ asset('assets/image/logo.svg') }}" width="200" alt="Logo" style="border: 0; max-width: 100%; line-height: 100%; vertical-align: middle;">
                </a>
              </td>
            </tr>
            <tr>
              <td align="center" class="sm-px-24" style="font-family: 'Montserrat',Arial,sans-serif;">
                <table style="font-family: 'Montserrat',Arial,sans-serif; width: 100%;" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                  <tbody>
                    <tr>
                      <td class="sm-px-24" style="--bg-opacity: 1; background-color: #ffffff; background-color: rgba(255, 255, 255, var(--bg-opacity)); border-radius: 4px; font-family: Montserrat, -apple-system, 'Segoe UI', sans-serif; font-size: 14px; line-height: 24px; padding: 48px; text-align: left; --text-opacity: 1;" bgcolor="rgba(255, 255, 255, var(--bg-opacity))" align="left">
                        <h2 style="text-align: center;">NEURAL SCAN INFO</h2>
                        <table style="width:100%">
                          <tr>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:40%">
                              <p style="font-weight:600;">Referral ID#</p>
                            </td>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:60%">
                              {{$patientMailData['referral_id']}}
                            </td>
                          </tr>
                          <tr>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:40%">
                              <p style="font-weight:600;">Date</p>
                            </td>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:60%">
                              {{$patientMailData['referral_date']}}
                            </td>
                          </tr>
                        </table>

                        <h2 style="text-align: center;">PATIENT INFO</h2>
                        <table style="width:100%">
                          <tr>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:40%">
                              <p style="font-weight:600;">Patient Name</p>
                            </td>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:60%">
                              {{$patientMailData['patient_name']}}
                            </td>
                          </tr>
                          <tr>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:40%">
                              <p style="font-weight:600;">Patient Phone</p>
                            </td>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:60%">
                              {{$patientMailData['patient_phone']}}
                            </td>
                          </tr>
                          <tr>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:40%">
                              <p style="font-weight:600;">Patient Email</p>
                            </td>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:60%">
                              {{$patientMailData['patient_email']}}
                            </td>
                          </tr>
                          <tr>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:40%">
                              <p style="font-weight:600;">Date of Birth</p>
                            </td>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:60%">
                              {{$patientMailData['patient_date_birth']}}
                            </td>
                          </tr>
                          <tr>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:40%">
                              <p style="font-weight:600;">Patient Address</p>
                            </td>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:60%">
                              {{$patientMailData['patient_street_adderss']}}
                            </td>
                          </tr>
                          <tr>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:40%">
                              <p style="font-weight:600;">Date of Injury</p>
                            </td>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:60%">
                              {{$patientMailData['patient_date_injury']}}
                            </td>
                          </tr>
                          <tr>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:40%">
                              <p style="font-weight:600;">Gender</p>
                            </td>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:60%">
                              {{$patientMailData['genders']}}
                            </td>
                          </tr>
                          <tr>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:40%">
                              <p style="font-weight:600;">Reason for Referral</p>
                            </td>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:60%">
                              {{$patientMailData['reason_referral']}}
                            </td>
                          </tr>
                        </table>

                        <h2 style="text-align: center;">PATIENT INSURANCE INFO</h2>
                        <table style="width:100%">
                          <tr>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:40%">
                              <p style="font-weight:600;">Patient's Insurance Company</p>
                            </td>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:60%">
                              {{$patientMailData['patient_insurance_company']}}
                            </td>
                          </tr>
                          <tr>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:40%">
                              <p style="font-weight:600;">Patient's Policy #</p>
                            </td>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:60%">
                              {{$patientMailData['patient_insurance_policy']}}
                            </td>
                          </tr>
                          <tr>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:40%">
                              <p style="font-weight:600;">Patient's Insurance Company Address</p>
                            </td>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:60%">
                              {{$patientMailData['patient_insurance_street_adderss']}}
                            </td>
                          </tr>
                        </table>

                        <h2 style="text-align: center;">DEFENDANT INSURANCE INFO</h2>
                        <table style="width:100%">
                          <tr>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:40%">
                              <p style="font-weight:600;">Defendant's Insurance Company</p>
                            </td>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:60%">
                              {{$patientMailData['defendant_insurance_company']}}
                            </td>
                          </tr>
                          <tr>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:40%">
                              <p style="font-weight:600;">Claim #</p>
                            </td>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:60%">
                              {{$patientMailData['defendant_insurance_claim']}}
                            </td>
                          </tr>
                          <tr>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:40%">
                              <p style="font-weight:600;">Defendant Policy Limit</p>
                            </td>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:60%">
                              {{$patientMailData['defendant_policy_limit']}}
                            </td>
                          </tr>
                          <tr>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:40%">
                              <p style="font-weight:600;">Defendant Insurance Company Address</p>
                            </td>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:60%">
                              {{$patientMailData['defendant_insurance_street_adderss']}}
                            </td>
                          </tr>
                          <tr>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:40%">
                              <p style="font-weight:600;">Is This A Hit & Run?</p>
                            </td>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:60%">
                              {{$patientMailData['defendant_insurance_hit']}}
                            </td>
                          </tr>
                          <tr>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:40%">
                              <p style="font-weight:600;">Is Defendant Insured?</p>
                            </td>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:60%">
                              {{$patientMailData['defendant_insure']}}
                            </td>
                          </tr>
                        </table>

                        <h2 style="text-align: center;">LAW FIRM INFO</h2>
                        <table style="width:100%">
                          <tr>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:40%">
                              <p style="font-weight:600;">Attorney Name</p>
                            </td>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:60%">
                              {{$patientMailData['attorney_name']}}
                            </td>
                          </tr>
                          <tr>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:40%">
                              <p style="font-weight:600;">Attorney Email</p>
                            </td>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:60%">
                              {{$patientMailData['attorney_phone']}}
                            </td>
                          </tr>
                          <tr>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:40%">
                              <p style="font-weight:600;">Attorney Phone</p>
                            </td>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:60%">
                              {{$patientMailData['referral_date']}}
                            </td>
                          </tr>
                          <tr>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:40%">
                              <p style="font-weight:600;">Lawfirm Address</p>
                            </td>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:60%">
                              {{$patientMailData['law_firm_adderss']}}
                            </td>
                          </tr>
                        </table>


                        <h2 style="text-align: center;">PHYSICIAN INFO</h2>
                        <table style="width:100%">
                          <tr>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:40%">
                              <p style="font-weight:600;">Clinic Name</p>
                            </td>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:60%">
                              {{$patientMailData['clinic_name']}}
                            </td>
                          </tr>
                          <tr>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:40%">
                              <p style="font-weight:600;">Doctor Name</p>
                            </td>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:60%">
                              {{$patientMailData['doctor_name']}}
                            </td>
                          </tr>
                          <tr>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:40%">
                              <p style="font-weight:600;">Doctor Email</p>
                            </td>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:60%">
                              {{$patientMailData['doctor_email']}}
                            </td>
                          </tr>
                          <tr>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:40%">
                              <p style="font-weight:600;">Doctor Phone</p>
                            </td>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:60%">
                              {{$patientMailData['doctor_phone']}}
                            </td>
                          </tr>
                          <tr>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:40%">
                              <p style="font-weight:600;">Clinic Address</p>
                            </td>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; font-size: 14px; padding-top: 10px; padding-bottom: 10px; width:60%">
                              {{$patientMailData['clinic_address']}}
                            </td>
                          </tr>
                          
                        </table>
                      </td>                      
                    </tr>
                    
                  <tr>
                    <td style="font-family: 'Montserrat',Arial,sans-serif; height: 20px;" height="20"></td>
                  </tr>
                  <tr>
                    <td style="font-family: 'Montserrat',Arial,sans-serif; height: 16px;" height="16"></td>
                  </tr>
                </tbody>
              </table>
              </td>
            </tr>
          </tbody>
        </table>
        </td>
      </tr>
    </tbody>
  </table>
  </div>


</body>
</html>