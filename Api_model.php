<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Api_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function getRows($params = array())
    {
            $this->db->select('*');
            $this->db->from('tbl_user');

            //fetch data by conditions
            if (array_key_exists("conditions", $params)) {
                foreach ($params['conditions'] as $key => $value) {
                    $this->db->where($key, $value);
                }
            }
            if (array_key_exists("id", $params)) {
                $this->db->where('id', $params['id']);
                $query = $this->db->get();
                $result = $query->row_array();
            } else {
                //set start and limit
                if (array_key_exists("start", $params) && array_key_exists("limit", $params)) {
                    $this->db->limit($params['limit'], $params['start']);
                } elseif (!array_key_exists("start", $params) && array_key_exists("limit", $params)) {
                    $this->db->limit($params['limit']);
                }
                $query = $this->db->get();
                if (array_key_exists("returnType", $params) && $params['returnType'] == 'count') {
                    $result = $query->num_rows();
                } elseif (array_key_exists("returnType", $params) && $params['returnType'] == 'single') {
                    $result = ($query->num_rows() > 0) ? $query->row_array() : FALSE;
                } else {
                    $result = ($query->num_rows() > 0) ? $query->result_array() : FALSE;
                }
            }
            return $result;
    }
    public function get_profile($id='')
    {
        $this->db->select('*');
        $this->db->from('tbl_user');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if($query && $query->num_rows()>0)
        {
            $result = $query->row_array();
            return $result;
        }

    }
    
    public function registration($data = '')
    {
        if ($result=$this->db->insert('tbl_user', $data)) {
            return $result;
        } else {
            return false;
        }
    }

     //Slider
    public function slider(){
        $slider_array = array();
        /*
           blogdetail images
        */
        $this->db->select('image');
        $this->db->order_by('id','DESC');
        $this->db->limit('2');
        $query = $this->db->get('tbl_blogdetail');        
        if($query->num_rows()>0)
         {
           foreach($query->result() as $blogdetail){
             array_push($slider_array, base_url('admin-assets/upload/'.$blogdetail->image));
           }
        }
        /*
           portfolio images
        */
        $this->db->select('image');
        $this->db->order_by('id','DESC');
        $this->db->limit('2');
        $query = $this->db->get('tbl_portfolio');        
        if($query->num_rows()>0)
         {
           foreach($query->result() as $portfolio){
             array_push($slider_array, base_url('admin-assets/upload/'.$portfolio->image));
           }
        }
        /*
          banner images
        */
        $this->db->select('image');
        $this->db->order_by('id','DESC');
        $this->db->limit('2');
        $query = $this->db->get('tbl_banner');        
        if($query->num_rows()>0)
         {
           foreach($query->result() as $banner){
             array_push($slider_array, base_url('admin-assets/upload/'.$banner->image));
           }
        }
       return $slider_array;
    }
    //Timeline
    public function timeline(){
        $this->db->select("id,DATE_FORMAT(str_to_date(date1,'%m/%d/%Y'),'%M %D, %Y') as date,heading,IF(image IS NOT NULL AND image !='',CONCAT('".base_url()."admin-assets/upload/',image),'') as timelineimage");
        $this->db->order_by('id','DESC');
       $query = $this->db->get('tbl_category');        
       if($query->num_rows()>0)
       {
           return $query->result();
       }
       return false;
    }
    //PWD
    public function getpwd(){
        //$this->db->select("id,IF(image IS NOT NULL AND image !='',CONCAT('".base_url()."admin-assets/upload/',image),'') as timelineimage,SUBSTRING(description,1,100) AS description,date2 as date,cat_name,status,created_date,updated_date");
        $this->db->select("id,IF(image IS NOT NULL AND image !='',CONCAT('".base_url()."admin-assets/upload/',image),'') as timelineimage,SUBSTRING(description,1,100) AS description,DATE_FORMAT(str_to_date(date2,'%m/%d/%Y'),'%M %D, %Y') as date,cat_name,status,created_date,updated_date");
        $this->db->where('cat_name=','pwd');
        $this->db->order_by('id','DESC');
       $query = $this->db->get('tbl_portfolio');        
       if($query->num_rows()>0)
        {
            $arr = $query->result();
            foreach($arr as &$row){
               $row->description = str_replace("&nbsp;",'',strip_tags($row->description));
               $row->description = ltrim(str_replace("1.",'',$row->description));
             }
            return $arr;
        }
       return false;
    }

    //foodprocesseing
    public function foodprocesseing(){
        //$this->db->select("id,IF(image IS NOT NULL AND image !='',CONCAT('".base_url()."admin-assets/upload/',image),'') as timelineimage,SUBSTRING(description,1,100) AS description,date2 as date,cat_name,status,created_date,updated_date");
        $this->db->select("id,IF(image IS NOT NULL AND image !='',CONCAT('".base_url()."admin-assets/upload/',image),'') as timelineimage,SUBSTRING(description,1,100) AS description,DATE_FORMAT(str_to_date(date2,'%m/%d/%Y'),'%M %D, %Y') as date,cat_name,status,created_date,updated_date");
        $this->db->where('cat_name=','foodprocesseing');
        $this->db->order_by('id','DESC');
       $query = $this->db->get('tbl_portfolio');        
       if($query->num_rows()>0)
       {
           $arr = $query->result();
           foreach($arr as &$row){
              $row->description = str_replace("&nbsp;",'',strip_tags($row->description));
              $row->description = ltrim(str_replace("1.",'',$row->description));
            }
           return $arr;
       }
       return false;
    }

    //publicEnterprises
    public function publicenterprises(){
        //$this->db->select("id,IF(image IS NOT NULL AND image !='',CONCAT('".base_url()."admin-assets/upload/',image),'') as timelineimage,SUBSTRING(description,1,100) AS description,date2 as date,cat_name,status,created_date,updated_date");
        $this->db->select("id,IF(image IS NOT NULL AND image !='',CONCAT('".base_url()."admin-assets/upload/',image),'') as timelineimage,SUBSTRING(description,1,100) AS description,DATE_FORMAT(str_to_date(date2,'%m/%d/%Y'),'%M %D, %Y') as date,cat_name,status,created_date,updated_date");
        $this->db->where('cat_name=','publicEnterprises');
        $this->db->order_by('id','DESC');
       $query = $this->db->get('tbl_portfolio');        
       if($query->num_rows()>0)
       {
           $arr = $query->result();
           foreach($arr as &$row){
              $row->description = str_replace("&nbsp;",'',strip_tags($row->description));
              $row->description = ltrim(str_replace("1.",'',$row->description));
              $row->description = trim(str_replace("\r\n",'',$row->description));
            }
           return $arr;
       }
       return false;
    }

    //blog 
    public function blog($offset, $limit){
        $this->db->limit($offset, $limit);
        //$this->db->select("id,date4 as date,time1 as time,user_name,heading,link,IF(image IS NOT NULL AND image !='',CONCAT('".base_url()."admin-assets/upload/',image),'') as blogimage,SUBSTRING(description,1,100) AS description,created_date,updated_date,status");
        $this->db->select("id,DATE_FORMAT(str_to_date(date4,'%m/%d/%Y'),'%M %D, %Y') as date,time1 as time,user_name,heading,link,IF(image IS NOT NULL AND image !='',CONCAT('".base_url()."admin-assets/upload/',image),'') as blogimage,SUBSTRING(description,1,100) AS description,created_date,updated_date,status");
        $this->db->order_by('id','DESC');
        $query = $this->db->get('tbl_blogdetail');        
        if($query->num_rows()>0)
        {
            $arr = $query->result();
            foreach($arr as &$row){
               $row->description = str_replace("&nbsp;",'',strip_tags($row->description));
               $row->description = ltrim(str_replace("1.",'',$row->description));
             }
            return $arr;
        }
        return false; 
    }
    public function blogdetails($id = null){
       //$this->db->select("id,date4 as date,time1 as time,user_name,heading,link,IF(image IS NOT NULL AND image !='',CONCAT('".base_url()."admin-assets/upload/',image),'') as blogimage,SUBSTRING(description,1,100) AS description,created_date,updated_date,status");
       $this->db->select("id,DATE_FORMAT(str_to_date(date4,'%m/%d/%Y'),'%M %D, %Y') as date,time1 as time,user_name,heading,link,IF(image IS NOT NULL AND image !='',CONCAT('".base_url()."admin-assets/upload/',image),'') as blogimage,description,created_date,updated_date,status");
        $this->db->where('id',$id);
        $query = $this->db->get('tbl_blogdetail');        
        if($query->num_rows()>0)
        {
            $arr = $query->row();
            $arr->description = strip_tags(str_replace("</p>","\n",$arr->description));
            return $arr;
        }
        return false;
}

    public function emailcheck($email){
       $query=$this->db->get_where('tbl_user',array('email' => $email));
       if($query){
        $row=$query->result();
        return $row;
       } else {
           return false;
       }
    }
    //biography 
    public function biography(){
        //$this->db->select("id,date1 as date,label,heading,link,IF(image IS NOT NULL AND image !='',CONCAT('".base_url()."admin-assets/upload/',image),'') as biographyimage,SUBSTRING(description,1,100) AS description,created_date,updated_date,status");
        $this->db->select("id,DATE_FORMAT(str_to_date(date1,'%m/%d/%Y'),'%M %D, %Y') as date,label,heading,link,IF(image IS NOT NULL AND image !='',CONCAT('".base_url()."admin-assets/upload/',image),'') as biographyimage,SUBSTRING(description,1,100) AS description,created_date,updated_date,status");
        $this->db->order_by('id','DESC');
        $query = $this->db->get('tbl_Journey');        
        if($query->num_rows()>0)
        {
            $arr = $query->result();
            foreach($arr as &$row){
               $row->description = str_replace("&nbsp;",'',strip_tags($row->description));
               $row->description = ltrim(str_replace("1.",'',$row->description));
             }
            return $arr;
        }
        return false; 
    }

        //biographydetails 
    public function biographydetails($id=null){
           //$this->db->select("id,date1 as date,label,heading,link,IF(image IS NOT NULL AND image !='',CONCAT('".base_url()."admin-assets/upload/',image),'') as biographyimage,SUBSTRING(description,1,100) AS description,created_date,updated_date,status");
           $this->db->select("id,DATE_FORMAT(str_to_date(date1,'%m/%d/%Y'),'%M %D, %Y') as date,label,heading,link,IF(image IS NOT NULL AND image !='',CONCAT('".base_url()."admin-assets/upload/',image),'') as biographyimage,description,created_date,updated_date,status");
            $this->db->where('id',$id);
            $query = $this->db->get('tbl_Journey');        
            if($query->num_rows()>0)
            {
                $arr = $query->row();
                $arr->description = strip_tags(str_replace("</p>","\n",$arr->description));
                return $arr;
            }
            return false; 
    }

        //ideal 
    public function ideal($offset, $limit){
        $this->db->select("id,name,bc,IF(image IS NOT NULL AND image !='',CONCAT('".base_url()."admin-assets/upload/',image),'') as idealimage,description,DATE_FORMAT(created_date,'%M %D, %Y') as created_date,updated_date,status");
        $this->db->order_by('id','DESC');
        $this->db->limit($offset, $limit);
        $query = $this->db->get('tbl_ideal');        
        if($query->num_rows()>0)
        {
            $arr = $query->result();
            foreach($arr as &$row){
               $row->description = str_replace("&nbsp;",'',strip_tags($row->description));
               $row->description = ltrim(str_replace("1.",'',$row->description));
            }
            return $arr;
        }
        return false;          }

        //idealdetail 
    public function idealdetail($cat_id=null){
            $this->db->select("id,show_num,cat_id,name,bc,IF(image IS NOT NULL AND image !='',CONCAT('".base_url()."admin-assets/upload/',image),'') as idealimage,description,created_date,updated_date,status");
            $this->db->where('cat_id',$cat_id);
            $this->db->order_by('id','DESC');
            $query = $this->db->get('tbl_ideal');        
            if($query->num_rows()>0)
            {
                $arr = $query->row();
                $arr->description = strip_tags(str_replace("</p>","\n",$arr->description));
                return $arr;
            }
                return false; 
    }

        //profile 
    public function add_profile($data){
            if ($result=$this->db->insert('tbl_user', $data)) {
               return $result;
           } else {
               return false;
           }
    }

       //gallery 
    public function gallery(){
        $gallery_array = array();
        /*
           blogdetail images
        */
        $this->db->select('image');
        $this->db->order_by('id','DESC');
        $this->db->limit('4');
        $query = $this->db->get('tbl_blogdetail');        
        if($query->num_rows()>0)
         {
           foreach($query->result() as $blogdetail){
             array_push($gallery_array, array('img'=>base_url('admin-assets/upload/'.$blogdetail->image)));
           }
        }
        /*
           portfolio images
        */
        $this->db->select('image');
        $this->db->order_by('id','DESC');
        $this->db->limit('4');
        $query = $this->db->get('tbl_portfolio');        
        if($query->num_rows()>0)
         {
           foreach($query->result() as $portfolio){
             array_push($gallery_array, array('img'=>base_url('admin-assets/upload/'.$portfolio->image)));
           }
        }
        /*
          banner images
        */
        $this->db->select('image');
        $this->db->order_by('id','DESC');
        $this->db->limit('4');
        $query = $this->db->get('tbl_banner');        
        if($query->num_rows()>0)
         {
           foreach($query->result() as $banner){
             array_push($gallery_array, array('img'=>base_url('admin-assets/upload/'.$banner->image)));
           }
        }
        /*
          addgallery images
        */
        $this->db->select('image');
        $this->db->order_by('id','DESC');
        $this->db->limit('4');
        $query = $this->db->get('tbl_addgallery_image');        
        if($query->num_rows()>0)
         {
           foreach($query->result() as $addgallery){
             array_push($gallery_array, array('img'=>base_url('admin-assets/upload/'.$addgallery->image)));
           }
        }
        /*
          mediadetail images
        */
        $this->db->select('image');
        $this->db->order_by('id','DESC');
        $this->db->limit('4');
        $query = $this->db->get('tbl_mediadetail');        
        if($query->num_rows()>0)
         {
           foreach($query->result() as $mediadetail){
             array_push($gallery_array, array('img'=>base_url('admin-assets/upload/'.$mediadetail->image)));
           }
        }
       return $gallery_array;
    }
     
        //todayThought
    public function todaythought(){
        $this->db->select("id,date1 as date,label,heading,link,IF(image IS NOT NULL AND image !='',CONCAT('".base_url()."admin-assets/upload/',image),'') as image,SUBSTRING(description,1,100) AS description,created_date,updated_date,status");
        $this->db->order_by('id','DESC');
        $query = $this->db->get('tbl_detail3');        
        if($query->num_rows()>0)
        {
            $arr = $query->result();
            foreach($arr as &$row){
               $row->description = str_replace("&nbsp;",'',strip_tags($row->description));
               $row->description = ltrim(str_replace("1.",'',$row->description));
              }
            return $arr;
       }
        return false;      
    }
        //feedback
    public function feedback($data){
        if ($result = $this->db->insert('tbl_feedback', $data)) {
            return $result;
        } else {
            return false;
        }
    }
     
      //news
     public function news(){
        //$this->db->select("id,cat_id,headline,news_url,date,IF(image IS NOT NULL AND image !='',CONCAT('".base_url()."admin-assets/upload/',image),'') as newsimage,SUBSTRING(description,1,100) AS description,created_date,updated_date,status");
        $this->db->select("id,cat_id,headline,news_url,DATE_FORMAT(str_to_date(date,'%m/%d/%Y'),'%M %D, %Y') as date,IF(image IS NOT NULL AND image !='',CONCAT('".base_url()."admin-assets/upload/',image),'') as newsimage,SUBSTRING(description,1,100) AS description,created_date,updated_date,status");
        $this->db->order_by('id','DESC');
        $query = $this->db->get('tbl_news');        
        if($query->num_rows()>0)
        {
            $arr = $query->result();
            foreach($arr as &$row){
               $row->description = str_replace("&nbsp;",'',strip_tags($row->description));
               $row->description = ltrim(str_replace("1.",'',$row->description));
              }
            return $arr;
       }
        return false;      
     }
        //speechs news part
     public function speeches(){
        $this->db->select("id,speech_title,speech_url,status,created,updated_date");
        $this->db->order_by('id','DESC');
        $query = $this->db->get('tbl_speech');        
        if($query->num_rows()>0)
        {
            return $query->result();
        }
        return false;
     }
        //MediaGalleryPage news part
    public function MediaGalleryPage(){
        $this->db->select('id,m_catname as media_category,slug,created_date,updated_date,status');
        $this->db->order_by('id','DESC');
        $query = $this->db->get('tbl_mediagallery_category');        
        if($query->num_rows()>0)
        {
            return $query->result();
        }
        return false;      
     }
       //MediaGalleryPage_images news part
    public function MediaGalleryPage_images($m_catid=null){
        $this->db->select("id,IF(image IS NOT NULL AND image !='',CONCAT('".base_url()."admin-assets/upload/',image),'') as idealimage,m_catid as media_category_id,created_date,updated_date,status");
        $this->db->where('m_catid',$m_catid);
        $this->db->order_by('id','DESC');
        $query = $this->db->get('tbl_addgallery_image');        
        if($query->num_rows()>0)
        {
            return $query->result();
        }
        return false; 
    }

       //useregister
    public function useregister($data){
        if ($result=$this->db->insert('tbl_user', $data)) {
           return $result;
       } else {
           return false;
       }
    }

      //latest_blog
    public function latest_blog(){
        //$this->db->select("id,date4 as date,time1 as time,user_name,heading,link,IF(image IS NOT NULL AND image !='',CONCAT('".base_url()."admin-assets/upload/',image),'') as blogimage,SUBSTRING(description,1,100) AS description,created_date,updated_date,status");
        $this->db->select("id,DATE_FORMAT(str_to_date(date4,'%m/%d/%Y'),'%M %D, %Y') as date,time1 as time,user_name,heading,link,IF(image IS NOT NULL AND image !='',CONCAT('".base_url()."admin-assets/upload/',image),'') as blogimage,SUBSTRING(description,1,100) AS description,created_date,updated_date,status",FALSE);  
        $this->db->order_by('id','DESC');
        $query = $this->db->get('tbl_blogdetail');  
        if($query->num_rows()>0)
        {
        $arr = $query->result();
        foreach($arr as &$row){
        $row->description = str_replace("&nbsp;",'',strip_tags($row->description));
        $row->description = ltrim(str_replace("1.",'',$row->description));
        }
        return $arr;
        }
        return false;      
    }
    //events
    public function events(){
        $this->db->select("id,event_catname,slug,IF(image IS NOT NULL AND image !='',CONCAT('".base_url()."admin-assets/upload/',image),'') as eventsimage,,created_date,updated_date,status");
        $this->db->order_by('id','DESC');
        $query = $this->db->get('tbl_event_category');        
        if($query->num_rows()>0)
        {
            return $query->result();
        }
        return false;      
        }
        //eventsdetail news part
    public function eventsdetail($event_catid=null){
        $this->db->select("id,IF(image IS NOT NULL AND image !='',CONCAT('".base_url()."admin-assets/upload/',image),'') as eventsimage,event_catid,created_date,updated_date,status");
        $this->db->where('event_catid',$event_catid);
        $this->db->order_by('id','DESC');
        $query = $this->db->get('tbl_addevent_image');        
        if($query->num_rows()>0)
        {
            return $query->result();
        }
        return false; 
    }

        //complaint
    public function complaint($data){
        $this->db->order_by('id','DESC');
        if ($result=$this->db->insert('tbl_subscribe', $data)) {
           return $result;
       } else {
           return false;
       }
    }
    
    
    public function getnewsdataimage($m_catid){
        $this->db->select("id,CONCAT('".base_url()."admin-assets/upload/',image) as newsgalleryimage,m_catid,created_date,status");
        $this->db->where('m_catid', $m_catid);
        $this->db->order_by('id','DESC');
        $query = $this->db->get('tbl_addgallery_image');        
        if($query->num_rows()>0)
         {
           $result=$query->result();
           return $result;
        }else{
            return false;
        }
        
    } 
    public function newscategray(){
        $this->db->select("*");
        $this->db->order_by('id', 'DESC');
        $query=$this->db->get('tbl_mediagallery_category');
        $result=$query->result();
        if($result){
            return $result;
        } else{
            return false;
        }
    }
    
    public function getnewscontent(){  
        $this->db->select("tbl_news.cat_id,tbl_news.headline,tbl_news.news_url,tbl_news.date, tbl_news_category.news_catname,CONCAT('".base_url()."admin-assets/upload/',tbl_news_category.image) as mediaHeadlineimage,tbl_news.status as status");
        $this->db->from('tbl_news');			
        $this->db->join('tbl_news_category','tbl_news.cat_id=tbl_news_category.id','left');	 			
        $this->db->order_by('tbl_news.id','DESC');			
        $q=$this->db->get();
        //$r=$q->result();     
        if($q->num_rows()>0)
        {
            return $q->result();
        }
        return false;
    }
    
    public function invitation($data=array()){
         if ($result=$this->db->insert('tbl_invitation', $data)) {
            return $result;
        } else {
            return false;
        }
    }

    public function forgotpassword($data=array(),$user_id){
        $this->db->where('id', $user_id);
    $result=$this->db->update('tbl_user', $data);
    if($result){
        return $result;
    } else{
        return false;
    }
}


    //userlogin 
    public function login($email,$password) 
    { 
        $this->db->select("id,user_name,phone_no,email,IF(image IS NOT NULL AND image !='',CONCAT('".base_url()."admin-assets/upload/',image),'') as profileimage,created_date,dob,status");
        $this->db->where('email', $email);
        $this->db->where('password', $password);
        $quer = $this->db->get('tbl_user');
        if($quer->num_rows()>0){
           return  $quer->row();
        }else{
            return false;  
        }
    }
    public function todaysthought() {
        //$this->db->select("id,date1,label,heading,link,CONCAT('".base_url()."admin-assets/upload/',image) as todaysthoughtimage,description,status");
        //$this->db->where('status','1');
        $this->db->select("id,DATE_FORMAT(str_to_date(date1,'%m/%d/%Y'),'%M %D, %Y') as date,label,heading,link,IF(image IS NOT NULL AND image !='',CONCAT('".base_url()."admin-assets/upload/',image),'') as todaysthoughtimage,description,status");
        $this->db->order_by('id','DESC');
        //$this->db->limit('1','0');
        $query=$this->db->get('tbl_detail3');
        $result=$query->result(); 
        if($result){
        return $result;
        } else {
        return false;
        }   
    }
    public function getslugdata(){
        $this->db->select("id,date,time,user_name,heading,link,CONCAT('".base_url()."admin-assets/upload/',image) as blogimage, SUBSTRING(description,1,100) as description,status");
        $this->db->order_by('id','DESC');
        $query=$this->db->get('tbl_blogdetail');
        if($query->num_rows()>0){
        $result=$query->result();
        return  $result; 
        }
        return false;
    }

    public function getrecord_pwd(){
        $this->db->select("id,name,slug,scheme,detail,created");
        $this->db->where('slug','pwd');
        $query=$this->db->get('department_caegory');
        if($query->num_rows()>0){
        $result=$query->row();
        return  $result; 
        }
        return false;
    }
    public function getrecord_foodProcessing(){
        $this->db->select("id,name,slug,scheme,detail,created");
        $this->db->where('slug','foodprocesseing');
        $query=$this->db->get('department_caegory');
        if($query->num_rows()>0){
        $result=$query->row();
        return  $result; 
        }
        return false;
    }
    public function getrecord_publicEnterprises(){
        $this->db->select("id,name,slug,scheme,detail,created");
        $this->db->where('slug','publicEnterprises');
        $query=$this->db->get('department_caegory');
        if($query->num_rows()>0){
        $result=$query->row();
        return  $result; 
        }
        return false;
    }
    public function check_email($email){
        $query = $this->db->get_where('tbl_user',array('email' => $email));
        if( $query->num_rows() > 0 ){
            $row = $query->row();
            return $row;
        } else {
            return false;
        }
    } 
    public function check_social($fb,$gmail){
        $query = $this->db->get_where('tbl_user',array('google_id' => $id,'facebook_id' => $id));
        if( $query->num_rows() > 0 ){
            $row = $query->row();
            return $row;
        } else {
            return false;
        }
    } 

    public function social_update($data){
        $result=$this->db->update('tbl_user', $data);
        if($result){
            return $result;
        } else{
            return false;
        }
    }
    public function social_exist($social_key,$social_type,$email){
       $this->db->select('*');
       $this->db->from('tbl_user');
       if($social_type == 'gmail' )
       {
         $this->db->where('google_id',$social_key);  
       }else{
        $this->db->where('facebook_id',$social_key);  
       }
       $this->db->where('email',$email); 
       $query = $this->db->get();
       if($query->num_rows() > 0){
           return $query->row();
       }else{
            if($social_type == 'gmail' )
            {
            $data = array('google_id' => $social_key);
            }else{
             $data = array('facebook_id' => $social_key);
            }
           $this->db->where('email',$email); 
           $this->db->update('tbl_user',$data);
           $res = $this->db->get_where('tbl_user',array('email' => $email))->row();
           return $res;
       }
      return false;
    }
    public function socialregister($data){
        if ($this->db->insert('tbl_user', $data)) {
            $id = $this->db->insert_id();
            $query = $this->db->get_where('tbl_user',array('id' => $id));
            if( $query->num_rows() > 0 ){
                $row = $query->row();
                return $row;
            } else {
                return false;
            }
       } 
        return false;
    }
    
    // public function blogtest(){
    //     // $this->db->select("id,user_name,phone_no,email,IF(image IS NOT NULL AND image !='',CONCAT('".base_url()."admin-assets/upload/',image),'') as profileimage,created_date,dob,status");
    //     // $this->db->order_by('id','DESC');
    //     //$query = $this->db->query('select user_name from tbl_user');
    //     //$query = $this->db->query("select * from tbl_ideal_category where id in (select cat_id from tbl_ideal where cat_id <= 5)");
    //     $this->db->select('*')
    //       ->group_start()
    //         ->where('id<=','20')
    //          ->or_group_start()
    //            //->where('id<=','10')
    //            ->order_by('id','desc')
    //          ->group_end()
    //       ->group_end();
    //     $query = $this->db->get('tbl_user');
    //     if( $query->num_rows() > 0 ){
    //         return $query->result();
    //     } else {
    //         return false;
    //     }
    // }
}