jQuery(function($){
    const stripe = Stripe('pk_test_q4woJySFwukWaFNfiIcqVd5f00kRa3z2L5');

    const elements = stripe.elements({
        fonts: [
          {
            cssSrc: 'https://fonts.googleapis.com/css?family=Titillium+Web:300,400,600,700'

          }
        ]
      });
    const cardElement = elements.create('card',{
    style: {
        base: {
        iconColor: '#000000',
        color: '#000000',
        fontWeight: 500,
        fontFamily:  '"Titillium Web", sans-serif',
        fontSize: '16px',
        border: '1px solid #444444',
        fontSmoothing: 'antialiased',
        ':-webkit-autofill': {
            color: '#999999',
            fontFamily:  '"Titillium Web", sans-serif',
        },
        '::placeholder': {
            color: '#999999',
            fontFamily:  '"Titillium Web," sans-serif',
        },
        },
        invalid: {
        iconColor: 'orangered',
        color: 'orangered',
        },
    },
    });

    cardElement.mount('#card-element');

    const cardHolderName = document.getElementById('card-holder-name');
    const PaymentMethodID = document.getElementById('pmID');
    const frmPaymentMethods = $('#frmPaymentMethods');
    const cardButton = document.getElementById('card-button');
    const clientSecret = cardButton.dataset.secret;

    cardButton.addEventListener('click', async (e) => {
        if(cardHolderName.value == ''){ cardHolderName.classList.add('required'); return false; }
        const { paymentMethod, error } = await stripe.createPaymentMethod(
            'card', cardElement, {
                billing_details: { name: cardHolderName.value }
            }
        );

        if (error) {
            console.log(error.message);
        } else {
            //card verified, so charge the payment
            
            var frmStripe = $('#frm-stripe-pay');
            frmStripe.find('#pmethod').val(paymentMethod.id);
            frmStripe.submit();
        }
    });
    cardHolderName.addEventListener('change',(e) => {
        cardHolderName.classList.remove('required');
    });
   
});