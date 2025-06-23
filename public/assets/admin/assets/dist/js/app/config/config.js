import xhrClient from "../../../../../../js/libs/xhrClient"

const Validemailfilter = (/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);;
const emailblockReg = /^([\w-\.]+@(?!yahoo.com)(?!hotmail.com)([\w-]+\.)+[\w-]{2,4})?$/;
const urlRegex = /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/;

var formdata = new FormData();

$(document).on('click', ".save-configuration-changes", async function (e) {
    e.preventDefault();
  
    const errors = {};
  
    // Validate company name
    const companyName = $.trim($('.company-name').summernote().val());
    if (companyName === '') {
      errors['company-name'] = 'Company name is required.';
    } else {
        const errorElement = $(`.company-name-error`);
        const parentElement = errorElement.parent();
        errorElement.text('');
        parentElement.removeClass('has-error');
    }
  
    // Validate company tagline
    const companyTagline = $.trim($("#company-tagline").val());
    if (companyTagline === '') {
      errors['company-tagline'] = 'Company tagline is required.';
    }
  
    // Validate company logo
    const companyLogo = $("#company-main-logo").val();
    let companyMainLogoFile =  $("#company-main-logo")[0].files;
    if (companyLogo === '') {
      errors['company-main-logo'] = 'Company logo is required.';
    } else {
      const extension = companyLogo.substr(companyLogo.lastIndexOf('.') + 1).toLowerCase();
      const allowedExtensions = ['jpg', 'jpeg', 'bmp', 'gif', 'png', 'svg','webp'];
      if (!allowedExtensions.includes(extension)) {
        errors['company-main-logo'] = 'Invalid logo format.';
      }
    }
  
    // Validate company icon logo
    const companyIconLogo = $("#company-icon-logo").val();
    let companyIconLogoFile =  $("#company-icon-logo")[0].files;
    if (companyIconLogo === '') {
      errors['company-icon-logo'] = 'Company icon logo is required.';
    } else {
      const extension = companyIconLogo.substr(companyIconLogo.lastIndexOf('.') + 1).toLowerCase();
      const allowedExtensions = ['jpg', 'jpeg', 'bmp', 'gif', 'png', 'svg', 'webp'];
      if (!allowedExtensions.includes(extension)) {
        errors['company-icon-logo'] = 'Invalid icon logo format.';
      }
    }
  
    // Validate company RC number
    const companyRcNumber = $.trim($("#company-rc-number").val());
    if (companyRcNumber === '') {
      errors['company-rc-number'] = 'Company RC number is required.';
    }
  
    // Validate company email
    const companyEmail = $.trim($("#company-email").val());
    if (companyEmail === '') {
      errors['company-email'] = 'Company email is required.';
    } else if (!Validemailfilter.test(companyEmail)) {
      errors['company-email'] = 'Invalid email format.';
    }
  
    // Validate company address
    const companyAddress = $.trim($(".company-address").summernote().val());
    if (companyAddress === '') {
      errors['company-address'] = 'Company address is required.';
    }else {
        const errorElement = $(`.company-address-error`);
        const parentElement = errorElement.parent();
        errorElement.text('');
        parentElement.removeClass('has-error');
    }
  
    // Validate company telephone
    const companyTelephone = $.trim($("#company-telephone").val());
    if (companyTelephone === '') {
      errors['company-telephone'] = 'Company telephone is required.';
    }
  
    // Validate company URL
    const companyUrl = $.trim($("#company-url").val());
    if (companyUrl === '') {
        errors['company-url'] = 'Company URL is required.';
    } else if (!urlRegex.test(companyUrl)) {
        errors['company-url'] = 'Invalid URL format.';
    }
  
    // Validate company country
    const companyCountry = $.trim($("#company-country").val());
    if (companyCountry === '') {
      errors['company-country'] = 'Company country is required.';
    }
  
    // Validate company city
    const companyCity = $.trim($("#company-city").val());
    if (companyCity === '') {
      errors['company-city'] = 'Company city is required.';
    }
  
    // Display errors
    Object.keys(errors).forEach((key) => {
      const errorElement = $(`.${key}-error`);
      errorElement.text(errors[key]);
      const parentElement = errorElement.parent();
      parentElement.addClass('has-error');
    });
  
    const allFields = [
        'company-name',
        'company-tagline',
        'company-main-logo',
        'company-icon-logo',
        'company-rc-number',
        'company-email',
        'company-address',
        'company-telephone',
        'company-url',
        'company-country',
        'company-city'
    ];
      
    allFields.forEach((field) => {
        const inputElement = $(`[name="${field}"]`);
        inputElement.on('input change', function () {
            const errorElement = $(`.${field}-error`);
            const parentElement = errorElement.parent();
            if (errorElement && parentElement.hasClass('form-group')) {
                const fieldValue = $.trim(inputElement.val());
                if (fieldValue !== '') {
                    errorElement.text('');
                    parentElement.removeClass('has-error');
                }
            }
        });
    });
      
    $('[name="company-main-logo"], [name="company-icon-logo"]').on('change', function () {
        const field = $(this).attr('name');
        const errorElement = $(`.${field}-error`);
        const parentElement = errorElement.parent();
        if (errorElement && parentElement.hasClass('form-group')) {
          errorElement.text('');
          parentElement.removeClass('has-error');
        }
    });
      
    if (Object.keys(errors).length === 0) {
        formdata.append('company-name',  companyName);
        formdata.append('company-tagline', companyTagline);
        formdata.append('company-main-logo', companyMainLogoFile[0]);
        formdata.append('company-icon-logo', companyIconLogoFile[0]);
        formdata.append('company-rc-number', companyRcNumber);
        formdata.append('company-email', companyEmail);
        formdata.append('company-address', companyAddress);
        formdata.append('company-telephone', companyTelephone);
        formdata.append('company-url', companyUrl);
        formdata.append('company-country', companyCountry);
        formdata.append('company-city', companyCity);
            
        try {   
            $.ajax({
                type: 'POST',
                data: formdata,
                url: base_url+'settings/create',
                cache: false,
                dataType: 'text',
                contentType: false,
                processData: false,
                success: function(response) {
                    var data_array = $.parseJSON(response);
                    if (data_array.status =="success") {
                        Swal('Success', data_array.message, 'success');
                        setTimeout(function () {
                            window.location.reload(1);
                        }, 500);
                    } else {
                        Swal({
                            title: "Failed",
                            text: data_array.message,
                            type: "error",
                            color: '#716add',
                            background: '#fff',
                            backdrop: `rgba(0,0,123,0.4)`,
                            timer: 2500,
                        });
                    }
                }
            });
        } catch (error) {
            Swal({
                title: "Failed",
                text: error,
                type: "error",
                color: '#716add',
                background: '#fff',
                backdrop: `rgba(0,0,123,0.4)`,
                timer: 2500,
            });
        }
      
    }
  });
  
  