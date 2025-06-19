

// invoice Initialize with one section
let sectionCount = 1;
let itemCount = 1;
let bank_name ="", account_name ="" , account_number ="";
let username = "", user_email = "", user_role_name = "", user_id = "", role_id = "", client_name = "", client_city = "";
var save_label;
var table;
let description, unitPrice;
var currentRow;
var tableRows;
var sectionSubtotal;
let customer_name = document.querySelector(".customer_name").value;
let invoice_number = document.querySelector(".customer_invoice_no").value;
let customer_telephone = document.querySelector(".customer_telephone").value;
let grandTotal;

var dateFormat = $(this).attr('data-vat-rate');
$('#invoice_date, #invoice_due_date').datetimepicker({
	showClose: false,
	format: dateFormat
});

// Helpers functions start here
function formatDateForInvoice(dateString) {
    if (!dateString) return "";
    const parts = dateString.split('/');
    if (parts.length !== 3) return dateString; 
    
    const day = parseInt(parts[0], 10);
    const month = parseInt(parts[1], 10) - 1;
    const year = parts[2];
    
    const date = new Date(year, month, day);
    if (isNaN(date.getTime())) return dateString; 
    const dayWithSuffix = day + (day > 0 ? ['th', 'st', 'nd', 'rd'][(day > 3 && day < 21) || day % 10 > 3 ? 0 : day % 10] : '');

    const monthNames = ["January", "February", "March", "April", "May", "June",
                       "July", "August", "September", "October", "November", "December"];
    const monthName = monthNames[month];
    
    return `${dayWithSuffix} ${monthName}, ${year}`;
}

function formatDateTime(dateString) {
	const date = new Date(dateString);
	const options = {
		day: '2-digit',
		month: 'long',
		year: 'numeric',
		hour: 'numeric',
		minute: '2-digit',
		hour12: true
	};
	return date.toLocaleString('en-US', options);
}

function addFieldError(inputElement, message) {
	const formGroup = inputElement.closest(".form-group");
	formGroup.classList.add("has-error");
	let errorMsg = formGroup.querySelector(".help-block-error-msg");
	if (!errorMsg) {
		errorMsg = document.createElement("small");
		errorMsg.className = "help-block-error-msg";
		errorMsg.style.color = "#dd4b39";
		formGroup.appendChild(errorMsg);
	}
	errorMsg.textContent = message;
}

function formatDateForInvoice(dateString) {
    if (!dateString) return "";
    const parts = dateString.split('/');
    if (parts.length !== 3) return dateString; 
    
    const day = parseInt(parts[0], 10);
    const month = parseInt(parts[1], 10) - 1;
    const year = parts[2];
    
    const date = new Date(year, month, day);
    if (isNaN(date.getTime())) return dateString; 
    const dayWithSuffix = day + (day > 0 ? ['th', 'st', 'nd', 'rd'][(day > 3 && day < 21) || day % 10 > 3 ? 0 : day % 10] : '');

    const monthNames = ["January", "February", "March", "April", "May", "June",
                       "July", "August", "September", "October", "November", "December"];
    const monthName = monthNames[month];
    
    return `${dayWithSuffix} ${monthName}, ${year}`;
}

function formatDateTime(dateString) {
	const date = new Date(dateString);
	const options = {
		day: '2-digit',
		month: 'long',
		year: 'numeric',
		hour: 'numeric',
		minute: '2-digit',
		hour12: true
	};
	return date.toLocaleString('en-US', options);
}

function addSection() {
	const container = document.getElementById('sections-container');
	const sectionDiv = document.createElement('div');
	sectionDiv.className = 'invoice-section';
	const currentSectionId = sectionCount;
	sectionDiv.dataset.sectionId = currentSectionId;
	
	sectionDiv.innerHTML = `
		<div class="section-controls">
			<h3>Section ${String.fromCharCode(64 + currentSectionId)}</h3>
			<div>
				<label>Discount (%): </label>
				<div style="display:flex;justify-items: flex-end;">
				<input type="number"  class="form-control section-discount" min="0" max="100" id="section-discount" value="${currentSectionId === 1 ? 20 : 10}" style="width:100px">
				<button class="btn btn-sm btn-danger remove-section" style="margin-left:10px">Remove Section</button>
				</div>
			</div>
		</div>
		<table class="section-table table table-bordered table-hover table-striped" id="invoice_table">
			<thead>
				<tr>
					<th>S/N</th>
					<th>QUANTITY</th>
					<th>DESCRIPTION</th>
					<th>UNIT PRICE NGN</th>
					<th>TOTAL NGN</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody class="item-rows"></tbody>
			<tfoot>
				<tr>
					<td colspan="6" style="text-align: center;">
						<button type="button" class="add-item btn btn-flat btn-sm btn-info add-row">Add Item to This Section</button>
					</td>
				</tr>
			</tfoot>
		</table>
	`;
	
	container.appendChild(sectionDiv);

	const addItemBtn = sectionDiv.querySelector('.add-item');
	if (addItemBtn) {
		addItemBtn.addEventListener('click', function() {
			addItemToSection(currentSectionId);
		});
	}
	
	const removeSectionBtn = sectionDiv.querySelector('.remove-section');
	if (removeSectionBtn) {
		removeSectionBtn.addEventListener('click', function() {
			if (confirm('Remove this section and all its items?')) {
				sectionDiv.remove();
				renumberSections();
			}
		});
	}
	
	addItemToSection(currentSectionId);
	
	sectionCount++;
}

function addItemToSection(sectionId) {
    const section = document.querySelector(`.invoice-section[data-section-id="${sectionId}"]`);
	if (!section) {
		$.jGrowl("Error", {
			header: `Section with ID ${sectionId} not found`
		});
        return;
    }
    
    const tbody = section.querySelector('.item-rows');
	if (!tbody) {
		$.jGrowl("Error", {
			header: 'Item rows tbody not found in section.'
		});
        return;
    }
    
    const rows = tbody.querySelectorAll('tr');
    const rowNumber = rows.length + 1;
    
    const row = document.createElement('tr');
    row.className = 'item-row';
    row.innerHTML = `
        <td>${rowNumber}</td>
        <td>
            <div class="form-group no-margin-bottom">
                <input type="number" class="form-control item-input quantity calculate" min="1" value="1" id="qty" style="width:70px"/>
            </div>
        </td>
        <td>
			<div class="form-group form-group-sm  no-margin-bottom">
				<div class="d-flex">
					<input class="form-control item-input product_description" placeholder="Description" id="description" name="product_description_${rowNumber}"/>&nbsp;or
				</div>
				<p class="item-select-link select_product">select a product</p>
			</div>
		</td>
        <td>
            <div class="input-group no-margin-bottom">
                <span class="input-group-addon">₦</span>
                <input type="number" class="unit-price form-control item-input calculate invoice_product_price required" aria-describedby="sizing-addon1" placeholder="0.00" id="unit_price" min="0"  name="invoice_product_price_${rowNumber}"/>
            </div>
        </td>
        <td>
            <div class="input-group input-group-sm">
                <span class="input-group-addon">₦</span>
                <input type="text" class="form-control calculate-sub item-total" id="invoice_product_sub" value="0.00" aria-describedby="sizing-addon1" disabled>
            </div>
        </td>
        <td><button type="button" class="remove-item btn btn-danger btn-xs delete-row"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button></td>
    `;
    tbody.appendChild(row);
    itemCount++;

    addRowEventListeners(row);
}

function addRowEventListeners(row) {

	description = row.querySelector('.product_description').value || 'No description';
	const quantity = parseFloat(row.querySelector('.quantity').value) || 0.00;
	unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0.00;
	const total = quantity * unitPrice;
	sectionSubtotal += total;
	

    const quantityInput = row.querySelector('.quantity');
    const unitPriceInput = row.querySelector('.unit-price');
    const removeBtn = row.querySelector('.remove-item');
    const totalInput = row.querySelector('.item-total');
    
    const calculateItemTotal = () => {
        const quantity = parseFloat(quantityInput.value) || 0;
        unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0.00;
        const total = quantity * unitPrice;
        totalInput.value = formatCurrency(total, true); 
    };
    
    quantityInput.addEventListener('input', calculateItemTotal);
    unitPriceInput.addEventListener('input', calculateItemTotal);
    
    removeBtn.addEventListener('click', () => {
        const tbody = row.parentElement;
        row.remove();
        renumberItemsInSection(tbody);
    });
}

function renumberItemsInSection(tbody) {
	const rows = tbody.querySelectorAll('tr');
	rows.forEach((row, index) => {
		row.cells[0].textContent = index + 1;
	});
}

function renumberSections() {
	const sections = document.querySelectorAll('.invoice-section');
	sections.forEach((section, index) => {
		section.dataset.sectionId = index + 1;
		section.querySelector('h3').textContent = `Section ${String.fromCharCode(65 + index)}`;
	});
	sectionCount = sections.length + 1;
}

function formatCurrency(amount) {
	return new Intl.NumberFormat('en-NG', {
		style: 'currency',
		currency: 'NGN',
		minimumFractionDigits: 2,
		maximumFractionDigits: 2
	}).format(amount).replace('NGN', 'NGN ');
}

// helper function
function getTotalDueLabel(sectionCount) {
	if (sectionCount === 1) {
		return String.fromCharCode(65); // "A"
	}
	
	let label = '';
	for (let i = 0; i < sectionCount; i++) {
		if (i > 0) {
			if (i === sectionCount - 1) {
				label += ' & ';
			} else {
				label += ', ';
			}
		}
		label += String.fromCharCode(65 + i);
	}
   
	return label;
}

function generateInvoice() {
	const errors = [];

	const customerNameInput = document.querySelector(".customer_name");
	const invoiceNumberInput = document.querySelector(".customer_invoice_no");
	const customerTelephoneInput = document.querySelector(".customer_telephone");
	const invoiceDateInput = document.querySelector(".due_date");
	const clientNameInput = document.querySelector(".client_name");
	const clientCityInput = document.querySelector(".client_city");
	// Clear all previous error messages and classes
	document.querySelectorAll(".form-group").forEach(group => group.classList.remove("has-error"));
	document.querySelectorAll(".help-block-error-msg").forEach(msg => msg.textContent = "");

	// Validate customer info
	if (!customerNameInput.value.trim()) addFieldError(customerNameInput, "Name is required.");
	if (!invoiceNumberInput.value.trim()) addFieldError(invoiceNumberInput, "Invoice Number is required.");
	if (!customerTelephoneInput.value.trim()) addFieldError(customerTelephoneInput, "Telephone is required.");
	if (!invoiceDateInput.value.trim()) addFieldError(invoiceDateInput, "Invoice Date is required.");

	// Validate client info
	if (!clientNameInput.value.trim()) addFieldError(clientNameInput, "Client name is required.");
	if (!clientCityInput.value.trim()) addFieldError(clientCityInput, "Client city/town is required.");
	
	if (!bank_name || !account_name || !account_number) {
		$.jGrowl("Error", {
			header: 'Your also need to select payment details.'
		});
		return;
	}
	
	const sections = document.querySelectorAll('.invoice-section');
	document.getElementById('print-invoice').disabled = false;
	
	if (sections.length === 0) {
		$.jGrowl("Error", {
			header: 'Please add at least one section with items to the invoice'
		});
		return;
	}

	const invoice_date = formatDateForInvoice(invoiceDateInput.value);
	invoice_number = invoiceNumberInput.value;
	client_name = clientNameInput.value;
	client_city = clientCityInput.value;

	let html = `
				<div id="preview_invoice">
					<div id="preview_invoice" style="font-family: Arial, sans-serif; padding: 20px;">
						<div style="display: flex; justify-content: space-between; align-items: flex-start;">
							<!-- Left Section: Company Info -->
							<div style="width: 50%;">
								<h4 style="margin: 0; font-size:20px; display:flex">SAFARI BOOKS LIMITED <span style="font-size: 14px;">RC.172479</span></h4>
								<span style="margin: 2px 0; font-size: 15px;" class="invoice-content">Ile Ori-Detu, No-1Shell Close Onireke</span><br/>
								<span style="margin: 2px 0; font-size: 15px;" class="invoice-content">Ibadan, Oyo-State</span><br/>
								<span style="margin: 2px 0; font-size: 15px;" class="invoice-content">Phone: +234(0)7060603020</span><br/>
								<span style="margin: 2px 0; font-size: 15px;" class="invoice-content">E-mail: info@safaribooks.com.ng</span><br/>
								<span style="margin: 2px 0; font-size: 15px;" class="invoice-content">Website: www.safaribooks.com.ng</span>

								<br/><br/><br/>
								<span style="font-size:12px; font-weight:bold">INVOICE TO</span><br/>
								<span class="invoice-content">${client_name}<br>${client_city}</span>
							</div>
							<div style="width: 35%; text-align: center;">
								<img src="${assets_url}images/logo.png" alt="logo" style="max-height: 90px; margin: 0 auto;" />
							<div style="font-weight: bold; color: gray; font-size: 20px; margin-top: 10px;">
								SALES INVOICE
							</div>
							<div style="margin-top: 4px; font-size: 14px;">
								Product Description: Books
							</div>
						</div>
					</div>
				</div>
				<table class="section-table table table-bordered table-hover table-striped" >
					<thead>
						<tr>
							<th style="text-align: center;"><span style="font-size:12px; display:flex">SAFARI CONTACT</span></th>
							<th style="text-align: center;"><span style="font-size:12px;  display:flex">COMMERCIAL INVOICE NUMBER</span></th>
							<th style="text-align: center;"><span style="font-size:12px; display:flex">INVOICE DATE</span></th>
							<th style="text-align: center;"><span style="font-size:12px; display:flex">SHIPPED VIA</span></th>
							<th style="text-align: center;"><span style="font-size:12px;">CUSTOMER REFERENCE</span></th>
							<th style="text-align: center;"><span style="font-size:12px;">TERMS</span></th>
						</tr>
					</thead>
					<tbody>
					<tr>
						<td><span class="invoice-content">${customer_name}<br>${customer_telephone}</span></td>
						<td><span class="invoice-content">${invoice_number}</span></td>
						<td><span class="invoice-content">Date: ${invoice_date}</span></td>
						<td></td>
						<td></td>
						<td><span class="invoice-content">Due on receipt</span></td>
					</tr>
				</tbody>
			</table><br/>
			<table class="section-table table table-bordered table-hover table-striped">
				<thead>
					<tr>
						<th style="text-align: center;"><span style="font-size: 12px;">S/N</span></th>
						<th style="text-align: center;"><span style="font-size: 12px;">QUANTITY</span></th>
						<th style="text-align: center;"><span style="font-size: 12px;">DESCRIPTION</span></th>
						<th style="text-align: center;"><span style="font-size: 12px;">UNIT PRICE NGN</span></th>
						<th style="text-align: center;"><span style="font-size: 12px;">TOTAL NGN</span></th>
					</tr>
					</thead>
				<tbody>`;
			grandTotal = 0;
			let itemCounter = 1;
		
			sections.forEach((section, sectionIndex) => {
				const discount = parseFloat(section.querySelector('.section-discount').value) || 0;
				const rows = section.querySelectorAll('.item-rows tr');
			
				if (rows.length === 0) return;
			
				sectionSubtotal = 0;
			
				// Add items to invoice
				rows.forEach(row => {
					description = row.querySelector('.product_description').value || 'No description';
					const quantity = parseFloat(row.querySelector('.quantity').value) || 0.00;
					unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0.00;
					const total = quantity * unitPrice;
					sectionSubtotal += total;
				
					html += `
					<tr>
						<td><span class="invoice-content">${itemCounter++}</span></td>
						<td><span class="invoice-content">${quantity}</span></td>
						<td><span class="invoice-content">${description}</span></td>
						<td><span class="invoice-content">${formatCurrency(unitPrice)}</span></td>
						<td><span class="invoice-content">${formatCurrency(total)}</span></td>
					</tr>
				`;
				});
			
				// Add subtotal and discount rows
				html += `
				<tr class="subtotal-row total-row">
					<td colspan="2"></td>
					<td class="centered-discount">Sub-Total</td>
					<td></td>
					<td>${formatCurrency(sectionSubtotal)}</td>
				</tr>
			`;
			
				if (discount > 0) {
					const discountAmount = sectionSubtotal * (discount / 100);
					sectionSubtotal -= discountAmount;
				
					html += `
					<tr class="total-row">
						<td colspan="2"></td>
						<td class="centered-discount">Less: Discount ${discount}%</td>
						<td></td>
						<td>${formatCurrency(discountAmount)}</td>
					</tr>
				`;
				}
			
				html += `
				<tr class="total-row">
					<td colspan="2"></td>
					<td class="centered-discount">Sub-Total ${String.fromCharCode(65 + sectionIndex)}</td>
					<td></td>
					<td>${formatCurrency(sectionSubtotal)}</td>
				</tr>
				<tr class="empty-row">
					<td colspan="5"></td>
				</tr>
			`;
			
				grandTotal += sectionSubtotal;
			});
		
			// Add grand total
			html += `
				<tr class="final-total">
					<td colspan="4" style="text-align: right;">TOTAL DUE ${sections.length === 1 ? 'A' :
					Array.from({ length: sections.length }, (_, i) => String.fromCharCode(65 + i))
						.join(', ').replace(/, ([^,]*)$/, ' & $1')
				}</td>
					<td>${formatCurrency(grandTotal)}</td>
				</tr>
			</tbody>
			</table>
			<br/><br/>
			<div class="invoice-wrapper" style="display: flex;flex-direction: column;min-height:30vh;padding: 20px;box-sizing: border-box;">
				<div class="payment-content" style="flex: 1;font-weight: 700">
				<span>Payment Details</span><br/>
				<span class="invoice-content">Account Name: ${account_name}</span><br/>
				<span class="invoice-content">Bank Name:  ${bank_name}</span><br/>
				<span class="invoice-content">Account Number:</span>  <span>${account_number}</span>
			</div>
			<div class="bottom_section" style="margin-top: 15px;text-align: center;">
				<div id="vat" class="invoice-content">
					VAT Registration No. SW1AYIVT13002172479. Registered in Nigeria No – RC.172479. Tin -00847354-0001
				</div>
			</div>
		</div>`;
	document.getElementById('invoice-container').innerHTML = html;
	document.getElementById('output-section').style.display = 'block';
}

// Event listeners
document.getElementById('add-section').addEventListener('click', addSection);
document.getElementById('calculate').addEventListener('click', generateInvoice);

document.getElementById('print-invoice').addEventListener('click', () => {

	document.getElementById('output-section').style.display = 'block';
	const invoiceTable = document.getElementById('preview_invoice').cloneNode(true);
	const printWindow = window.open('', '_blank');
    if (!invoiceTable) {
        $.jGrowl("Error", {
			header: 'Please generate the invoice first by clicking "Generate Invoice'
		});
        return;
    }
	
	// Create basic HTML structure for printing
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Invoice Print</title>
            <style>
                body {font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; font-size: 14px;line-height: 1.42857143;color: #333; margin: 20px; }
                table {border-spacing: 0;border-collapse: collapsebackground-color: transparent;border-collapse: collapse; width: 100%;max-width: 100%; margin-bottom: 20px;}
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; }
                .total-row { font-weight: bold; }
                .final-total { font-weight: bold; font-size: 1.1em; background-color: #f2f2f2; }
                .centered-discount { text-align: center !important; }
				.invoice-content {color: #010101 !important;text-decoration: none;font-weight: 400 !important;font-size: 12px !important;}
            </style>
        </head>
        <body>
            ${invoiceTable.outerHTML}
            <script>
                window.onload = function() {
                    window.print();
                    setTimeout(function() {
                        window.close();
                    }, 100);
                };
            </script>
        </body>
        </html>
    `);
    printWindow.document.close();
});


function getPaymentDetails() {
    const apiUrl = base_url + 'api/payment_details';

    fetch(apiUrl)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            const select = document.getElementById('bank-select');
            select.innerHTML = '';

            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = '-- Select Bank --';
            defaultOption.disabled = false;
            defaultOption.selected = true;
            select.appendChild(defaultOption);

            if (Array.isArray(data)) {
                data.forEach(bank => {
                    const option = document.createElement('option');
                    option.value = bank.account_number;
                    option.textContent = `${bank.account_name} - ${bank.bank_name} (${bank.account_number})`;

                    option.dataset.accountNumber = bank.account_number;
                    option.dataset.accountHolderName = bank.account_name;
                    option.dataset.bankName = bank.bank_name;

                    select.appendChild(option);
                });
            } else {
                const option = document.createElement('option');
                option.value = data.account_number;
                option.textContent = `${data.account_name} - ${data.bank_name} (${data.account_number})`;
                option.dataset.accountNumber = data.account_number;
                option.dataset.accountHolderName = data.account_name;
                option.dataset.bankName = data.bank_name;

                select.appendChild(option);
            }
        })
		.catch(error => {
			$.jGrowl(error, {
				header: 'Error fetching payment details:'
			});
        });
}

function getUsersList() {
	const apiUrl = base_url + 'api/users';
	const table = $("#user-list-table").DataTable();

	fetch(apiUrl)
		.then(response => {
			if (!response.ok) throw new Error('Network response was not ok');
			return response.json();
		})
		.then(data => {
			table.clear();
			data.forEach(user => {
				const formattedDate = formatDateTime(user.created_at);

				table.row.add([
					`<span style="color:#49474; font-weight:normal">${user.name}</span>`,
					`<span style="color:#49474; font-weight:normal">${user.email}</span>`,
					`<span style="color:#49474; font-weight:normal; font-size:12px">${user.role_name}</span>`,
					`<span style="color:#49474; font-weight:normal; font-size:12px">${user.telephone}</span>`,
					`<span style="color:#49474; font-weight:normal">${formattedDate}</span>`,
					`<div class="btn btn-primary btn-xs select-customer_relation_officer" data-user-id="${user.id}">Select</div>`
				]).draw();
			});
		})
		.catch(error => {
			$.jGrowl(error, {
				header: 'Error fetching users list:'
			});
		});
}

function getInvoiceNumber() {
	const apiUrl = base_url + 'api/invoice_number';
	fetch(apiUrl)
		.then(response => {
			if (!response.ok) throw new Error('Network response was not ok');
			return response.json();
		})
		.then(data => {
			$(".customer_invoice_no").val(data);
		})
		.catch(error => {
			$.jGrowl(error, {
				header: 'Error fetching users list:'
			});
		});
}

function getBookList() {
	const apiUrl = base_url + 'api/books';
	const table = $("#product-list-table").DataTable();
	let item_number = 1;
	fetch(apiUrl)
		.then(response => {
			if (!response.ok) throw new Error('Network response was not ok');
			return response.json();
		})
		.then(data => {
			table.clear();
			data.forEach(book => {
				table.row.add([
					`<span style="color:#49474; font-weight:normal">${item_number}</span>`,
					`<span style="color:#49474; font-weight:normal; font-size:12px">${book.title}</span>`,
                    `<span style="color:#49474; font-weight:normal; font-size:12px">${book.binding}</span>`,
					`<span style="color:#49474; font-weight:normal" data-product-price=${book.sale_price}>${formatCurrency(book.sale_price)}</span>`,
					`<div class="btn btn-primary btn-xs select-product" data-user-id="${book.id}">Select</div>`
				]).draw();
				item_number++;
			});
		})
		.catch(error => {
			console.error('Error fetching users list:', error);
		});
}

getBookList();
getInvoiceNumber();
getUsersList();
addSection();
getPaymentDetails();

$(document).on('click', ".payment-details", function (e) {
	e.preventDefault;
	$('#select-add-payment-details').modal('show');
	return false;
});

$(document).on('click', ".select-customer", function (e) {
	e.preventDefault;
	$('#select-customer-users').modal('show');
	return false;
});

$(document).on('click', ".close-select-customer", function (e) {
	e.preventDefault;
	$('#select-customer-users').modal('hide');
	return false;
});

$(document).on('click', ".close-payment-details", function (e) {
	e.preventDefault;
	$('#select-add-payment-details').modal('hide');
	bank_name = "", account_name = "", account_number = "";
	$("#bank-select-group").removeClass("has-error");
	$('#select-add-payment-details').modal('hide');
	$('.help-block-msg').show().html('');
	return false;
});

const select_payment_details= document.getElementById("bank-select");

select_payment_details.addEventListener('change', (e) => {
	const selectedOption = e.target.options[e.target.selectedIndex];
	account_number = selectedOption.getAttribute('data-account-number');
	bank_name = selectedOption.getAttribute('data-bank-name');
	account_name = selectedOption.getAttribute('data-account-holder-name');
})

$(document).on('click', ".button-payment-details", function (e) {
	e.preventDefault();
  
	const selectedOption = $("#bank-select option:selected");
  
	const account_name = selectedOption.data('accountHolderName');
	const bank_name = selectedOption.data('bankName');
	const account_number = selectedOption.data('accountNumber');
  
	if (!bank_name || !account_number || !account_name) {
		$("#bank-select-group").addClass("has-error");
		$('.help-block-msg').show().html("The field is required.");
	} else {
	  	$("#bank-select-group").removeClass("has-error");
		$('#select-add-payment-details').modal('hide');
		$('.help-block-msg').show().html('');
	}
  
	return false;
});

$(document).on('click', ".select-customer_relation_officer", function (e) {
	e.preventDefault();
	const row = $(this).closest('tr');
	const table = $('#user-list-table').DataTable();
	const rowData = table.row(row).data();
	customer_name = $(rowData[0]).text();  
	customer_telephone = $(rowData[3]).text(); 
	const userId = $(this).data('user-id');

	$(".customer_name").val(customer_name);
	$(".customer_telephone").val(customer_telephone);
	$("#selected_user_id").val(userId);

	$('#select-customer-users').modal('hide');
});

$(document).on('click', ".select_product", function (e) {
	e.preventDefault();
	currentRow = $(this).closest('.item-row');
	$('#select-stock-product').modal('show');
});
	
$(document).on('click', ".select-product", function (e) {
	e.preventDefault();
	const row = $(this).closest('tr');
	const table = $('#product-list-table').DataTable();
	const rowData = table.row(row).data();
	// const product_esn13 = $(rowData[0]).text();
	// const product_isbn10 = $(rowData[1]).text();
	// const product_author_name = $(rowData[2]).text();
	const product_title = $(rowData[1]).text();
	// const product_country = $(rowData[4]).text();
	// const product_year = $(rowData[5]).text();
	const productPriceSpan = $(rowData[3]);
	const product_price = productPriceSpan.attr('data-product-price');
	description = product_title;
	unitPrice = product_price;
	currentRow.find('.product_description').val(description);
	currentRow.find('.invoice_product_price').val(unitPrice);
	const product_row = currentRow[0];
	const quantityInput = product_row.querySelector('.quantity');
	const unitPriceInput = product_row.querySelector('.unit-price');
	const totalInput = product_row.querySelector('.item-total');
	const calculateItemTotal = () => {
	  const quantity = parseFloat(quantityInput.value) || 0;
	  unitPrice = parseFloat(product_row.querySelector('.unit-price').value) || 0.00;
	  const total = quantity * unitPrice;
	  totalInput.value = formatCurrency(total, true);
	};
	quantityInput.addEventListener('input', calculateItemTotal);
	unitPriceInput.addEventListener('input', calculateItemTotal);
	calculateItemTotal(); 
	$('#select-stock-product').modal('hide');
  });
  

  function calculateTotal() {
	let total = 0;
	$('.item-total').each(function() {
	  total += parseFloat($(this).val().replace(/[^0-9.-]+/g, '')) || 0;
	});
	$('#total').val(formatCurrency(total, true));
  }
  
  
  
  const calculateItemTotal = () => {
	const quantity = parseFloat(quantityInput.value) || 0;
	unitPrice = parseFloat(product_row.querySelector('.unit-price').value) || 0.00;
	const total = quantity * unitPrice;
	totalInput.value = formatCurrency(total, true);
	calculateTotal(); 
  };
  
  
$(document).on('click', ".close-select-stock-product", function (e) {
	e.preventDefault;
	$('#select-stock-product').modal('hide');
	return false;
}); 


