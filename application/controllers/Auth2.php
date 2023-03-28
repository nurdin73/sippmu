<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Auth2 extends Public_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->model("user_model");
        $this->load->library('Auth');
        $this->load->library('Enc_lib');
    }

    function login() {

        if ($this->auth->logged_in()) {
            $this->auth->is_logged_in(true);
        }

        $data = array();
        $data['title'] = 'Login';
        //$app = $this->setting_model->get();
        //$data['app'] = $app[0];
        $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('admin/login', $data);
        } else {
            $login_post = array(
                'username' => $this->input->post('username'),
                'password' => $this->input->post('password')
            );
            $setting_result = $this->setting_model->get();
            $result = $this->user_model->checkLogin($login_post);
           

            if ($result) {
                if($result->is_active){
                    $setting_result = $this->setting_model->get();
                    $session_data = array(
                        'id' => $result->id,
                        'username' => $result->username,
                        'name' => $result->name,
                        'email' => $result->email,
                        'roles' => $result->roles,
                        'cabang' => $result->cabang,
                        'cabang_name' => $result->cabang_name,
                        'is_pusat' => $result->is_pusat,
                        'date_format' => $setting_result[0]['date_format'],
                        'currency_symbol' => $setting_result[0]['currency_symbol'],
                        'app_name' => $setting_result[0]['name'],
                        'timezone' => $setting_result[0]['timezone'],
                        'sch_name' => $setting_result[0]['name'],
                        'language' => array('lang_id' => $setting_result[0]['lang_id'], 'language' => $setting_result[0]['language']),
                        'is_rtl' => $setting_result[0]['is_rtl'],
                        'theme' => $setting_result[0]['theme'],
                    );
                    $this->session->set_userdata('admin', $session_data);
                    $role = $this->customlib->getStaffRole();
                    $role_name = json_decode($role)->name;
                    $this->customlib->setUserLog($this->input->post('username'), $role_name);

                    if (isset($_SESSION['redirect_to']))
                        redirect($_SESSION['redirect_to']);
                    else
                        redirect('dashboard');
                }else{
                     $data['error_message'] = 'Your account is disabled please contact to administrator';
                      $this->load->view('admin/login', $data);
                }
               
            } else {
                $data['error_message'] = 'Invalid Username or Password';
                $this->load->view('admin/login', $data);
            }
        }
    }

    function logout() {
        $admin_session = $this->session->userdata('admin');
        $this->auth->logout();
        if ($admin_session) {
            redirect('auth2/login');
        } else {
            redirect('auth2/login');
        }
    }

    function forgotpassword() {

        $this->load->library('mailer');
        $this->mailer;

        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|required|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('admin/forgotpassword');
        } else {
            $email = $this->input->post('email');

            $result = $this->user_model->getByEmail($email);

            if ($result && $result->email != "") {

                $verification_code = $this->enc_lib->encrypt(uniqid(mt_rand()));
                $update_record = array('id' => $result->id, 'verification_code' => $verification_code);
                $this->user_model->add($update_record);
                $name = $result->name;

                $resetPassLink = site_url('admin/resetpassword') . "/" . $verification_code;

                $body = $this->forgotPasswordBody($name, $resetPassLink);
                $body_array = json_decode($body);

                if (!empty($this->mail_config)) {
                    $result = $this->mailer->send_mail($result->email, $body_array->subject, $body_array->body);
                }

                $this->session->set_flashdata('message', "Please check your email to recover your password");

                redirect('auth2/login', 'refresh');
            } else {
                $data = array(
                    'error_message' => 'Invalid Email'
                );
            }
            $this->load->view('admin/forgotpassword', $data);
        }
    }

    //reset password - final step for forgotten password
    public function admin_resetpassword($verification_code = null) {
        if (!$verification_code) {
            show_404();
        }

        $user = $this->user_model->getByVerificationCode($verification_code);

        if ($user) {
            //if the code is valid then display the password reset form
            $this->form_validation->set_rules('password', 'Password', 'required');
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');
            if ($this->form_validation->run() == false) {


                $data['verification_code'] = $verification_code;
                //render
                $this->load->view('admin/admin_resetpassword', $data);
            } else {

                // finally change the password
                $password = $this->input->post('password');
                $update_record = array(
                    'id' => $user->id,
                    'password' => $this->enc_lib->passHashEnc($password),
                    'verification_code' => ""
                );

                $change = $this->user_model->update($update_record);
                if ($change) {
                    //if the password was successfully changed
                    $this->session->set_flashdata('message', "Password Reset successfully");
                    redirect('auth2/login', 'refresh');
                } else {
                    $this->session->set_flashdata('message', "Something went wrong");
                    redirect('admin_resetpassword/' . $verification_code, 'refresh');
                }
            }
        } else {
            //if the code is invalid then send them back to the forgot password page
            $this->session->set_flashdata('message', 'Invalid Link');
            redirect("auth2/forgotpassword", 'refresh');
        }
    }


    function forgotPasswordBody($name, $resetPassLink) {
        //===============
        $subject = "Password Update Request";
        $body = 'Dear ' . $name . ', 
                <br/>Recently a request was submitted to reset password for your account. If you didn\'t make the request, just ignore this email. Otherwise you can reset your password using this link <a href="' . $resetPassLink . '"><button>Click here to reset your password</button></a>';
        $body .= '<br/><hr/>if you\'re having trouble clicking the password reset button, copy and paste the URL below into your web browser';
        $body .= '<br/>' . $resetPassLink;
        $body .= '<br/><br/>Regards,
                <br/>' . $this->customlib->getAppName();

        //======================
        return json_encode(array('subject' => $subject, 'body' => $body));
    }


    
    public function show_404() {
        $this->load->view('errors/error_message');
    }

    
}

?>