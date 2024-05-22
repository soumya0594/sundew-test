<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="//cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" />

    <title>Payment</title>
</head>

<body>
    <div class="container p-3 ">
        <div class="card justify-content-between col-10 mx-auto">
            <div class="card-header text-center">
                <div class="h4">Payment Form</div>
            </div>
            <div class="card-body ">
                <form action="{{ route('charge') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="form-group col-4">
                            <label for="amount">Amount</label>
                            <input type="number" name="amount" value="{{ old('amount') }}" id="amount"
                                class="form-control" min="1" max="999.99" step=".01">
                            @error('amount')
                                <span class="invalid-feedback d-block text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-4">
                            <label for="discount">Discount</label>
                            <input type="number" name="discount" id="discount" value="{{ old('discount') }}"
                                min="0" class="form-control">
                            @error('discount')
                                <span class="invalid-feedback d-block text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-4">
                            <label for="tax">Tax</label>
                            <input type="number" name="tax" id="tax" value="{{ old('tax') }}"
                                class="form-control" min="1" max="99.99" step=".01">
                            @error('tax')
                                <span class="invalid-feedback d-block text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-block btn-info">Pay</button>
                    </div>
                </form>
                <div class="m-2">
                    @if (Session::has('success'))
                        <p class="alert alert-success">{{ Session('success') }}</p>
                    @endif
                    @if (Session::has('error'))
                        <p class="alert alert-error">{{ Session('error') }}</p>
                    @endif
                </div>
            </div>
        </div>

    </div>
    <script src="//cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="//code.jquery.com/jquery-3.5.1.min.js"></script>
</body>

</html>
