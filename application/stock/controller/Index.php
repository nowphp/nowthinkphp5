<?php
namespace app\stock\controller;

use think\Controller;
use think\View;
use function GuzzleHttp\json_encode;
class Index extends Controller
{
    public function index()
    {
        //dump(model('dm')->find()->toarray());
        $view = new View();
        return $view->fetch();
    }
    
    public function ajaxGetAppointmentList(){
        return json_encode($value);
    }
    
    //ajax获取预约列表
    public function ajaxGetAppointmentListAction1(){
        $data = $this->request->get();
        $start = isset($data["start"]) && intval($data["start"])>0?intval($data["start"]):0;
        $limit = isset($data["length"]) && intval($data["length"])?intval($data["length"]):10;
        $ap_model = new DxfTtySoilAppointment();
        $total = $ap_model->count();
        $mobile = isset($data["search"]["value"])?trim($data["search"]["value"]):'';
        $conditionstr = $mobile?" mobile like '$mobile%' ":'';
        $conditionstr .= $mobile?" or username like '$mobile%' ":'';
        session_start();
        $_SESSION['dxf_conditionstr'] = $conditionstr?:'1=1';
        /*$conditionstr .= $mobile?" or addr like '%$mobile%' ":'';
         $conditionstr .= $mobile?" or provincename like '%$mobile%' ":'';
         $conditionstr .= $mobile?" or cityname like '%$mobile%' ":'';
         $conditionstr .= $mobile?" or countryname like '%$mobile%' ":'';*/
        $cont = $ap_model->count([
            "conditions" => $conditionstr,
        ]
            );
        $res_data = $ap_model->find([
            "offset" => $start,
            "conditions" => $conditionstr,
            "columns" => "id,mobile,username,concat(provincename,cityname,countryname,addr) addr,ctime,concat(cropname,'/',area) area",
            "order"  => "id desc",
            "limit" => $limit,
        ])->toArray();
        $columns = array(
            array( 'db' => 'id','dt' => 0 ),
            array( 'db' => 'mobile','dt' => 1 ),
            array( 'db' => 'username','dt' => 2 ),
            array( 'db' => 'addr','dt' => 3 ),
            array( 'db' => 'ctime','dt' => 4),
            array( 'db' => 'area','dt' => 5)
        );
        $ap_list['data'] = $this->data_output($columns, $res_data);
        $ap_list['data'] = array_values($ap_list['data']);
        $ap_list['recordsTotal'] = $total;
        $ap_list['recordsFiltered'] = $cont;
        $ap_list['draw'] = isset($data['draw'])?$data['draw']:1;
        $this->rspJson($ap_list);
    }
    
    //导出csv
    function export_csv($filename,$data) {
        header("Content-type:text/csv");
        header("Content-Disposition:attachment;filename=".$filename);
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        echo $data;
    }
    
    //获取导出数据
    public function outputAction(){
        session_start();
        if(isset($_SESSION['dxf_conditionstr'])){
            $conditionstr =  $_SESSION['dxf_conditionstr'] ;
            $ap_model = new DxfTtySoilAppointment();
            $res_data = $ap_model->find([
                "conditions" => $conditionstr,
                "columns" => "id,mobile,username,concat(provincename,cityname,countryname,addr) addr,ctime,concat(cropname,'/',area) area",
                "order"  => "id desc",
            ])->toArray();
            $str = iconv('utf-8','gb2312',"编号,手机,姓名,详细地址,提交时间,作物/面积\n");
            foreach ($res_data as $v){
                $username = iconv('utf-8','gb2312',$v['username']);
                $addr = iconv('utf-8','gb2312',$v['addr']);
                $area = iconv('utf-8','gb2312',$v['area']);
                $str .= $v['id'].",".$v['mobile'].",".$username.",".$addr.",".$v['ctime'].",".$area."\n"; //用引文逗号分开
            }
            $filename = date('Ymd').'.csv'; //设置文件名
            $this->export_csv($filename,$str); //导出
        }else{
            echo '非法请求';exit;
        }
    }
    
    static function data_output ( $columns, $data )
    {
        $out = array();
    
        for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
            $row = array();
    
            for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
                $column = $columns[$j];
    
                // Is there a formatter?
                if ( isset( $column['formatter'] ) ) {
                    $row[ $column['dt'] ] = $column['formatter']( $data[$i][ $column['db'] ], $data[$i] );
                }
                else {
                    $row[ $column['dt'] ] = $data[$i][ $columns[$j]['db'] ];
                }
            }
    
            $out[] = $row;
        }
    
        return $out;
    }
     
    /**
     * @brief rspJson
     * @param $code
     * @param $data
     * @return
     */
    public function rspJson( $data = [])
    {
        $this->response->setContentType('application/json', 'UTF-8');
        $this->response->setJsonContent([
            'draw' => $data['draw'],
            'recordsTotal' => $data['recordsTotal'],
            'recordsFiltered' => $data['recordsFiltered'],
            'data' => $data['data']
        ]);
        $this->response->send();
        exit();
    }
}
