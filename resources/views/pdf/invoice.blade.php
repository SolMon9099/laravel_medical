<div class="container">
    <div class="header">
        <table>
            <tr>
                <td style="text-align:left;width:75%;">
                    <div class="logo">
                        <?php
                            $image_data = 'data:image/png;base64,'.base64_encode(file_get_contents(public_path('assets/image/logo.svg')));
                        ?>
                        <img src = {{$image_data}}/>
                    </div>
                </td>
                <td>
                    <div class="right-stick">
                        <div class="head-part">
                            <label>&nbsp;&nbsp;</label>
                            <label>&nbsp;&nbsp;</label>
                        </div>
                        <div class="right-item">
                            <table>
                                <tr>
                                    <td class="title">Statement Date:</td>
                                    <td class="value">{{date('m-d-Y')}}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="right-item">
                            <table>
                                <tr>
                                    <td class="title">Due Date:</td>
                                    <td class="value">
                                        @isset($data['referral_date'])
                                        {{date('m-d-Y', strtotime($data['referral_date']))}}
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="right-item">
                            <table>
                                <tr>
                                    <td class="title">Total Current Charges:</td>
                                    <td class="value">$8000</td>
                                </tr>
                            </table>
                        </div>
                        <div class="right-item">
                            <table>
                                <tr>
                                    <td class="title">Total Past Due:</td>
                                    <td class="value">$0</td>
                                </tr>
                            </table>
                        </div>
                        <div class="foot-part">
                            <table>
                                <tr>
                                    <td style="text-align: left;padding-left:20px;">Total Due:</td>
                                    <td class="value">$8000</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="main-area">
        <div class="bill-user">
            <table style="width:100%;">
                <tr>
                    <td style="text-align: left;width:50%;vertical-align:top;">
                        <div class="first-row">Bill To:</div>
                        <div>
                            @if(isset($data['patient_name']))
                                {{$data['patient_name']}}
                            @endif
                        </div>
                        <div>
                            @if(isset($data['patient_name']))
                                {{$data['patient_street_adderss']}}
                            @endif
                        </div>
                        <div>
                            @if(isset($data['patient_name']))
                            {{$data['patient_city'].', '.$data['patient_state'].' '.$data['patient_postal']}}
                            @endif
                        </div>
                    </td>
                    <td style="text-align: left;width:50%;vertical-align:top;">
                        <div class="first-row">Bill From:</div>
                        <div>Limitless Regenerative, LLC</div>
                        <div>5900 Balcones Drive STE 100</div>
                        <div>Austin, TX 78731</div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="invoice-area">
            <div class="invoice-title" style="text-align: right;">Invoice</div>
            <div class='breakdown'>Breakdown of Charges</div>
            <div class="first-table">
                <table>
                    <thead>
                        <th>Client</th>
                        <th>Accession Number</th>
                        <th>Visit Number</th>
                        <th>Referring Physician</th>
                        <th>Patient Name</th>
                        <th>Patient DOB</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="text-align:left;">Limitless Regenerative, LLC</td>
                            <td>&nbsp;</td>
                            <td>1</td>
                            <td>
                                @if(isset($data['doctor_name']))
                                    {{$data['doctor_name']}}
                                @endif
                            </td>
                            <td>
                                @if(isset($data['patient_name']))
                                    {{$data['patient_name']}}
                                @endif
                            </td>
                            <td>
                                @if(isset($data['patient_date_birth']))
                                    {{date('m-d-Y', strtotime($data['patient_date_birth']))}}
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="note-area">Notes:</div>
            <div class="second-table">
                <table>
                    <thead>
                        <th>Date of Service</th>
                        <th>Description</th>
                        <th>ICD10 Code</th>
                        <th>units</th>
                        <th>Charge/Unit</th>
                        <th>Charge Amount</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{date('m-d-Y')}}</td>
                            <td>Neural Scan </td>
                            <td>H18.049</td>
                            <td>1</td>
                            <td style="text-align:right;padding-right:25px;">$7, 500.00</td>
                            <td style="text-align:right;padding-right:25px;">$7, 500.00</td>
                        </tr>
                        <tr>
                            <td>{{date('m-d-Y')}}</td>
                            <td>Neural Scan Read</td>
                            <td>H18.049</td>
                            <td>1</td>
                            <td style="text-align:right;padding-right:25px;">$500.00</td>
                            <td style="text-align:right;padding-right:25px;">$500.00</td>
                        </tr>
                        <tr>
                            <td class="final-td" colspan="6">Total for Patient: $8,000</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="footer">
        <div class="footer-item footer-first">
            <label>Total for CN-0000013:</label>
            <label style="width:250px">$8,000</label>
        </div>
        <div class="footer-item footer-second">
            <label>Total for Statement CN-0000013-202211:</label>
            <label style="width:250px">$8,000</label>
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
        position: relative;
        height: 90%;
    }
    .header{
        margin-bottom: 25px;
    }
    img{
        width:150px;
    }
    .right-stick{
        width:250px;
        border-radius: 25px;
        border: 1px solid;
    }
    .title{
        width:140px;
        padding-left:15px;
        padding-top: 1px;
        padding-bottom: 1px;
        background: rgb(78, 204, 216);
        text-align: left;
    }
    .right-item{
        border-bottom: 1px black dashed;
    }
    .value{
        padding-right:20px;
        padding-top: 1px;
        padding-bottom: 1px;
        text-align: right;
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
        text-align: center;
    }
    .note-area{
        padding-left: 10px;
        font-size: 15px;
        min-height: 50px;
        margin-top: 15px;
        border-bottom: 10px solid seagreen;
    }
    .second-table th{
        border-bottom:10px solid rgb(78, 204, 216);
    }
    .final-td{
        text-align: right;
        background: seagreen;
        padding-right:25px;
        font-size: 13px;
    }
    .footer-item{
        text-align: right;
        padding:10px;
    }
    .footer{
        position: absolute;
        bottom: 0px;
        width:100%;
    }
    .footer-first{
        background: rgb(78, 204, 216);;
    }
    .footer-second{
        color:white;
        background: seagreen;
    }
</style>
