import xhrClient from "./libs/xhrClient"


$(document).on('click', "#btn_add_product", async function (e) {
    e.preventDefault();
    $(".error-message").remove();

    const p_title = $(".product_title").val().trim();
    const p_binding = $(".product_binding").val().trim();
    const formatted_price = $(".product_price").val().trim();
    const p_price = getRawPrice(formatted_price); 

    let isValid = true;
    if (!p_title) {
        isValid = false;
        $(".product_title").after('<small class="error-message text-danger">Book title is required.</small>');
    }

    // if (!formatted_price) {
    //     isValid = false;
    //     $(".product_price_container").after('<small class="error-message text-danger">Product price is required.</small>');
    // } else if (isNaN(p_price)) {
    //     isValid = false;
    //     $(".product_price_container").after('<small class="error-message text-danger">Enter a valid number.</small>');
    // }
    // if (!p_binding) {
    //     isValid = false;
    //     if ($(".product_binding").hasClass("select2-hidden-accessible")) {
    //         $(".product_binding").next('.select2').after('<small class="error-message text-danger">Binding is required.</small>');
    //     } else {
    //         $(".product_binding").after('<small class="error-message text-danger">Binding is required.</small>');
    //     }
    // }


    if (!isValid) return;

    const data = {
        product_title: p_title,
        product_binding: p_binding,
        product_price: p_price
    };

    const state = $(".form_state").val();
    const productId = $("#product_id").val();
    let endpoint = '';
    let method = '';

    if (state === 'edit') {
        endpoint = base_url + 'api/edit_product/' + productId;
        method = 'PUT';
    } else {
        endpoint = base_url + 'api/add_product';
        method = 'POST';
    }
    try {
        const request = await xhrClient(endpoint, method, {
            'Content-Type': 'application/json',
        }, data);
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


$(document).on('input', '.product_price', function () {
    const input = $(this);
    let raw = input.val().replace(/,/g, '');
    if (!/^\d*\.?\d*$/.test(raw)) {
        return; 
    }
    const parts = raw.split('.');
    let integerPart = parts[0];
    const decimalPart = parts[1] || '';
    integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    const formatted = decimalPart.length > 0 ? `${integerPart}.${decimalPart}` : integerPart;

    input.val(formatted);
});

function getRawPrice(formattedValue) {
    const raw = formattedValue.replace(/,/g, ''); 
    const num = parseFloat(raw);
    return isNaN(num) ? "0.00" : num.toFixed(2);
}


// Format number with comma separators
function formatWithComma(amount) {
    return amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
