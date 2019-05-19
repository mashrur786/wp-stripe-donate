<?php if(!empty($successMessage)) { ?>
<div id="success-message"><?php echo $successMessage; ?></div>
<?php  } ?>
<div id="error-message"></div>
<form id="frmStripePayment" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
    <div class="field-row">
        <label>Card Holder Name</label>
        <span id="card-holder-name-info" class="info"></span><br>
        <input type="text" id="name" name="name" class="demoInputBox">
    </div>
    <div class="field-row">
        <label>Email</label> <span id="email-info" class="info"></span><br>
        <input type="text" id="email" name="email" class="demoInputBox">
    </div>
    <div class="field-row">
        <label>Card Number</label> <span id="card-number-info" class="info"></span><br>
        <input type="text" id="card-number" name="card-number" class="demoInputBox">
    </div>
    <div class="field-row">
        <div class="contact-row column-right">
            <label>Expiry Month / Year</label> <span id="userEmail-info" class="info"></span><br>
            <select name="month" id="month" class="demoSelectBox">
                <option value="08">08</option>
                <option value="09">9</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
            </select>
            <select name="year" id="year" class="demoSelectBox">
                <option value="19">2019</option>
                <option value="20">2020</option>
                <option value="21">2021</option>
                <option value="22">2022</option>
                <option value="23">2023</option>
                <option value="24">2024</option>
                <option value="25">2025</option>
                <option value="26">2026</option>
                <option value="27">2027</option>
                <option value="28">2028</option>
                <option value="29">2029</option>
                <option value="30">2030</option>
            </select>
        </div>
        <div class="contact-row cvv-box">
            <label>CVC</label>
            <span id="cvv-info" class="info"></span><br>
            <input type="text" name="cvc" id="cvc" class="demoInputBox cvv-input">
        </div>
    </div>
    <div>
        <input type="submit" name="pay_now" value="Submit" id="submit-btn"
               class="btnAction" onClick="stripePay(event);">
        <div id="loader">
            <img alt="loader" src="<?php echo WPSD_URL ?>/public/img/ajax-loader.gif">
        </div>
    </div>
    <input type='hidden' name='amount' value='0.5'>
    <input type='hidden' name='currency_code' value='USD'>
    <input type='hidden' name='item_name' value='Test Product'>
    <input type='hidden' name='item_number' value='MASHRUR#786'>
    <input type="hidden"
           name="handle_stripe_donation_nonce"
           value="<?php wp_create_nonce( 'handle_stripe_donation_nonce' ) ?>">
    <input type="hidden" name="action" value="handle_stripe_donation">
</form>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script>
function cardValidation () {
    var valid = true;
    var name = $('#name').val();
    var email = $('#email').val();
    var cardNumber = $('#card-number').val();
    var month = $('#month').val();
    var year = $('#year').val();
    var cvc = $('#cvc').val();

    $("#error-message").html("").hide();

    if (name.trim() == "") {
        valid = false;
    }
    if (email.trim() == "") {
    	   valid = false;
    }
    if (cardNumber.trim() == "") {
    	   valid = false;
    }

    if (month.trim() == "") {
    	    valid = false;
    }
    if (year.trim() == "") {
        valid = false;
    }
    if (cvc.trim() == "") {
        valid = false;
    }

    if(valid == false) {
        $("#error-message").html("All Fields are required").show();
    }

    return valid;
}
//set your publishable key
Stripe.setPublishableKey("pk_test_enwX9R2jSSi4ETafck01yJVI00mrCk4B4p");

//callback to handle the response from stripe
function stripeResponseHandler(status, response) {
    if (response.error) {
        //enable the submit button
        $("#submit-btn").show();
        $( "#loader" ).css("display", "none");
        //display the errors on the form
        $("#error-message").html(response.error.message).show();
    } else {
        //get token id
        var token = response['id'];
        //insert the token into the form
        $("#frmStripePayment").append("<input type='hidden' name='token' value='" + token + "' />");
        //submit form to the server
        $("#frmStripePayment").submit();
    }
}
function stripePay(e) {
    e.preventDefault();
    var valid = cardValidation();

    if(valid == true) {
        $("#submit-btn").hide();
        $( "#loader" ).css("display", "inline-block");
        Stripe.createToken({
            number: $('#card-number').val(),
            cvc: $('#cvc').val(),
            exp_month: $('#month').val(),
            exp_year: $('#year').val()
        }, stripeResponseHandler);

        //submit from callback
        return false;
    }
}
</script>