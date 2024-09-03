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
                <!-- content Start -->
                <div class="content">
                    <!-- Pay Option -->
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
                                <span class="d-inline-block"><span class="fw-lighter divder-txt"></span></span>
                            </div>
                            <div class="col-4 text-center">
                                <hr>
                            </div>
                        </div>
                    </div>
                    <!-- Divider Ends -->
                    <!-- Start Links -->
                    <div class="container link mt-4 hide">
                        <!-- Link 1 -->
                        <div class="link-box">
                            <a href="#" onclick="toggleBox('form-1')">
                                <div class="row p-2">
                                    <div class="col-1">
                                        <img src="{{url('assets/img/card-img/wallet.png')}}" alt="">
                                    </div>
                                    <div class="col-11">
                                        <span class="fw-bolder">
                                            Alipay
                                        </span><br>
                                        <span>
                                            Scan QR Code using Alipay App
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <!-- End Link 1 -->
                    </div>
                    <!-- Notification -->
                        <div class="container d-flex justify-content-center mt-4 d-none">
                            <!-- Success Notification Started -->
                            <div class="container success-container ">
                                <div class="success-img text-center">
                                    <img src="{{asset('assets/img/icons/success.png')}}" alt=""></img>
                                </div>
                                <div class="success-text text-center">
                                    <h2 class="fw-light text-dark mt-4">Payment Success</h2>
                                    <div class="transaction-detail mt-4">
                                        <h6 class="fw-bold">Transaction Id : 152258966</h6>
                                        <h6 class="fw-bold ">Amount : $2</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- Success Notification Ended -->
                            <!-- Failure Notification Started -->
                            <div class="container success-container">
                                <div class="success-img text-center">
                                    <img src="{{asset('assets/img/icons/failed.png')}}" alt=""></img>
                                </div>
                                <div class="success-text text-center">
                                    <h2 class="fw-light text-dark mt-4">Payment Failed</h2>
                                    <div class="transaction-detail mt-4">
                                        <h6 class="fw-bold">Transaction Id : 152258966</h6>
                                        <h6 class="fw-bold ">Amount : $2</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- Failure Notification Ended -->
                        </div>
                    <!-- End Notification -->
                    <!-- Payment Component Start -->
                    <div class="payment-component">
                        <!-- Form Container Start -->
                        <div class="container d-flex justify-content-center mt-4 ">
                            <!-- Start Default Form -->
                            <div id="default-form" class="form-box">
                                <form action="" method="post" class="mt-4">
                                    <!-- Hidden Fields Starts -->
                                    <input type="hidden" name="acquirer_id" id="acquirer_id" value="{{$acquirer_id}}">
                                    <input type="hidden" name="api_endpoint" id="api_endpoint" value="{{$api_endpoint}}">
                                    <input type="hidden" name="gtw_public_key" id="gtw_public_key" value="{{$acquirer_fields->public_key}}">
                                    <input type="hidden" name="gtw_terno" id="gtw_terno" value="{{$acquirer_fields->terno}}">
                                    <input type="hidden" name="conf_return_url" id="conf_return_url" value="{{$acquirer_fields->return_url}}">
                                    <input type="hidden" name="public_key" id="public_key" value="{{$public_key}}">
                                    <input type="hidden" name="terno" id="terno" value="{{$terNO}}">
                                    <input type="hidden" name="checkout_url" id="checkout_url"value="{{$checkout_url}}">
                                    <input type="hidden" name="success_url" id="success_url" value="{{$success_url}}">
                                    <input type="hidden" name="failur_url" id="failur_url" value="{{$failur_url}}">
                                    <input type="hidden" name="return_url" id="return_url" value="{{$return_url}}">
                                    <input type="hidden" name="webhook_url" id="webhook_url" value="{{$webhook_url}}">
                                    <input type="hidden" name="product_name" id="product_name"
                                        value="{{$product_name}}">
                                    <input type="hidden" name="fullname" id="fullname" value="{{$fullname}}">
                                    <!-- <input type="hidden" name="bill_email" value="{{$bill_email}}"> -->
                                    <input type="hidden" name="bill_address" id="bill_address"
                                        value="{{$bill_address}}">
                                    <input type="hidden" name="bill_city" id="bill_city" value="{{$bill_city}}">
                                    <input type="hidden" name="bill_state" id="bill_state" value="{{$bill_state}}">
                                    <!-- <input type="hidden" name="bill_country" value="{{$bill_country}}"> -->
                                    <input type="hidden" name="bill_zip" id="bill_zip" value="{{$bill_zip}}">
                                    <input type="hidden" name="bill_phone" id="bill_phone" value="{{$bill_phone}}">
                                    <input type="hidden" name="bill_amt" id="bill_amt" value="{{$bill_amt}}">
                                    <input type="hidden" name="bill_currency" id="bill_currency"
                                        value="{{$bill_currency}}">
                                    <!-- Hidden Fields Ended -->
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="text" id="bill_email" name="bill_email" value="{{$bill_email}}"
                                            class="form-control" required>
                                    </div>
                                    <label for="card-element" class="mt-3">Credit or debit card:</label>
                                    <!-- Card field -->
                                    <input type="text" id="ccno" name="ccno" class="form-control"
                                        placeholder="Creadit Card Number" value="4242424242424242">
                                    <br>
                                    <input type="text" id="card_month" name="month" class="form-control" value="12"
                                        placeholder="Month">
                                    <br>
                                    <input type="text" id="card_year" name="year" class="form-control" value="30"
                                        placeholder="Year">
                                    <br>
                                    <input type="text" id="cvv" name="ccvv" class="form-control" value="123"
                                        placeholder="CVV"></inpuyt>
                                    <div id="card-errors" class="text-danger" role="alert"></div>
                                    <div class="form-group mt-3">
                                        <label for="card-owner">Cardholder name</label>
                                        <input type="text" class="form-control" id="card_holder_name"
                                            name="card_holder_name" value="Pawnesh" placeholder="Full name on card"
                                            required>
                                    </div>
                                    <div class="form-group mt-3">
                                        <label for="country">Country or region</label>
                                        <input type="text" class="form-control" value="{{$bill_country}}"
                                            id="bill_country" name="bill_country" placeholder="Country...">
                                    </div>

                                    <div class="form-group mt-5 text-center">
                                        <button type="submit" id="submit-payment" name="pay_button"
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
                            <!-- Form-1 Start -->
                            <div id="form-1" class="form-box hide">
                                <!-- Back Button -->
                                <a href="#" onclick="toggleBox('form-1')">
                                    <span><svg xmlns="http://www.w3.org/2000/svg" style="width:32px"
                                            viewBox="0 0 448 512">
                                            <path
                                                d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z" />
                                        </svg>
                                    </span>
                                </a><br>
                                <!-- Back Button -->
                                <span class="fw-bolder">Pay using : Alipay</span>
                                <div class="form-group mt-5 text-center">
                                    <button type="button" name="pay_button" class="btn pay_button">Pay</button>
                                </div>
                            </div>
                            <!-- Form-1 End -->
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
                        <!-- Payment Component Ended -->
                    </div>
                </div>
                <!-- content Ended -->
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
// Data Initializing
var transID = "";
var fetch_trnsStatus = "https://gtw.online-epayment.com/fetch_trnsStatus";
var interval;
var reportId= "";
var return_url = "";
// Interval to check status of transaction every 1 second unti it get expire 
$('#submit-payment').click(function() {
    // Set a timeout to call the function every 1 second for 10 seconds
    var timer = setTimeout(function() {
        // Call the function every 1 second
        interval = setInterval(function() {
            myFunction(transID,reportId); // Pass the variable to myFunction
        }, 1000);

        // After 10 seconds, clear the interval
        setTimeout(function() {
            clearInterval(interval);
        }, 60000); // 10000 milliseconds = 10 seconds
    }, 0); // Start the timer immediately
});


// function to check status of the transaction 
function myFunction(transID,reportId) {
    if (transID != '') {
        $.ajax({
            url: 'gtw_fetch',
            method: 'POST',
            data: {
                transID: transID,
                reportId: reportId
            },
            success: function(response) {
                var responseObject = JSON.parse(response);
                var orderStatus = responseObject.order_status;
                alert(orderStatus);
                if (orderStatus != 0) {
                    clearInterval(interval);
                    window.location.href = return_url;
                }
            },
            error: function(xhr, status, error) {
                // Handle error response from the server.
                alert(error.message);
            }
        });
    } else {}
}
// Handle form submission with AJAX.
$('#submit-payment').click(function(event) {
    $('#submit-payment').prop('disabled', true);
    event.preventDefault();
    $.ajax({
        url: 'gtw_checkout',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            acquirer_id: $('#acquirer_id').val(),
            api_endpoint: $('#api_endpoint').val(),
            gtw_public_key: $('#gtw_public_key').val(),
            gtw_terno: $('#gtw_terno').val(),
            conf_return_url: $('#conf_return_url').val(),
            public_key: $('#public_key').val(),
            terno: $('#terno').val(),
            checkout_url: $('#checkout_url').val(),
            failur_url: $('#failur_url').val(),
            success_url: $('#success_url').val(),
            return_url: $('#return_url').val(),
            webhook_url: $('#webhook_url').val(),
            fullname: $('#fullname').val(),
            product_name: $('#product_name').val(),
            bill_email: $('#bill_email').val(),
            bill_address: $('#bill_address').val(),
            bill_country: $('#bill_country').val(),
            bill_city: $('#bill_city').val(),
            bill_state: $('#bill_state').val(),
            bill_zip: $('#bill_zip').val(),
            bill_phone: $('#bill_phone').val(),
            bill_amt: $('#bill_amt').val(),
            bill_currency: $('#bill_currency').val(),
            card_holder_name: $('#card_holder_name').val(),
            ccno: $('#ccno').val(),
            month: $('#card_month').val(),
            year: $('#card_year').val(),
            ccvv: $('#cvv').val()
        },
        beforeSend: function() {
            // Disable the button before the AJAX call is sent
            $('.btn-txt').addClass('hide');
            $('.loader').removeClass('hide');
        },
        success: function(response) {
            alert(JSON.stringify(response));
            alert(response.return_url);
            transID = response.transID;
            reportId = response.reportId;
            return_url = response.return_url;
            if('redirect' in response){
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
            $('#submit-payment').prop('disabled', false);
        }
    });
});
</script>
<script>
// Toggle Content
function toggleBox(box) {
    $(document).ready(function() {
        $("#" + box).slideToggle("slow");
        $(".link").slideToggle("slow");
    });
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
    var originalDividerText = "Pay with card";
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