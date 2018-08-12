<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AuthController extends ApiController {

    public function __construct()
    {
        parent::__construct();
    }



    /**
     * Register users function
     */
    public function register()
    {
        $username = !empty($_REQUEST['username']) ? $_REQUEST['username'] : '';
        $password = !empty($_REQUEST['password']) ? $_REQUEST['password'] : '';

        if (empty($username) || strlen($username) < 4) {
            $this->ERROR('Bad or Empty Username');
            return 0;
        }

        $this->db->from('users');
        $this->db->where('username', $username);
        $users_found = $this->db->count_all_results();

        if ($users_found != 0) {
            $this->ERROR('Name is already in use');
            return 0;
        }

        if (empty($password) || strlen($password) < 8) {
            $this->ERROR('Bad or Empty Password');
            return 0;
        }

        $this->db->insert('users', array(
            'username' => $username,
            'password' => md5($password),
            'token' => $this->generate_token()
        ));

        $this->login();

        return 0;
    }


    /**
     * Register users function
     */
    public function login()
    {
        $username = !empty($_REQUEST['username']) ? $_REQUEST['username'] : '';
        $password = !empty($_REQUEST['password']) ? $_REQUEST['password'] : '';

//        var_dump($_POST);

        if (empty($username) || empty($password)) {
            $this->ERROR(array('msg' => 'Empty Username or Password', 'auth' => $_REQUEST));
            return 0;
        }

        $this->db->select('username, token');
        $this->db->from('users');
        $this->db->where('username', $username);
        $this->db->where('password', md5($password));
        $user = $this->db->get()->row();

        if (empty($user)) {
            $this->ERROR('Wrong Username or Password');
            return 0;
        }

        $this->JSON($user);
        return 0;
    }


    /**
     * Get Bingo Cells
     */
    public function get_bingo_cells()
    {

    }


}
