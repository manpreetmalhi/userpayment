<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Mail\PaymentSuccessful;
use App\Mail\MailController;

use Illuminate\Support\Facades\Mail;

class StripeController extends Controller
{

    public function stripe(Request $request)
    {
        try{
        $stripe = new \Stripe\StripeClient(config('stripe.stripe_sk'));
        $response = $stripe->checkout->sessions->create([
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $request->product_name,
                        ],
                        'unit_amount' => $request->price*100,
                    ],
                    'quantity' => $request->quantity,
                ],
            ],
            'mode' => 'payment',
            'success_url' => route('success').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('cancel'),
        ]);
        if(isset($response->id) && $response->id != ''){
            session()->put('product_name', $request->product_name);
            session()->put('quantity', $request->quantity);
            session()->put('price', $request->price);
            return redirect($response->url);
        } else {
            return redirect()->route('cancel');
        }
    }catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'msg' => $e->getMessage(),
        ]);
    }
    }

    public function success(Request $request)
    {
try{
        if(isset($request->session_id)) {


    
            $stripe = new \Stripe\StripeClient(config('stripe.stripe_sk'));
            $response = $stripe->checkout->sessions->retrieve($request->session_id);
               
            $payment = new Payment();
            $payment->payment_id = $response->id;
            $payment->product_name = session()->get('product_name');
            $payment->quantity = session()->get('quantity');
            $payment->amount = session()->get('price');
            $payment->currency = $response->currency;
            $payment->payer_name = $response->customer_details->name;
            $payment->payer_email = $response->customer_details->email;
            // $payment->payer_name ='Test';
            // $payment->payer_email = 'Test123@gmail.com';
            $payment->payment_status = $response->status;
            $payment->payment_method = "Stripe";
            $payment->save();
            $data['url'] = 'http://127.0.0.1:8000';
            $data['email'] = $response->customer_details->email;
            $data['payer_name'] = $response->customer_details->name;
            $data['title'] = 'Stripe Payment';
            $data['body'] = 'We are pleased to inform you that your payment has been successfully processed. Thank you for your purchase!';
           
            Mail::send('stripePayment', ['data'=>$data], function($message) use ($data){
                $message->to($data['email'])->subject($data['title']);
            });
           
           return "Payment is successful";
            
            session()->forget('product_name');
            session()->forget('quantity');
            session()->forget('price');
    
        } else {
            return redirect()->route('cancel');
        }
    }catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'msg' => $e->getMessage(),
        ]);
    }
    }
    

    public function refund(Request $request)
    {

        $request->validate([
            'payment_id' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ]);

        try {

           $stripe = new \Stripe\StripeClient(config('stripe.stripe_sk'));
           $payment = Payment::where('payment_id', $request->payment_id)->first();

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'msg' => 'Payment not found',
                ]);
            }

            // Create a refund
            $refund = $stripe->refunds->create([
                'charge' => $payment->payment_id,
                'amount' => $request->amount * 100, 
            ]);

            return response()->json([
                'success' => true,
                'msg' => 'Refund processed successfully',
                'refund' => $refund,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    public function payment()
    {
        return view('payment');
       
    }
    
    public function cancel()
    {
        return "Payment is canceled.";
    }
}
