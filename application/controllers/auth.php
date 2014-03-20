<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->library('form_validation');

        $this->load->database();

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

        $this->lang->load('auth');
        $this->load->helper('language');
    }

    //redirect if needed, otherwise display the user list
    function index()
    {
        check_profile(array("Administrador"));
        
        if (!$this->ion_auth->logged_in())
        {
            //redirect them to the login page
            redirect('auth/login', 'refresh');
        }
        elseif (!$this->ion_auth->is_admin()) //remove this elseif if you want to enable this for non-admins
        {
            //redirect them to the home page because they must be an administrator to view this
            return show_error('You must be an administrator to view this page.');
        }
        else
        {
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            //list the users
            $this->data['users'] = $this->ion_auth->users()->result();
            foreach ($this->data['users'] as $k => $user)
            {
                $this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
            }

            //$this->_render_page('auth/index', $this->data);
            $this->twiggy->set($this->data, null);
            $this->twiggy->template('auth/index');
            $this->twiggy->display();
        }
    }

    //log the user in
    function login()
    {
        $this->data['title'] = "Login";

        //validate form input
        //$this->form_validation->set_rules('identity', 'Identity', 'required');
        //$this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->input->post())
        {
            $this->load->library("user_agent");
            if(($this->agent->browser() == 'Internet Explorer' and $this->agent->version() <= 9)) {
                $this->twiggy->template("auth/unsupported");
                $this->twiggy->display();
                return;
            }
            
            //check to see if the user is logging in
            //check for "remember me"
            $remember = (bool) $this->input->post('remember');

            if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember))
            {
                //if the login is successful
                //redirect them back to the home page
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect('/', 'refresh');
            }
            else
            {
                //if the login was un-successful
                //redirect them back to the login page
                //$this->session->set_flashdata('message', $this->ion_auth->errors());
                $this->data['message'] = $this->ion_auth->errors();
                //redirect('auth/login', 'refresh'); //use redirects instead of loading views for compatibility with MY_Controller libraries
            }
        }
        //else
        {
            //the user is not logging in so display the login page
            //set the flash data error message if there is one
            //$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $this->data['identity'] = array('name' => 'identity',
                'id' => 'identity',
                'type' => 'text',
                'value' => $this->form_validation->set_value('identity'),
                'class' => 'form-control',
                'style' => 'height:50px',
                'placeholder' => 'Nombre de Usuario',
            );
            $this->data['password'] = array('name' => 'password',
                'id' => 'password',
                'type' => 'password',
                'class' => 'form-control',
                'style' => 'height:50px',
                'placeholder' => 'Contraseña',
            );

            //$this->_render_page('auth/login', $this->data);
            
            $this->twiggy->set("old_ie", $old_ie);
            $this->twiggy->set($this->data, null);
            $this->twiggy->template('auth/login');
            $this->twiggy->display();
        }
    }

    //log the user out
    function logout()
    {
        //$this->data['title'] = "Logout";

        //log the user out
        $logout = $this->ion_auth->logout();

        //redirect them to the login page
        //$this->session->set_flashdata('message', $this->ion_auth->messages());
        redirect('/', 'refresh');
    }

    //change password
    function change_password()
    {
        $this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
        $this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
        $this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');

        if (!$this->ion_auth->logged_in())
        {
            redirect('auth/login', 'refresh');
        }

        $user = $this->ion_auth->user()->row();

        if ($this->form_validation->run() == false)
        {
            //display the form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
            $this->data['old_password'] = array(
                'name' => 'old',
                'id'   => 'old',
                'type' => 'password',
            );
            $this->data['new_password'] = array(
                'name' => 'new',
                'id'   => 'new',
                'type' => 'password',
                'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
            );
            $this->data['new_password_confirm'] = array(
                'name' => 'new_confirm',
                'id'   => 'new_confirm',
                'type' => 'password',
                'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
            );
            $this->data['user_id'] = array(
                'name'  => 'user_id',
                'id'    => 'user_id',
                'type'  => 'hidden',
                'value' => $user->id,
            );

            //render
            $this->_render_page('auth/change_password', $this->data);
        }
        else
        {
            $identity = $this->session->userdata($this->config->item('identity', 'ion_auth'));

            $change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

            if ($change)
            {
                //if the password was successfully changed
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                $this->logout();
            }
            else
            {
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect('auth/change_password', 'refresh');
            }
        }
    }

    //activate the user
    public function activate($id, $code=false)
    {
        if ($code !== false)
        {
            $activation = $this->ion_auth->activate($id, $code);
        }
        else if ($this->ion_auth->is_admin())
        {
            $activation = $this->ion_auth->activate($id);
        }

        if ($activation)
        {
            //redirect them to the auth page
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("auth", 'refresh');
        }
        else
        {
            //redirect them to the forgot password page
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect("auth/forgot_password", 'refresh');
        }
    }


    function deactivate($id = NULL)
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
        {
            $this->ion_auth->deactivate($id);
        }
        redirect('auth', 'refresh');
    }


    //create a new user
    function create_user()
    {
        check_profile(array("Administrador"));
        
        $this->data['title'] = "Create User";

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
        {
            redirect('auth', 'refresh');
        }

        //validate form input
        $this->form_validation->set_rules('username', 'Nombre de Usuario', 'trim|required|min_length[4]|max_length[50]|callback_islower|is_unique[users.username]');
        $this->form_validation->set_message('is_unique', 'El %s ya está siendo usado. Escriba otro.');
        $this->form_validation->set_rules('first_name', 'Nombre', 'trim|required');
        $this->form_validation->set_rules('last_name', 'Apellidos', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'valid_email');
        $this->form_validation->set_rules('password', 'Contraseña', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', 'Confirmación de Contraseña', 'required');
        $this->form_validation->set_rules('profile', 'Perfil', 'required');
        if ($this->form_validation->run() == true)
        {
            $username = $this->input->post('username');
            $email    = strtolower($this->input->post('email'));
            $password = $this->input->post('password');

            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name'  => $this->input->post('last_name'),
                'phone'      => $this->input->post('phone'),
            );
        
            if ($this->ion_auth->register($username, $password, $email, $additional_data, array($this->input->post('profile'))))
            {
                //check to see if we are creating the user
                //redirect them back to the admin page
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect("auth", 'refresh');
            }
            else {
                $this->data['message'] = $this->ion_auth->errors();
            }
        }
	    
        $perfiles = assoc($this->ion_auth->groups()->result(), 'id', 'name');
        $this->twiggy->set("perfiles",  $perfiles);
        $this->twiggy->set($this->data, null);
        $this->twiggy->template("auth/create_user");
        $this->twiggy->display();
        
    }

    public function islower($str)
    {
        if(strtolower($str)==$str) return true;
        else 
        {
            $this->form_validation->set_message('islower', 'El campo %s debe estar minúsculas.');
            return false;
        }
    }

    //edit a user
    function edit_user($id)
    {
        $this->data['title'] = "Edit User";

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
        {
            redirect('auth', 'refresh');
        }

        $user = $this->ion_auth->user($id)->row();
        $groups=$this->ion_auth->groups()->result();
        //$currentGroups = $this->ion_auth->get_users_groups($id)->result();

        //validate form input
        //$this->form_validation->set_rules('username', 'Nombre de Usuario', 'trim|required|min_length[4]|max_length[50]|callback_islower|is_unique[users.username]');
        $this->form_validation->set_message('is_unique', 'El %s ya está siendo usado. Escriba otro.');
        $this->form_validation->set_rules('first_name', 'Nombre', 'trim|required');
        $this->form_validation->set_rules('last_name', 'Apellidos', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'valid_email');
        $this->form_validation->set_rules('password', 'Contraseña', 'min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', 'Confirmación de Contraseña');
        $this->form_validation->set_rules('profile', 'Perfil', 'required');

        if (isset($_POST) && !empty($_POST))
        {
            $data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name'  => $this->input->post('last_name'),
                'phone'      => $this->input->post('phone'),
            );

            //Update the groups user belongs to
            $groupData = $this->input->post('profile');

            if (isset($groupData) && !empty($groupData)) {
                $this->ion_auth->remove_from_group('', $id);
                $this->ion_auth->add_to_group($groupData, $id);
            }

            //update the password if it was posted
            if ($this->input->post('password'))
            {
                $this->form_validation->set_rules('password', "Contraseña", 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
                $this->form_validation->set_rules('password_confirm', "Confirmación de Contraseña", 'required');

                $data['password'] = $this->input->post('password');
            }

            if ($this->form_validation->run() === TRUE)
            {
                $this->ion_auth->update($user->id, $data);
                $this->session->set_flashdata('message', "Usuario actualizado exitosamente");
                redirect("auth", 'refresh');
            }
        }

        //display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();

        //set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        //pass the user to the view
        $this->data['user'] = $user;
        $this->data['perfiles'] = assoc($groups,'id','name');
        $this->data['currentProfile'] = $this->ion_auth->get_users_groups($id)->row()->id;

        
        $this->data['username'] = array(
            'name' => 'username',
            'value' => set_value('username', $user->username),
            'class' => 'form-control',
            'disabled' => 'disabled',
        );

        $this->data['email'] = array(
            'name' => 'email',
            'value' => set_value('email', $user->email),
            'class' => 'form-control',
        );

        $this->data['first_name'] = array(
            'name'  => 'first_name',
            'id'    => 'first_name',
            'type'  => 'text',
            'value' => $this->form_validation->set_value('first_name', $user->first_name),
            'class' => 'form-control',
        );
        $this->data['last_name'] = array(
            'name'  => 'last_name',
            'id'    => 'last_name',
            'type'  => 'text',
            'value' => $this->form_validation->set_value('last_name', $user->last_name),
            'class' => 'form-control',
        );
        $this->data['phone'] = array(
            'name'  => 'phone',
            'id'    => 'phone',
            'type'  => 'text',
            'value' => $this->form_validation->set_value('phone', $user->phone),
            'class' => 'form-control',
        );

        $this->data['password'] = array(
            'name' => 'password',
            'id'   => 'password',
            'type' => 'password'
        );
        $this->data['password_confirm'] = array(
            'name' => 'password_confirm',
            'id'   => 'password_confirm',
            'type' => 'password'
        );

        //$this->_render_page('auth/edit_user', $this->data);
        //$this->twiggy->set("perfiles",  $perfiles);
        $this->twiggy->set($this->data, null);
        $this->twiggy->template("auth/edit_user");
        $this->twiggy->display();
    }


    function _get_csrf_nonce()
    {
        $this->load->helper('string');
        $key   = random_string('alnum', 8);
        $value = random_string('alnum', 20);
        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);

        return array($key => $value);
    }

    function _valid_csrf_nonce()
    {
        if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
            $this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue'))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }


    public function personal_info()
    {
        if(!$this->ion_auth->logged_in()) redirect("auth/login");

        //show: email, first_name, last_name, phone, identification
        //edit: email, phone, password, identification
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('email', "Email", "valid_email");
        $this->form_validation->set_rules('phone');

        if($this->form_validation->run()) 
        {
            $ok = true;
            $pass_changed = false;
            $this->user_id = $this->session->userdata('user_id');
            $user_data = array(
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
            );

            if($this->input->post('new_password')) {
                $min_pass = $this->config->item('min_password_length', 'ion_auth');
                $this->form_validation->set_rules('cur_password', 'Contraseña Actual', 'required|callback__cur_password_check');
                $this->form_validation->set_rules('new_password', 'Contraseña Nueva', "required|min_length[$min_pass]");
                $this->form_validation->set_rules('new_password_confirm', 'Confirmar Contraseña Nueva',"matches[new_password]");
                
                if($ok = $this->form_validation->run()) {
                    $user_data['password'] = $this->input->post('new_password');
                    $pass_changed = true;
                }
            }

            if($ok) {
                $this->ion_auth->update($this->user_id, $user_data);
                $msg = 'Información Personal Actualizada Exitosamente';
                if($pass_changed) $msg .= "<br> Contraseña Cambiada";
                $this->session->set_flashdata("notif", array('type'=>'success', 'text' => $msg));
                redirect("/");
            }
        }


        $this->twiggy->set("user", $this->ion_auth->user()->row());
        $this->twiggy->set("group", $this->ion_auth->get_users_groups()->row());

        $this->twiggy->template("auth/personal_info");
        $this->twiggy->display();
        
    }

    function _cur_password_check($pass)
    {
        if($this->ion_auth->hash_password_db($this->user_id, $pass))
            return true;

        $this->form_validation->set_message("_cur_password_check", "Contraseña Incorrecta");
        return false;
    }
}
