jQuery(function($){
    $('input[name=pmethod]').change(function(e){
        var target = $(this).attr('data-target');
        $(''+target).tab('show');
        $(target).siblings('.tab-pane').removeClass('active');
    });




    /* stripe */
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
        if(cardHolderName.value==''){ alert('Card holder name required'); return false; }
        $('#card-button').addClass('disabled loading').attr('disabled',true);
        const { setupIntent, error } = await stripe.confirmCardSetup(
            clientSecret, {
                payment_method: {
                    card: cardElement,
                    billing_details: { name: cardHolderName.value }
                }
            }
        );

        if (error) {
            alert(error.message);
            $('#card-button').removeClass('disabled loading').attr('disabled',false);
        } else {
            PaymentMethodID.value = setupIntent.payment_method;
            
            var FormData = frmPaymentMethods.serialize();
            var formAction = frmPaymentMethods.attr('action');
            
            $.ajax({
                url: formAction,
                type: 'post',
                dataType: 'json',
                data: FormData,
                success: function(res){
                    if(res.status == 'success'){
                        window.location.reload();
                    }else{
                        alert("error");
                    }
                }
            });
        }
    });
    
});
