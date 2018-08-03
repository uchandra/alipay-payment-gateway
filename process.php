
Step 1- How to create order:-

use App\PaymentGateway\AliPay;
  $aop = new AliPay();
  $out_trade_no = $request->case_history_id . uniqid();
  $seller_id = config('alipay.seller_id');
  $buyer_logon_id = $request->get('buyer_logon_id'); // 'ljekab2340@sandbox.com';
  $subject = 'Umesh Software Payment: Case ID/Service ID: ' . $request->case_id . '/' . $request->case_history_id;
  $body = 'Umesh Software';
  $total_amount = $arrPaymentData->customer_price;
                    
$paymentData = array();
                    $paymentData['out_trade_no'] = $out_trade_no;
                    $paymentData['seller_id'] = $seller_id;
                    $paymentData['total_amount'] = $total_amount;
                    $paymentData['buyer_logon_id'] = $buyer_logon_id;
                    $paymentData['subject'] = $subject;
                    $paymentData['body'] = $body;
                    $responseData = $aop->createAlipayOrder($paymentData);
                    if ($responseData) {
                        if ($responseData->alipay_trade_create_response->code == 10000) {
                            $updateData = [
                                'out_trade_no' => $responseData->alipay_trade_create_response->out_trade_no,
                                'trade_no' => $responseData->alipay_trade_create_response->trade_no,
                                'buyer_logon_id' => $buyer_logon_id,
                                'buyer_pay_amount' => $total_amount,
                                'payment_method' => $request->get('payment_method'),
                                'payment_comment' => $request->get('payment_comment')
                            ];
                           }
                      }
       
Step 2-Check response-:

 $aop = new AliPay();
            $paymentData['out_trade_no'] = $ut_trade_no;
            $responseData = $aop->checkAlipayPaymentStatus($paymentData);
            if ($responseData->alipay_trade_query_response->code == 10000) {
                if ($responseData->alipay_trade_query_response->trade_status == 'TRADE_FINISHED' || $responseData->alipay_trade_query_response->trade_status == 'TRADE_SUCCESS') {
                    $updateData = [ 'payment_status' => 'Completed',
                        'buyer_user_id' => $responseData->alipay_trade_query_response->buyer_user_id,
                        'buyer_pay_amount' => $responseData->alipay_trade_query_response->buyer_pay_amount,
                        'trade_status' => $responseData->alipay_trade_query_response->trade_status,
                        //'open_id'=>$responseData->alipay_trade_query_response->open_id,
                        'sign' => $responseData->sign
                    ];

                }              

            }
