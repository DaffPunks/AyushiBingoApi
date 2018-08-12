<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class OpenDataController extends ApiController {

    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Get Bingo Cells
     */
    public function get_bingo_cells()
    {
        $this->db->from('cells');
        $cells = $this->db->get()->result();

        $this->JSON($cells);
    }


}
