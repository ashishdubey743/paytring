// // Define a function to be executed if the transaction fails
// function failFun(order_id){
//     console.log("failed");
//         // This function executes if the transaction fails
//         // You will receive the order id as an argument
//         // You can use the Fetch API to check order details
//     }
    
//     // Define a function to be executed if the transaction is successful
//     function successFun(order_id){
//         // This function executes if the transaction is successful
//         // You will receive the order id as an argument
//         // You can use the Fetch API to check order details
//     }
    
//     // Define a function to log events
//     function event(resp) {
//         console.log("Event Name: " + resp.event_name);
//         console.log("Event Value: " + resp.event_value);
//     }
    
//     // Define options for the Paytring Iframe
//     var options = {
//         "order_id": 100, // Replace with the order id you received when creating the order
//         "success": successFun,
//         "failed": failFun,
//         "events": event // This function executes on various events, such as when the user selects a payment option, changes the payment option, or clicks the payment proceed button
//     };
    
//     // Create an instance of the Paytring class with the specified options
//     var paytring2 = new Paytring(options);
    
//     // Open the Paytring Iframe
//     paytring2.open();
    
    


// setTimeout(() => {
// console.log(document.querySelector('[data-test="payment-form"] ul'))
    
// }, 7000);


let paymentFormList;
let baseurl = "https://d84c-59-163-196-138.ngrok-free.app";
const checkForPaymentForm = () => {
  paymentFormList = document.querySelector('[data-test="payment-form"] ul');
  if (paymentFormList) {
    clearInterval(intervalId);
    const newListItem = document.createElement('li');
    newListItem.classList.add('form-checklist-item', 'optimizedCheckout-form-checklist-item');

    const innerHTML = `
    <div class="form-checklist-header">
        <div class="form-field">
        <input id="radio-paytring" type="radio" class="form-checklist-checkbox optimizedCheckout-form-checklist-checkbox" name="paymentProviderRadio" value="paytring" onClick="generate_paytring_payment_req();">
        <label for="radio-paytring" class="form-label optimizedCheckout-form-label"> Paytring </label>
        </div>
    </div>`;

    newListItem.innerHTML = innerHTML;
    paymentFormList.appendChild(newListItem);
  }
};

const intervalId = setInterval(checkForPaymentForm, 2000);
    var csrfToken = "{{ csrf_token() }}";



function generate_paytring_payment_req(){
    $.ajax({
        url: baseurl+"/api/generate_paytring_payment_req",
        type: "POST",
        data: { _token : csrfToken },
        dataType: "json",
        success: function(response){
            if (response.url) { // Check if 'url' property exists in response
            const newWindow = window.open(response.url, "_blank"); // Open in new tab
            newWindow.focus(); // Focus the newly opened window
            } else {
            console.error("Error: Missing 'url' property in response");
            }
        }
    });
}