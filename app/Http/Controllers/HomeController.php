<?php

namespace App\Http\Controllers;

use App\Contracts\PaymentInterface;
use Exception;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function __construct(protected PaymentInterface $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index(Request $request)
    {
        return view('payment');
    }

    public function payment(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1|max:999.99',
            'discount' => 'required|numeric|min:0|lte:amount',
            'tax' => 'required|min:1|max:99.99',
        ]);

        try {
            if ($request->amount < 1 || $request->amount > 999.99 || $request->discount < 0 || $request->discount > $request->amount || $request->tax < 1 || $request->tax > 99.99) {
                throw new Exception("Invalid input values");
            }
            $isPaymentCharged = $this->paymentService->charge($request->amount, $request->discount, $request->tax);
            if ($isPaymentCharged) {
                return redirect()->back()->with('success', 'The Result is ' . $isPaymentCharged);
            }
        } catch (\Throwable $th) {
            logger($th->getMessage() . ' -- ' . $th->getLine() . ' -- ' . $th->getFile());
            return redirect()->back()->with('error', $th->getMessage());
        }

    }
}
