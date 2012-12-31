<?php
/**
***************************************************************************************************
 * @Software    AjaxMint Gallery
 * @Author      Rajapandian - arajapandi@gmail.com
 * @Copyright	Copyright (c) 2010-2011. All Rights Reserved.
 * @License		GNU GENERAL PUBLIC LICENSE
 **************************************************************************************************
 This source code is licensed under the terms of the GNU GENERAL PUBLIC LICENSE
 http://www.gnu.org/licenses/gpl.html
 **************************************************************************************************
 Copyright (c) 2010-2011 http://ajaxmint.com. All Rights Reserved.
 **************************************************************************************************
**/

class ModelPictures extends Model {
	
    function __construct() {
        $this->load->helper('pictures');
        $this->helper = new HelperPictures();

        
        $this->user_id = $this->session->data['emb_user_id'];
        if (! $this->user_id) $this->user_id = 0;
        
        $this->admin_level = $this->session->data['admin_level'];
        if (! $this->admin_level) $this->admin_level = 0;
 
    }
    
    function updatePictureData($picture_id,$data) {
    
        $keypairs = '';
        foreach ($data as $key=>$val) {
            $keypairs .= "$key='$val',";
            }
        
        $keypairs .= ' date_updated=NOW() ';
        $query = "update ".DB_PREFIX."pictures 
                    set $keypairs
                    where picture_id='". $picture_id ."'";
                           
        $this->db->query($query);  
        return $query; 
    }
    
    function picturesDetails($album_id,$start,$limit) {
        // $limit is the config_per_page value, which is the panel/batch size (8)
        $c_panel =($start / $limit) + 1;

        $last = $limit -1;     
           
        $details = $this->getPictures($album_id,$start,$limit);
                
        $return = array();
        foreach($details as $key=>$value) {
            $return[$key] = $value;    
            if(!$value['image']) {
                $value['image'] = 'noimage.jpg';
            }
 
            $album_image = HTTP_GALLERIA_THUMB.'/'. $value['image']; //MK  
                        
            $return[$key]['image'] = $album_image;  //HTTP_THUMB.'/'.$value['image'];
            
            $link = 'index.php?c=pictures_single&picture_id='.
                                        $value['picture_id'] .'&title='.$value['title']. '&cp='.$c_panel;
            // my attempts to control the page no.
            $link = 'index.php?c=pictures_single&picture_id='.
                    $value['picture_id'] . '&cp='.$c_panel.'&page=all';
                     
            $return[$key]['link'] = $this->seourl->rewrite($link);  
                                                    
        }
        return $return;
    }

    function getPictures($album_id,$start,$limit) {
  
//echo  "getPictures $this->user_id -- $this->admin_level </br>";
  
        return $this->helper->getPictures($album_id,$start,$limit);
    }

    function getPicture($picture_id) {
    
        if(!(int)$picture_id)die('dead at getPicture');
        
        $query = "SELECT * FROM  ".DB_PREFIX."pictures pic
                                where
                                pic.picture_id='".$this->db->escape($this->picture_id)."' ";
//echo $query; exit;                                
        $result = $this->db->query($query);
        $return = $result->row;
        
        if(!$return['image']) {
            $return['image'] = 'noimage.jpg';
        }

        $album_image = HTTP_GALLERIA_THUMB. '/'.$return['image']; //MK  
 
        $return['image_large'] = $album_image;  //HTTP_LARGE.'/'.$return['image'];
        
        //$return['image'] = HTTP_IMAGE.'/'.$return['image'];
        $return['image'] = HTTP_GALLERIA_LARGE.'/' .$return['image']; //MK  

        return $return;
    }

    function getAllPictures($album_id) {
  
 
        $user_id = $this->user_id;
        $admin_level = $this->admin_level;
 
        $morequery = '';
        if ($admin_level < AUTHOR_LEVEL) {
            $morequery .= " AND (pic.date_released <= NOW() 
                            OR pic.date_released = 0 
                            OR gallery_id = '$user_id'  )";
            }
 
        $morequery .= " AND  (pic.sortorder < al.first_paid_page 
                                OR al.first_paid_page = 0
                                OR gallery_id = '$user_id') ";
               
        $albums_tbl = DB_PREFIX.'albums al';
                              
        $result = $this->db->query("SELECT *, pic.image as image
                                    FROM 
                                    ".DB_PREFIX."pictures pic 
                                    LEFT JOIN $albums_tbl 
                                    ON (al.album_id = pic.album_id) 
                                    WHERE
                                    pic.album_id='".$this->db->escape($album_id)."'
                                    $morequery
                                    ORDER BY pic.sortorder");    
 
        return $result->rows; 
    }
    
    function getPictureList($album_id) {
  
        //echo  "getAllPictures $this->user_id -- $this->admin_level </br>";
 
        $user_id = $this->user_id;
        $admin_level = $this->admin_level;
            
        $morequery = '';
        if ($admin_level < AUTHOR_LEVEL) {
            $morequery .= " AND (pic.date_released <= NOW() 
                            OR pic.date_released = 0 
                            OR gallery_id = '$user_id'  )";
            }
 
        $morequery .= " AND  (pic.sortorder < al.first_paid_page OR al.first_paid_page = 0) ";
     
        $albums_tbl = DB_PREFIX.'albums al';

        $query = "SELECT picture_id,pic.title as title,gallery_id
                                    FROM ".DB_PREFIX."pictures pic 
                                    LEFT JOIN $albums_tbl 
                                    ON (al.album_id = pic.album_id) 
                                    WHERE
                                    pic.album_id='".$this->db->escape($album_id)."'
                                    $morequery
                                    ORDER BY pic.sortorder";
                                    
//echo  "getPictureList $this->user_id -- $this->admin_level $query</br>";
                             
        $result = $this->db->query($query); 
                                        
        $picture_list = array();
        foreach($result->rows as $value) {
            $picture_list[] = $value['picture_id'];
            $picture_title[] = $value['title'];
        }

        $this->picture_title = $picture_title;
        return $this->picture_list = $picture_list;
    }
    
    function totalPictures($album_id) {
 
        return $this->helper->totalPictures($album_id);
    }

    function albumNavLink($album_id) {
//echo  "albumNavLink $this->user_id -- $this->admin_level </br>";
 
        $user_id = $this->user_id;
        $admin_level = $this->admin_level;
        
        $query = "SELECT al.image as cover_image,
                                        al.album_id,
                                        gl.gallery_id,
                                        al.title as album,
                                        gl.name as gallery,
                                        al.first_paid_page
                                        FROM 
                                        ".DB_PREFIX."albums al 
                                        JOIN ".DB_PREFIX."gallery gl ON (al.gallery_id=gl.gallery_id)
                                        WHERE
                                        al.album_id='".$this->db->escape($album_id)."' 
                                        limit 1";
 //echo $query; exit;                                                  
        $result = $this->db->query($query);
        $result_row = $result->row;    
        $return = array();
        $return[1]['link']=$this->seourl->rewrite('index.php?c=albums&gallery_id='.$result_row['gallery_id'].'&title='.$result_row['gallery']);
        $return[1]['title']=$result_row['gallery'];
        $return[2]['title']=$result_row['album'];
        $return[4] = $result_row['gallery_id'];
        $return[5] = $result_row['cover_image'];
        $return[6] = $result_row['first_paid_page'];
        
        return $return;
    }
    
    function pictureNavLink($picture_id) {
        
 
        $user_id = $this->user_id;
        $admin_level = $this->admin_level;
        
        $album_first_paid_page = $this->album_first_paid_page; 
                
        $morequery = '';
        if ($admin_level < AUTHOR_LEVEL) {
            $morequery .= " AND (pic.date_released <= NOW() 
                            OR pic.date_released = 0 
                            OR gl.gallery_id = '$user_id'  )";
            }
        
        $query = "SELECT pic.picture_id,
                         al.album_id,
                         gl.gallery_id,
                         pic.title as picture,
                         al.title as album,
                         gl.name as gallery
                         FROM  ".DB_PREFIX."pictures pic 
                            JOIN ".DB_PREFIX."albums al ON (al.album_id=pic.album_id)
                            JOIN ".DB_PREFIX."gallery gl ON (al.gallery_id=gl.gallery_id)
                        WHERE pic.picture_id='".$this->db->escape($picture_id)."' 
                        $morequery
                        limit 1";
                        
 //echo  "pictureNavLink $this->user_id -- $this->admin_level $query</br>";
                                                 
        $result = $this->db->query($query);                
            
        $result_row = $result->row;    
        $return = array();
        $return[1]['link']=$this->seourl->rewrite('index.php?c=albums&gallery_id='.$result_row['gallery_id'].'&title='.$result_row['gallery']);
        $return[1]['title']=$result_row['gallery'];
        $return[2]['link'] = $this->seourl->rewrite('index.php?c=pictures&album_id='.$result_row['album_id'].'&title='.$result_row['album']);
        $return[2]['title']=$result_row['album'];
        $return[3]['title']=$result_row['picture'];
//print_array($return); exit;        
        return $return;
    }
    
    function getNextPicture() { 
        // not used for now
        
//echo  "getNextPicture $this->user_id -- $this->admin_level </br>";
  
        $this_picture = array_search($this->picture_id, $this->picture_list);
    
        if ($this_picture < sizeof($this->picture_list)-1) {
            return $this->data['next'] = $this->seourl->rewrite('index.php?c=pictures_single&picture_id='.$this->picture_list[$this_picture+1].'&title='.$this->picture_title[$this_picture+1]);
        }
        return ;
    }
    
    function getPrevPicture() {
        // not used for now

//echo  "getPrevPicture $this->user_id -- $this->admin_level </br>";
    
        $image_list = $this->picture_list;
        $this_picture = array_search($this->picture_id, $this->picture_list);
        if ($this_picture > 0) {
            return $this->data['prev'] = $this->seourl->rewrite('index.php?c=pictures_single&picture_id='.$this->picture_list[$this_picture-1].'&title='.$this->picture_title[$this_picture-1]);
        }
        return ;
    }
 
    function getNextPanel($album_id,$current_panel) { 
 //echo  "getNextPanel $this->user_id -- $this->admin_level </br>";
       
        $total_pages = $this->totalPictures($album_id);
        $per_page = $this->session->data['panel_count'];
        $nbr_panels = ceil($total_pages / $per_page);
        
 //echo $total_pages.'--'.$current_panel.'--'.$per_page.'--'.$nbr_panels;  
        
        if ($current_panel == $nbr_panels) return false;
        $next = $current_panel + 1;
        
        return $this->data['next'] = $this->seourl->rewrite('index.php?c=pictures&album_id='.$album_id.'&page='.$next);
        
    }   
    
    function getPrevPanel($album_id,$current_panel) { 

        if ($current_panel == 1) return false;
        $prev = $current_panel - 1;
        return $this->data['prev'] = $this->seourl->rewrite('index.php?c=pictures&album_id='.$album_id.'&page='.$prev);
 
    } 
}