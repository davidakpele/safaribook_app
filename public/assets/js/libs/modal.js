
export default class Modal{
    controller() {
        $(document).on('click', ".select-customer", function (e) {
            e.preventDefault();
            $('#select-customer-users').modal('show');
            return false;
        });

        $(document).on('click', ".payment-details", function (e) {
            e.preventDefault();
            $('#select-add-payment-details').modal('show');
            return false;
        });

        $(document).on('click', ".close-select-customer", function (e) {
            e.preventDefault();
            $('#select-customer-users').modal('hide');
            return false;
        });

        $(document).on('click', ".close-select-stock-product", function (e) {
            e.preventDefault();
            $('#select-stock-product').modal('hide');
            return false;
        }); 
        
    }
}
