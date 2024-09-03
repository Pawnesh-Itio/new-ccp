<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <section>
        <!-- Main Content -->
        <div class="container">
            <div class="row">
                <div class="col-2"></div>
                <div class="col-8">
                    <div class="card text-center">
                        <div class="card-header">
                            <!-- Form Title -->
                            Order Detail Form
                        </div>
                        <form id="formId" target="_blank" method="post"action="http://127.0.0.1:8000/checkout">

                            <div class="card-body">
                                <!-- Form Body -->
                                <div class="mb-3">
                                    <input type="text" class="form-control" name="terNO" title="terNO"
                                        placeholder="TerNo">
                                </div>
                                <div class="mb-3">
                                    <select class="form-control" name="public_key">
                                        <option value>Business Terminal Id</option>
                                        @foreach($merchantData AS $md)
                                        <option data-title="{{$md->terno}}" value="{{$md->public_key}}">
                                            {{$md->public_key}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <input type="text" class="form-control" name="checkout_url"
                                        placeholder="Checkout URL" value="http://127.0.0.1:8000/checkout">
                                </div>
                                <div class="mb-3">
                                    <input type="text" class="form-control" name="return_url" placeholder="Return URL"
                                        value="http://127.0.0.1:8000/return_url">
                                </div>
                                <div class="mb-3">
                                    <input type="text" class="form-control" name="success_url" placeholder="Success URL"
                                        value="http://127.0.0.1:8000/success">
                                </div>
                                <div class="mb-3">
                                    <input type="text" class="form-control" name="failur_url" placeholder="Failur URL"
                                        value="http://127.0.0.1:8000/failur">
                                </div>
                                <div class="mb-3">
                                    <input type="text" class="form-control" name="webhook_url" placeholder="Webhook URL"
                                        value="http://127.0.0.1:8000/webhook_url">
                                </div>
                                <div class="mb-3">
                                    <input type="text" class="form-control" name="product_name"
                                        placeholder="Product Name" value="Test Product">
                                </div>
                                <div class="mb-3">
                                    <input type="text" class="form-control" name="fullname" placeholder="Full Name"
                                        value="Test Full Name">
                                </div>
                                <div class="mb-3">
                                    <input type="text" class="form-control" name="bill_email" placeholder="Bill Email"
                                        value="test5849@test.com">
                                </div>
                                <div class="mb-3">
                                    <input type="text" class="form-control" name="bill_address"
                                        placeholder="Bill Address" value="161 Kallang Way">
                                </div>
                                <div class="mb-3">
                                    <input type="text" class="form-control" name="bill_city" placeholder="Bill City"
                                        value="New Delhi">
                                </div>
                                <div class="mb-3">
                                    <input type="text" class="form-control" name="bill_state" placeholder="Bill State"
                                        value="Delhi">
                                </div>
                                <div class="mb-3">
                                    <input type="text" class="form-control" name="bill_country"
                                        placeholder="Bill Country" value="US">
                                </div>
                                <div class="mb-3">
                                    <input type="text" class="form-control" name="bill_zip" placeholder="Zip Code"
                                        value="110001">
                                </div>
                                <div class="mb-3">
                                    <input type="text" class="form-control" name="bill_phone" placeholder="Bill Phone"
                                        value="9802155849">
                                </div>
                                <div class="mb-3">
                                    <select name="bill_currency" class="form-control">
                                        <option value="USD" selected="selected">USD</option>
                                        <option value="AUD">AUD</option>
                                        <option value="BTC">BTC</option>
                                        <option value="CAD">CAD</option>
                                        <option value="CNY">CNY</option>
                                        <option value="CZK">CZK</option>
                                        <option value="EUR">EUR</option>
                                        <option value="GBP">GBP</option>
                                        <option value="HKD">HKD</option>
                                        <option value="IDR">IDR</option>
                                        <option value="INR">INR</option>
                                        <option value="JPY">JPY</option>
                                        <option value="KHR">KHR</option>
                                        <option value="MXN">MXN</option>
                                        <option value="MYR">MYR</option>
                                        <option value="PHP">PHP</option>
                                        <option value="PLN">PLN</option>
                                        <option value="SGD">SGD</option>
                                        <option value="THB">THB</option>
                                        <option value="USD">USD</option>
                                        <option value="VND">VND</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <input type="text" class="form-control" name="bill_amt" placeholder="Bill Amount"
                                        value="2.00">
                                </div>
                            </div>
                            <div class="card-footer">
                                <input id="form_submit" type="submit" class="btn btn-primary btn-sm w-100 my-1"
                                    value="CONTINUE TO REDIRECT-HOST">
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-2">
                    <br>
                </div>
            </div>
        </div>
        <!-- End Main Content -->
    </section>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</html>