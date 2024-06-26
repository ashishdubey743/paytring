
let paymentFormList;
let baseurl = "https://fa61-59-163-196-138.ngrok-free.app";
let storeUrl = location.origin;
let cartid;
let shippingAddress;
let billingAddress;
let cart;
const checkForPaymentForm = () => {
  paymentFormList = document.querySelector('[data-test="payment-form"] ul');
  if (paymentFormList) {
    clearInterval(intervalId);
    const newListItem = document.createElement('li');
    newListItem.classList.add('form-checklist-item', 'optimizedCheckout-form-checklist-item');

    const innerHTML = `
    <div class="form-checklist-header">
        <div class="form-field">
        <input id="radio-paytring" type="radio" class="form-checklist-checkbox optimizedCheckout-form-checklist-checkbox" name="paymentProviderRadio" value="paytring" >
        <label for="radio-paytring" class="form-label optimizedCheckout-form-label"> Paytring </label>
        </div>
    </div>`;

    newListItem.innerHTML = innerHTML;
    paymentFormList.appendChild(newListItem);

    $("#radio-paytring").click(function(){
        
        if ($(this).is(":checked")) {
            // code for paytring click
            document.getElementById('checkout-payment-continue').setAttribute('type','button');
            document.getElementById('checkout-payment-continue').setAttribute('onclick','generate_paytring_payment_req();');
        }
    });

    $("input[type='radio']:not(#radio-paytring)").click(function(){
        if ($(this).is(":checked")) {
            document.getElementById('checkout-payment-continue').setAttribute('type','submit');
            document.getElementById('checkout-payment-continue').setAttribute('onclick','');
        }
    });

  }
};

const intervalId = setInterval(checkForPaymentForm, 2000);
    var csrfToken = "{{ csrf_token() }}";



function generate_paytring_payment_req(){
    $.ajax({
        url: baseurl+"/api/generate_paytring_payment_req",
        type: "POST",
        data: { _token : csrfToken, name: shippingAddress.firstName+ ' '+ shippingAddress.lastName , email:shippingAddress.email , phone:shippingAddress.phone , amount: cart[0].cartAmount, cart:cart[0], shippingAddress:shippingAddress, billingAddress:billingAddress, cartid:cart[0].id },
        dataType: "json",
        success: function(response){
            if (response.url) {
            // const newWindow = window.open(response.url, "_blank");
            // newWindow.focus();
            location.href=response.url;
            } else {
            console.error("Error: Missing 'url' property in response");
            }
        }
    });
}

async function get_cart(){
    
    let options = {
        method: 'GET',
        headers: {Accept: 'application/json', 'Content-Type': 'application/json'}
      };
      
     cart = await fetch(storeUrl+'/api/storefront/carts', options);
    cart = await cart.json();
    if(cart.length > 0){
        cartid = cart[0].id;
        const module = await checkoutKitLoader.load('checkout-sdk');
        const service = module.createCheckoutService();
        const state = await service.loadCheckout(cartid);
        shippingAddress = state.data.getShippingAddress();
        billingAddress = state.data.getBillingAddress()
    }
}
get_cart();

if(document.getElementById('checkout-shipping-continue')){
    document.getElementById('checkout-shipping-continue').onclick = function(){
        location.reload();
    }
}




const checkForCustomerForm = () => {
    let formContainer = document.getElementById('checkout-page-container');
    if(formContainer){
        clearInterval(checkout_customer_form_interval_id);
        if(document.getElementById('checkout-customer-continue')){
            document.getElementById('checkout-customer-continue').onclick = function(){
                if(document.getElementById('email').value.includes('.com') && document.getElementById('email').value.includes('@')){
                    const checkForShippingForm = () => {
                        let shippingFormContainer = document.querySelector('.checkout-step--shipping');
                        if(shippingFormContainer){
                            clearInterval(checkout_step_shipping_interval);
                            // location.reload();
                            // console.log(document.getElementById('checkout-shipping-continue'));
                            // alert();
                            if(document.getElementById('checkout-shipping-continue')){
                                document.getElementById('checkout-shipping-continue').onclick = function() {
                                    if($('#firstNameInput').val() != '' && $('#lastNameInput').val() != '' && $('#firstNameInput').val() != '' && $('#addressLine1Input').val() != '' && $('#cityInput').val() != '' && $('#countryCodeInput').val() != '' && $('#postCodeInput').val() != ''){
                                        const checkForPaymentForm = async () => {
                                            let paymentForm = document.querySelector('[data-test="payment-form"]');
                                            if(paymentForm){
                                                clearInterval(checkForPaymentForm);
                                                // location.reload();
                                                // const module = await checkoutKitLoader.load('checkout-sdk');
                                                // const service = module.createCheckoutService();
                                                // const state = await service.loadCheckout(cartid);
                                                // shippingAddress = state.data.getShippingAddress();
                                                // billingAddress = state.data.getBillingAddress();

                                                const checkSDK = async () => {
                                                    const module = await checkoutKitLoader.load('checkout-sdk');
                                                    const service = module.createCheckoutService();
                                                    const state = await service.loadCheckout(cartid);
                                                    if(state){
                                                        clearInterval(checkout_sdk_interval);
                                                        shippingAddress = state.data.getShippingAddress();
                                                        billingAddress = state.data.getBillingAddress();
                                                    }
                                                }
                                                const checkout_sdk_interval = setInterval(checkSDK, 2000);
                                            }
                                        }
                                        const payment_form_interval_id = setInterval(checkForPaymentForm, 2000);
                                    }
                                }
                            }
                        }
                    }
                    const checkout_step_shipping_interval = setInterval(checkForShippingForm, 2000);
                }
            }
        }
    }
}
const checkout_customer_form_interval_id = setInterval(checkForCustomerForm, 2000);



$("body").on("click", "#checkout-shipping-continue, #checkout-billing-continue", function() {
    setTimeout(() => { location.reload() }, 5000);
    
});

// $("body").on("click", '.stepHeader is-clickable', function() {
//   alert();
//   });
  