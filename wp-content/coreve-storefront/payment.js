/* Native Razorpay payment handoff with reliable DOM readiness. */
(function () {
 'use strict';
 function init() {
  var data = window.razorpay_wc_checkout_vars;
  if (!data) return;
  if (data === 'checkoutForm' || data[0] === 'checkoutForm' || data === 'routeAnalyticsForm' || data[0] === 'routeAnalyticsForm') {
   var hosted = document.getElementById(data === 'routeAnalyticsForm' || data[0] === 'routeAnalyticsForm' ? 'routeAnalyticsForm' : 'checkoutForm');
   if (hosted) hosted.submit();
   return;
  }
  var button = document.getElementById('btn-razorpay');
  var form = document.forms.namedItem('razorpayform');
  if (!button || !form) return;
  button.textContent = 'Continue to payment';
  var status = document.createElement('p');
  status.setAttribute('role', 'status');
  status.textContent = 'Opening secure payment…';
  button.parentNode.insertBefore(status, button);
  var client;
  function retry(message) { button.disabled = false; status.textContent = message; }
  function open(event) {
   if (event) event.preventDefault();
   if (!window.Razorpay) { retry('Payment could not load. Please check your connection and try again.'); return; }
   try {
    if (!client) {
     data.modal = Object.assign({}, data.modal, { ondismiss: function () { retry('Payment window closed. Continue when you are ready.'); } });
     data.handler = function (payment) {
      button.disabled = true;
      var cancel = document.getElementById('btn-razorpay-cancel');
      if (cancel) cancel.disabled = true;
      document.getElementById('razorpay_payment_id').value = payment.razorpay_payment_id;
      document.getElementById('razorpay_signature').value = payment.razorpay_signature;
      status.textContent = 'Verifying your payment…';
      form.submit();
     };
     client = new window.Razorpay(data);
    }
    button.disabled = true;
    status.textContent = 'Complete your payment in the secure window.';
    client.open();
   } catch (error) { retry('Please continue to payment to try again.'); }
  }
  button.addEventListener('click', open);
  open();
 }
 if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init, { once: true });
 else init();
})();
