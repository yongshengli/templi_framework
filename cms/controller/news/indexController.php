<?php
defined('IN_TEMPLI') or die('非法引用');
class indexController extends Controller{
    protected function init(){
        $this->M_news = Templi::model('news');
        $this->M_category = Templi::model('category');
        $this->category = $this->M_category->find(array('category'=>'news'));
    }
    public function index(){
        $seo['title'] ='新闻-'.Templi::get_config('app_name');
        $this->assign('seo',$seo);
        $this->display();
    }
    public function lists(){
        //$catid =(int)$_GET['catid'] or die;
        $page = (int)$_GET['page'];
        $M_menu = Templi::model('menu');
        $menu = $M_menu->select(array('module'=>'news'));
        //print_r($menu);
        $where = 'status=99';
        $where = $catid?'catid=$catid':'';
        $data =$this->M_news->getlist($where, '*', $order='', $page, 2, $pageNum=8, '', $maxpage=100);
        $seo['title'] ='新闻列表-'.Templi::get_config('app_name');
        $seo['keywords'] =$this->category['keywords'];
        $seo['description'] =$this->category['description'];
        $this->assign('data',$data);
        $this->assign('seo',$seo);
        $this->display();
    }
    public function detail(){
        $id=(int)$_GET['id'] or die;
        $data['info'] = $this->M_news->find(array('id'=>$id));
        if(!$data['info']){
            $this->show_message('您查看的内容不存在或者已删除！');
        }
        $M_data = Templi::model('news_data');
        $data['content'] =$M_data->find(array('news_id'=>$data['info']['id']));
        $seo['title'] = $data['info']['title'].Templi::get_config('app_name');
        $this->assign('data',$data);
        $this->assign('seo',$seo);
        $this->display();
    }
}
?>