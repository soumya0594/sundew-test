namespace App\Interfaces;

interface PaymentInterface
{
public function charge($amount, $discount, $tax);
}


<?php

namespace App\Services;

use App\Interfaces\PaymentInterface;
use Exception;

class PayPal implements PaymentInterface
{
    public function charge($amount, $discount, $tax)
    {
        if ($amount < 1 || $amount > 999.99 || $discount < 0 || $discount > $amount || $tax < 1 || $tax > 99.99) {
            throw new Exception("Invalid input values");
        }
        return ($amount - $discount) * ($tax / 100);
    }
}



<?php

namespace App\Services;

use App\Interfaces\PaymentInterface;
use Exception;

class Stripe implements PaymentInterface
{
    public function charge($amount, $discount, $tax)
    {
        if ($amount < 1 || $amount > 999.99 || $discount < 0 || $discount > $amount || $tax < 1 || $tax > 99.99) {
            throw new Exception("Invalid input values");
        }
        return ($amount * ($tax / 100)) - $discount;
    }
}



<?php

namespace App\Providers;

use App\Interfaces\PaymentInterface;
use App\Services\PayPal;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(PaymentInterface::class, PayPal::class);
    }

    public function boot()
    {
        //
    }
}


<?php

namespace App\Http\Controllers;

use App\Interfaces\PaymentInterface;
use Exception;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentInterface $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index()
    {
        return view('home');
    }

    public function payment(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1|max:999.99',
            'discount' => 'required|numeric|min:0|max:' . $request->input('amount'),
            'tax' => 'required|numeric|min:1|max:99.99',
        ]);

        try {
            $result = $this->paymentService->charge(
                $request->input('amount'),
                $request->input('discount'),
                $request->input('tax')
            );
            return back()->with('success', 'The result is: ' . $result)->withInput();
        } catch (Exception $e) {
            return back()->withErrors($e->getMessage())->withInput();
        }
    }
}




<!DOCTYPE html>
<html>
<head>
    <title>Payment Form</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="container">
    <h2>Payment Form</h2>
    <form method="POST" action="{{ route('payment') }}">
        @csrf
        <div class="form-group">
            <label for="amount">Amount:</label>
            <input type="text" class="form-control" id="amount" name="amount" value="{{ old('amount') }}">
        </div>
        <div class="form-group">
            <label for="discount">Discount:</label>
            <input type="text" class="form-control" id="discount" name="discount" value="{{ old('discount') }}">
        </div>
        <div class="form-group">
            <label for="tax">Tax (%):</label>
            <input type="text" class="form-control" id="tax" name="tax" value="{{ old('tax') }}">
        </div>
        <button type="submit" class="btn btn-primary">Pay</button>
    </form>
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success">
            <p>{{ session('success') }}</p>
        </div>
    @endif
</div>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>



'providers' => [
    // Other service providers...
    App\Providers\PaymentServiceProvider::class,
],


use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('index');
Route::post('/payment', [HomeController::class, 'payment'])->name('payment');