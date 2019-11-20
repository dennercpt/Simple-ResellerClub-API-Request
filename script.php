<?php
    define('USER_ID', '1234567'); //https://manage.br.resellerclub.com/servlet/ModResellerProfileServlet
    define('API_KEY', 'MY_API_KEY'); //https://manage.br.resellerclub.com/servlet/APIManagementServlet       
    
    function getOrderId($domain)
    {
        $www = file_get_contents("https://httpapi.com/api/domains/orderid.json?auth-userid=" . USER_ID . "&api-key=" . API_KEY . "&domain-name=" . $domain);
        return isset($www) ? $www : NULL;
    }
    
    function getDetails($orderId)
    {
        $www = file_get_contents("https://httpapi.com/api/domains/details.json?auth-userid=" . USER_ID . "&api-key=" . API_KEY . "&order-id=" . $orderId . "&options=OrderDetails");
        return isset($www) ? $www : NULL;
    }
    
    function getDNS($orderId)
    {
        $www = file_get_contents("https://httpapi.com/api/impressly/dns-record.json?auth-userid=" . USER_ID . "&api-key=" . API_KEY . "&order-id=" . $orderId);
        return isset($www) ? $www : NULL;
    }
    
    function modifyNS(&$array)
    {
        $ch = curl_init();        
        curl_setopt($ch, CURLOPT_URL, "https://httpapi.com/api/domains/modify-ns.json");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, str_replace(['%5B3%5D', '%5B2%5D', '%5B1%5D', '%5B0%5D'], '',http_build_query($array)));
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       
        
        $response = curl_exec($ch);       
        curl_close($ch);             
        
        return $response;
    }
    
    if(isset($_POST['editNS'], $_POST['domain'], $_POST['ns1'], $_POST['ns2']))
    {
        $array = [
            "auth-userid" => USER_ID,
            "api-key" => API_KEY,
            "order-id" => getOrderId($_POST['domain']),
            "ns" => [
                $_POST['ns1'],
                $_POST['ns2']            
            ]
        ];  
        
        $editNS = modifyNS($array);
        print_r($editNS);
    }
    
    
    