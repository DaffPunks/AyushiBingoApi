<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class ApiController extends CI_Controller
{
    public $user_id;

    public function __construct()
    {
        parent::__construct();

        header('Content-Type: application/json');

        $this->load->database();
    }

    /**
     * Get All headers from request
     */
    private function _get_all_headers () {
        foreach ( $_SERVER as $name => $value ) {
            if ( substr( $name, 0, 5 ) == 'HTTP_' ) {
                $name = str_replace( ' ', '-', ucwords( strtolower( str_replace( '_', ' ', substr( $name, 5 ) ) ) ) );
                $headers[ $name ] = $value;
            } else if ( $name == "CONTENT_TYPE" ) {
                $headers["Content-Type"] = $value;
            } else if ( $name == "CONTENT_LENGTH" ) {
                $headers["Content-Length"] = $value;
            }
        }

        return $headers;
    }


    /**
     * Default wrapper for output JSON data
     */
    protected function verify_token () {
        $headers = $this->_get_all_headers();

        $hash = isset( $_POST["hash"] ) ? $_POST["hash"] : ( isset( $headers["Hash"] ) ? $headers["Hash"] : FALSE );

        if(!empty($hash)) {
            $this->db->from('users');
            $this->db->where('token', $hash);
            $user = $this->db->get()->row();

            if (!empty($user)) {
                $this->user_id = $user->id;
                return 0;
            }
        }

        $this->ERROR('Invalid Hash');
        exit();
    }


    /**
     * Default wrapper for output JSON data
     * @param $data
     * @param int $code
     */
    protected function JSON($data, $code = 200) {
        echo json_encode(array(
            'status' => $code,
            'message' => $data
        ), JSON_UNESCAPED_UNICODE);
    }


    /**
     * Wrapper for throw errors;
     * @param $condition
     * @param int $code
     */
    protected function ERROR($condition, $code = 400) {
        echo json_encode(array(
            'status' => $code,
            'message' => $condition
        ), JSON_UNESCAPED_UNICODE);
    }


    /**
     * Generating token for users
     * @param int $length
     * @return string
     */
    protected function generate_token($length = 25) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}

