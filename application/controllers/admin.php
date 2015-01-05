<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->helper('url');
		$this->load->library('parser');
		$this->load->library('grocery_CRUD');
	}
		
	public function User()
	{
		$crud = new grocery_CRUD();
// 		$crud->set_theme('twitter-bootstrap');
		$crud->set_table('user');	
		$crud->columns('id','name','chepai','chexin');
		//$crud->edit_fields('username','phone','secret','type');
		$crud->where('type','客户');
		$crud->display_as('name','姓名');
		$crud->display_as('phone','手机号');
		$crud->display_as('chexin','车型');
		$crud->display_as('password','密码');
		$crud->display_as('type','类型');
		$crud->display_as('chepai','车牌');
		
		$crud->set_subject('客户');
		$output = $crud->render();	
		$this->load->view('admin.php',$output);
	}
	
	public function Item()
	{
		$crud = new grocery_CRUD();
		$crud->set_table('item');
		$crud->columns('id','itemname','price');
		$crud->set_subject('服务项目');
		$crud->display_as('itemname','项目名称');
		$crud->display_as('price','价格');
		$output = $crud->render();
		$this->load->view('admin.php',$output);
	}
	
	
	public function record()
	{
		$crud = new grocery_CRUD();
		//$crud->set_theme('twitter-bootstrap');
		$crud->set_table('records');
		//$crud->columns('id','userid','itemname','quantity','price','total','shop','date');
		//$crud->edit_fields('partner_id','name','sex','age','photo','type');
		$crud->set_subject('服务记录');
		$crud->display_as('userid','车牌');
		$crud->display_as('itemid','服务项目');
		$crud->display_as('price','价格');
		$crud->display_as('quantity','数量');
		$crud->display_as('total','总价');
		$crud->display_as('memo','备注');
		$crud->display_as('date','日期');
		$crud->display_as('workerid','操作人员');
		$crud->set_relation('userid','user','chepai');
		$crud->set_relation('itemid','item','itemname');
		$crud->set_relation('workerid','user','name',array('type'=>'worker'));
		
		$output = $crud->render();
		$this->load->view('admin.php',$output);
	}
	
// 	public function insurance()
// 	{
// 		$crud = new grocery_CRUD();
// // 		$crud->set_theme('twitter-bootstrap');
// 		$crud->set_table('insurance');
// 		$crud->columns('id','userid','image');
// 		//$crud->edit_fields('partner_id','name','sex','age','photo','type');
// 		$crud->set_subject('维修记录');
// 		$crud->display_as('userid','用户名');
// 		$crud->display_as('image','保单照片');
// 		$crud->display_as('ID_front_image','身份证正面');
// 		$crud->display_as('ID_back_image','身份证反面');
// 		$crud->display_as('bank_image','银行理赔卡照片');
// 		$crud->set_relation('userid','user','username');
		
// 		$crud->set_field_upload('ID_front_image','assets/uploads/files');
// 		$crud->set_field_upload('ID_back_image','assets/uploads/files');
// 		$crud->set_field_upload('bank_image','assets/uploads/files');
// 		$crud->set_field_upload('image','assets/uploads/files');
		
		
// 		$output = $crud->render();
// 		$this->load->view('UserManagement.php',$output);
// 	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */