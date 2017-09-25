<?php

return array(

    /**
     * Set our Sandbox and Live credentials
     */
    'sandbox_client_id' => env('PAYPAL_SANDBOX_CLIENT_ID', 'AQDEKq4WUwNs-PkAPe5IwpE4QoR1iCCVl82VCSjWHyG4hOpX-8573MoNoRAQ22qgzH3ZB2TAie31xgzY'),
    'sandbox_secret' => env('PAYPAL_SANDBOX_SECRET', 'ELooHOVHf84m0Ov4jYh5lt1KQ7UeCqq_781eWbfOrDTi8l_ScBAZLOWyC8QFLl-0KNEH458Gv2tXIAs1'),
    'live_client_id' => env('PAYPAL_LIVE_CLIENT_ID', 'AebHsSdZdePT3omEiLlf9ZWCUNHU6P5LFIjT9Ba9WHg7VLJiYVXZKhJk3T34mbb-2NtEAWCM2VRUe2Oy'),
    'live_secret' => env('PAYPAL_LIVE_SECRET', 'EByg2Ma7kSbvGlESzJ1Qa1r7KqUxE7loeR60WnJfcvKeY7FHEGONEeTrA0yRkqjktWrinZUCc7_lMUBD'),

    
    /**
     * SDK configuration settings
     */
    'settings' => array(

        /** 
         * Payment Mode
         *
         * Available options are 'sandbox' or 'live'
         */
        'mode' => env('PAYPAL_MODE', 'live'),
        
        // Specify the max connection attempt (3000 = 3 seconds)
        'http.ConnectionTimeOut' => 3000,
       
        // Specify whether or not we want to store logs
        'log.LogEnabled' => true,
        
        // Specigy the location for our paypal logs
        'log.FileName' => storage_path() . '/logs/paypal.log',
        
        /** 
         * Log Level
         *
         * Available options: 'DEBUG', 'INFO', 'WARN' or 'ERROR'
         * 
         * Logging is most verbose in the DEBUG level and decreases 
         * as you proceed towards ERROR. WARN or ERROR would be a 
         * recommended option for live environments.
         * 
         */
        'log.LogLevel' => 'DEBUG'
    ),
);