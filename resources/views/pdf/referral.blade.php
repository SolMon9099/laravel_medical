<div class="container">
    <div class="header">
        <table>
            <tr>
                <td class="title">Date</td>
                <td class="value">{{(isset($data['referral_date'])) ? date("l, F j, Y", strtotime($data['referral_date'])) : ''}}</td>
                <td class="title">Referral ID #</td>
                <td class="value">{{isset($data['id']) ? $data['id'] :''}}</td>
            </tr>
        </table>
    </div>
    <div class="main-area">
        <div class="blog">
            <h1>Patient Info</h1>
            <table>
                <tr>
                    <td class="title">Patient Name</td>
                    <td class="value">{{isset($data['patient_name']) ? $data['patient_name'] :''}}</td>
                    <td class="title">Date of Birth</td>
                    <td class="value">{{(isset($data['patient_date_birth'])) ? date("l, F j, Y", strtotime($data['patient_date_birth'])) : ''}}</td>
                </tr>
                <tr>
                    <td class="title">Patient Phone #</td>
                    <td class="value">{{isset($data['patient_phone']) ? $data['patient_phone'] :''}}</td>
                    <td class="title">Gender</td>
                    <td class="value">{{isset($data['genders']) ? $data['genders'] :''}}</td>
                </tr>
                <tr>
                    <td class="title">Address</td>
                    <?php
                        $address = '';
                        if (isset($data['patient_street_adderss'])){
                            $address .= $data['patient_street_adderss'].', ';
                        }
                        if (isset($data['patient_street_adderss_line2'])){
                            $address .= $data['patient_street_adderss_line2'].', ';
                        }
                        if (isset($data['patient_city'])){
                            $address .= $data['patient_city'].', ';
                        }
                        if (isset($data['patient_state'])){
                            $address .= $data['patient_state'].', ';
                        }
                        if (isset($data['patient_postal'])){
                            $address .= $data['patient_postal'].'';
                        }
                    ?>
                    <td class="value">{{$address}}</td>
                    <td class="title">Date of Injury</td>
                    <td class="value">{{(isset($data['patient_date_injury'])) ? date("l, F j, Y", strtotime($data['patient_date_injury'])) : ''}}</td>
                </tr>
                <tr>
                    <td class="title">Reason for Referral</td>
                    <td colspan="3" class="value">{{isset($data['reason_referral']) ? $data['reason_referral'] :''}}</td>
                </tr>
            </table>
        </div>
        <div class="blog">
            <h1>Patient Insurance</h1>
            <table>
                <tr>
                    <td class="title">Patient's Insurance Company</td>
                    <td class="value">{{isset($data['patient_insurance_company']) ? $data['patient_insurance_company'] :''}}</td>
                    <td class="title">Patient's Insurance Company Address</td>
                    <?php
                        $address = '';
                        if (isset($data['patient_insurance_street_adderss'])){
                            $address .= $data['patient_insurance_street_adderss'].', ';
                        }
                        if (isset($data['patient_insurance_street_adderss_line2'])){
                            $address .= $data['patient_insurance_street_adderss_line2'].', ';
                        }
                        if (isset($data['patient_insurance_city'])){
                            $address .= $data['patient_insurance_city'].', ';
                        }
                        if (isset($data['patient_insurance_state'])){
                            $address .= $data['patient_insurance_state'].', ';
                        }
                        if (isset($data['patient_insurance_postal'])){
                            $address .= $data['patient_insurance_postal'].'';
                        }
                    ?>
                    <td class="value">{{$address}}</td>
                </tr>
                <tr>
                    <td class="title">Patient's Policy # </td>
                    <td class="value">{{isset($data['patient_insurance_policy']) ? $data['patient_insurance_policy'] :''}}</td>
                    <td class="title"></td>
                    <td class="value"></td>
                </tr>
            </table>
        </div>
        <div class="blog">
            <h1>Defendant Insurance Information</h1>
            <table>
                <tr>
                    <td class="title">Is This A Hit & Run?</td>
                    <td class="value">{{isset($data['defendant_insurance_hit']) ? $data['defendant_insurance_hit'] : ''}}</td>
                    <td class="title">Is Defendant Insured?</td>
                    <td class="value">{{isset($data['defendant_insure']) ? $data['defendant_insure'] : ''}}</td>
                </tr>
                <tr>
                    <td class="title">Defendant's Insurance Company</td>
                    <td class="value">{{isset($data['defendant_insurance_company']) ? $data['defendant_insurance_company'] :''}}</td>
                    <td class="title">Defendant's Insurance Company Address</td>
                    <?php
                        $address = '';
                        if (isset($data['defendant_insurance_street_adderss'])){
                            $address .= $data['defendant_insurance_street_adderss'].', ';
                        }
                        if (isset($data['defendant_insurance_street_adderss_line2'])){
                            $address .= $data['defendant_insurance_street_adderss_line2'].', ';
                        }
                        if (isset($data['defendant_insurance_city'])){
                            $address .= $data['defendant_insurance_city'].', ';
                        }
                        if (isset($data['defendant_insurance_state'])){
                            $address .= $data['defendant_insurance_state'].', ';
                        }
                        if (isset($data['defendant_insurance_postal'])){
                            $address .= $data['defendant_insurance_postal'].'';
                        }
                    ?>
                    <td class="value">{{$address}}</td>
                </tr>
                <tr>
                    <td class="title">Claim #</td>
                    <td class="value">{{isset($data['defendant_insurance_claim']) ? $data['defendant_insurance_claim'] :''}}</td>
                    <td class="title"></td>
                    <td class="value"></td>
                </tr>
            </table>
        </div>
        <div class="blog">
            <h1>Attorney Information</h1>
            <table>
                <tr>
                    <td class="title">Firm Name</td>
                    <td class="value"></td>
                    <td class="title">Attorney Name</td>
                    <td class="value">{{isset($data['attorney_name']) ? $data['attorney_name'] :''}}</td>
                </tr>
                <tr>
                    <td class="title">Attorney Email</td>
                    <td class="value">{{isset($data['attorney_email']) ? $data['attorney_email'] :''}}</td>
                    <td class="title">Firm Phone #</td>
                    <td class="value">{{isset($data['attorney_phone']) ? $data['attorney_phone'] :''}}</td>
                </tr>
                <tr>
                    <td class="title">Law Firm Address</td>
                    <?php
                        $address = '';
                        if (isset($data['law_firm_adderss'])){
                            $address .= $data['law_firm_adderss'].', ';
                        }
                        if (isset($data['law_firm_adderss_line2'])){
                            $address .= $data['law_firm_adderss_line2'].', ';
                        }
                        if (isset($data['law_firm_city'])){
                            $address .= $data['law_firm_city'].', ';
                        }
                        if (isset($data['law_firm_state'])){
                            $address .= $data['law_firm_state'].', ';
                        }
                        if (isset($data['law_firm_postal'])){
                            $address .= $data['law_firm_postal'].'';
                        }
                    ?>
                    <td colspan="3" class="value">{{$address}}</td>
                </tr>
            </table>
        </div>
        <div class="blog">
            <h1>Physician Information</h1>
            <table>
                <tr>
                    <td class="title">Referring Physician Name</td>
                    <td class="value">{{isset($data['doctor_name']) ? $data['doctor_name'] :''}}</td>
                    <td class="title">Referring Physician Phone #</td>
                    <td class="value">{{isset($data['doctor_phone']) ? $data['doctor_phone'] :''}}</td>
                </tr>
                <tr>
                    <td class="title">Clinic Name</td>
                    <td class="value">{{isset($data['clinic_data']) ? $data['clinic_data']['name'] :''}}</td>
                    <td class="title">Physician Address</td>
                    <?php
                        $address = '';
                        if (isset($data['clinic_data'])){
                            if (isset($data['clinic_data']['clinic_adderss'])){
                                $address .= $data['clinic_data']['clinic_adderss'].', ';
                            }
                            if (isset($data['clinic_data']['clinic_adderss_line2'])){
                                $address .= $data['clinic_data']['clinic_adderss_line2'].', ';
                            }
                            if (isset($data['clinic_data']['clinic_city'])){
                                $address .= $data['clinic_data']['clinic_city'].', ';
                            }
                            if (isset($data['clinic_data']['clinic_state'])){
                                $address .= $data['clinic_data']['clinic_state'].', ';
                            }
                            if (isset($data['clinic_data']['clinic_postal'])){
                                $address .= $data['clinic_data']['clinic_postal'];
                            }
                        }
                    ?>
                    <td class="value">{{$address}}</td>
                </tr>
            </table>
        </div>
    </div>
</div>

<style>
    .container{
        font-size:12px;
        margin-top:0px;
        margin-left:40px;
        margin-right:20px;
        margin-bottom: 20px;
    }
    .header{
        margin-bottom: 20px;
    }
    h1{
        color:cornflowerblue;
        font-weight: bold;
    }
    .title{
        font-weight: bold;
    }
    .right-item{
        border-bottom: 1px black dashed;
    }
    .value{
    }
    .head-part{
        color:white;
        text-align: center;
        background: seagreen;
        border-top-left-radius: 25px;
        border-top-right-radius: 25px;
        padding-top:5px;
        padding-bottom:5px;
    }
    .foot-part{
        color:white;
        background: seagreen;
        border-bottom-left-radius: 25px;
        border-bottom-right-radius: 25px;
    }
    .foot-part td{
        color:white;
    }
    .first-row{
        font-weight: bold;
        color:blue;
    }
    .invoice-title{
        text-align: right;
        font-size: 20px;
        font-weight: bold;
        margin-right: 0px;
        margin-bottom:20px;
    }
    .breakdown{
        font-size: 20px;
        font-weight: bold;
        background: rgb(10, 58, 146);
        color: white;
        padding: 5px;
        margin-bottom: 35px;
    }
    table{
        width:100%;
        border-collapse: collapse;
    }
    .first-table thead{
        background: rgb(78, 204, 216);
    }
    th, td{
        padding:5px;
    }
    td{
        text-align: left;
        width:25%;
    }
    .note-area{
        padding-left: 10px;
        font-size: 15px;
        min-height: 50px;
        margin-top: 15px;
        border-bottom: 10px solid seagreen;
    }
</style>
