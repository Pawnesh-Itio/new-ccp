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
                        <form id="formId" target="_blank" method="post"action="https://connect.trolimpay.com/checkout">

                            <div class="card-body">
                                <!-- Form Body -->
                                <div class="mb-3">
                                    <input type="text" class="form-control" name="terNO" title="terNO"
                                        placeholder="TerNo">
                                </div>
                                <div class="mb-3">
                                    <input type="text" class="form-control" name="public_key" placeholder="Public Key">
                                </div>
                                <div class="mb-3">
                                    <input type="text" class="form-control" name="reference" placeholder="Reference">
                                </div>
                                <div class="mb-3">
                                    <input type="text" class="form-control" name="success_url" placeholder="Success URL"
                                        value="https://connect.trolimpay.com/success">
                                </div>
                                <div class="mb-3">
                                    <input type="text" class="form-control" name="failur_url" placeholder="Failur URL"
                                        value="https://connect.trolimpay.com/failure">
                                </div>
                                <div class="mb-3">
                                    <input type="text" class="form-control" name="product_info"
                                        placeholder="Product Info" value="Test Product" >
                                </div>
                                <div class="mb-3">
                                    <input type="text" class="form-control" name="firstname" placeholder="First Name"
                                    value="Test">
                                </div>
                                <div class="mb-3">
                                    <input type="text" class="form-control" name="phone" placeholder="Phone Number" value="0123456789">
                                </div>
                                <div class="mb-3">
                                    <input type="email" class="form-control" name="email" placeholder="E-mail" value="test@gmail.com">
                                </div>
                                <div class="mb-3">
                                    <input type="text" class="form-control" name="amount" placeholder="Amount (Decimal Number)" value="5.2">
                                </div>
                                <div class="mb-3">
                                    <input type="text" class="form-control" name="address_1" placeholder="Address 1" value="Address 1">
                                </div>
                                <div class="mb-3">
                                    <input type="text" class="form-control" name="address_2" placeholder="Address 2" value="Address 2">
                                </div>
                                <div class="mb-3">
                                    <input type="text" class="form-control" name="city" placeholder="City" value="test city">
                                </div>
                                <div class="mb-3">
                                    <input type="text" class="form-control" name="state" placeholder="State" value="test state">
                                </div>
                                <div class="mb-3">
                                    <input type="text" class="form-control" name="country" placeholder="Country" value="test country">
                                </div>
                                <div class="mb-3">
                                    <input type="text" class="form-control" name="zip" placeholder="Zip Code" value="123456">
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