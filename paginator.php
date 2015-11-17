<?php

/**
 * Class Paginator
 *
 * Produces an array of page numbers to be loaded in pagination bar
 *
 * @author "d3nislav"
 *
 */
class Paginator{

    #number of pages around CURRENT
    private $core_size = 5;

    #number of pages beyond the core (Positive and Negative)
	private	$num_of_spreads = 3;

    #stores the generated pages
	private $list = array();

    #tracks the number of pages in core and spreads
    private $list_stats = array();

    #current page
    private $current_page = null;

    #total records to paginate
    private $total_records = null;

    #number of records per page
    private $records_per_page = null;

    #total number of records
    private $total_number_pages = null;

    /**
     * Paginator constructor.
     *
     * @param $current_page
     * @param $total_records
     * @param int $records_per_page
     * @param null $core_size
     * @param null $num_of_spreads
     */
    public function __construct($current_page, $total_records, $records_per_page = 10, $core_size = null, $num_of_spreads = null)
    {
        try{
            $this->current_page = (int)$current_page;
            $this->total_records = (int)$total_records;
            $this->records_per_page = (int)$records_per_page;
            $this->total_number_pages = (int)ceil($total_records/$records_per_page);

            #Ensures that current_page is not larger than total_number_pages
            if ($this->current_page > $this->total_number_pages) {
                $this->current_page = $this->total_number_pages;
            }

            if(is_numeric($core_size)){

                $this->core_size = (int) $core_size;

            }
            
            if(is_numeric($num_of_spreads)){

                $this->num_of_spreads = (int) $num_of_spreads;

            }

            $this->generate($this->core_size,$this->num_of_spreads);

        } catch (Exception $e) {

            print $e->getMessage();

        }
    }

    /**
     * Returns the generated page array
     *
     * @return array
     */
    public function getPages()
    {
        return $this->list;
    }

    /**
     * Returns next page
     *
     * @return mix
     */
    public function getNext()
    {
		if (!is_null($this->current_page) && $this->current_page < $this->total_number_pages) {
			
			return $this->current_page + 1;
		
		} else {
			
			return null;
				
		}
	}

    /**
     * Returns previous page
     *
     * @return mix
     */
	public function getPrev()
    {
		if (!is_null($this->current_page) && $this->current_page > 1) {
			
			return $this->current_page - 1;
		
		} else {
			
			return null;
				
		}
	}

    /**
     * Returns the last page
     *
     * @return mix
     */
	public function getLast()
	{
		return $this->total_number_pages;
	}

    /**
     * Returns an array with pages and fixed size
     *
     * IF POSSIBLE
     *
     * @param $num_elem
     * @return array
     */
    public function fixedPages($num_elem)
    {
        $list_count = count($this->list);
        if ($list_count > $num_elem) {
            $offset = (int) ($list_count-$num_elem)/2;
            return array_slice($this->list,$offset,$num_elem);

        } elseif ($list_count < $num_elem) {

            $diff = $num_elem - $list_count;
            if (!empty($this->list_stats)) {

                $core = $this->list_stats["count_core"]+1;
                $spreads = $this->num_of_spreads;

                #if core is full
                if ($this->list_stats["count_core"] == $this->core_size) {
                    $spreads += ceil($diff / 2);
                } else {
                #core is not full
                    $core_diff = $this->core_size - $this->list_stats["count_core"];
                    $spreads += ceil($diff / 2)+$core_diff;
                }


            }

            $this->generate($core, $spreads);

            return $this->list;
        } else {
            return $this->list;
        }
    }

    /**
     * Generates the Page Array
     *
     * @param $core_size
     * @param $num_of_spreads
     * @return bool
     */
    private function generate($core_size, $num_of_spreads)
    {
        #Generating CORE
        if($this->current_page > $this->total_number_pages){
            return false;
        }
        
        $list = array("core"=>array(),"negative"=>array(),"positive"=>array());
        
        $center = $this->current_page;
        $core_radius = floor($core_size / 2);
        for($i = ($center - $core_radius); $i <= ($center + $core_radius); $i++) {
            if ($i > 0 and $i <= $this->total_number_pages) {
                $list["core"][] = (int)$i;
            }
        }

        #Generating SPREADS
        if($num_of_spreads > 0) {
         
	        $pos_spread_size = floor(($this->total_number_pages - $this->current_page+1) / $num_of_spreads );
	
	        for($i = 1; $i <= $num_of_spreads; $i++){
	
	            if(($this->current_page + $i*$pos_spread_size - 1) > $this->current_page + $core_radius){
	
	                $list["positive"][] = (int)($this->current_page + $i*$pos_spread_size - 1);
	
	            }
	            
	        }
        
	        $neg_spread_size = floor(($this->current_page+1) / $num_of_spreads );
	
	        if ($neg_spread_size > 1) {
	
	            for($i = 1; $i <= $num_of_spreads; $i++){
	
	                if(($this->current_page - $i*$neg_spread_size) - $core_radius > 1){
	                    $list["negative"][] = (int)($this->current_page - $i*$neg_spread_size);
	                }
	                else{
	                    $list["negative"][] = (int)1;
	                    break;
	                }
	                
	                
	            }
	
	            $list["negative"] = array_reverse($list["negative"]);
	        
	        }
		} else {
		 
			$list["positive"] = [];
			$list["negative"] = [];
		
		}
		
		
        $this->list_stats["count_negative"] = count($list["negative"]);
        $this->list_stats["count_core"] = count($list["core"]);
        $this->list_stats["count_positive"] = count($list["positive"]);

        $this->list = array_merge($list["negative"],$list["core"],$list["positive"]);
        
        return true;
    }
    
//end class	
}
