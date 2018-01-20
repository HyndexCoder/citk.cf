<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Log_model extends MY_model{
    private $dirs;
    private $base_dir;
    private $time_placeholder = '{{time}}';

    public function __construct($base_path = '.edit_logs'){
        parent::__construct();

        $this->base_dir = $base_path.DIRECTORY_SEPARATOR;

        $this->dirs = array(
            'note' => $this->base_dir.'note'.DIRECTORY_SEPARATOR,
            'subject' => $this->base_dir.'subject'.DIRECTORY_SEPARATOR,
            'teacher' => $this->base_dir.'teacher'.DIRECTORY_SEPARATOR,
            'unit' => $this->base_dir.'unit'.DIRECTORY_SEPARATOR,
            'user' => $this->base_dir.'user'.DIRECTORY_SEPARATOR,
            'file' => $this->base_dir.'file'.DIRECTORY_SEPARATOR
        );
    }

    public function log($type, $file_name, $text = ''){
        $type = strtolower($type);

        if (!isset($this->dirs[$type])) return false;

        $text .= PHP_EOL;

        //Replace placeholder with time
        $text = str_replace($this->time_placeholder, $this->now(), $text);

        $file = $this->get_file($type, $file_name);

        if (! $file) return false;

        $res = fwrite($file, $text);

        fclose($file);

        return $res;
    }

    /**
     * Creates a log file if not exists
     * Writes initial data
     * Returns file handle in append mode
     */
    public function get_file($type, $file_name, $mode = 'a'){
        $type = strtolower($type);

        if (!isset($this->dirs[$type])){
            return false;
        }

        //Create the directory if not exist
        if (!is_dir($this->dirs[$type])){
            mkdir($this->dirs[$type]);
        }

        $file_path = $this->dirs[$type].$file_name.'.log';

        //Create file if not exists
        if (! file_exists($file_path)){
            $file = fopen($file_path, 'w');
            fwrite($file, 'This log was created on '.$this->now().PHP_EOL);
            fclose($file);
        }

        return fopen($file_path, $mode);
    }

    private function now($format = 'D d M Y, h:i a'){
        $this->load->helper('date');
        return date($format, now());
    }
};