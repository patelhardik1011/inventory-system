<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inventory System</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
            crossorigin="anonymous"></script>
    <style>
        html,
        body {
            height: 100%;
        }

        body {
            display: -ms-flexbox;
            display: -webkit-box;
            display: flex;
            -ms-flex-align: center;
            -ms-flex-pack: center;
            -webkit-box-align: center;
            align-items: center;
            -webkit-box-pack: center;
            justify-content: center;
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #f5f5f5;
        }

        .row {
            margin-bottom: 15px;
        }

        .form-inventory {
            width: 100%;
            max-width: 500px;
            padding: 15px;
            margin: 0 auto;
        }

        .form-inventory .checkbox {
            font-weight: 400;
        }

        .form-inventory .form-control {
            position: relative;
            box-sizing: border-box;
            height: auto;
            padding: 10px;
            font-size: 16px;
        }

        .form-inventory .form-control:focus {
            z-index: 2;
        }

        .hide {
            display: none;
        }

        .show {
            display: block !important;
        }
    </style>
</head>
<body class="text-center">
<form class="form-inventory">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-3 font-weight-normal">Inventory Stock</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <label for="inputEmail" class="sr-only">Stock Quantity</label>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <input type="text" name="quantity" id="inputQuantity" class="form-control" placeholder="Quantity" required
                   autofocus>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <button class="btn btn-lg btn-primary btn-block" name="submitQty" type="button">Submit</button>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-3 font-weight-normal">Stock Amount:&nbsp;<b style="color: red" id="stockAmount"></b>
            </h1>
        </div>
        <div class="col-12 hide" id="errors"></div>
    </div>
</form>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
<script type="text/javascript">
    let appUrl = "{{ url('/') }}";
    $(document).ready(function () {
        $('button[name=submitQty]').on('click', function () {
            let saveData = $.ajax({
                type: 'POST',
                url: appUrl + "/api/inventory",
                data: {'quantity': $('input[name=quantity]').val()},
                dataType: "text",
                success: function (resultData) {
                    let resultArray = JSON.parse(resultData);
                    if (resultArray.success === true) {
                        $('b#stockAmount').html(resultArray.data.amount);
                        $('b#stockAmount').parent().addClass('show');
                        $('#errors').addClass('hide');
                        $('#errors').html('');
                    } else {
                        $('b#stockAmount').parent().addClass('hide');
                        $('#errors').addClass('show');
                        if (typeof resultArray.errors.quantity != 'undefined') {
                            $('#errors').html('<span style="color:red">' + resultArray.errors.quantity + '</span>');
                        } else {
                            $('#errors').html('<span style="color:red">' + resultArray.errors + '</span>');
                        }
                    }
                },
                error: function (result) {

                }
            });
        });
    });
</script>
</html>
