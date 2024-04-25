
<html lang="en">
   <head>
      <link rel="stylesheet" href="https://cdn11.bigcommerce.com/shared/microapp/checkout/dist/checkout-1c08c47a.css">
      <title>Order Confirmation - test</title>
      <meta name="platform" content="bigcommerce.stencil">
      <link href="https://cdn11.bigcommerce.com/r-c62647d0d3134613d7bfba4e687ca8feeaf8de52/img/bc_favicon.ico" rel="shortcut icon">
      <base href="/" target="_top">
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
      <link href="https://cdn11.bigcommerce.com/shared/css/open_sans-d6a25005e2cc4260148ad7d363953fc5f6ba209a.css" rel="stylesheet" type="text/css">
      <script type="text/javascript">
         var BCData = {"csrf_token":"e05c331f6c32aa46571ee391361ad9d41e498d860693c6e95af0892418c701bf"};
      </script>
      <link data-stencil-stylesheet="" href="https://cdn11.bigcommerce.com/s-ekjie9tctd/stencil/04a03260-b13e-013c-5cec-56639750d060/css/optimized-checkout-c7cc38c0-e039-013c-e0e1-0217943b7735.css" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css?family=Montserrat:700,500,400%7CKarla:400&amp;display=swap" rel="stylesheet">
      <script type="text/javascript">
         window.language = {"locale":"en","locales":{},"translations":{}};
      </script>
      <script src="https://checkout-sdk.bigcommerce.com/v1/loader.js" defer=""></script><script src="https://code.jquery.com/jquery-3.7.1.js" defer=""></script><script src="https://pay.paytring.com/iframe.v1.0.0.js" defer=""></script>
      <link as="script" rel="prefetch" href="https://cdn11.bigcommerce.com/shared/microapp/checkout/dist/134-8367947e.js">
      <link as="script" rel="prefetch" href="https://cdn11.bigcommerce.com/shared/microapp/checkout/dist/51-2ffee65b.js">
      <link as="script" rel="prefetch" href="https://cdn11.bigcommerce.com/shared/microapp/checkout/dist/652-eb59eb8a.js">
      <link as="script" rel="prefetch" href="https://cdn11.bigcommerce.com/shared/microapp/checkout/dist/850-83a0e1bc.js">
      <link as="script" rel="prefetch" href="https://cdn11.bigcommerce.com/shared/microapp/checkout/dist/904-08425d6b.js">
      <link as="script" rel="prefetch" href="https://cdn11.bigcommerce.com/shared/microapp/checkout/dist/billing-fb3ae6c6.js">
      <link as="script" rel="prefetch" href="https://cdn11.bigcommerce.com/shared/microapp/checkout/dist/cart-summary-drawer-ded8a756.js">
      <link as="script" rel="prefetch" href="https://cdn11.bigcommerce.com/shared/microapp/checkout/dist/cart-summary-7c720193.js">
      <link as="script" rel="prefetch" href="https://cdn11.bigcommerce.com/shared/microapp/checkout/dist/order-summary-drawer-b4e28d6c.js">
      <link as="script" rel="prefetch" href="https://cdn11.bigcommerce.com/shared/microapp/checkout/dist/order-summary-4647fd2d.js">
      <link as="script" rel="prefetch" href="https://cdn11.bigcommerce.com/shared/microapp/checkout/dist/payment-2b00b240.js">
      <link as="script" rel="prefetch" href="https://cdn11.bigcommerce.com/shared/microapp/checkout/dist/shipping-d4d05566.js">
      <link as="style" rel="prefetch" href="https://cdn11.bigcommerce.com/shared/microapp/checkout/dist/billing-5cb3e9e6.css">
      <link as="style" rel="prefetch" href="https://cdn11.bigcommerce.com/shared/microapp/checkout/dist/cart-summary-drawer-5084b1db.css">
      <link as="style" rel="prefetch" href="https://cdn11.bigcommerce.com/shared/microapp/checkout/dist/cart-summary-5084b1db.css">
      <link as="style" rel="prefetch" href="https://cdn11.bigcommerce.com/shared/microapp/checkout/dist/payment-1787d317.css">
      <link as="style" rel="prefetch" href="https://cdn11.bigcommerce.com/shared/microapp/checkout/dist/shipping-58a96450.css">
   </head>
   <body style="">
      <header class="checkoutHeader optimizedCheckout-header">
         <div class="checkoutHeader-content">
            <h1 class="is-srOnly">Checkout</h1>
            <h2 class="checkoutHeader-heading">
               <a class="checkoutHeader-link" href="https://test-e3.mybigcommerce.com/">
               <span class="header-logo-text">test</span>
               </a>
            </h2>
         </div>
      </header>
      <script>
         window.checkoutVariantIdentificationToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczovL3Rlc3QtZTMubXliaWdjb21tZXJjZS5jb20iLCJpYXQiOjE3MTM5NDQ1MTMsImRvbWFpbiI6eyJjYXJ0SWQiOiIxOTA2NThjNi1kMTM4LTQ3ZDktOGQyYy1mMTIzY2JmY2RmMjUiLCJjaGVja291dFZhcmlhbnQiOiJvcHRpbWl6ZWRfb25lX3BhZ2VfY2hlY2tvdXQifX0.3UueW0rqMNd9kUhlxGu_9H0OFKaM-6Be-QGP3PzZaoY';
      </script>
      <script>
         document.body.style.display = 'block';
      </script>
      <div id="checkout-app">
         <div class="layout optimizedCheckout-contentPrimary">
            <div class="layout-main">
               <div class="orderConfirmation">
                  <h1 class="optimizedCheckout-headingPrimary" data-test="order-confirmation-heading">Thank you {{ $order->billing_address->first_name }}!</h1>
                  <section class="orderConfirmation-section">
                     <p data-test="order-confirmation-order-number-text"><span>Your order number is <strong>{{ $order->id }}</strong></span></p>
                     <p data-test="order-confirmation-order-status-text"><span>We've received your order and are processing your payment. Once the payment is verified, your order will be completed. We will send you an email when it's completed. Please note, this process may take a few minutes depending on the processing times of your chosen method. If you have any questions about your purchase, email us at <a target="_top" href="mailto:karrar82001@gmail.com?Subject=Order 153">karrar82001@gmail.com</a>.</span></p>
                  </section>
                  <!-- <fieldset class="form-fieldset">
                     <legend class="form-legend optimizedCheckout-headingSecondary">Create an account for a faster checkout in the future</legend>
                     <div class="form-body">
                        <div>
                           <form class="guest-signup form" novalidate="">
                              <div class="form-field"><label for="password" id="password-label" class="form-label optimizedCheckout-form-label">Password <small>7-character minimum, case sensitive</small></label><input id="password" type="password" class="form-input optimizedCheckout-form-input" name="password" value=""></div>
                              <div class="form-field"><label for="confirmPassword" id="confirmPassword-label" class="form-label optimizedCheckout-form-label">Confirm Password</label><input id="confirmPassword" type="password" class="form-input optimizedCheckout-form-input" name="confirmPassword" value=""></div>
                              <div class="form-actions"><button id="createAccountButton" class="button button--primary optimizedCheckout-buttonPrimary" type="submit">Create Account</button></div>
                           </form>
                        </div>
                     </div>
                  </fieldset> -->
                  <div class="continueButtonContainer">
                     <form action="https://test-e3.mybigcommerce.com" method="get" target="_top"><button class="button button--tertiary optimizedCheckout-buttonSecondary" type="submit">Continue Shopping »</button></form>
                  </div>
               </div>
            </div>
            <aside class="layout-cart">
               <article class="cart optimizedCheckout-orderSummary" data-test="cart">
                  <header class="cart-header">
                     <h3 class="cart-title optimizedCheckout-headingSecondary">Order Summary</h3>
                     <!-- <a class="cart-header-link" href="#" id="cart-print-link">
                        <div class="icon">
                           <svg height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                              <path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"></path>
                           </svg>
                        </div>
                        Print
                     </a> -->
                  </header>
                  <section class="cart-section optimizedCheckout-orderSummary-cartSection">
                     <h3 class="cart-section-heading optimizedCheckout-contentPrimary" data-test="cart-count-total">1 Item</h3>
                     <ul aria-live="polite" class="productList">
                        @foreach($products as $product)
                        <li class="productList-item is-visible">
                           <div class="product" data-test="cart-item">
                              <figure class="product-column product-figure"><img alt="{{ $product->name }}" data-test="cart-item-image" src="{{ $product->image_url }}"></figure>
                              <div class="product-column product-body">
                                 <h4 class="product-title optimizedCheckout-contentPrimary" data-test="cart-item-product-title">{{ $product->quantity }} x {{ $product->name }}</h4>
                              </div>
                              <div class="product-column product-actions">
                                 <div class="product-price optimizedCheckout-contentPrimary" data-test="cart-item-product-price">${{ number_format($product->price_inc_tax, 2) }}</div>
                              </div>
                           </div>
                        </li>
                        @endforeach
                     </ul>
                  </section>
                  <section class="cart-section optimizedCheckout-orderSummary-cartSection">
                     <div data-test="cart-subtotal">
                        <div aria-live="polite" class="cart-priceItem optimizedCheckout-contentPrimary cart-priceItem--subtotal"><span class="cart-priceItem-label"><span data-test="cart-price-label">Subtotal  </span></span><span class="cart-priceItem-value"><span data-test="cart-price-value">{{ number_format($order->subtotal_inc_tax, 2) }}</span></span></div>
                     </div>
                     <div data-test="cart-shipping">
                        <div aria-live="polite" class="cart-priceItem optimizedCheckout-contentPrimary"><span class="cart-priceItem-label"><span data-test="cart-price-label">Shipping  </span></span><span class="cart-priceItem-value"><span data-test="cart-price-value">{{ number_format($order->shipping_cost_inc_tax, 2) }}</span></span></div>
                     </div>
                     <div data-test="cart-taxes">
                        <div aria-live="polite" class="cart-priceItem optimizedCheckout-contentPrimary"><span class="cart-priceItem-label"><span data-test="cart-price-label">Tax  </span></span><span class="cart-priceItem-value"><span data-test="cart-price-value">${{ number_format($order->subtotal_tax, 2) }}</span></span></div>
                     </div>
                  </section>
                  <section class="cart-section optimizedCheckout-orderSummary-cartSection">
                     <div data-test="cart-total">
                        <div aria-live="polite" class="cart-priceItem optimizedCheckout-contentPrimary cart-priceItem--total"><span class="cart-priceItem-label"><span data-test="cart-price-label">Total (SGD)  </span></span><span class="cart-priceItem-value"><span data-test="cart-price-value">${{ number_format($order->total_inc_tax, 2) }}</span></span></div>
                     </div>
                  </section>
               </article>
            </aside>
         </div>
      </div>
      <script src="https://cdn11.bigcommerce.com/shared/microapp/checkout/dist/loader-1.474.2.js"></script>
      <!-- <script>
         checkoutLoader.loadFiles({ publicPath: 'https://cdn11.bigcommerce.com/shared/microapp/checkout/dist' })
             .then(function(app) {
                 document.body.style.display = '';
         
                                     app.renderOrderConfirmation({
                         orderId: '153',
                         containerId: 'checkout-app',
                         sentryConfig: {"release":"checkout-js@1.474.2","whitelistUrls":["https:\/\/cdn11.bigcommerce.com\/shared\/microapp\/checkout\/dist"],"dsn":"https:\/\/418e8e81a209403e9c8740c4fdbff5a6@sentry.io\/1542560","environment":"production","normalizeDepth":5,"tags":{"environment":"production","tier":2},"userId":"1003172969-1"},
                         
                     });
                             });
      </script> -->
      <script src="https://cdn11.bigcommerce.com/shared/microapp/checkout/dist/runtime-af5f8323.js"></script><script src="https://cdn11.bigcommerce.com/shared/microapp/checkout/dist/700-d575b870.js"></script><script src="https://cdn11.bigcommerce.com/shared/microapp/checkout/dist/608-6573dede.js"></script><script src="https://cdn11.bigcommerce.com/shared/microapp/checkout/dist/375-d1c6c471.js"></script><script src="https://cdn11.bigcommerce.com/shared/microapp/checkout/dist/985-67f1886c.js"></script><script src="https://cdn11.bigcommerce.com/shared/microapp/checkout/dist/checkout-85306e41.js"></script>
      <script type="text/javascript" src="https://cdn11.bigcommerce.com/shared/js/csrf-protection-header-95f3d9ac8c049e3ed132c83a168cf1d6a8ed0237.js"></script>
      <script type="text/javascript" src="https://cdn11.bigcommerce.com/shared/js/csrf-protection-header-95f3d9ac8c049e3ed132c83a168cf1d6a8ed0237.js"></script>
      <script type="text/javascript" src="https://cdn11.bigcommerce.com/r-c62647d0d3134613d7bfba4e687ca8feeaf8de52/javascript/visitor_stencil.js"></script>
      <script src="https://7962-59-163-196-138.ngrok-free.app/js/app.js" defer=""></script>
      <script src="https://checkout-sdk.bigcommerce.com/v1/checkout-sdk-6773e10f.js"></script>
      <div class="ReactModalPortal"></div>
   </body>
</html>
