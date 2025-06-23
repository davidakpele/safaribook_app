import xhrClient from "./libs/xhrClient" 

var count;
const table = $("#users-list-table").DataTable({
    dom:
    "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
    "<'row'<'col-sm-12'tr>>" +
    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
    buttons: [
        'csv',
        'excel',
        'pdf',
        'print'
    ],
    oLanguage: {
        sProcessing: "loading..."
    },
    processing: true,

});
$('#iz').hide();


$(document).ready(() => {
    $(document).on('click', ".update_user_details", async function (e) {
        e.preventDefault();
        console.log($(".statusMsg"));
        
        let id = $('#id').val();
        let name = document.querySelector('.username').value;
        let email = document.querySelector('.user_email').value;
        let telephone = document.querySelector('.user_telephone').value;
        let role = document.querySelector('.user_role').value;
        if (id == "") {
            $(".statusMsg").show().html("&nbsp;&nbsp; Please Provide The User ID Number ").show();
            
            $("#id").focus()
            return false;
        }
        if (name == "") {
            $(".statusMsg").fadeIn().html("&nbsp;&nbsp; Enter Username ");
            $("#username").focus()
            return false;
        }
        if (email == "") {
            $(".statusMsg").fadeIn().html("&nbsp;&nbsp; Enter email address ");
            $("#user_email").focus()
            return false;
        }
        if (telephone == "") {
            $(".statusMsg").fadeIn().html("&nbsp;&nbsp; Enter your mobile number ");
            $("#user_telephone").focus()
            return false;
        }
        if (role == "") {
            $(".statusMsg").fadeIn().html("&nbsp;&nbsp; Please select your role ");
            $("#user_role").focus()
            return false;
        }
        const data = { "id": id, "username": name, "telephone": telephone, "role": role, "email": email };

        let btn = $('#btn-info');
        btn.text('Process...');
        
        try {
            const request = await xhrClient(base_url+`user/edit_user/${id}`, "PUT", {
                'Content-Type': 'application/json',
            }, data);
            btn.text('Updated');
            Swal.fire('Success', request.message, 'success');
            setTimeout(function () {
                window.location.reload(1);
            }, 500);
        } catch (error) {
            Swal.fire({
                title: "Failed",
                text: error,
                type: "error",
                color: '#716add',
                background: '#fff',
                backdrop: `rgba(0,0,123,0.4)`,
                timer: 2500,
            });
        }
    });

    //userlevel
    $(document).on('click', ".update_password_btn", async function (e) {
        e.preventDefault();
        let id = $('#id').val();
        let old = $('#old').val();
        let newpass = $('#new').val();
        let confirm = $('#new_confirm').val();
         const data = {
            "id": id,
            "old": old,
            "new": newpass,
            "confirmpassword":confirm
         };
         let btn = $('#btn-pass');
         btn.text('Process...');
         try {
            const response = await xhrClient(base_url+`user/update_password/${id}`, "PUT", {
                'Content-Type': 'application/json',
            }, data);
            if (response.status == "success") {
                $(".oji1").removeClass('has-error');
                $('.help-block1').hide();
                $(".oji2").removeClass('has-error');
                $('.help-block2').hide();
                $(".oji3").removeClass('has-error');
                $('.help-block3').hide();
                $('#old').val('');
                $('#new').val('');
                $('#new_confirm').val('');
                Swal.fire({
                    "title": "Successful",
                    "text": response.message,
                    "type": "success"
                });
                
                btn.text('Updated');
            } else {
                btn.text('Change Password');
                if (response.status2 == false) {
                    $(".oji1").addClass('has-error');
                    $('.help-block1').show().html(response.message);
                } else {
                    $(".oji1").removeClass('has-error');
                    $('.help-block1').hide();
                }
                if (response.status3 == false) {
                   $(".oji2").addClass('has-error');
                   $('.help-block2').show().html(response.message);
                } else {
                    $(".oji2").removeClass('has-error');
                    $('.help-block2').hide();
                }
                if (response.status4 == false) {
                    $(".oji3").addClass('has-error');
                    $('.help-block3').show().html(response.message);
                } else {
                    $(".oji3").removeClass('has-error');
                    $('.help-block3').hide();
                }
                if (response.status == "error") {
                    Swal.fire({
                        title: "Failed",
                        text: response.message,
                        type: "error",
                        color: '#716add',
                        background: '#fff',
                        backdrop: `rgba(0,0,123,0.4)`,
                    });
                }
                return false;
            }
         } catch (error) {

            Swal.fire({
                title: "Failed",
                text: error,
                type: "error",
                color: '#716add',
                background: '#fff',
                backdrop: `rgba(0,0,123,0.4)`,
                timer: 2500,
            });
        }
  
    });

    function getUserList() {
        const apiUrl = base_url + 'api/users';
        
        let item_number = 1;
        fetch(apiUrl)
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                table.clear();
                data.forEach(user => {
                    table.row.add([
                        `<span style="color:#49474; font-weight:normal">${item_number}</span>`,
                        `<input type="checkbox" id="dataX" class="checkboxid" name="checkproduct[]" value="${user.id}"/>`,
                        `<span class="company-table-content-display-sm" style="color:#49474; font-weight:normal; font-size:12px">${user.name}</span>`,
                        `<span style="color:#49474; font-weight:normal; font-size:12px">${user.email}</span>`,
                        `<span class="company-table-content-display-lg">${user.role_name}</span>`,
                        `<span style="color:#49474; font-weight:normal; font-size:12px"">${user.created_at}</span>`,
                        `<span style="color:#49474; font-weight:normal; font-size:12px"">${user.updated_at}</span>`,
                        `<div class="flex" style="display:flex">
                            <div class="text-center">
                                <a class="btn btn-xs btn-primary" href="${base_url}dashboard/edituser/${user.id}">
                                    <i class="fa fa-pencil"></i>
                                </a>&nbsp;
                                <button type="button" class="btn btn-xs btn-danger delete-user" data-delete-id="${user.id}"  style="cursor:pointer">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </div>`
                    ]).draw();
                    item_number++;
                });
            })
            .catch(error => {
                Swal.fire({
                    title: "Failed",
                    text: error,
                    type: "error",
                    color: '#716add',
                    background: '#fff',
                    backdrop: `rgba(0,0,123,0.4)`,
                    timer: 2500,
                });
            });
    }

    if (document.getElementsByClassName('user_list_table').length > 0) {
        getUserList();

        $(document).on('click', ".delete-user", function (e) {
            e.preventDefault();
              var id = $(this).attr('data-delete-id');
              let pushId = [];
              pushId.push(id);
              const data = { ids: pushId };
              Swal.fire({
                  title: "Are you sure?",
                  text: "Data will be deleted!",
                  type: "question",
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  background: '#fff',
                  backdrop: `rgba(0,0,123,0.4)`,
                  confirmButtonText: 'Yes, Delete!',
              }).then(async (result) => {
                  if (result.value) {
                      try {
                          const request = await xhrClient(base_url+'user/delete', 'DELETE', {
                              'Content-Type': 'application/json',
                          }, data);
                  
                          Swal.fire('Success', request.message, 'success');
                          setTimeout(function() {
                              window.location.reload(1);
                          }, 500);
                      } catch (error) { 
                          Swal.fire({
                              title: "Failed",
                              text: error,
                              type: "error",
                              color: '#716add',
                              background: '#fff',
                              backdrop: `rgba(0,0,123,0.4)`,
                              timer: 2500,
                              });
                      }
                  } else {
                      return false;
                  }
              });
          });
          
          $(document).on('change', ".checkboxid", function (e) { 
              let items = $('.checkboxid');
              let StringData = [];
              let count = 0;
              for (var i in items) {
                  if (items[i].checked) {
                      count++;
                  }
              }
              if (count == 1) {
                  $('#iz').show();
                  for (var i = 0; i < items.length; i++) {
                      if (items[i].checked) {
                          StringData.push(items[i].value);
                          document.getElementById("deletebadge").innerHTML = count;
                      }
                  }
              } else if (count > 1) {
                  $('#iz').show();
                  for (var i = 0; i < items.length; i++) {
                      if (items[i].checked) {
                          StringData.push(items[i].value);
                          document.getElementById("deletebadge").innerHTML = count;
                      }
                  }
              } else {
                  $('#iz').hide();
                  items[i].checked = false;
              }
              const data = { ids: StringData };
              const element = document.getElementById('delete__Btn')
              element.addEventListener("click", () => {
                  Swal.fire({
                      title: "Are you sure?",
                      text: "Data will be deleted!",
                      type: "question",
                      showCancelButton: true,
                      confirmButtonColor: '#3085d6',
                      cancelButtonColor: '#d33',
                      background: '#fff',
                      backdrop: `rgba(0,0,123,0.4)`,
                      confirmButtonText: 'Yes, Delete!',
                  }).then(async (result) => {
                      if (result.value) {
                          try {
                              const request = await xhrClient(base_url+'user/delete', 'DELETE', {
                                  'Content-Type': 'application/json',
                              }, data);
                              Swal.fire('Success', request.message, 'success');
                              setTimeout(function() {
                                  window.location.reload(1);
                              }, 500);
                          } catch (error) { 
                              Swal.fire({
                                  title: "Failed",
                                  text: error,
                                  type: "error",
                                  color: '#716add',
                                  background: '#fff',
                                  backdrop: `rgba(0,0,123,0.4)`,
                                  timer: 2500,
                                  });
                          }
                      } else {
                          return false;
                      }
                  });
              });
          })
          
          $(document).on('change', "#chk_all", function (e) { 
              let inputs = $(".checkboxid");
              count = 0;
              let pushId = [];
              for (let i = 0; i < inputs.length; i++) {
                  let type = inputs[i].getAttribute("type");
                  if (type == "checkbox") {
                      if (this.checked) {
                          count++;
                          $('#iz').show();
                          pushId.push(inputs[i].value);
                          inputs[i].checked = true;
                      } else {
                          $('#iz').hide();
                          inputs[i].checked = false;
                      }
                  }
              }
              document.getElementById("deletebadge").innerHTML = count;
              const data = { ids: pushId };
              const element = document.getElementById('delete__Btn')
              element.addEventListener("click", () => {
                  Swal.fire({
                      title: "Are you sure?",
                      text: "Data will be deleted!",
                      type: "question",
                      showCancelButton: true,
                      confirmButtonColor: '#3085d6',
                      cancelButtonColor: '#d33',
                      background: '#fff',
                      backdrop: `rgba(0,0,123,0.4)`,
                      confirmButtonText: 'Yes, Delete!',
                  }).then(async (result) => {
                      if (result.value) {
                          try {
                              const request = await xhrClient(base_url+'user/delete', 'DELETE', {
                                  'Content-Type': 'application/json',
                              }, data);
                              Swal.fire('Success', request.message, 'success');
                              setTimeout(function() {
                                  window.location.reload(1);
                              }, 500);
                          } catch (error) { 
                              Swal.fire({
                                  title: "Failed",
                                  text: error,
                                  type: "error",
                                  color: '#716add',
                                  background: '#fff',
                                  backdrop: `rgba(0,0,123,0.4)`,
                                  timer: 2500,
                                  });
                          }
                      } else {
                          return false;
                      }
                  });
              });
          });
          
          
          table.on('click', 'tbody tr', function (e) {
              e.currentTarget.classList.toggle('selected');
          });
    }

    $(document).on('click', ".add-new-user", async function (e) { 
        e.preventDefault();
        let btn = $('#btn-pass');

        let role = $('#role').val();
        let firstname = $('#firstname').val();
        let lastname = $('#lastname').val();
        let email = $('#email').val();
        let mobile = $('#mobile').val();

        const data = {
            "role": role,
            "firstname": firstname,
            "lastname": lastname,
            "email": email,
            "telephone": mobile,
        };

        try {
            btn.text('Process...');
            await xhrClient(base_url + `user/create`, "POST", {
                'Content-Type': 'application/json',
            }, data).then((response) => {
                if (response.errors) {
                    var errors = response.errors;
                    Object.keys(errors).forEach((key) => {
                        const errorElement = document.querySelector(`.${key}-error`);
                        const parentElement = errorElement.parentNode;
                        if (errorElement && parentElement.classList.contains('form-group')) {
                            if (errors[key]) {
                                errorElement.textContent = errors[key];
                                parentElement.classList.add('has-error');
                            } else {
                                errorElement.textContent = '';
                                parentElement.classList.remove('has-error');
                            }
                        }
                    });

                    const allFields = ['email', 'role', 'telephone', 'firstname', 'lastname'];
                    allFields.forEach((field) => {
                        if (!(field in errors)) {
                            const errorElement = document.querySelector(`.${field}-error`);
                            const parentElement = errorElement.parentNode;
                            if (errorElement && parentElement.classList.contains('form-group')) {
                                errorElement.textContent = errors[field] || '';
                                if (errors[field]) {
                                    parentElement.classList.add('has-error');
                                } else {
                                    parentElement.classList.remove('has-error');
                                }
                            }
                        }
                    });
                } else {
                    Swal.fire('Success', response.message, 'success');
                    setTimeout(function () {
                        window.location.reload(1);
                    }, 500);
                }
            }).catch(error => {
                Swal.fire({
                    title: "Failed",
                    text: error,
                    type: "error",
                    color: '#716add',
                    background: '#fff',
                    backdrop: `rgba(0,0,123,0.4)`,
                });
            });
        }catch (error) { 
            Swal.fire({
                title: "Failed",
                text: error,
                type: "error",
                color: '#716add',
                background: '#fff',
                backdrop: `rgba(0,0,123,0.4)`,
            });
        }
    });
    
});


