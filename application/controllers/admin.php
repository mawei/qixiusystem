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
		$this->shopid = 1;
	}
	
	public function ShopIdCallback($post_array)
	{
		$post_array['shopid'] = 1;
		return $post_array;
	}
		
	public function User()
	{
		$crud = new grocery_CRUD();
		$crud->set_table('user');	
		$crud->columns('id','name','chepai','chexin','baoxian_duedate','nianjian_duedate');
		$crud->display_as('name','姓名');
		$crud->display_as('phone','手机号');
		$crud->display_as('chexin','车型');
		$crud->display_as('chepai','车牌');
		$crud->display_as('xinshi_image','行驶证照片');
		$crud->display_as('id_image','身份证照片');
		$crud->display_as('baoxian_duedate','保险到期日');
		$crud->display_as('nianjian_duedate','年检到期日');
		$crud->required_fields('chepai');
		$crud->set_field_upload('xinshi_image','assets/uploads/files');
		$crud->set_field_upload('id_image','assets/uploads/files');
		
		$crud->set_subject('客户');
		$output = $crud->render();	
		$this->load->view('admin.php',$output);
	}
	
	public function Worker()
	{
		$crud = new grocery_CRUD();
		$crud->set_table('worker');
		$crud->columns('id','name','phone');
		$crud->edit_fields('name','phone');
		$crud->display_as('name','姓名');
		$crud->display_as('phone','手机号');
		
		$crud->set_subject('员工');
		$output = $crud->render();
		$this->load->view('admin.php',$output);
	}
	
	public function Accident()
	{
		$crud = new grocery_CRUD();
		$crud->set_table('accident_record');
		$crud->columns('id','userid','date','address');
		$crud->display_as('userid','车牌');
		$crud->display_as('date','时间');
		$crud->display_as('address','地址');
		$crud->display_as('image1','照片1');
		$crud->display_as('image2','照片2');
		
		$crud->set_field_upload('image1','assets/uploads/files');
		$crud->set_field_upload('image2','assets/uploads/files');
		
		$crud->set_relation('userid', 'user', 'chepai');
		$crud->set_subject('事故登记');
		$output = $crud->render();
		$this->load->view('admin.php',$output);
	}
	
	
	public function Shop()
	{
		$crud = new grocery_CRUD();
		// 		$crud->set_theme('twitter-bootstrap');
		$crud->set_table('shop');
		$crud->columns('id','shopname','username');
		$crud->display_as('shopname','店家名称');
		$crud->display_as('username','管理员用户名');
		$crud->display_as('password','密码');
		$crud->display_as('status','状态');
		$crud->set_subject('店家');
		$output = $crud->render();
		$this->load->view('admin.php',$output);
	}
	
		
	public function Item()
	{
		$crud = new grocery_CRUD();
		$crud->set_table('item');
		$crud->columns('id','itemname','price');
		$crud->edit_fields('itemname','price');
		$crud->set_subject('服务项目');
		$crud->display_as('itemname','项目名称');
		$crud->display_as('price','价格');
		
		$output = $crud->render();
		$this->load->view('admin.php',$output);
	}
	
	public function toUserUrl($primary_key , $row)
	{
		return site_url('admin/user?id='.$row->userid);
	}
	
	public function record()
	{
		$crud = new grocery_CRUD();
		//$crud->set_theme('twitter-bootstrap');
		$crud->set_table('repair_record');
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
		$crud->set_relation('workerid','worker','name');
		
		$output = $crud->render();
		$this->load->view('admin.php',$output);
	}
	
	public function remind()
	{
		$query = $this->db->query("SELECT *,DATEDIFF(baoxian_duedate,CURDATE()) as duedays FROM `user` where DATEDIFF(baoxian_duedate,CURDATE()) < 30");
		$baoxians = $query->result_array();
		$query = $this->db->query("SELECT *,DATEDIFF(nianjian_duedate,CURDATE()) as duedays FROM `user` where DATEDIFF(nianjian_duedate,CURDATE()) < 30");
		$nianjians = $query->result_array();
		$output = "<ul>";
		foreach($baoxians as $v)
		{
			$output .= "<li><a href=\"user/read/{$v['id']}\">{$v['chepai']}</a> 的保险将要过期，过期日期为{$v['baoxian_duedate']},还有{$v['duedays']}天过期</li>";  
		}foreach($nianjians as $v)
		{
			$output .= "<li><a href=\"user/read/{$v['id']}\">{$v['chepai']}</a> 的年检将要过期，过期日期为{$v['nianjian_duedate']},还有{$v['duedays']}天过期</li>";  
		}
		$output .= "</ul>";
		$data['output'] = $output;
		$this->parser->parse('remind',$data);
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