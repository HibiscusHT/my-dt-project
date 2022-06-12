<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dtmodel extends CI_Model
{
   private $table;

   private $primary;

   public function __construct()
   {
       parent::__construct();
   }

   /**
    * process query options
    * param{$options}
    */

    private function processOption($option)
    {

        if(isset($option['attribute'])){
            $this->db->select($option['attribute']);
        }

        if(isset($option['where'])){
            $this->db->where($option['where']);
        }

        if(isset($option['like'])){
            $this->db->like($option['like']);
        }

        if(isset($option['join'])){
            foreach($option['join'] as $k => $join){
                $this->db->join($join['key'],$join['on']);
            }
        }

        if(isset($option['group'])){
            $this->db->group_by($option['group']);
        }

        if(isset($option['order'])){
            $this->db->order_by($option['order']);
        }
    }

   /**
    * give access to database table
    * param{$table}
    * return class
    */

   public function schema($table)
   {
       $this->table = $table;
       return $this;
   }

   /**
    * insert new data to database table
    * param{$data}
    */

    public function create($data)
    {
        $this->db->insert($this->table,$data);
    }

    /**
     * find one or insert new data to database table
     * param{$options}
     * return false or class
     */

     public function findOneOrNew($options)
     {
         
            $this->processOption($options);
            $res = $this->db->get($this->table)->result();
            if(count($res) > 0){
                 return false;
            } else {
                 return $this;
            }
        
     }

     /**
      * find one data and set column Id as identity
      * param{$options}
      * return object
      */

    public function findOne($options)
    {
            $this->processOption($options);
            $this->db->limit(1);
            $res = $this->db->get($this->table)->result();
            
            if(count($res) > 0){
                if(isset($options['where']['Id'])){
                    $this->primary = ['Id'=>$options['where']['Id']];
                }
                 return $res;
            } else {
                 return false;
            }   
    }

     /**
      * find all data
      * return object
      */

    public function findAll($options)
      {
              $this->processOption($options); 
              $res = $this->db->get($this->table)->result();
              if(count($res) > 0){
                   return $res;
              } else {
                   return false;
              }  
      }

      /**
       * update one active data
       * param{$data}
       * return boolean
       */

    public function update($data)
    {
        $this->db->where($this->primary);
        $this->db->update($this->table,$data);
        return true;
    }

     /**
       * remove one active data
       * param{$data}
       * return boolean
       */

      public function remove()
      {
          $this->db->where($this->primary);
          $this->db->delete($this->table);
          return true;
      }

}