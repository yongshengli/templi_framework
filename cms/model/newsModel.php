<?php
defined('IN_TEMPLI') or die('无权访问');
class newsModel extends Model{
    function __construct(){
        parent::__construct('news');
    }
    public function add_news($data){
        $news = $content = array();
        $M_data = Templi::model('news_data');
        $news['title'] = $data['title'];
        $news['category_id'] = $data['category_id'];
        $news['description'] = $data['description']?$data['description']:str_cut(strip_tags($data['content']), 200); 
        $news['ctime'] =SYS_TIME;
        $news_id = $this->insert($news, true);
        $content['news_id'] =$news_id;
        $content['content'] =$data['content'];
        return $M_data->insert($content);
    }
    public function edit_news($data){
        
    }
}
?>