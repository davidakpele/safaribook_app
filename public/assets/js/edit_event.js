
import util from "./libs/util";
import modal from "./libs/modal";
import xhrClient from "./libs/xhrClient"


var util_ = new util();
var modalControler = new modal();
modalControler.controller();

let deliveryCost = 0;
let includeDeliveryCost = false;
let sectionCount = 1;
let itemCount = 1;
var bank_name = "", account_name = "", account_number = "";
var count, customer_relation_id, selectedPaymentMethod;
let customer_name = document.querySelector(".customer_name").value;
let invoice_number = document.querySelector(".customer_invoice_no").value;
let customer_telephone = document.querySelector(".customer_telephone").value;
var selectedPaymentMethod;
let description, unitPrice;
var client_name = "", client_address="", client_city = "", client_telephone="";
var currentRow;
var sectionSubtotal;
let invoice_date;
let grandTotal;
var payload_items = [];
var payload = {};
var company_detail =
{
	company_name: '',
	company_tagline:'', 
	company_logo: '',
	company_icon_logo: '',
	company_rc: '',
	company_email:'',
	company_address:'',
	company_telephone:'',
	company_website:'',
    company_country: '',
    company_city:'',
}
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

util_.getPaymentDetails();
util_.getBookList();
util_.getUsersList();

// Get the ID from URL
const invoiceId = util_.getUrlParameter('id');

if (invoiceId) {
    util_.getInvoiceById(invoiceId)
        .then(data => {
			if (data) {
				
                // Basic fields
                document.querySelector('.client_name').value = data.invoice.client_name;
                document.querySelector('.client_city').value = data.invoice.client_city;
                document.querySelector('.customer_name').value = data.invoice.officer;
                document.querySelector('.customer_invoice_no').value = data.invoice.invoice_number;
                document.querySelector('.customer_shipping_via').value = data.invoice.shipping_via;
                document.querySelector('.customer_reference').value = data.invoice.customer_reference;
                document.querySelector('.customer_telephone').value = data.invoice.office_telephone;
				
				if (data.invoice.delivery_cost !="" && data.invoice.delivery_cost !="0.00") {
					
						// Dynamically add input if it doesn't exist
                    const inputField = `
                        <div id="delivery_cost_wrapper" style="margin-top: 10px;">
                            <div class="form-group">
                                <label for="address" style="color:black">Delivery Cost <small>(Optional)</small>:*</label>
                                <input type="number" step="0.01" min="0" id="delivery_cost_input" 
                                                    class="form-control" placeholder="Enter Cost of Delivery" 
                                                    style="width: 200px;" value="${data.invoice.delivery_cost}" />
                            </div>
                        </div>
						`;
						includeDeliveryCost = true;
						$('.add-delivery-cost').after(inputField);
						$('.add-delivery-cost').html('<i class="fa fa-trash"></i> Remove Cost of Delivery');
				} else {
					$('#delivery_cost_wrapper').remove();
					$('.add-delivery-cost').html('<i class="fa fa-usd"></i> Add Cost of Delivery');
					includeDeliveryCost = false;
                }
                
                const _efdate = util_.reformate_date_back_to_normal(data.invoice.invoice_date);
                document.querySelector('.due_date').value = _efdate;
                customer_name = data.invoice.officer;
                customer_telephone = data.invoice.office_telephone;
                // Payment method selection and variable extraction
                const paymentId = data.invoice.paymentMethod.id;
                $("#bank-select option").each(function () {
                    if ($(this).attr('data-bank-container-id') == paymentId) {
                        $(this).prop('selected', true);
                    }
                });
            
                // client address 
                document.getElementById('address_row_container').innerHTML = '';

                // Populate address lines
                const addressLines = data.invoice.client_address;
                document.querySelector(".client_telephone").value = data.invoice.client_telephone || "";

                if (Array.isArray(addressLines)) {
                    // First address line goes into the default input
                    const defaultInput = document.getElementById('address-line');
                    if (defaultInput) {
                        defaultInput.value = addressLines[0] || '';
                    }

                    // Remaining address lines go into dynamic fields
                    for (let i = 1; i < addressLines.length; i++) {
                        addAddressLine();
                        const allInputs = document.querySelectorAll('.address-line');
                        const lastInput = allInputs[allInputs.length - 1];
                        lastInput.value = addressLines[i];
                    }
                }

                // Set bank/account variables after selection
                const selectedOption = $("#bank-select option:selected");
                bank_name = selectedOption.data('bankName') || '';
                account_name = selectedOption.data('accountHolderName') || '';
                account_number = selectedOption.data('accountNumber') || '';
            
                // customer relational detail and payment details Id
                customer_relation_id = data.invoice.customer_id;
                selectedPaymentMethod = data.invoice.paymentMethod.id;
            
                // Inject sections/items here:
                document.getElementById('sections-container').innerHTML = '';
                sectionCount = 1;
                itemCount = 1;

                data.sections.forEach((sectionBlock, sectionIndex) => {
                    const labelKey = Object.keys(sectionBlock)[0];
                    const sectionData = sectionBlock[labelKey][0];
                    const discountPercent = parseFloat(sectionData.discount_percent) || 0;
                    const items = sectionData.items;

                    addSection(true);
                    const currentSectionId = sectionCount - 1;
                    const sectionEl = document.querySelector(`.invoice-section[data-section-id="${currentSectionId}"]`);

                    if (sectionEl) {
                        const discountInput = sectionEl.querySelector('.section-discount');
                        if (discountInput) discountInput.value = discountPercent;

                        items.forEach(item => {
                            addItemToSection(currentSectionId);
                            const rows = sectionEl.querySelectorAll('.item-row');
                            const row = rows[rows.length - 1];
                            if (row) {
                                row.querySelector('.product_item_id').value = item.product.id;
                                row.querySelector('.product_description').value = item.product.title;
                                row.querySelector('.quantity').value = item.quantity;
                                row.querySelector('.unit-price').value = item.unit_price;
                                row.querySelector('.item-total').value = parseFloat(item.total).toFixed(2);

                                row.querySelector('.quantity').dispatchEvent(new Event('input'));
                                row.querySelector('.unit-price').dispatchEvent(new Event('input'));
                            }
                        });
                    }
				});
				
				document.getElementById('page-loader').style.display = 'none';
				document.querySelector('.main-edit-container').style.display = 'block';
            }
        })
        .catch(error => {
            $.jGrowl("Error", {
                header: error
            });
        });
} else {
    $.jGrowl("Error", {
        header: 'No invoice ID specified in URL'
    });
}

function load_app_settings() {
	const apiUrl = base_url + 'api/load_app_settings';
	fetch(apiUrl)
		.then(response => {
			if (!response.ok) throw new Error('Network response was not ok');
			return response.json();
		})
        .then(data => { 
			company_detail.company_name = data.company_name;
			company_detail.company_tagline= data.company_tagline;
			company_detail.company_logo= data.company_logo;
			company_detail.company_icon_logo= data.company_icon_logo;
			company_detail.company_rc= data.company_rc;
			company_detail.company_email= data.company_email;
			company_detail.company_address= data.company_address;
			company_detail.company_telephone= data.company_telephone;
			company_detail.company_website= data.company_website;
			company_detail.company_country= data.company_country;
            company_detail.company_city = data.company_city;
            
		}).catch(error => {
			console.error('Error fetching users list:', error);
        });
    
}

load_app_settings();

function addSection(skipDefaultRow = false) {
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
				<input type="number" class="form-control section-discount" min="0" max="100" id="section-discount" value="${currentSectionId === 1 ? 20 : 10}" style="width:100px">
				<button class="btn btn-sm btn-danger remove-section" type="button" style="margin-left:10px">Remove Section</button>
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
		</table>`;

	container.appendChild(sectionDiv);

	const addItemBtn = sectionDiv.querySelector('.add-item');
	if (addItemBtn) {
		addItemBtn.addEventListener('click', function () {
			addItemToSection(currentSectionId);
		});
	}

	const removeSectionBtn = sectionDiv.querySelector('.remove-section');
	if (removeSectionBtn) {
		removeSectionBtn.addEventListener('click', function () {
			Swal.fire({
                title: 'Are you sure?',
                text: "This section and all its items will be removed.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, remove it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    sectionDiv.remove();
                    renumberSections();
                    $.jGrowl("Removed", { header: "Section removed successfully" });
                }
            });
            
		});
	}

    if (!skipDefaultRow) {
		addItemToSection(currentSectionId);
	}

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
        <td class="hidden" style="display:none; visibility:hidden;">
            <div class="form-group no-margin-bottom">
                <input type="number" class="form-control item-input product_item_id" min="1" value="" id="product_item_id_${rowNumber}"/>
            </div>
        </td>
        <td>
            <div class="form-group no-margin-bottom">
                <input type="number" class="form-control item-input quantity calculate" min="1" value="1" id="qty" style="width:70px"/>
            </div>
        </td>
        <td>
            <div class="form-group form-group-sm no-margin-bottom">
                <div class="d-flex">
                    <input class="form-control item-input product_description" placeholder="Description" id="description" name="product_description_${rowNumber}"/>&nbsp;or
                </div>
                <p class="item-select-link select_product">select a product</p>
            </div>
        </td>
        <td>
            <div class="input-group no-margin-bottom">
                <span class="input-group-addon">₦</span>
                <input type="number" class="unit-price form-control item-input calculate invoice_product_price required" aria-describedby="sizing-addon1" placeholder="0.00" id="unit_price" min="0" name="invoice_product_price_${rowNumber}"/>
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

	description = row.querySelector('.product_description').value || '';
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

function formatCurrency(amount) {
	return new Intl.NumberFormat('en-NG', {
		style: 'currency',
		currency: 'NGN',
		minimumFractionDigits: 2,
		maximumFractionDigits: 2
	}).format(amount).replace('NGN', 'NGN ');
}

function generateInvoice() {
	const errors = [];
	payload =  {};
	payload_items = [];
	$('.manually-added-empty-row-button').removeClass('hidden');

	const customerNameInput = document.querySelector(".customer_name");
	const invoiceNumberInput = document.querySelector(".customer_invoice_no");
	const customerTelephoneInput = document.querySelector(".customer_telephone");
	const invoiceDateInput = document.querySelector(".due_date");
	const clientNameInput = document.querySelector(".client_name");
	const clientCityInput = document.querySelector(".client_city");
	const clientMobileInput = document.querySelector(".client_telephone");

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

	invoice_date = util_.formatDateForInvoice(invoiceDateInput.value);
	invoice_number = invoiceNumberInput.value;
	client_name = clientNameInput.value;
	client_city = clientCityInput.value;
	client_telephone = clientMobileInput.value;
	const addressInputs = document.querySelectorAll('.address-line');
	client_address = Array.from(addressInputs)
	.map(input => input.value.trim())
	.filter(val => val !== "")
	.map(val => val.endsWith(",") ? val : val + ",") 
	.join("<br/>");
    
	let html = `
				<div id="preview_invoice">
					<div id="preview_invoice" style="font-family: Arial, sans-serif; padding: 20px;">
						<div style="display: flex; justify-content: space-between; align-items: flex-start;">
							<div style="width: 50%;">
								<h4 style="margin: 0; font-size:20px; display:flex">${company_detail.company_name}<span style="font-size: 14px;">${company_detail.company_rc}</span></h4>
								<span style="margin: 2px 0; font-size: 15px;" class="invoice-content">${company_detail.company_address}</span><br/>
								<span style="margin: 2px 0; font-size: 15px;" class="invoice-content">${company_detail.company_city}</span><br/>
								<span style="margin: 2px 0; font-size: 15px;" class="invoice-content">Phone: ${company_detail.company_telephone}</span><br/>
								<span style="margin: 2px 0; font-size: 15px;" class="invoice-content">E-mail: ${company_detail.company_email}</span><br/>
								<span style="margin: 2px 0; font-size: 15px;" class="invoice-content">Website: ${company_detail.company_website}</span>

								<br/><br/><br/>
								<span style="font-size:12px; font-weight:bold">INVOICE TO</span><br/>
								<span class="invoice-content">${client_name}<br/>${client_address}<br/>${client_city}<br/>TEL: ${client_telephone}</span>
							</div>
							<div style="width: 35%; text-align: center;">
								<img src="${base_url}${company_detail.company_logo}" alt="logo" style="max-height: 90px; margin: 0 auto;" />
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
						<td><span class="invoice-content">${customer_name}<br><span style="display:flex"><b>Tel:</b> ${customer_telephone}</span></span></td>
						<td><span class="invoice-content">${invoice_number}</span></td>
						<td><span class="invoice-content" style="display:flex; width:120px">Date: ${invoice_date}</span></td>
						<td></td>
						<td></td>
						<td><span class="invoice-content">Due on receipt</span></td>
					</tr>
				</tbody>
			</table><br/>
			<table class="section-table table table-bordered table-hover table-striped main_product_invoice_table" style="margin-bottom:50px !important">
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
				const sectionItems = [];
			
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
					</tr>`;

					const pid = row.querySelector('.product_item_id').value;
					sectionItems.push({
						"product_id": pid,
						"quantity": quantity,
						"unit_price": parseFloat(unitPrice).toFixed(2),
						"total": parseFloat(total).toFixed(2)
					});
				});
			
				// Add subtotal and discount rows
				html += `
				<tr class="subtotal-row total-row">
					<td colspan="2"></td>
					<td class="centered-discount">Sub-Total</td>
					<td></td>
					<td>${formatCurrency(sectionSubtotal)}</td>
				</tr>`;
			
				if (discount > 0) {
					const discountAmount = sectionSubtotal * (discount / 100);
					sectionSubtotal -= discountAmount;
				
					html += `
					<tr class="total-row">
						<td colspan="2"></td>
						<td class="centered-discount">Less: Discount ${discount}%</td>
						<td></td>
						<td>${formatCurrency(discountAmount)}</td>
					</tr>`;
				
				payload_items.push(
					{
						[`label${String.fromCharCode(65 + sectionIndex)}`]:
							[
							{
								"discount_percent": discount,
								"discount_amount":parseFloat(discountAmount).toFixed(2),
								"sub_total":parseFloat(sectionSubtotal+discountAmount).toFixed(2),
								"total_after_discount":parseFloat(sectionSubtotal).toFixed(2),
								"items": sectionItems
							}
						],
					}
				);
				
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
				</tr>`;
			
				// Add delivery cost if applicable
				const deliveryInput = document.getElementById('delivery_cost_input');
				deliveryCost = includeDeliveryCost && deliveryInput.value ? parseFloat(deliveryInput.value) : 0;

				// Always add section subtotal
				grandTotal += sectionSubtotal;

				// If delivery cost is added, add it too
				if (deliveryCost > 0) {
					grandTotal += deliveryCost;
				}
			});
			if (deliveryCost > 0) {
				html += `
				<tr class="total-row">
					<td colspan="2"></td>
					<td class="centered-discount">Cost of Delivery</td>
					<td></td>
					<td>${formatCurrency(deliveryCost)}</td>
				</tr>`;
			}
			// Add grand total
			html += `
				<tr class="final-total" id="final-total">
					<td colspan="4" style="text-align: right;">TOTAL DUE ${sections.length === 1 ? 'A' :
					Array.from({ length: sections.length }, (_, i) => String.fromCharCode(65 + i))
						.join(', ').replace(/, ([^,]*)$/, ' & $1')
				}</td>
					<td>${formatCurrency(grandTotal)}</td>
				</tr>
			</tbody>
			</table>
			<br/><br/>
			<div style="display: flex; justify-content: space-between; margin-top: 50px;">
				<div style="width: 45%;">
					<h4 style="margin-bottom: 10px;">Payment Details</h4>
					<span class="invoice-content">Account Name: ${account_name}</span><br/>
					<span class="invoice-content">Bank Name: ${bank_name}</span><br/>
					<span class="invoice-content">Account Number: ${account_number}</span>
				</div>
				<div style="width: 45%; text-align: right;">
					<h4 style="margin-bottom: 60px;">Authorized Signatory</h4>
					<div style="border-bottom: 1px solid #333; width: 180px; float: right;"></div>
				</div>
			</div>
			<div class="bottom_section" style="text-align: center;  margin-top:74px">
				<div class="print-footer">
					VAT Registration No. SW1AYIVT13002172479. Registered in Nigeria No – RC.172479. Tin -00847354-0001
				</div>
			</div>
		</div>
		<div class="">
			<button type="button" class="btn btn-flat btn-sm btn btn-primary manually-added-empty-row-button">Add Empty Row</button>
		</div>`;
	document.getElementById('invoice-container').innerHTML = html;
	document.getElementById('output-section').style.display = 'block';

    const addressLines = Array.from(addressInputs)
    .map(input => input.value.trim())
    .filter(val => val !== "");

    payload = {
        "invoice": {
            "invoice_number": invoice_number,
            "customer_id": customer_relation_id,
            "invoice_date": invoice_date,
            "shipping_via": '',
            "customer_reference": '',
            "client_name": client_name,
            "client_city": client_city,
            "client_telephone":client_telephone,
            "client_address": addressLines,
            "invoice_type": "Invoice",
            "terms": "Due on receipt",
            "total_amount": parseFloat(grandTotal).toFixed(2),
            "paymentMethod":selectedPaymentMethod,
            "deliveryCost": deliveryCost
        },
        "sections": payload_items
    }
}

document.getElementById('calculate').addEventListener('click', generateInvoice);

document.getElementById('add-section').addEventListener('click', () => {
    addSection(false)
});

document.getElementById('print-invoice').addEventListener('click', async () => {
    const result = await Swal.fire({
        title: 'What would you like to do?',
        icon: "question",
        showDenyButton: true,
        showCancelButton: true,
        showCloseButton: true,
        focusConfirm: false,
        confirmButtonText: 'Update and Print',
        denyButtonText: `Update Only`,
        cancelButtonText: 'Cancel',
        color: '#716add',
        background: '#fff',
        backdrop: `rgba(0,0,123,0.4)`,
    });
  
    if (result.isConfirmed) {
        await handleUpdateAndPrint();
    } else if (result.isDenied) {
        await update_invoice();
        $.jGrowl("Saved", {
            header: 'Invoice updated successfully'
        });
    } else if (result.isDismissed) {
        Swal.fire({
            title: "You cancelled or closed the dialog, This means no Action was taken to process update.",
            icon: "success",
            draggable: true
        });
    }
    

});

async function handleUpdateAndPrint() {
    document.getElementById('output-section').style.display = 'block';
    const invoiceTable = document.getElementById('preview_invoice').cloneNode(true);

    if (!invoiceTable) {
        $.jGrowl("Error", {
            header: 'Please generate the invoice first by clicking "Generate Invoice"'
        });
        return;
    }

    const printWindow = window.open('', '_blank');

    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Invoice for ${client_name}</title>
            <style>
                body {font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; font-size: 14px;line-height: 1.42857143;color: #333; margin: 20px; }
                table {border-spacing: 0;border-collapse: collapse;background-color: transparent;border-collapse: collapse; width: 100%;max-width: 100%; margin-bottom: 20px;}
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; }
                .total-row { font-weight: bold; }
                .final-total { font-weight: bold; font-size: 1.1em; background-color: #f2f2f2; }
                .centered-discount { text-align: center !important; }
                .invoice-content {color: #010101 !important;text-decoration: none;font-weight: 400 !important;font-size: 12px !important;}
                .print-footer {position: fixed;bottom: 0;left: 0;right: 0;text-align: center;font-size: 12px;margin-top:70px;background-color: #fff;}
                .payment-section {page-break-before: always;page-break-inside: avoid;break-inside: avoid;}
                .manually-added-empty-row-button{display:none; visibility:hidden}
                .remove-empty-row{display:none; visibility:hidden}
            </style>
        </head>
        <body>
            ${invoiceTable.outerHTML}
            <script>
                window.onload = function() {
                    window.print();
                    window.onafterprint = function() {
                        window.opener.postMessage('save_invoice', '*');
                        setTimeout(() => {
                            window.close();
                        }, 100);
                    };
                };
            </script>
        </body>
        </html>
    `);
    printWindow.document.close();

    window.addEventListener('message', async (event) => {
        if (event.data === 'save_invoice') {
            try {
                await update_invoice();
                Swal.fire({
                    title: "Invoice updated & printed successfully.",
                    icon: "success",
                    draggable: true
                });
            } catch (error) {
                Swa.fire({
                    title: "Failed",
                    text: error,
                    type: "error",
                    color: '#716add',
                    background: '#fff',
                    backdrop: `rgba(0,0,123,0.4)`,
                });
            }
        }
    });
}

async function update_invoice() { 
	try {
        const request = await xhrClient( base_url + `api/update_invoice/${invoiceId}`, 'PUT', {
            'Content-Type': 'application/json',
        }, payload);
        Swal.fire('Success', request.message, 'success');
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

$(document).on('click', ".button-payment-details", function (e) {
	e.preventDefault();
  
	const selectedOption = $("#bank-select option:selected");
  
	const account_name = selectedOption.data('accountHolderName');
	const bank_name = selectedOption.data('bankName');
	const account_number = selectedOption.data('accountNumber');
	selectedPaymentMethod = selectedOption.data('bankContainerId');
	
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

$(document).on('click', ".close-payment-details", function (e) {
	e.preventDefault;
	$('#select-add-payment-details').modal('hide');
	$("#bank-select-group").removeClass("has-error");
	$('#select-add-payment-details').modal('hide');
	$('.help-block-msg').show().html('');
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
    customer_relation_id = userId;
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
    const product_id = $(this).data('product-id');
    const product_title = $(rowData[1]).text();
    const productPriceSpan = $(rowData[3]);
    const product_price = productPriceSpan.attr('data-product-price');
    description = product_title;
    unitPrice = product_price;
    currentRow.find('.product_description').val(description);
    currentRow.find('.invoice_product_price').val(unitPrice);
    currentRow.find('.product_item_id').val(product_id);
    const product_row = currentRow[0];
    const quantityInput = product_row.querySelector('.quantity');
    const unitPriceInput = product_row.querySelector('.unit-price');
    const totalInput = product_row.querySelector('.item-total');
    const calculateItemTotal = () => {
      const quantity = parseFloat(quantityInput.value) || 0.00;
      unitPrice = parseFloat(product_row.querySelector('.unit-price').value) || 0.00;
      const total = quantity * unitPrice;
      totalInput.value = formatCurrency(total, true);
    };
    quantityInput.addEventListener('input', calculateItemTotal);
    unitPriceInput.addEventListener('input', calculateItemTotal);
    calculateItemTotal(); 
    $('#select-stock-product').modal('hide');
});

$(document).on('click', ".manually-added-empty-row-button", function (e) {
	e.preventDefault();

	const invoiceTable = document.querySelector('.main_product_invoice_table tbody');
	if (!invoiceTable) return;

	const finalTotalRow = invoiceTable.querySelector('.final-total');

	const emptyRow = document.createElement('tr');
	emptyRow.classList.add('manually-added-empty-row');
	emptyRow.innerHTML = `
		<td colspan="4"></td>
		<td>
			<button type="button" class="btn btn-sm btn-danger remove-empty-row">Remove</button>
		</td>
	`;

	invoiceTable.insertBefore(emptyRow, finalTotalRow);
});

// Event handler for removing the manually added row
$(document).on('click', '.remove-empty-row', function () {
	$(this).closest('tr').remove();
});

$(document).on('click', '.add-delivery-cost', function (e) {
    e.preventDefault();

    const $button = $(this);
    let $input = $('#delivery_cost_input');

    if ($input.length === 0) {
        // Dynamically add input if it doesn't exist
        const inputField = `
            <div id="delivery_cost_wrapper" style="margin-top: 10px;">
                <div class="form-group">
                    <label for="address" style="color:black">Delivery Cost <small>(Optional)</small>:*</label>
                    <input type="number" step="0.01" min="0" id="delivery_cost_input" 
                        class="form-control" placeholder="Enter Cost of Delivery" 
                        style="width: 200px;" />
                </div>
            </div>
        `;
        $button.after(inputField);
        includeDeliveryCost = true;
        $button.html('<i class="fa fa-trash"></i> Remove Cost of Delivery');
    } else {
        // Toggle visibility/remove
        $('#delivery_cost_wrapper').remove();
        includeDeliveryCost = false;
        $button.html('<i class="fa fa-usd"></i> Add Cost of Delivery');
    }
});

function addAddressLine() {
    const rowContainer = document.getElementById('address_row_container');

    const colDiv = document.createElement('div');
    colDiv.classList.add('col-md-12', 'mt-2', 'mb-2');

    // Create a flex container
    const flexWrapper = document.createElement('div');
    flexWrapper.className = 'd-flex gap-3 align-items-center';

    // Input field
    const input = document.createElement('input');
    input.type = 'text';
    input.placeholder = 'Address Line';
    input.className = 'form-control mr-2 copy-input address-line';

    // Remove button with glyphicon
    const removeBtn = document.createElement('button');
    removeBtn.className = 'remove-item btn btn-danger btn-xs delete-row';
    removeBtn.type = 'button';
    removeBtn.innerHTML = '<i class="fa fa-trash"></i>';
    removeBtn.onclick = () => {
        rowContainer.removeChild(colDiv);
        adjustAddressInputWidths();
    };

    // Append input and button to flex container
    flexWrapper.appendChild(input);
    flexWrapper.appendChild(removeBtn);

    // Add to column
    colDiv.appendChild(flexWrapper);

    // Add to row
    rowContainer.appendChild(colDiv);

    // Adjust widths
    adjustAddressInputWidths();
}

function adjustAddressInputWidths() {
    const rowContainer = document.getElementById('address_row_container');
    const cols = rowContainer.querySelectorAll('.col-md-12, .col-md-6');

    cols.forEach(col => {
        col.classList.remove('col-md-12', 'col-md-6');
    });

    const newClass = cols.length === 1 ? 'col-md-12' : 'col-md-6';

    cols.forEach(col => {
        col.classList.add(newClass);
    });
}

$(document).on('click', '.add_new_address_line', function () {
	addAddressLine();
});