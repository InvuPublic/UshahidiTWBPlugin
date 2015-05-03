<?php defined('SYSPATH') or die('No direct script access.');

class TWB_Install
{
    public function __construct()
    {
        $this->db = Database::instance();
    }

    public function run_install()
    {
        $query = "CREATE TABLE IF NOT EXISTS "
            .Kohana::config('database.default.table_prefix')
            ."TWB_settings (id int unsigned NOT NULL AUTO_INCREMENT, api_key varchar(40) NOT NULL, project_id varchar(40), PRIMARY KEY (id))";
        $this->db->query($query);
    }

    public function uninstall()
    {
        $this->db->query('DROP TABLE '.Kohana::config('database.default.table_prefix').'TWB_settings');
    }
}