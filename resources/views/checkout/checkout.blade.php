<?php
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
.checkout-box {
    box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px;
    border-radius: 20px;
    padding-bottom: 70px;
    width: 50%;
}

.content {
    margin-top: 50px;
}

.pay-btn {
    background-color: #31e4b8;
    width: 300px;
}

.view-detail-btn {
    width: 100%;
}

.amount-box {
    border: 2px solid lightgray;
    border-radius: 5px;
    margin-right: 10px;
}

.amount-txt {
    color: gray;
    font-weight: bold;
}

.pay-btn:hover {
    background-color: #70e7c9;
}

.pay-btn .link-txt {
    font-weight: 700;
    font-style: italic;

}

label {
    font-size: 13px;
    font-weight: 500;
    color: gray;
}

.form-box {
    width: 70%;
    transition: opacity 2s ease-in-out;
}

.pay_button {
    width: 300px;
    background-color: #0074d4;
    font-weight: bold;
    color: #97c6ed;
}

.pay_button:hover {
    background-color: #97c6ed;
    color: #0074d4;
}

.all-card {
    background: url("<?= url('assets/img/card-img/visacard.png') ?>"), url("<?= url('assets/img/card-img/mastercard.png') ?>"), url("<?= url('assets/img/card-img/amex.png') ?>");
    background-repeat: no-repeat, no-repeat, no-repeat;
    background-position: right 10px top 50%, right 45px top 50%, right 78px top 50%;
    background-size: 30px, 30px, 30px;
}

.master-card {
    background: url("assets/img/card-img/mastercard.png") no-repeat right;
    background-size: 30px;
}

.visa-card {
    background: url("assets/img/card-img/visacard.png") no-repeat right;
    background-size: 30px;
}

.amex-card {
    background: url("assets/img/card-img/amex.png") no-repeat right;
    background-size: 30px;
}

.pay-option-box {}

.link-box {

    border: 2px solid #e8ebe6;
    width: 100%;
    border-radius: 10px;
}

a {
    text-decoration: none;
    color: black;
}

a:hover {
    text-decoration: none;
    color: black;
}

.link-box:hover {

    color: black;
    background-color: #cbfcf0;
}

.hide {
    display: none;
}

@media (min-width: 600px) and (max-width: 1000px) {
    .checkout-box {
        width: 100%;
    }

    .form-box {
        width: 80%
    }
}

@media screen and (max-width: 600px) {
    .checkout-box {
        width: 100%;
    }

    .form-box {
        width: 90%
    }
}
</style>

<body>
    <section>
        <!-- Main Content -->
        <div class="container d-flex justify-content-center">
            <div class="checkout-box">
                <div class="content">
                    <!-- Links -->
                    <div class="top-links text-center">
                        <button type="button" id="link-btn" onclick="toggleLinkButton('default-form')"
                            class="btn pay-btn">
                            Pay With <span class="link-txt pay-btn-txt">Link</span>
                        </button>
                    </div>
                    <!-- Divider -->
                    <div class="container mt-4">
                        <div class="row align-items-center justify-content-center">
                            <div class="col-4 text-center">
                                <hr>
                            </div>
                            <div class="col-4 text-center">
                                <span class="d-inline-block"><span class="fw-lighter divder-txt">Other payment
                                        options</span></span>
                            </div>
                            <div class="col-4 text-center">
                                <hr>
                            </div>
                        </div>
                    </div>
                    <!-- Divider Ends -->
                    <!-- Start Links -->
                    <div class="container link  mt-4 ">
                        <!-- Link 1 -->
                        @foreach($acquirer_data as $ad)
                        <?php
                        $acquirer_fields = $ad['fields'];
                        ?>
                        <div class="link-box mt-4">
                            <a href="#"
                                onclick="toggleBox('{{$ad['acquirer_name']}}',{{$ad['acquirer_id']}},'{{$ad['api_endpoint']}}',{{$acquirer_fields}})">
                                <div class="row p-2">
                                    <div class="col-1">
                                        <img src="{{url('assets/img/card-img/wallet.png')}}" alt="">
                                    </div>
                                    <div class="col-11">
                                        <span class="fw-bolder">
                                            {{$ad->acquirer_name;}}
                                        </span><br>
                                        <span>
                                            {{$ad->acquirer_name;}}
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                        <!-- End Link 1 -->
                    </div>
                    <!-- End Links -->
                    <div class="payment-component">
                        <!-- Form Container Start -->
                        <div class="container d-flex justify-content-center mt-4 ">
                            <!-- Start Default Form -->
                            <div id="Stripe-box" class="form-box hide">
                                <!-- Back Button -->
                                <a href="#" onclick="toggleBox('Stripe')">
                                    <span><svg xmlns="http://www.w3.org/2000/svg" style="width:32px"
                                            viewBox="0 0 448 512">
                                            <path
                                                d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z" />
                                        </svg>
                                    </span>
                                </a><br>
                                <!-- Back Button -->
                                <span class="fw-bolder">Pay using : <span class="form-txt"></span></span>
                                <form action="" id="Stripe-form" method="post" class="mt-4">
                                    <!-- Hidden Fields Starts -->
                                    <!-- Configration Variables -->
                                    <input type="hidden" name="test_secret_key" id="test_secret_key">
                                    <input type="hidden" name="acquirer_id" id="acquirer_id" >
                                    <!-- End Configration Variables -->
                                    <input type="hidden" name="public_key" id="public_key" value="{{$public_key}}">
                                    <input type="hidden" name="terno" id="terno" value="{{$terNO}}">
                                    <input type="hidden" name="checkout_url" id="checkout_url"value="{{$checkout_url}}">
                                    <input type="hidden" name="success_url" id="success_url" value="{{$success_url}}">
                                    <input type="hidden" name="failur_url" id="failur_url" value="{{$failur_url}}">
                                    <input type="hidden" name="return_url" id="return_url" value="{{$return_url}}">
                                    <input type="hidden" name="webhook_url" id="webhook_url" value="{{$webhook_url}}">
                                    <input type="hidden" name="product_name" id="product_name" value="{{$product_name}}">
                                    <input type="hidden" name="fullname" id="fullname" value="{{$fullname}}">
                                    <input type="hidden" name="bill_address" id="bill_address"value="{{$bill_address}}">
                                    <input type="hidden" name="bill_city" id="bill_city" value="{{$bill_city}}">
                                    <input type="hidden" name="bill_state" id="bill_state" value="{{$bill_state}}">
                                    <input type="hidden" name="bill_zip" id="bill_zip" value="{{$bill_zip}}">
                                    <input type="hidden" name="bill_phone" id="bill_phone" value="{{$bill_phone}}">
                                    <input type="hidden" name="bill_amt" id="bill_amt" value="{{$bill_amt}}">
                                    <input type="hidden" name="bill_currency" id="bill_currency"value="{{$bill_currency}}">
                                    <!-- Hidden Fields Ended -->
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="text" id="bill_email" name="bill_email" value="{{$bill_email}}"
                                            class="form-control" required>
                                    </div>
                                    <label for="card-element" class="mt-3">Credit or debit card:</label>
                                    <div id="card-element" class="form-control">
                                        <!-- A Stripe Element will be inserted here. -->
                                    </div>
                                    <!-- Used to display form errors. -->
                                    <div id="card-errors" class="text-danger" role="alert"></div>
                                    <div class="form-group mt-3">
                                        <label for="card-owner">Cardholder name</label>
                                        <input type="text" class="form-control" id="card_holder_name"
                                            name="card_holder_name" placeholder="Full name on card" required>
                                    </div>
                                    <div class="form-group mt-3">
                                        <label for="country">Country or region</label>
                                        <input type="text" class="form-control" value="{{$bill_country}}"
                                            id="bill_country" name="bill_country" placeholder="Country...">
                                    </div>

                                    <div class="form-group mt-5 text-center">
                                        <button type="button" id="stripe-payment" name="pay_button"
                                            class="btn pay_button">
                                            <div class="btn-txt">
                                                Pay
                                            </div>
                                            <div class="loader hide">
                                                <img src="{{asset('assets/img/loader/spinner.gif')}}" width="40px"
                                                    height="40px" alt="...">
                                            </div>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <!-- Default-form End -->
                            <!-- GTW-Form Start -->
                            <div id="GTW-box" class="form-box hide">
                                <!-- Back Button -->
                                <a href="#" onclick="toggleBox('GTW')">
                                    <span><svg xmlns="http://www.w3.org/2000/svg" style="width:32px"
                                            viewBox="0 0 448 512">
                                            <path
                                                d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z" />
                                        </svg>
                                    </span>
                                </a><br>
                                <!-- Back Button -->
                                <span class="fw-bolder">Pay using : <span class="form-txt"></span></span>
                                <form action="" id="GTW-form" method="post" class="mt-4">
                                    <!-- Hidden Fields Starts -->
                                    <!-- Configuration variables -->
                                    <input type="hidden" name="acquirer_id" id="acquirer_id">
                                    <input type="hidden" name="api_endpoint" id="api_endpoint">
                                    <input type="hidden" name="gtw_public_key" id="gtw_public_key">
                                    <input type="hidden" name="gtw_terno" id="gtw_terno">
                                    <input type="hidden" name="conf_return_url" id="conf_return_url">
                                    <!-- End Configuration variables -->
                                    <input type="hidden" name="public_key" id="public_key" value="{{$public_key}}">
                                    <input type="hidden" name="terno" id="terno" value="{{$terNO}}">
                                    <input type="hidden" name="checkout_url" id="checkout_url"
                                        value="{{$checkout_url}}">
                                    <input type="hidden" name="success_url" id="success_url" value="{{$success_url}}">
                                    <input type="hidden" name="failur_url" id="failur_url" value="{{$failur_url}}">
                                    <input type="hidden" name="return_url" id="return_url" value="{{$return_url}}">
                                    <input type="hidden" name="webhook_url" id="webhook_url" value="{{$webhook_url}}">
                                    <input type="hidden" name="product_name" id="product_name"
                                        value="{{$product_name}}">
                                    <input type="hidden" name="fullname" id="fullname" value="{{$fullname}}">
                                    <input type="hidden" name="bill_address" id="bill_address" value="{{$bill_address}}">
                                    <input type="hidden" name="bill_city" id="bill_city" value="{{$bill_city}}">
                                    <input type="hidden" name="bill_state" id="bill_state" value="{{$bill_state}}">
                                    <input type="hidden" name="bill_zip" id="bill_zip" value="{{$bill_zip}}">
                                    <input type="hidden" name="bill_phone" id="bill_phone" value="{{$bill_phone}}">
                                    <input type="hidden" name="bill_amt" id="bill_amt" value="{{$bill_amt}}">
                                    <input type="hidden" name="bill_currency" id="bill_currency"value="{{$bill_currency}}">
                                    <!-- Hidden Fields Ended -->
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="text" id="bill_email" name="bill_email" value="{{$bill_email}}"
                                            class="form-control" required>
                                    </div>
                                    <label for="card-element" class="mt-3">Credit or debit card:</label>
                                    <!-- Card field -->
                                    <input type="text" id="ccno" name="ccno" class="form-control"
                                        placeholder="Creadit Card Number" value="">
                                    <br>
                                    <input type="text" id="card_month" name="month" class="form-control" value=""
                                        placeholder="Month">
                                    <br>
                                    <input type="text" id="card_year" name="year" class="form-control" value=""
                                        placeholder="Year">
                                    <br>
                                    <input type="text" id="cvv" name="ccvv" class="form-control" value=""
                                        placeholder="CVV"></inpuyt>
                                    <div id="card-errors" class="text-danger" role="alert"></div>
                                    <div class="form-group mt-3">
                                        <label for="card-owner">Cardholder name</label>
                                        <input type="text" class="form-control" id="card_holder_name"
                                            name="card_holder_name" value="" placeholder="Full name on card"
                                            required>
                                    </div>
                                    <div class="form-group mt-3">
                                        <label for="country">Country or region</label>
                                        <input type="text" class="form-control" value="{{$bill_country}}"
                                            id="bill_country" name="bill_country" placeholder="Country...">
                                    </div>

                                    <div class="form-group mt-5 text-center">
                                        <button type="submit" id="gtw-payment" name="pay_button" class="btn pay_button">
                                            <div class="btn-txt">
                                                Pay
                                            </div>
                                            <div class="loader hide">
                                                <img src="{{asset('assets/img/loader/spinner.gif')}}" width="40px"
                                                    height="40px" alt="...">
                                            </div>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <!-- GTW-Form End -->
                            <!-- Epay-Form Start -->
                            <div id="epay-box" class="form-box hide">
                                <!-- Back Button -->
                                <a href="#" onclick="toggleBox('epay')">
                                    <span><svg xmlns="http://www.w3.org/2000/svg" style="width:32px"
                                            viewBox="0 0 448 512">
                                            <path
                                                d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z" />
                                        </svg>
                                    </span>
                                </a><br>
                                <!-- Back Button -->
                                <span class="fw-bolder">Pay using : <span class="form-txt"></span></span>
                                <form action="{{route('epayCheckout')}}" id="epay-form" method="post" class="mt-4">
                                    <!-- Hidden Fields Starts -->
                                    <!-- Configuration variables -->
                                    <input type="text" name="acquirer_id" id="acquirer_id">
                                    <input type="hidden" name="api_endpoint" id="api_endpoint">
                                    <input type="hidden" name="token" id="token">
                                    <input type="hidden" name="authorization" id="authorization">
                                    <!-- End Configuration variables -->
                                    <input type="hidden" name="public_key" id="public_key" value="{{$public_key}}">
                                    <input type="hidden" name="terno" id="terno" value="{{$terNO}}">
                                    <input type="hidden" name="checkout_url" id="checkout_url"value="{{$checkout_url}}">
                                    <input type="hidden" name="success_url" id="success_url" value="{{$success_url}}">
                                    <input type="hidden" name="failur_url" id="failur_url" value="{{$failur_url}}">
                                    <input type="hidden" name="return_url" id="return_url" value="{{$return_url}}">
                                    <input type="hidden" name="webhook_url" id="webhook_url" value="{{$webhook_url}}">
                                    <input type="hidden" name="product_name" id="product_name"value="{{$product_name}}">
                                    <input type="hidden" name="fullname" id="fullname" value="{{$fullname}}">
                                    <input type="hidden" name="bill_address" id="bill_address"value="{{$bill_address}}">
                                    <input type="hidden" name="bill_city" id="bill_city" value="{{$bill_city}}">
                                    <input type="hidden" name="bill_state" id="bill_state" value="{{$bill_state}}">
                                    <input type="hidden" name="bill_zip" id="bill_zip" value="{{$bill_zip}}">
                                    <input type="hidden" name="bill_phone" id="bill_phone" value="{{$bill_phone}}">
                                    <input type="hidden" name="bill_amt" id="bill_amt" value="{{$bill_amt}}">
                                    <input type="hidden" name="bill_currency" id="bill_currency"value="{{$bill_currency}}">
                                    <input type="hidden" id="bill_email" name="bill_email" value="{{$bill_email}}">
                                    <input type="hidden" id="bill_country" name="bill_country" value="{{$bill_country}}"> 
                                    <div class="form-group mt-5 text-center">
                                        <button type="submit"  name="pay_button" class="btn pay_button">
                                            <div class="btn-txt">
                                                Pay
                                            </div>
                                            <div class="loader hide">
                                                <img src="{{asset('assets/img/loader/spinner.gif')}}" width="40px"
                                                    height="40px" alt="...">
                                            </div>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <!-- Epay-Form Ended -->
                        </div>
                        <!-- Form Container Ended -->
                        <!-- Amount button -->
                        <div class="container d-flex justify-content-center">
                            <div class="container">
                                <div class="row mt-5">
                                    <div class="col text-center amount-box">
                                        <span class="amount-txt">$ {{$bill_amt}}</span>
                                    </div>
                                    <div class="col text-center">
                                        <button class="btn view-detail-btn btn-info text-white"
                                            onclick="toggleDetail('details-box')">View
                                            Details</button>
                                    </div>
                                </div>
                                <!-- End Amount Button -->
                                <!-- Start Amount Detail Box -->
                                <div class="details-box mt-4 hide" id="details-box"
                                    style="border:2px solid lightgray;padding:10px;border-radius:10px">
                                    <span class="fw-bolder">View Details</span>
                                    <div class="detail-box">
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:32px"
                                                viewBox="0 0 448 512">
                                                <path
                                                    d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z" />
                                            </svg>
                                        </span>
                                        <span>
                                            {{$fullname}}
                                        </span>
                                    </div>
                                    <div class="detail-box">
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:32px"
                                                viewBox="0 0 512 512">
                                                <path
                                                    d="M48 64C21.5 64 0 85.5 0 112c0 15.1 7.1 29.3 19.2 38.4L236.8 313.6c11.4 8.5 27 8.5 38.4 0L492.8 150.4c12.1-9.1 19.2-23.3 19.2-38.4c0-26.5-21.5-48-48-48H48zM0 176V384c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V176L294.4 339.2c-22.8 17.1-54 17.1-76.8 0L0 176z" />
                                            </svg>
                                        </span>
                                        <span>
                                            {{$bill_email}}
                                        </span>
                                    </div>
                                    <div class="detail-box">
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:32px"
                                                viewBox="0 0 384 512">
                                                <path
                                                    d="M80 0C44.7 0 16 28.7 16 64V448c0 35.3 28.7 64 64 64H304c35.3 0 64-28.7 64-64V64c0-35.3-28.7-64-64-64H80zm80 432h64c8.8 0 16 7.2 16 16s-7.2 16-16 16H160c-8.8 0-16-7.2-16-16s7.2-16 16-16z" />
                                            </svg>
                                        </span>
                                        <span>
                                            {{$bill_phone}}
                                        </span>
                                    </div>
                                    <div class="detail-box">
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:32px"
                                                viewBox="0 0 384 512">
                                                <path
                                                    d="M215.7 499.2C267 435 384 279.4 384 192C384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2c12.3 15.3 35.1 15.3 47.4 0zM192 128a64 64 0 1 1 0 128 64 64 0 1 1 0-128z" />
                                            </svg>
                                        </span>
                                        <span>
                                            {{$bill_address}}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Amount Detail Box -->
                    </div>
                </div>
            </div>
        </div>
        <!-- End Main Content -->
    </section>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Include Stripe.js -->
<script src="https://js.stripe.com/v3/"></script>
<script>
// GTW code
// Data Initializing
var transID = "";
var fetch_trnsStatus = "https://gtw.online-epayment.com/fetch_trnsStatus";
var interval;
var reportId = "";
var return_url ="";
// Interval to check status of transaction every 1 second unti it get expire 
function intervalStart(transID,reportId,return_url) {
        interval = setInterval(function() {
            myFunction(transID, reportId,return_url); // Pass the variable to myFunction
        }, 1000);
        // After 3 minutes, clear the interval
        setTimeout(function() {
            clearInterval(interval);
        }, 1000*60*3); // 100 milliseconds = 1 seconds
};


// function to check status of the transaction 
function myFunction(transID, reportId,return_url) {
    if (transID != '') {
        $.ajax({
            url: 'gtw_fetch',
            method: 'POST',

            data: {
                transID: transID,
                reportId: reportId,
                return_url: return_url
            },
            success: function(response) {
                var responseObject = JSON.parse(response);
                var orderStatus = responseObject.response.order_status;
                if (orderStatus != 0) {
                    clearInterval(interval);
                    window.location.href = responseObject.return;
                }
            },
            error: function(xhr, status, error) {
                // Handle error response from the server.
            }
        });
    } else {}
}
// Handle form submission with AJAX.
$('#gtw-payment').click(function(event) {
    $('#gtw-payment').prop('disabled', true);
    event.preventDefault();
    $.ajax({
        url: 'gtw_checkout',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            acquirer_id: $('#GTW-form input[name="acquirer_id"]').val(),
            api_endpoint: $('#GTW-form input[name="api_endpoint"]').val(),
            gtw_public_key: $('#GTW-form input[name="gtw_public_key"]').val(),
            gtw_terno: $('#GTW-form input[name="gtw_terno"]').val(),
            conf_return_url: $('#GTW-form input[name="conf_return_url"]').val(),
            public_key: $('#GTW-form input[name="public_key"]').val(),
            terno: $('#GTW-form input[name="terno"]').val(),
            checkout_url: $('#GTW-form input[name="checkout_url"]').val(),
            failur_url: $('#GTW-form input[name="failur_url"]').val(),
            success_url: $('#GTW-form input[name="success_url"]').val(),
            return_url: $('#GTW-form input[name="return_url"]').val(),
            webhook_url: $('#GTW-form input[name="webhook_url"]').val(),
            fullname: $('#GTW-form input[name="fullname"]').val(),
            product_name: $('#GTW-form input[name="product_name"]').val(),
            bill_email: $('#GTW-form input[name="bill_email"]').val(),
            bill_address: $('#GTW-form input[name="bill_address"]').val(),
            bill_country: $('#GTW-form input[name="bill_country"]').val(),
            bill_city: $('#GTW-form input[name="bill_city"]').val(),
            bill_state: $('#GTW-form input[name="bill_state"]').val(),
            bill_zip: $('#GTW-form input[name="bill_zip"]').val(),
            bill_phone: $('#GTW-form input[name="bill_phone"]').val(),
            bill_amt: $('#GTW-form input[name="bill_amt"]').val(),
            bill_currency: $('#GTW-form input[name="bill_currency"]').val(),
            card_holder_name: $('#GTW-form input[name="card_holder_name"]').val(),
            ccno: $('#GTW-form input[name="ccno"]').val(),
            month: $('#GTW-form input[name="month"]').val(),
            year: $('#GTW-form input[name="year"]').val(),
            ccvv: $('#GTW-form input[name="ccvv"]').val()
        },
        beforeSend: function() {
            // Disable the button before the AJAX call is sent
            $('.btn-txt').addClass('hide');
            $('.loader').removeClass('hide');
        },
        success: function(response) {
            transID = response.transID;
            reportId = response.reportId;
            return_url = response.return_url;
            intervalStart(transID,reportId,return_url);
            if ('redirect' in response) {
                window.open(response.redirect);
            }
        },
        error: function(xhr, status, error) {
            // Handle error response from the server.
            alert(error.message);
        },
        complete: function() {
            $('.btn-txt').removeClass('hide');
            $('.loader').addClass('hide');
            // Re-enable the button after the request is completed
            $('#gtw-payment').prop('disabled', false);
        }
    });
});
</script>
<script>
// Stripe JS Code
var test_public_key = "";
var stripe = "";
var elements = "";

// Create an instance of the card Element.
var card = "";

// Handle form submission with AJAX.
$('#stripe-payment').click(function(event) {
    $('#stripe-payment').prop('disabled', true);
    event.preventDefault();
    stripe.createToken(card).then(function(result) {
        if (result.error) {
            // Inform the user if there was an error.
            var errorElement = document.getElementById('card-errors');
            errorElement.textContent = result.error.message;
            $('#stripe-payment').prop('disabled', false);
        } else {
            // Send the token to your server using AJAX.
            $.ajax({
                url: 'Strippayinitiate',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    stripeToken: result.token.id,
                    public_key: $('#Stripe-form input[name="public_key"]').val(),
                    terno: $('#Stripe-form input[name="terno"]').val(),
                    checkout_url: $('#Stripe-form input[name="checkout_url"]').val(),
                    failur_url: $('#Stripe-form input[name="failur_url"]').val(),
                    success_url: $('#Stripe-form input[name="success_url"]').val(),
                    return_url: $('#Stripe-form input[name="return_url"]').val(),
                    webhook_url: $('#Stripe-form input[name="webhook_url"]').val(),
                    fullname: $('#Stripe-form input[name="fullname"]').val(),
                    product_name: $('#Stripe-form input[name="product_name"]').val(),
                    bill_email: $('#Stripe-form input[name="bill_email"]').val(),
                    bill_address: $('#Stripe-form input[name="bill_address"]').val(),
                    bill_country: $('#Stripe-form input[name="bill_country"]').val(),
                    bill_city: $('#Stripe-form input[name="bill_city"]').val(),
                    bill_state: $('#Stripe-form input[name="bill_state"]').val(),
                    bill_zip: $('#Stripe-form input[name="bill_zip"]').val(),
                    bill_phone: $('#Stripe-form input[name="bill_phone"]').val(),
                    bill_amt: $('#Stripe-form input[name="bill_amt"]').val(),
                    bill_currency: $('#Stripe-form input[name="bill_currency"]').val(),
                    card_holder_name: $('#Stripe-form input[name="card_holder_name"]').val(),
                    acquirer_id: $('#Stripe-form input[name="acquirer_id"]').val(),
                    test_secret_key: $('#Stripe-form input[name="test_secret_key"]').val()
                },
                beforeSend: function() {
                    // Disable the button before the AJAX call is sent
                    $('.btn-txt').addClass('hide');
                    $('.loader').removeClass('hide');
                },
                success: function(response) {
                    if (response.status == "success") {
                        window.location.replace(response.return_url);
                    }
                    if (response.status == "error") {
                        alert(response.error_msg);
                        window.location.replace(response.return_url);
                    }
                },
                error: function(xhr, status, error) {
                    // Handle error response from the server.
                    alert(error.message);
                },
                complete: function() {
                    $('.btn-txt').removeClass('hide');
                    $('.loader').addClass('hide');
                    // Re-enable the button after the request is completed
                    $('#stripe-payment').prop('disabled', false);
                }
            });
        }
    });
});
</script>
<script>
function toggleBox(box, acquirer_id = 0, api_endpoint = 0, acquirer_fields = 0) {
    $(".form-txt").text(box);
    if (acquirer_fields != 0 && 'test_public_key' in acquirer_fields) {
        test_public_key = acquirer_fields.test_public_key;
        stripe = Stripe(test_public_key);
        elements = stripe.elements();
        // Create an instance of the card Element.
        card = elements.create('card');
        // Add an instance of the card Element into the `card-element` div.
        card.mount('#card-element');
        // Handle real-time validation errors from the card Element.
        card.addEventListener('change', function(event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });
    }
    if (acquirer_id != 0) {
        $('#' + box + '-form input[name="acquirer_id"]').val(acquirer_id)
    }
    if (api_endpoint != 0) {
        $('#' + box + '-form input[name="api_endpoint"]').val(api_endpoint)
    }
    if (acquirer_fields != 0 && 'test_secret_key' in acquirer_fields) {
        $('#' + box + '-form input[name="test_secret_key"]').val(acquirer_fields.test_secret_key)
    }
    if (acquirer_fields != 0 && 'live_public_key' in acquirer_fields) {
        $('#' + box + '-form input[name="live_public_key"]').val(acquirer_fields.live_public_key)
    }
    if (acquirer_fields != 0 && 'live_secret_key' in acquirer_fields) {
        $('#' + box + '-form input[name="live_secret_key"]').val(acquirer_fields.live_secret_key)
    }
    if (acquirer_fields != 0 && 'public_key' in acquirer_fields) {
        $('#' + box + '-form input[name="gtw_public_key"]').val(acquirer_fields.public_key)
    }
    if (acquirer_fields != 0 && 'terno' in acquirer_fields) {
        $('#' + box + '-form input[name="gtw_terno"]').val(acquirer_fields.terno)
    }
    if (acquirer_fields != 0 && 'token' in acquirer_fields) {
        $('#' + box + '-form input[name="token"]').val(acquirer_fields.token)
    }
    if (acquirer_fields != 0 && 'authorization' in acquirer_fields) {
        $('#' + box + '-form input[name="authorization"]').val(acquirer_fields.authorization)
    }
    $("#" + box + "-box").slideToggle("slow");
    $(".link").slideToggle("slow");
}

function toggleDetail(id) {
    $(document).ready(function() {
        $("." + id).slideToggle("slow");
    });
}

function toggleLinkButton(defForm) {
    $(document).ready(function() {
        if ($("#" + defForm).css("display") === "none" && $('.link').css("display") === "none") {
            $(".form-box").css("display", "none");
            $("#" + defForm).slideToggle("slow");
        } else {
            $("#" + defForm).slideToggle("slow");
            $(".link").slideToggle("slow");
        }

    });
}
$(document).ready(function() {
    var originalText = "Link";
    var alternateText = "Card";
    var originalDividerText = "Or pay with card";
    var alternateDividerText = "Other payment options";
    // Initially set text
    $(".pay-btn-txt").text(originalText);
    $(".divder-txt").text(originalDividerText);

    // Toggle text on click
    $("#link-btn").click(function() {
        $(".pay-btn-txt").text(function(_, currentText) {
            return currentText === originalText ? alternateText : originalText;
        });
    });
    $("#link-btn").click(function() {
        $(".divder-txt").text(function(_, currentDividerText) {
            return currentDividerText === originalDividerText ? alternateDividerText :
                originalDividerText;
        });
    });
});
</script>

</html>