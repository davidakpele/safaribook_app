<?php

namespace JwtToken;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;

final class JwtService
{
    private static $jwtKey;
    private $isoArray;

    public function __construct() {
        $this->isoArray = [];
        self::$jwtKey = getenv('API_SECURITY_KEY');
    }

    public static function createTokenByUserDetails($id, $email, $role):string{
        $key = base64_decode(self::$jwtKey);
        $headers = ['typ' => 'JWT'];

        $payload = [
            'roles' => [$role],
            'sub' => $email,
            'aso'=> "SAFARI-BOOKS LTD",
            'group'=>[
                'id'=>$id
            ],
            'iat' => time(),
            'exp' => time() + 60 * 60 * 24,
        ];

        $jwt = JWT::encode($payload, $key, 'HS256', null, $headers);
        return json_encode(['token' => $jwt]);
    }
   
    public static function createTokenByAdmniDetails():string{
        $key = base64_decode(self::$jwtKey);
        $headers = ['typ' => 'JWT'];

        $payload = [
            'roles' => ['ADMIN'],
            'sub' => 'SUPER USER',
            'group'=>[
                'id'=>'1'
            ],
            'iat' => time(),
            'iss'=> "SAFARI-BOOKS LTD, SERVER IMPRINT TOKEN",
        ];

        $jwt = JWT::encode($payload, $key, 'HS256', null, $headers);
        return json_encode(['token' => $jwt]);
    }

    public static function decodeToken($token) {
        try {
            $key = base64_decode(self::$jwtKey);
            $decoded = JWT::decode($token, new Key($key, 'HS256'));

            return [
                'status' => '202',
                'data' => [
                    'token' => $token,
                    'decoded' => (array) $decoded,
                ]
            ];
        } catch (ExpiredException $e) {
            // Return a specific message for expired tokens
            return [
                'status' => '401',
                'error' => 'Token expired',
                'details' => 'The token has expired. Please request a new one.'
            ];
        } catch (SignatureInvalidException | BeforeValidException | UnexpectedValueException | InvalidArgumentException $e) {
            // Return a general error response for other JWT issues
            return [
                'status' => '401',
                'error' => 'Invalid token',
                'details' => $e->getMessage()
            ];
        }
    }

    public static function isNotValidUserToken():bool{
        return false;
    }

    public function getToken(){
        $length = 250;
        $token = "";
        $encrypted  = "A41wt2Lsq30A9Ox/WehogvJckPI4aY9RoSxhb8FMtVnqaUle1AtI6Yf7Wk+7";
        $encrypted .= "joxNjI2MjIwNzk5LCJpc1N1YmRvbWFpbiI6dHJ1ZSwiaXNUaGlyZFBhcnR5Ijp0cnVlfQ==";
        $encrypted .= "0123456789";
        $encrypted .= "+Wm0AfDDOkMX+Wn6wnDpBWYgWwYAAAB8eyJvcmlnaW4iOiJodHRwczovL2Fkcm9sbC5jb206NDQzIiw";
        $encrypted .= "A41wt2Lsq30A9Ox/WehogvJckPI4aY9RoSxhb8FMtVnqaUle1AtI6Yf7Wk+7+";
        $max = strlen($encrypted);
        $tokenCore = '';
        for ($i=0; $i < $length; $i++) {
            $tokenCore .= $encrypted[crypto_rand_secure(0, $max-1)];
        }
        return $tokenCore.'=';
    }
}
