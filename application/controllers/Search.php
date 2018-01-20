<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Search extends MY_Controller{
    public function index(){
        $query = $this->input->get('query');
        $query = is_null($query)? '': $query;
        
        $ref = $this->input->get('ref');
        $ref = is_null($ref)? '/': $ref;

        if ($query == ''){
            set_flashmsg($ref, 'You must type something to search', 'error');
        }

        $host = parse_url(base_url(), PHP_URL_HOST);

        if (is_null($host)){
            set_flashmsg($ref, 'There was some error, unable to search', 'error');
        }

        $query .= ' site:'.$host;

        redirect('https://google.com/search?q='.urlencode($query));
        /*$limit = $this->input->get('limit');
        $offset = $this->input->get('offset');

        $limit = (int)(empty($limit)? 10: $limit);
        $offset = (int)(empty($offset)? 0: $offset);
        
        if($query == null) {
            exit;
        }

        $terms = explode(' ', $query);
        foreach ($terms as $i=>$term){
            if(empty($term)) unset($terms[$i]);
        }

        $this->load->model('unit_model');
        $this->load->model('note_model');
        //$this->load->model('teacher_model');

        $results = array(
            //array('title'=>'Heat and entropy', 'link' => site_url())
        );

        //Searching units
        $results['units'] = $this->unit_model->search($terms, $limit, $offset);

        //Searching notes
        $results['notes'] = $this->note_model->search($terms, $limit, $offset);

        $total = 0;
        foreach ($results as $res){
            $total += count($res);
        }
        
        $next_page = site_url('search').'?query='.urlencode($query).'&limit='.urlencode($limit).'&offset='.urlencode($offset+1);

        $prev_page = null;
        if($offset>0){
            $prev_page = site_url('search').'?query='.urlencode($query).'&limit='.urlencode($limit).'&offset='.urlencode($offset-1);
        }

        $this->load->view('header', array('page_title' => 'Search results for "'.$query.'"') );
        $this->load->view('search', array('query'=>$query, 'results'=>$results, 'total'=>$total, 'limit'=>$limit, 'offset'=>$offset, 'next_page'=>$next_page, 'prev_page'=>$prev_page ));
        $this->load->view('footer');*/
    }
};