<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/BaseController.php';
/**
 * Class : Pages (PagesController)
 * Login class to control to authenticate user credentials and starts user's session.
 * @author : Rangaballava Swain
 * @version : 1.1
 * @since : 02 May 2018
 */
class Pages extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('pages_model');
        $this->load->model('user_model');
        $this->isLoggedIn(); 
    }

    /**
     * Index Page for this controller.
     */
    public function index()
    {
        $this->pages();
    }
    
    /**
     * This function used to pages dynamic 
     */
    public function pages()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {        
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
            $data['searchText'] = $searchText;
            
            $this->load->library('pagination');
            
            $count = $this->pages_model->userListingCount($searchText);

            $returns = $this->paginationCompress("pages/", $count, 10);
            
            $data['userRecords'] = $this->pages_model->pages($searchText, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = 'RB : Pages';
            
            $this->loadViews("pages/pages_view", $this->global, $data, NULL);
        } 
    }
    public function addNew()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->model('user_model');
            $data['roles'] = $this->user_model->getUserRoles();
            
            $this->global['pageTitle'] = 'Rb : Add New Pages';

            $this->loadViews("pages/add_new_pages_view", $this->global, $data, NULL);
        } 
    }
    public function edit()
    {
        $this->loadViews("pages/edit_view", $this->global, $data, NULL);
    }
    public function delete()
    {
        $this->loadViews('pages/delete_view', $this->global, $data, NULL);
    } 
}

?>