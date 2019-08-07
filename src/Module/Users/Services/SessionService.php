<?php

    namespace Lab19\Cart\Module\Users\Services;

    use Illuminate\Support\Str;
    use Lab19\Cart\Module\Users\Session;
    use Lab19\Cart\Module\Users\User;
    use Lab19\Cart\Module\Orders\Cart;
    use Illuminate\Http\Request;

    class SessionService {

        const NAMESPACE = 'cart';

        public function __construct( Request $request ){
            $this->request = $request;
            $this->session = new Session;
            $this->session->token = Str::random(60);
            $this->session->data = [
                'cart_uuid' => Str::uuid()
            ];

            // If the request provides a bearer token
            // we can potentially get the session from the request
            if( $this->request->bearerToken() ){
                $this->session->token = $request->bearerToken();
                if( $request->session && $request->session->id ){
                    $this->session = $request->session;
                }
            }
        }

        public function exists(){
            return $this->session->id !== null;
        }

        public function setFromToken( $token ){
            $session = Session::where('token', '=', $token )->first();
            if( !$session ){
                // TODO: This is dangerous, we don't want to give users the ability
                // to arbitrarily set their token due to CSRF.
                // This is used only in the scenario where a guest user
                // logs in and needs to merge their current session
                // Use this method with care
                $this->session->token = $token;
                $this->session->save();
            } else {
                $this->session = $session;
                return $this->session;
            }
        }

        public function getFromToken( $token ){
            $session = Session::where('token', '=', $token )->first();
            return $session;
        }

        public function mergeWithUser( User $user ){
            $this->session->user_id = $user->id;
            $this->session->save();
            $this->session->cart('load');
            if( $cart = $this->session->cart ){
                $cart->user_id = $user->id;
                $cart->save();
            }
        }

        public function update( Array $array ){
            $this->session->data = array_merge( $this->session->data, $array );
            $this->save();
        }

        public function save(){
            $this->session->save();
        }

        public function close(){
            $result = $this->session->delete();
            return $result;
        }

        public function get( $key = null ){
            if( $key ){
                return $this->session->data[ $key ] ?? null;
            }
            return $this->session->data;
        }

        public function getToken(){
            return $this->session->token;
        }

    }
