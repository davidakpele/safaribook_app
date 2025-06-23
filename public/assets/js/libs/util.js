import xhrClient from "./xhrClient"

class Util {

    reformate_date_back_to_normal(date_string) {
        const months = {
            "January": "01", "February":"02", "March":"03", "April":"04", "May":"05", "June":"06",
            "July": "07", "August": "08", "September": "09", "October": "10", "November": "11", "December": "12"
        };
        
        const parts = date_string.replace('st', '').replace('nd', '').replace('rd', '').replace('th', '').split(' ');
        const day = parts[0];
        const month = months[parts[1].replace(',', '')];
        const year = parts[2];

        return `${day.padStart(2, '0')}/${month}/${year}`;
    }

    formatDateTime(dateString) {
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
    
    formatCurrency(amount) {
        return new Intl.NumberFormat('en-NG', {
            style: 'currency',
            currency: 'NGN',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(amount).replace('NGN', 'NGN ');
    }

    getUrlParameter(name) {
        name = name.replace(/[\[\]]/g, '\\$&');
        const regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)');
        const results = regex.exec(window.location.href);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, ' '));
    }

    getPaymentDetails() {
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
                        option.dataset.bankContainerId = bank.id;
    
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
    
    getUsersList() {
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
                    const formattedDate = this.formatDateTime(user.created_at);
    
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
    
    getInvoiceNumber() {
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
    
    getBookList() {
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
                        `<span style="color:#49474; font-weight:normal" data-product-price=${book.sale_price}>${this.formatCurrency(book.sale_price)}</span>`,
                        `<div class="btn btn-primary btn-xs select-product" data-product-id="${book.id}">Select</div>`
                    ]).draw();
                    item_number++;
                });
            })
            .catch(error => {
                console.error('Error fetching users list:', error);
            });
    }

    async getInvoiceById(id) {
        if (!id) {
            $.jGrowl("Error", {
                header: 'No invoice ID found in URL'
            });
            return;
        }
    
        const apiUrl = `${base_url}api/invoice/${id}`;
        
        try {
            const response = await fetch(apiUrl);
            if (!response.ok) {
                $.jGrowl("Error", {
                    header: 'Network response was not ok'
                });
                return;
            }
            const data = await response.json();
            return data; 
        } catch (error) {
            $.jGrowl("Error", {
                header: 'Error fetching invoice:', error
            });
            return;
        }
    }

    formatDateForInvoice(dateString) {
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
}


export default Util;