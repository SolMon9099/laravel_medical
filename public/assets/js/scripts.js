$(window).on('load', function() {
  if (feather) {
      feather.replace({
          width: 14,
          height: 14
      });
  }
})

const delete_form = $('.delete_form');
delete_form.submit(function(e){
    e.preventDefault();
     Swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover this record!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            this.submit();
        }
    });
})

//User Management
var dtUserTable = $('.user-list-table');  
var newUserSidebar = $('.new-user-modal');
var newUserForm = $('.add-new-user');
var EditUserForm = $('.user_edit_form');

var dt_user = dtUserTable.DataTable({      
//   dom: '<"card-header border-bottom p-1"<"user-label"><"dt-action-buttons text-end"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
    dom:
    '<"d-flex justify-content-between align-items-center header-actions mx-2 row mt-75"' +
    '<"col-sm-12 col-lg-4 d-flex justify-content-center justify-content-lg-start" l>' +
    '<"col-sm-12 col-lg-8 ps-xl-75 ps-0"<"dt-action-buttons d-flex align-items-center justify-content-center justify-content-lg-end flex-lg-nowrap flex-wrap"<"me-1"f>B>>' +
    '>t' +
    '<"d-flex justify-content-between mx-2 row mb-1"' +
    '<"col-sm-12 col-md-6"i>' +
    '<"col-sm-12 col-md-6"p>' +
    '>',
    buttons: [
    {
        text: feather.icons['plus'].toSvg({ class: 'me-50 font-small-4' }) + 'Add New User',
        className: 'create-new btn btn-success',
        attr: {
        'data-bs-toggle': 'modal',
        'data-bs-target': '#user-modals'
        },
        init: function (api, node, config) {
        $(node).removeClass('btn-secondary');
        }
    }
    ]
});    

  // User Form Validation
  if (newUserForm.length) {
    newUserForm.validate({
      errorClass: 'error',
      rules: {
        'name': {
          required: true
        },
        'email': {
          required: true,
          email: true
        },
        'password': {
          required: true
        },
        'confirm_password': {
            required: true,
            equalTo: '#password'
        },
        'roles': {
            required: true
        }
      }
    });
  }

//User Edit Form
if(EditUserForm.length) {
    EditUserForm.validate({
        errorClass: 'error',
        rules: {
            'name': {
              required: true
            },
            'email': {
              required: true,
              email: true
            },            
            'confirm_password': {              
                equalTo: '#password'
            },
            'roles': {
                required: true
            }
          }
    });
}


//Role Management
var dtRoleTable = $('.role-list-table');  
var newRoleSidebar = $('.new-role-modal');
var newRoleForm = $('.add-new-role');
var EditRoleForm = $('.role_edit_form');

var dt_role = dtRoleTable.DataTable({  
    order: [[2, 'desc']],
//   dom: '<"card-header border-bottom p-1"<"user-label"><"dt-action-buttons text-end"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
    dom:
    '<"d-flex justify-content-between align-items-center header-actions mx-2 row mt-75"' +
    '<"col-sm-12 col-lg-4 d-flex justify-content-center justify-content-lg-start" l>' +
    '<"col-sm-12 col-lg-8 ps-xl-75 ps-0"<"dt-action-buttons d-flex align-items-center justify-content-center justify-content-lg-end flex-lg-nowrap flex-wrap"<"me-1"f>B>>' +
    '>t' +
    '<"d-flex justify-content-between mx-2 row mb-1"' +
    '<"col-sm-12 col-md-6"i>' +
    '<"col-sm-12 col-md-6"p>' +
    '>',
    buttons: [
    {
        text: feather.icons['plus'].toSvg({ class: 'me-50 font-small-4' }) + 'Add New Role',
        className: 'create-new-role btn btn-success',
        attr: {        
        'href': createRoleUrl
        },
        init: function (api, node, config) {
          $(node).removeClass('btn-secondary');
        }
    }
    ]
});    

  // User Form Validation
  if (newUserForm.length) {
    newUserForm.validate({
      errorClass: 'error',
      rules: {
        'name': {
          required: true
        },
        'email': {
          required: true,
          email: true
        },
        'password': {
          required: true
        },
        'confirm_password': {
            required: true,
            equalTo: '#password'
        },
        'roles': {
            required: true
        }
      }
    });
  }

  //User Edit Form
if(EditUserForm.length) {
    EditUserForm.validate({
        errorClass: 'error',
        rules: {
            'name': {
              required: true
            },
            'email': {
              required: true,
              email: true
            },            
            'confirm_password': {              
                equalTo: '#password'
            },
            'roles': {
                required: true
            }
          }
    });
}

//Edit Password
var editPasswordForm = $('.editPasswordForm');
// User Form Validation
if (editPasswordForm.length) {
  editPasswordForm.validate({
    errorClass: 'error',
    rules: {
      'current_password': {
        required: true
      },      
      'new_password': {
        required: true,
      },
      'confirm_password': {
          required: true,
          equalTo: '#new_password'
      }
    }
  });
}

//Clinic Management
var dtClinicTable = $('.clinic-list-table');  
var newClinicForm = $('.add-new-clinic');
var EditClinicForm = $('.clinic_edit_form');

var dt_clinic = dtClinicTable.DataTable({  
    order: [[2, 'desc']],
//   dom: '<"card-header border-bottom p-1"<"user-label"><"dt-action-buttons text-end"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
    dom:
    '<"d-flex justify-content-between align-items-center header-actions mx-2 row mt-75"' +
    '<"col-sm-12 col-lg-4 d-flex justify-content-center justify-content-lg-start" l>' +
    '<"col-sm-12 col-lg-8 ps-xl-75 ps-0"<"dt-action-buttons d-flex align-items-center justify-content-center justify-content-lg-end flex-lg-nowrap flex-wrap"<"me-1"f>B>>' +
    '>t' +
    '<"d-flex justify-content-between mx-2 row mb-1"' +
    '<"col-sm-12 col-md-6"i>' +
    '<"col-sm-12 col-md-6"p>' +
    '>',
    buttons: [
    {
        text: feather.icons['plus'].toSvg({ class: 'me-50 font-small-4' }) + 'Add New Clinic',
        className: 'create-new-clinic btn btn-success',
        attr: {        
          'data-bs-toggle': 'modal',
          'data-bs-target': '#clinic-modals'
        },
        init: function (api, node, config) {
          $(node).removeClass('btn-secondary');
        }
    }
    ]
});    

  // User Form Validation
  if (newClinicForm.length) {
    newClinicForm.validate({
      errorClass: 'error',
      rules: {
        'name': {
          required: true
        },
        'clinic_adderss': {
          required: true
        },
        'clinic_city': {
          required: true
        },
        'clinic_state': {
          required: true
        },
        'clinic_postal': {
          required: true
        }
      }
    });
  }
//Patient Referral list
var dtPatientReferralTable = $('.patients-referral-table');  
var dt_patientReferral = dtPatientReferralTable.DataTable({  
  order: [[2, 'desc']],
  dom:
  '<"d-flex justify-content-between align-items-center header-actions mx-2 row mt-75"' +
  '<"col-sm-12 col-lg-4 d-flex justify-content-center justify-content-lg-start" l>' +
  '<"col-sm-12 col-lg-8 ps-xl-75 ps-0"<"dt-action-buttons d-flex align-items-center justify-content-center justify-content-lg-end flex-lg-nowrap flex-wrap"<"me-1"f>B>>' +
  '>t' +
  '<"d-flex justify-content-between mx-2 row mb-1"' +
  '<"col-sm-12 col-md-6"i>' +
  '<"col-sm-12 col-md-6"p>' +
  '>',
  buttons: [
  {
      text: feather.icons['plus'].toSvg({ class: 'me-50 font-small-4' }) + 'Add New Patient',
      className: 'create-new-patient btn btn-success',
      attr: {        
        'href': createPatientReferralUrl
      },
      init: function (api, node, config) {
        $(node).removeClass('btn-secondary');
      }
  }
  ]
});    

//referral Form 
var referralForm = $('#referralForm'); 
referralForm.validate({
  errorClass: 'error',
  rules: {
    'patient_name': {
      required: true
    },
    'patient_email': {
      required: true,
      email: true
    },
    'patient_phone': {
      required: true
    },
    'patient_date_birth': {
      required: true
    },
    'patient_street_adderss': {
      required: true
    },
    'patient_city': {
      required: true
    },
    'patient_state': {
      required: true
    },   
    'patient_postal': {
      required: true
    },
    'patient_date_injury': {
      required: true
    },
    'reason_referral': {
      required: true
    },
    'patient_insurance_company': {
      required: true
    },
    'patient_insurance_policy': {
      required: true
    },
    'patient_insurance_street_adderss': {
      required: true
    },
    'patient_insurance_city': {
      required: true
    },
    'patient_insurance_state': {
      required: true
    },
    'patient_insurance_postal': {
      required: true
    },
    'defendant_insurance_company': {
      required: true
    },
    'defendant_insurance_claim': {
      required: true
    },
    'defendant_policy_limit': {
      required: true
    },
    'defendant_insurance_street_adderss': {
      required: true
    },
    'defendant_insurance_city': {
      required: true
    },
    'defendant_insurance_state': {
      required: true
    },
    'defendant_insurance_postal': {
      required: true
    },
    'attorney_name': {
      required: true
    },
    'attorney_email': {
      required: true,
      email:true
    },
    'attorney_phone': {
      required: true
    },
    'law_firm_adderss': {
      required: true
    },
    'law_firm_city': {
      required: true
    },
    'law_firm_state': {
      required: true
    },
    'law_firm_postal': {
      required: true
    },
    'doctor_email': {
      required: true,
      email:true
    },
    'doctor_phone': {
      required: true
    }
  }
});

$('.create-new-role').click(function (e) { 
  var link = $(this).attr('href');
  window.location.href = link;
});

$('.create-new-patient').click(function (e) { 
  var link = $(this).attr('href');
  window.location.href = link;
});