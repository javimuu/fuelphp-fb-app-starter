<?php

class FacebookUtility {

    // check if user or FB bot
    public static function is_bot() {
        if (strstr(strtoupper($_SERVER['HTTP_USER_AGENT']), 'FACEBOOK')) {
            return true;
        } else {
            return false;
        }
    }

    // modified from FB docs
    public static function parse_signed_request($signed_request = null, $secret = null)
    {
        if ($signed_request == null || $secret == null) {
            return null;
        }

        list($encoded_sig, $payload) = explode('.', $signed_request, 2);

        // decode the data
        $sig = base64_decode(strtr($encoded_sig, '-_', '+/'));
        $request_data = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);

        if (strtoupper($request_data['algorithm']) !== 'HMAC-SHA256') {
            return null;
        }

        // check sig
        $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
        if ($sig !== $expected_sig) {
            return null;
        }

        return $request_data;
    }

    //build the full_request_id from request_id and user_id
    public static function build_full_request_id($request_id, $user_id)
    {
        return $request_id . '_' . $user_id;
    }

}

/* End of file facebookutility.php */