<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

require(APPPATH . 'third_party/vendor/autoload.php');

class Api extends RestController
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('text');
        $this->load->model('api_model');
    }

     //useregister 
     public function useregister_post(){
        try{
            $this->form_validation->set_rules('name','User Name','required');
            $this->form_validation->set_rules('phone','Phone Number','required|numeric');
            $this->form_validation->set_rules('email','Email','required|valid_email|is_unique[tbl_user.email]',
                  array('is_unique' => "This email is already registerd!")
             );
            $this->form_validation->set_rules('dob', 'Date of Birth', 'required');
            $this->form_validation->set_rules('password', 'Password', 'required|max_length[10]');
            $this->form_validation->set_rules('confirm_pass','Confirm Password','required|matches[password]');
        if ($this->form_validation->run() == TRUE){
            $data = array(
                'user_name' => $this->post('name'),
                'email' => $this->post('email'),
                'dob' =>  $this->post('dob'),
                'phone_no' => $this->post('phone'),
                'password' =>  md5($this->post('password'))
            );
            $res = $this->api_model->useregister($data);
            if($res){
                $this->response([
                    'status' => true,
                    'data' => $data,
                    'message' => 'Registration successfully!'
                ], 200);
            }else{
                $this->response([
                    'status' => false,
                    'data' => $data,
                    'message' => 'Registration failed!'
                ], 500);
            }
        }
            $this->response([
                'status' => false,
                'message' => validation_errors()
            ], 500);
        }
        catch (Exception $e) {
            $this->response($e->getMessage, 500);
        }
   }

    //userlogin
    public function login_post()
    {
        try{
            $this->form_validation->set_rules('email', 'Email','required|valid_email');
            $this->form_validation->set_rules('password','Password','required');
            if ($this->form_validation->run() == TRUE){

                $res = $this->api_model->login($this->post('email'),md5($this->post('password')) );
                if(!empty($res)){
                    $this->response([
                        'status' => true,
                        'data' => $res,
                        'message' =>  'User login Successful!'
                    ], 200);
                }else{
                    $this->response([
                        'status' => false,
                        'message' =>  'Login or password does not match! try again'
                    ], 500);
                }
            }
            else{
                $this->response([
                    'status' => false,
                    'message' => validation_errors()
                ], 500);
            }
        }catch (Exception $e) {
            $this->response($e->getMessage, 500);
        }
    }


    //Gallery
    public function gallery_get(){
        try{
            $data =  $this->api_model->gallery();
            if(!empty($data))
            {
                $this->response([
                    'status' => true,
                    'data' => $data,
                    'message' => 'success'
                ], 200);
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'No image found'
                ], 500);
            }
        }catch(Exception $e){
            $this->response($e->getMessage, 500);
        }
    }

    //Slider
    public function slider_get(){
        try{
            $data =  $this->api_model->slider();
            if(!empty($data))
            {
                $this->response([
                    'status' => true,
                    'data' => $data,
                    'message' => 'success'
                ], 200);
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'No image found'
                ], 500);
            }
        }catch(Exception $e){
            $this->response($e->getMessage, 500);
        }
    }

    //Timeline
    public function timeline_get(){
        try{
            $data=$this->api_model->timeline();
            if(!empty($data))
            {
                $this->response([
                    'status' => true,
                    'data' => $data,
                    'message' => 'success'
                ], 200);
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'No data found'
                ], 500);
            }
        }catch(Exception $e){
            $this->response($e->getMessage, 500);
        }
    }

    //pwd
    public function pwd_get() {
        try{
            $data['gallery'] = $this->api_model->getpwd();
            $dataAll = $this->api_model->getrecord_pwd();
            $data['scheme'] = strip_tags(str_replace("</p>","\n",preg_replace("/&nbsp;/",'',$dataAll->scheme!=''?$dataAll->scheme:'')));
            $data['description'] = strip_tags(str_replace("</p>","\n",preg_replace("/&nbsp;/",'',$dataAll->detail!=''?$dataAll->detail:'')));
            if(!empty($data))
            {
                $this->response([
                    'status' => true,
                    'data' => $data,
                    'message' => 'success'
                ], 200);
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'No data found'
                ], 500);
            }
        }catch(Exception $e){
            $this->response($e->getMessage, 500);
        }
    }

    //foodprocessing
    public function foodProcessing_get()
    {
        try{
            $data['gallery'] = $this->api_model->foodprocesseing();
            $dataAll = $this->api_model->getrecord_foodProcessing();
            $data['scheme'] = strip_tags(str_replace("</p>","\n",preg_replace("/&nbsp;/",'',$dataAll->scheme!=''?$dataAll->scheme:'')));
            $data['description'] = strip_tags(str_replace("</p>","\n",preg_replace("/&nbsp;/",'',$dataAll->detail!=''?$dataAll->detail:'')));
            if(!empty($data))
            {
                $this->response([
                    'status' => true,
                    'data' => $data,
                    'message' => 'success'
                ], 200);
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'No data found'
                ], 500);
            }
        }catch(Exception $e){
            $this->response($e->getMessage, 500);
        }
    }

    //publicEnterprises
    public function publicEnterprises_get()
    {
        try{
            $data['gallery'] = $this->api_model->publicenterprises();
            $dataAll = $this->api_model->getrecord_publicEnterprises();
            $data['scheme'] = strip_tags(str_replace("</p>","\n",preg_replace("/&nbsp;/",'',$dataAll->scheme!=''?$dataAll->scheme:'')));
            $data['description'] = strip_tags(str_replace("</p>","\n",preg_replace("/&nbsp;/",'',$dataAll->detail!=''?$dataAll->detail:'')));
            if(!empty($data))
            {
                $this->response([
                    'status' => true,
                    'data' => $data,
                    'message' => 'success'
                ], 200);
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'No data found'
                ], 500);
            }
        }catch(Exception $e){
            $this->response($e->getMessage, 500);
        }
    }

    //Blog
    public function blog_get(){
        try{
            $offset = $this->input->get('offset');
            $limit = $this->input->get('limit');
            $data = $this->api_model->blog($offset, $limit);
            if(!empty($data))
            {
                $this->response([
                    'status' => true,
                    'data' => $data,
                    'message' => 'success'
                ], 200);
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'No data found'
                ], 500);
            }
        }catch(Exception $e){
            $this->response($e->getMessage, 500);
        }
    }

    //Blog-details
    public function blogDetails_get(){
        try{
            $id = $this->get('id');
            if($id!=''){
                $data = $this->api_model->blogdetails($id);
                if(!empty($data))
                {
                    $this->response([
                        'status' => true,
                        'data' => $data,
                        'message' => 'success'
                    ], 200);
                }else{
                    $this->response([
                        'status' => FALSE,
                        'message' => 'No data found'
                    ], 500);
                }
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'No result found'
                ], 500);
            }
        }catch(Exception $e){
            $this->response($e->getMessage, 500);
        }
    }

    //biography
    public function biography_get(){
        try{
            $data = $this->api_model->biography();
            if(!empty($data))
            {
                $this->response([
                    'status' => true,
                    'data' => $data,
                    'message' => 'success'
                ], 200);
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'No data found'
                ], 500);
            }
        }catch(Exception $e){
            $this->response($e->getMessage, 500);
        }
    }

    //biography-details
    public function biographyDetails_get(){
        try{
            $id = $this->get('id');
            if($id!=''){
                $data = $this->api_model->biographydetails($id);
                if(!empty($data))
                {
                    $this->response([
                        'status' => true,
                        'data' => $data,
                        'message' => 'success'
                    ], 200);
                }else{
                    $this->response([
                        'status' => FALSE,
                        'message' => 'No data found'
                    ], 500);
                }
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'No result found'
                ], 500);
            }
        }catch(Exception $e){
            $this->response($e->getMessage, 500);
        }
    }

    //Ideal
    public function ideal_get(){
        try{
            $offset = $this->input->get('offset');
            $limit = $this->input->get('limit');
            $data = $this->api_model->ideal($offset,$limit);
            if(!empty($data))
            {
                $this->response([
                    'status' => true,
                    'data' => $data,
                    'message' => 'success'
                ], 200);
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'No data found'
                ], 500);
            }
        }catch(Exception $e){
            $this->response($e->getMessage, 500);
        }
    }

    //Ideal-detail
    public function idealDetails_get(){
        try{
            $id = $this->get('id');
            if($id!=''){
                $data = $this->api_model->idealdetail($id);
                if(!empty($data))
                {
                    $this->response([
                        'status' => true,
                        'data' => $data,
                        'message' => 'success'
                    ], 200);
                }else{
                    $this->response([
                        'status' => FALSE,
                        'message' => 'No data found'
                    ], 500);
                }
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'No result found'
                ], 500);
            }
        }catch(Exception $e){
            $this->response($e->getMessage, 500);
        }
    }

    //todaythought
    public function todayThought_get(){
        try{
            $data =  $this->api_model->todaythought();
            if(!empty($data))
            {
                $this->response([
                    'status' => true,
                    'data' => $data,
                    'message' => 'success'
                ], 200);
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'No data found'
                ], 500);
            }
        }catch(Exception $e){
            $this->response($e->getMessage, 500);
        }
    }

    //news
    public function news_get(){
        try{
            $data =  $this->api_model->news();
            if(!empty($data))
            {
                $this->response([
                    'status' => true,
                    'data' => $data,
                    'message' => 'success'
                ], 200);
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'No data found'
                ], 500);
            }
        }catch(Exception $e){
            $this->response($e->getMessage, 500);
        }
    }

    //events
    public function event_get(){
        try{
            $data = $this->api_model->events();
            if(!empty($data))
            {
                $this->response([
                    'status' => true,
                    'data' => $data,
                    'message' => 'success'
                ], 200);
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'No data found'
                ], 500);
            }
        }catch(Exception $e){
            $this->response($e->getMessage, 500);
        }
    }
    //eventsdetail event part
    public function eventDetails_get(){
        try{
            $id = $this->get('id');
            if($id!=''){
                $data = $this->api_model->eventsdetail($id);
                if(!empty($data))
                {
                    $this->response([
                        'status' => true,
                        'data' => $data,
                        'message' => 'success'
                    ], 200);
                }else{
                    $this->response([
                        'status' => FALSE,
                        'message' => 'No data found'
                    ], 500);
                }
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'No result found'
                ], 500);
            }
        }catch(Exception $e){
            $this->response($e->getMessage, 500);
        }
    }

    //speeches news part
    public function speeches_get(){
        try{
            $data =  $this->api_model->speeches();
            if(!empty($data))
            {
                $this->response([
                    'status' => true,
                    'data' => $data,
                    'message' => 'success'
                ], 200);
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'No data found'
                ], 500);
            }
        }catch(Exception $e){
            $this->response($e->getMessage, 500);
        }
    }

     //MediaGalleryPage news part
     public function MediaGalleryPage_get(){  
        try{
            $data = $this->api_model->MediaGalleryPage();
            if(!empty($data))
            {
                $this->response([
                    'status' => true,
                    'data' => $data,
                    'message' => 'success'
                ], 200); 
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'No data found'
                ], 500);   
            }
        }catch(Exception $e){
            $this->response($e->getMessage, 500);
        }
      }
      //MediaGalleryPage_images news part
      public function MediaGalleryPage_images_get(){
        try{
            $id = $this->get('m_catid');
            if($id!=''){
                $data = $this->api_model->MediaGalleryPage_images($id);
                if(!empty($data))
                {
                    $this->response([
                        'status' => true,
                        'data' => $data,
                        'message' => 'success'
                    ], 200); 
                }else{
                    $this->response([
                        'status' => FALSE,
                        'message' => 'No data found'
                    ], 500);   
                }
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'No result found'
                ], 500);   
            }
        }catch(Exception $e){
            $this->response($e->getMessage, 500);
        }
     }


    public function addinvitation_post()
    {
        try {
            $id = $this->post('id');
            if ($id != "") {
                $checkid = $this->api_model->islogin($id);
                if (!empty($checkid)) {
                    $this->form_validation->set_rules('name', 'Name', 'required');
                    $this->form_validation->set_rules('website', 'website', 'required');
                    $this->form_validation->set_rules('email', 'Email', 'required|valid_email',
                        array(
                            'required' => 'You have not provided email id',
                            'valid_email' => 'Your email is not valid! please fill another valid email'
                        )
                    );
                    $this->form_validation->set_rules('phone', 'Phone', 'required');
                    $this->form_validation->set_rules('event_title', 'Event Title', 'required');
                    $this->form_validation->set_rules('event_date', 'Event date', 'required');
                    $this->form_validation->set_rules('media_present', 'Media present', 'required');
                    $this->form_validation->set_rules('event_location', 'Event location', 'required');
                    $this->form_validation->set_rules('street', 'Street', 'required');
                    $this->form_validation->set_rules('state', 'State', 'required');
                    $this->form_validation->set_rules('country', 'Country', 'required');
                    $this->form_validation->set_rules('p_code', 'Pin code', 'required');
                    $this->form_validation->set_rules('audience', 'Audience', 'required');
                    $this->form_validation->set_rules('des_event', 'Event description', 'required');
                    if ($this->form_validation->run() == TRUE) {
                            $filename = "";
                            if (!empty($_FILES['image']['name'])) {
                            $config['upload_path'] = './admin-assets/upload/';
                            $config['allowed_types'] = 'gif|jpg|png|pdf';
                            $config['file_name'] = $_FILES['image']['name'];
                            $this->load->library('upload', $config);
                            $this->upload->initialize($config);
                            if ($this->upload->do_upload('image')) {
                                $image_metadata = $this->upload->data();
                                $filename = $image_metadata['file_name'];
                            }
                        } 
                            $data = array(
                                'name' => strip_tags($this->post('name')),
                                'website' => strip_tags($this->post('website')),
                                'email' =>  strip_tags($this->post('email')),
                                'phone' => strip_tags($this->post('phone')),
                                'phone' => strip_tags($this->post('event_title')),
                                'event_date' => strip_tags($this->post('event_date')),
                                'media_present' => strip_tags($this->post('media_present')),
                                'event_location' => $this->post('event_location'),
                                'street' => $this->post('street'),
                                'state' => $this->post('state'),
                                'country' => $this->post('country'),
                                'p_code' => $this->post('p_code'),
                                'audience' => $this->post('audience'),
                                'des_event' => strip_tags($this->post('des_event')),
                                'image' => $filename,
                                'created_date' => date('Y-m-d')
                            );
                            $res = $this->api_model->saveinvitation($data);
                            if (!empty($res)) {
                                $this->response([
                                    'status' => true,
                                    'data' => $data,
                                    'message' => 'Invitation has been successfully submited'
                                ], 200);
                            } else {
                                $this->response([
                                    'status' => false,
                                    'data' => $data,
                                    'message' => 'Your file  is not submited! please try again'
                                ], 500);
                            }
                    }
                    $this->response([
                        'status' => false,
                        'message' => validation_errors()
                    ], 500);
                }
                $this->response([
                    'status' => false,
                    'message' => 'Invalid user login!'
                ], 500);
            }
            $this->response([
                'status' => false,
                'message' => 'User not exists'
            ], 500);
        } 
        catch (Exception $e) {
            $this->response($e->getMessage, 500);
        }
    }
    public function forgotpassword_post()
    {
        try {
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email',
                array(
                    'required' => 'You have not provided.',
                    'valid_email' => 'Your email is not valid! please fill another valid email'
                )
            );
            if ($this->form_validation->run() == TRUE) {
                $email = $this->post('email');
                $checemail = $this->api_model->emailcheck($email);
                if (!empty($checemail)) {
                    $rndno=rand(100000, 999999);
                    $user_id = $checemail[0]->id;
                    $newpassword = $this->post('newpassword');
                    $con_password = $this->post('con_password');
                    if ($newpassword == $con_password) {
                        $data = array(
                            'password' => md5($newpassword),
                            'otp'    => $rndno
                        );
                        $forgot = $this->api_model->forgotpassword($data, $user_id);
                        if (!empty($forgot)) {
                            $this->response([
                                'status' => true,
                                'data' => $forgot,
                                'message' => 'Your password has been successfully change'
                            ], 200);
                        }
                    } else {
                        $this->response([
                            'status' => false,
                            'message' => 'newpassword and conform password does not match ! please try again'
                        ], 500);
                    }
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'No record of that email address.'
                    ], 500);
                }
            } else {
                $this->response([
                    'status' => false,
                    'message' => validation_errors()
                ], 500);
            }
        } catch (Exception $e) {
            $this->response($e->getMessage, 500);
        }
    }

    //Profile
    public function profile_post(){
        try{
            $this->form_validation->set_rules('name','User Name','required');
            $this->form_validation->set_rules('email','Email','required|valid_email');
            $this->form_validation->set_rules('phone_no', 'Phone Number', 'required|numeric');
            $this->form_validation->set_rules('dob', 'Date of Birth', 'required');
            if ($this->form_validation->run() == TRUE){
                $image="";
                if(!empty($_FILES['image']['name'])){
                    $config['upload_path'] = './admin-assets/upload/';
                    $config['allowed_types'] = 'gif|jpg|png';
                    $config['file_name'] = $_FILES['image']['name'];
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if($this->upload->do_upload('image')){
                        $uploadData = $this->upload->data();
                        $image = $uploadData['file_name'];
                    }
                }
                    $data = array(
                        'user_name' => $this->post('name'),
                        'email' => $this->post('email'),
                        'phone_no' =>  $this->post('phone_no'),
                        'dob' =>  $this->post('dob'),
                        'image' => $image
                    );
                    $res = $this->api_model->add_profile($data);
                    if($res){
                        $this->response([
                            'status' => true,
                            'data' => $data,
                            'message' => 'Profile has been successfully updated'
                        ], 200);
                    }else{
                        $this->response([
                            'status' => false,
                            'data' => $data,
                            'message' => 'Profile is not updated! please try again'
                        ], 500);
                    }
            }
            $this->response([
                'status' => false,
                'message' => validation_errors()
            ], 500);
        }
        catch (Exception $e) {
            $this->response($e->getMessage, 500);
        }
    }

    //feedback
    public function feedback_post(){
        try{
            $id = $this->post('id');
            if($id != ""){
                $checkid = $this->api_model->islogin($id);
                if(!empty($checkid)){
                    $data = array(
                        'user_id' => $id,
                        'message' => strip_tags($this->post('message'))
                    );
                    $res = $this->api_model->feedback($data);
                    if($res){
                        $this->response([
                            'status' => true,
                            'data' => $data,
                            'message' => 'Feedback successfully sent.'
                        ], 200);
                    }else{
                        $this->response([
                            'status' => false,
                            'message' => 'Feedback not sent! please try again'
                        ], 500);
                    }
                    $this->response([
                        'status' => false,
                        'message' => 'Invalid user login!'
                    ], 500);
                }else{
                    $this->response([
                        'status' => false,
                        'message' => 'User not exists'
                    ], 500);
                }
            }
            $this->response([
                'status' => false,
                'message' => 'User not logged in or not exists'
            ], 500);
        }
        catch (Exception $e) {
            $this->response($e->getMessage, 500);
        }
    }

    public function latest_blog_get(){
        try{
            $data = $this->api_model->latest_blog();
            if(!empty($data))
            {
                $this->response([
                    'status' => true,
                    'data' => $data,
                    'message' => 'success'
                ], 200);
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'No data found'
                ], 500);
            }
        }catch(Exception $e){
            $this->response($e->getMessage, 500);
        }
    }
    //complaint
    public function complaint_post(){
        try{
            $id = $this->post('id');
            if($id != ""){
                $checkid = $this->api_model->islogin($id);
                if(!empty($checkid)){
                    $this->form_validation->set_rules('pincode', 'Pincode', 'required');
                    $this->form_validation->set_rules('city', 'City', 'required');
                    $this->form_validation->set_rules('state', 'State', 'required');
                    $this->form_validation->set_rules('district', 'District', 'required');
                    $this->form_validation->set_rules('user_name', 'User name', 'required');
                    $this->form_validation->set_rules('user_email', 'User Email', 'required|valid_email');
                    $this->form_validation->set_rules('user_phone', 'Mobile No.', 'required|regex_match[/^[0-9]{10}$/]');
                    $this->form_validation->set_rules('address', 'Address', 'required');
                    $this->form_validation->set_rules('email_subject', 'Subject', 'required');
                    $this->form_validation->set_rules('email_message', 'Meassage', 'required');
                    if ($this->form_validation->run() == TRUE){
                        $image="";
                        if(!empty($_FILES['image']['name'])){
                            $config['upload_path'] = './admin-assets/upload/';
                            $config['allowed_types'] = 'gif|jpg|png|pdf';
                            $config['file_name'] = $_FILES['image']['name'];
                            $this->load->library('upload', $config);
                            $this->upload->initialize($config);
                            if($this->upload->do_upload('image')){
                                $uploadData = $this->upload->data();
                                $image = $uploadData['file_name'];
                                }else{
                                    $error = array('error' => $this->upload->display_errors());
                                    $this->response([
                                        'status' => false,
                                        'message' => $error
                                    ], 500);
                                }
                            }
                                $data = array(
                                    'user_name' => $this->post('user_name'),
                                    'mobile_no' => $this->post('user_phone'),
                                    'address' => $this->post('address'),
                                    'user_email' => $this->post('user_email'),
                                    'email_subject' => $this->post('email_subject'),
                                    'pincode' => $this->post('pincode'),
                                    'district' => $this->post('district'),
                                    'state' => $this->post('state'),
                                    'email_message' => strip_tags($this->post('email_message')),
                                    'image' => $image,
                                    'city' => $this->post('city')
                                );
                                $res = $this->api_model->complaint($data);
                                if($res){
                                    $this->response([
                                        'status' => true,
                                        'data' => $data,
                                        'message' => 'Complaint successfully sent!'
                                    ], 200);
                                }else{
                                    $this->response([
                                        'status' => false,
                                        'data' => $data,
                                        'message' => 'Complaint failed!'
                                    ], 500);
                                }
                    }else{
                        $this->response([
                            'status' => false,
                            'message' => validation_errors()
                        ], 500);
                    }
                }else{
                    $this->response([
                        'status' => false,
                        'message' => 'User not exists'
                    ], 500);
                }
                $this->response([
                    'status' => false,
                    'message' => 'Invalid user login!'
                ], 500);
            }
            $this->response([
                'status' => false,
                'message' => 'User not exists'
            ], 500);
        }
        catch (Exception $e) {
            $this->response($e->getMessage, 500);
        }
    }

    //invitation
    public function invitation_post(){
        try{
            $id = $this->post('id');
            if($id != ""){
                $checkid = $this->api_model->islogin($id);
                if(!empty($checkid)){
                    $this->form_validation->set_rules('user_name', 'User name', 'required');
                    $this->form_validation->set_rules('website', 'Website name', 'required');
                    $this->form_validation->set_rules('user_email', 'User email', 'required|valid_email');
                    $this->form_validation->set_rules('user_phone', 'User phone', 'required|integer|max_length[11]');
                    $this->form_validation->set_rules('event_title', 'Event title', 'required');
                    $this->form_validation->set_rules('event_date', 'Event Date', 'required');
                    $this->form_validation->set_rules('media', 'media', 'required');
                    $this->form_validation->set_rules('event_location', 'Event location', 'required');
                    $this->form_validation->set_rules('street', 'Street', 'required');
                    $this->form_validation->set_rules('state', 'User state', 'required');
                    $this->form_validation->set_rules('country', 'Usercountry', 'required');
                    $this->form_validation->set_rules('pincode', 'User postal code', 'required');
                    $this->form_validation->set_rules('message', 'Message', 'required');
                    $this->form_validation->set_rules('description', ' Describe event', 'required');
                    if ($this->form_validation->run() == TRUE){
                        $image="";
                        if(!empty($_FILES['image']['name'])){
                            $config['upload_path'] = './admin-assets/upload/';
                            $config['allowed_types'] = 'gif|jpg|png|pdf';
                            $config['file_name'] = $_FILES['image']['name'];
                            $this->load->library('upload', $config);
                            $this->upload->initialize($config);
                            if($this->upload->do_upload('image')){
                                $uploadData = $this->upload->data();
                                $image = $uploadData['file_name'];
                                }
                            }
                                $data = array(
                                    'name' => $this->post('user_name'),
                                    'website' => $this->post('website'),
                                    'email' => $this->post('user_email'),
                                    'phone' => $this->post('user_phone'),
                                    'event_title' => $this->post('event_title'),
                                    'event_date' => $this->post('event_date'),
                                    'media_present' => $this->post('media'),
                                    'event_location' => $this->post('event_location'),
                                    'street' => $this->post('street'),
                                    'state' => $this->post('state'),
                                    'country' => $this->post('country'),
                                    'p_code' => $this->post('pincode'),
                                    'audience' => $this->post('message'),
                                    'des_event' => strip_tags($this->post('description')),
                                    'image' => $image
                                );
                                $res = $this->api_model->invitation($data);
                                if($res){
                                    $this->response([
                                        'status' => true,
                                        'data' => $data,
                                        'message' => 'invitation successfully sent!'
                                    ], 200);
                                }else{
                                    $this->response([
                                        'status' => false,
                                        'data' => $data,
                                        'message' => 'invitation failed!'
                                    ], 500);
                                }
                    }else{
                        $this->response([
                            'status' => false,
                            'message' => validation_errors()
                        ], 500);
                    }
                }else{
                    $this->response([
                        'status' => false,
                        'message' => 'User not exists'
                    ], 500);
                }
                $this->response([
                    'status' => false,
                    'message' => 'Invalid user login!'
                ], 500);
            }
            $this->response([
                'status' => false,
                'message' => 'User not exists'
            ], 500);
        }
        catch (Exception $e) {
            $this->response($e->getMessage, 500);
        }
    }

    public function department_get(){
        try{
            $data = $this->api_model->department();
            if(!empty($data))
            {
                $this->response([
                    'status' => true,
                    'data' => $data,
                    'message' => 'success'
                ], 200);
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'No data found'
                ], 500);
            }
        }catch(Exception $e){
            $this->response($e->getMessage, 500);
        }
    }

      //AUTHORIZATION
    public function social_post()
    {
        try {
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('social_type', 'Social Media', 'required');
            $this->form_validation->set_rules('social_key', 'Token Key', 'required');
            $this->form_validation->set_rules('name', 'Name', 'required');
            if ($this->form_validation->run() == TRUE) {
                $email = $this->post('email');
                $social_type = $this->post('social_type');
                $social_key = $this->post('social_key');
                if ($email != "") {
                    $checkemail = $this->api_model->check_email($email);
                    if (!empty($checkemail)) {
                        if ( in_array($social_type, ['google', 'facebook']) ) {
                            $record = $this->api_model->social_exist($social_key, $social_type, $email);
                            $tokenData = array();
                            $tokenData['userId'] = $record->id;
                            $tokenData['timestamp'] = now();
                            $record->auth_tooken = AUTHORIZATION::generateToken($tokenData);
                            $this->response([
                                'status' => true,
                                'data' => $record,
                                'message' => 'User login Successful!'
                            ], 200);
                        } else {
                            $this->response([
                                'status' => FALSE,
                                'message' => 'Social type required!'
                            ], 403);
                        }
                    } else {
                        if (in_array($social_type, ['google', 'facebook'])) {
                            $data = array(
                                'user_name' => $this->post('name'),
                                'email' => $this->post('email'),
                                'google_id' => $social_key
                            );
                            $res = $this->api_model->socialregister($data);
                            $tokenData = array();
                            $tokenData['userId'] = $res->id;
                            $tokenData['timestamp'] = now();
                            $res->auth_tooken = AUTHORIZATION::generateToken($tokenData);
                            $this->response([
                                'status' => true,
                                'data' => $res,
                                'message' => 'Registration successfully!'
                            ], 200);
                        } else {
                            $this->response([
                                'status' => FALSE,
                                'message' => 'Invalid Social method!'
                            ], 403);
                        }
                    }
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'Email required!'
                    ], 500);
                }
            } else {
                $this->response([
                    'status' => false,
                    'message' => validation_errors()
                ], 500);
            }
        } catch (exceptions $e) {
            $this->response($e->getMessage, 500);
        }
    }
    public function blogtest_get(){
        try{
            $data = $this->api_model->blogtest();
            if(!empty($data))
            {
                $this->response([
                    'status' => true,
                    'data' => $data,
                    'message' => 'success'
                ], 200);
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'No data found'
                ], 500);
            }
        }catch(Exception $e){
            $this->response($e->getMessage, 500);
        }

    }
    public function register_post(){
        try{
            $this->form_validation->set_rules('name','User Name','required');
            $this->form_validation->set_rules('phone','Phone Number','required|numeric');
            if ($this->form_validation->run() == TRUE){
                $otp = generateNumericOTP(5);
                $data = array(
                    'user_name' => $this->post('name'),
                    'phone' => $this->post('phone'),
                    'otp' => $otp
                );
                $res = $this->api_model->useregister($data);
                $this->sendotp($this->post('phone'),$otp);
                if($res){
                    $this->response([
                        'status' => true,
                        'data' => $data,
                        'message' => 'Registration successfully!'
                    ], 200);
                }else{
                    $this->response([
                        'status' => false,
                        'data' => $data,
                        'message' => 'Registration failed!'
                    ], 500);
                }
            }
            $this->response([
                'status' => false,
                'message' => validation_errors()
            ], 500);
        }
        catch (Exception $e) {
            $this->response($e->getMessage, 500);
        }
    }
    public function sendotp($phone=null,$otp=null)
    {
//      $phone = '916387081028';
// 		$otp = generateNumericOTP(6);
// 		$password = '12345';
        $message = urlencode('Your PAGE3 DryCleaners OTP ('.$otp.'). It expires in 5 minutes. Do not share this OTP with anyone.');
        $url = "https://www.smsgatewayhub.com/api/mt/SendSMS?APIKey=5nXiFiTHGEaEpaGbQrDjeA&senderid=CDLIND&channel=1&DCS=0&flashsms=0&number=".$phone."&text=".$message."&route=11";
        $ch=curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $ab=curl_exec($ch);
        curl_close($ch);
    }

}
