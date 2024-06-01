<?php
class Api_model extends CI_Model {

    function seeder() {
        $this->load->database();
        echo "Seeding of database started... <br>";

        //check/create if db exist
        $query = $this->db->query('CREATE DATABASE IF NOT EXISTS blog_db');

        //select db
        $this->db->query('USE blog_db');

        //create table
        $create_tbl = "CREATE TABLE IF NOT EXISTS blog (".
        "    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,".
        "    title CHAR(255),".
        "    content TEXT(255),".
        "    author CHAR(255),".
        "    created_at DATETIME NOT NULL,".
        "    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP".
        ")";
        $this->db->query($create_tbl);

        //insert data
        $current_date = date("Y-m-d h:i:s");
        $blog_data = array( 
            array(
                "title" => "Blog post 1", 
                "content" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad",
                "author" => "John Doe",
                "created_ad" => $current_date
            ), 
            array(
                "title" => "Blog post 2", 
                "content" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad",
                "author" => "Jane Doe",
                "created_ad" => $current_date
            ), 
            array(
                "title" => "Blog post 3", 
                "content" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad",
                "author" => "John Doe",
                "created_ad" => $current_date
            ), 
            array(
                "title" => "Blog post 4", 
                "content" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad",
                "author" => "Jane Doe",
                "created_ad" => $current_date
            ), 
            array(
                "title" => "Blog post 5", 
                "content" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad",
                "author" => "John Doe",
                "created_ad" => $current_date
            )
        );
        
        $insert_tbl = "INSERT INTO blog (title, content, author, created_at) VALUES ";
        $length = count($blog_data);
        $ctr = 1;
        foreach($blog_data as $blog) {
            $insert_tbl.="('".$blog['title']."', '".$blog['content']."', '".$blog['author']."', '".$blog['created_ad']."')";
            $ctr++;

            if ($ctr <= $length) {
                $insert_tbl.=",";
            } else {
                $insert_tbl.=";";
            }
        }
        $this->db->query($insert_tbl);

        echo "Seeding completed... <br>";
    }

    function get_blogs() {
        $this->load->database();

        $this->db->query('USE blog_db');

        $query = $this->db->query('SELECT * FROM blog');

        return json_encode($query->result_array());
    }

    function get_blog($id) {
        $this->load->database();

        $this->db->query('USE blog_db');

        $query = $this->db->query("SELECT * FROM blog WHERE id = '".$id."';");

        return json_encode($query->result_array());
    }

    function insert_blog($blog) {
        $this->load->database();
        $this->db->query('USE blog_db');
        $insert_tbl = "INSERT INTO blog (title, content, author) VALUES ('".$blog['title']."', '".$blog['content']."', '".$blog['author']."');";
        $query = $this->db->query($insert_tbl);
        
        if ($query) {
            return $this->get_blog($this->db->insert_id());
        }
        
    }

    function update_blog($blog) {
        $current_time = date("Y-m-d h:i:s");

        $this->load->database();
        $this->db->query('USE blog_db');
        $insert_tbl = "UPDATE blog SET ";
        $ctr = 1;
        $length = count($blog);  
        foreach ($blog as $key => $value) {
            if($value && $key != 'id') {
                $insert_tbl .= " ".$key." = '".$value."' ";
            }

            if ($key != 'id' && $ctr < $length) {
                $insert_tbl.=",";
            } 

            $ctr++;
        }
        $insert_tbl.=", updated_at = '".$current_time."' WHERE id = ".$blog['id'].";";
        $query = $this->db->query($insert_tbl);

        if ($query) {
            return $this->get_blog($blog['id']);
        }
        
    }

    function delete_blog($id) {
        $this->load->database();
        $this->db->query('USE blog_db');

        $delete = "DELETE FROM blog WHERE id=".$id;

        return $query = $this->db->query($delete);

    }



    function fetch_all() {
        
        $this->load->database();
        // echo "test";
        $query = $this->db->query('SELECT name, help_category_id, name FROM help_category');
        // print_r($query->result_array());
        // echo json_encode($query->result_array());

        $JSON = array('name' => 'John Doe', 'age' => 25, 'city' => 'Example City');
        echo json_encode($JSON);

        // echo ($this->db);
        // $this->db->order_by('help_category_id', 'DESC');
        // return $this->db->get('help_category');
    }
}

?>
