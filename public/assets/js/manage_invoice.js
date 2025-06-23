import xhrClient from "./libs/xhrClient"
var count;
const table = $("#invoice-list-table").DataTable({
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


async function getAllInvoiceList() {
	const apiUrl = base_url + 'api/invoice_list';
	let item_number = 1;
	fetch(apiUrl)
		.then(response => {
			if (!response.ok) throw new Error('Network response was not ok');
			return response.json();
		})
		.then(data => {
			table.clear();
            data.forEach(invoice => {
				table.row.add([
                    `<span style="color:#49474; font-weight:normal">${item_number}</span>`,
                    `<input type="checkbox" id="dataX" class="checkboxid" name="checkinvoice[]" value="${invoice.invoice_id}"/>`,
					`<span style="color:#49474; font-weight:normal; font-size:12px">${invoice.invoice_number}</span>`,
                    `<span style="color:#49474; font-weight:normal; font-size:12px">${invoice.client_name}</span>`,
					`<span style="color:#49474; font-weight:normal; font-size:12px">${invoice.invoice_date}</span>`,
                    `<span style="color:#49474; font-weight:normal; font-size:12px">${invoice.invoice_type}</span>`,
                    `<span style="color:#49474; font-weight:normal; font-size:12px">${invoice.name}</span>`,
                    `<div class="dropdown">
                        <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                            Action&nbsp;&nbsp;<i class="fa fa-caret-down" aria-hidden="true" style="font-size:12px"></i>
                            <span class="sr-only">Dropdown</span>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="font-size:14px">
                            <a class="dropdown-item" href="${base_url}dashboard/edit_invoice?action=edit&id=${invoice.invoice_id}">
                                <span class="fa fa-edit text-primary"></span>
                                &nbsp;&nbsp;Edit
                            </a>
                            <div class="dropdown-divider"></div>
                            <span class="dropdown-item delete-invoice" data-delete-id="${invoice.invoice_id}"  style="cursor:pointer">
                                <span class="fa fa-trash text-danger"></span>
                                &nbsp;Delete
                            </span>
                        </div>
                    </div>`
				]).draw();
				item_number++;
			});
		})
		.catch(error => {
			console.error('Error fetching invoices list:', error);
		});
}

await getAllInvoiceList();


$(document).on('click', ".delete-invoice", function (e) {
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
                  const request = await xhrClient(base_url+'api/delete_invoice', 'DELETE', {
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
                    const request = await xhrClient(base_url+'api/delete_invoice', 'DELETE', {
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
                    const request = await xhrClient(base_url+'api/delete_invoice', 'DELETE', {
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