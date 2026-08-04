    Log::info('payFuel called', ['request' => $request->all()]);

    $validator = Validator::make($request->all(), [
        'phone' => 'required',
        'amount' => 'required|numeric|min:10',
        'station_id' => 'required', 
    ]);

    if ($validator->fails()) {
        Log::warning('Validation failed', ['errors' => $validator->errors()]);
        return response()->json(['errors' => Helpers::error_processor($validator)], 403);
    }
    $station_id = $request->input('station_id');
    //$vehicle_id = $request->input('vehicle_id');
     
      
    //Langata 
    if($station_id =="1"){ 
    $amount = $request->input('amount');
    $phone = str_replace('+', '', $request->input('phone'));
    $consumerkey ='a1coOpqANG9Jdl8L6a8Afwyy8TYqdwpbmDxNHfxXA1mfFiJm';
    $consumersecret = 'jNuO5yqP60oe1CnhG9drsFg2j2J4MAIEWCsq8tm414ySMknP92925V3ej8ACRjhE';
    $key = '0hGItotBR23JXHiAvaYNzlet22yzlZQ7sa9_mo7VNX_xHeSBfClLufmCVZRUuyTwJF311JHuT';

    $authenticationurl = 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

    $headers = ['Content-Type: application/json; charset=utf-8'];
    $ch = curl_init($authenticationurl);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_USERPWD, "$consumerkey:$consumersecret");

    $result = curl_exec($ch);
    $error = curl_error($ch);
    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($error) {
        Log::error('Authentication cURL error', ['error' => $error]);
        return response()->json(['error' => 'Connection error during authentication'], 500);
    }

    Log::info('Authentication response', ['status' => $statusCode, 'response' => $result]);

    $result = json_decode($result);
    $access_token = $result->access_token ?? null;

    if (!$access_token) {
        Log::error('Access token not found in response', ['response' => $result]);
        return response()->json(['error' => 'Unable to authenticate with Safaricom API'], 500);
    }

    $shortcode = '5561300';
    $passkey = 'e49582ad8b4afa18453a1a5f6b98cdbbf5ae16bad1817bd010998c59108b7139';
    $timestamp = date('YmdHis');
    $password = base64_encode($shortcode . $passkey . $timestamp);

    $stkUrl = 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

    $payload = [
        'BusinessShortCode' => $shortcode,
        'Password' => $password,
        'Timestamp' => $timestamp,
        'TransactionType' => 'CustomerBuyGoodsOnline',
        'Amount' => $amount,
        'PartyA' => $phone,
        'PartyB' => '5633294',
        'PhoneNumber' => $phone,
        'CallBackURL' => 'https://fundyetu-api.onrender.com/api/payments/callback',
        'AccountReference' => 'Fuel',
        'TransactionDesc' => 'Fuel',
    ];

    Log::info('Sending STK push', ['payload' => $payload]);

    $curl = curl_init($stkUrl);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $access_token
    ]);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));

    $response = curl_exec($curl);
    $error = curl_error($curl);
    curl_close($curl);

    if ($error) {
        Log::error('STK push cURL error', ['error' => $error]);
        return response()->json(['error' => 'Payment request failed'], 500);
    }

    $responseData = json_decode($response, true);
    Log::info('STK push response received', ['response' => $responseData]);

    return response()->json([
        'message' => 'Payment request sent successfully',
        'data' => $responseData
    ]);
        
    }