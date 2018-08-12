<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RestController extends ApiController {

    public function __construct()
    {
        parent::__construct();

        $this->verify_token();
    }


    /**
     * Get My Bingo Cells
     */
    public function get_my_bingo()
    {
        $this->db->select('cell_id');
        $this->db->where('user_id', $this->user_id);
        $this->db->from('users_checks');
        $my_cells = $this->db->get()->result();

        $cells_array = array();
        foreach ($my_cells as $cell) {
            $cells_array[] = $cell->cell_id;
        }

        $this->JSON($cells_array);
    }


    /**
     * Get My Bingo Cells
     */
    public function set_my_bingo()
    {
        $cell_id = !empty($_REQUEST['cell_id']) ? $_REQUEST['cell_id'] : '';

        if (empty($cell_id)) {
            $this->ERROR('No Cell ID');
            return 0;
        }

        $this->db->where('user_id', $this->user_id);
        $this->db->where('cell_id', $cell_id);
        $this->db->from('users_checks');
        $user_check = $this->db->get()->row();

        if (empty($user_check)) {
            $this->db->insert('users_checks', array(
                'user_id' => $this->user_id,
                'cell_id' => $cell_id
            ));
        } else {
            $this->db->delete('users_checks', array(
                'user_id' => $this->user_id,
                'cell_id' => $cell_id
            ));
        }

        $this->JSON(array(
            'status' => 1
        ));
    }


}
