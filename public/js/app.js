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
    
    




