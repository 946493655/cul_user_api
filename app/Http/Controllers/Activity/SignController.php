<?php
namespace App\Http\Controllers\Activity;

use App\Models\Wallet\SignModel;

class SignController extends BaseController
{
    /**
     * 用户签到
     */

    public function __construct()
    {
        $this->selfModel = new SignModel();
    }

    /**
     * 签到列表
     */
    public function index()
    {
        $uid = isset($_POST['uid'])?$_POST['uid']:0;
        $limit = (isset($_POST['limit'])&&$_POST['limit'])?$_POST['limit']:$this->limit;     //每页显示记录数
        $page = isset($_POST['page'])?$_POST['page']:1;         //页码，默认第一页
        $start = $limit * ($page - 1);      //记录起始id

        if ($uid) {
            $models = SignModel::where('uid',$uid)
                ->orderBy('id','desc')
                ->skip($start)
                ->take($limit)
                ->get();
            $total = SignModel::where('uid',$uid)
                ->count();
        } else {
            $models = SignModel::orderBy('id','desc')
                ->skip($start)
                ->take($limit)
                ->get();
            $total = SignModel::orderBy('id','desc')
                ->count();
        }
        if (!count($models)) {
            $rstArr = [
                'error' => [
                    'code'  =>  -2,
                    'msg'   =>  '未获取到数据！',
                ],
            ];
            echo json_encode($rstArr);exit;
        }
        //整理数据
        $datas = array();
        foreach ($models as $k=>$model) {
            $datas[$k] = $this->getArrByModel($model);
        }
        $rstArr = [
            'error' => [
                'code'  =>  0,
                'msg'   =>  '成功获取数据！',
            ],
            'data'  =>  $datas,
            'pagelist'  =>  [
                'total' =>  $total,
            ],
        ];
        echo json_encode($rstArr);exit;
    }

    /**
     * 给人家 uid 获取所有签到记录
     */
    public function all()
    {
        $uid = (isset($_POST['uid'])&&$_POST['uid'])?$_POST['uid']:0;
        if ($uid) {
            $models = SignModel::where('uid',$uid)->get();
        } else {
            $models = SignModel::all();
        }
        if (!count($models)) {
            $rstArr = [
                'error' =>  [
                    'code'  =>  -2,
                    'msg'   =>  '没有数据！',
                ],
            ];
            echo json_encode($rstArr);exit;
        }
        //整理数据
        $datas = array();
        foreach ($models as $k=>$model) {
            $datas[$k] = $this->getArrByModel($model);
        }
        $rstArr = [
            'error' => [
                'code'  =>  0,
                'msg'   =>  '成功获取数据！',
            ],
            'data'  =>  $datas,
            'model' =>  [],
        ];
        echo json_encode($rstArr);exit;
    }

    /**
     * 根据 uid、from、to 得到记录
     */
    public function getSignsByTime()
    {
        $uid = $_POST['uid'];
        $from = $_POST['from'];
        $to = $_POST['to'];
        if (!$uid || !$from || !$to) {
            $rstArr = [
                'error' =>  [
                    'code'  =>  -1,
                    'msg'   =>  '参数有误！',
                ],
            ];
            echo json_encode($rstArr);exit;
        }
        $models = SignModel::where('uid',$uid)
            ->where('created_at','>',$from)
            ->where('created_at','<',$to)
            ->orderBy('id','desc')
            ->get();
        $total = SignModel::where('uid',$uid)
            ->where('created_at','>',$from)
            ->where('created_at','<',$to)
            ->count();
        if (!count($models)) {
            $rstArr = [
                'error' =>  [
                    'code'  =>  -2,
                    'msg'   =>  '没有数据！',
                ],
            ];
            echo json_encode($rstArr);exit;
        }
        //整理数据
        $datas = array();
        foreach ($models as $k=>$model) {
            $datas[$k] = $this->getArrByModel($model);
        }
        $rstArr = [
            'error' => [
                'code'  =>  0,
                'msg'   =>  '成功获取数据！',
            ],
            'data'  =>  $datas,
            'pagelist'  =>  [
                'total' =>  $total,
            ],
        ];
        echo json_encode($rstArr);exit;
    }

    /**
     * 根据 uid、from、to 得到记录列表
     */
    public function getSignListByTime()
    {
        $uid = $_POST['uid'];
        $from = $_POST['from'];
        $to = $_POST['to'];
        $limit = isset($_POST['limit'])?$_POST['limit']:$this->limit;     //每页显示记录数
        $page = isset($_POST['page'])?$_POST['page']:1;         //页码，默认第一页
        $start = $limit * ($page - 1);      //记录起始id

        if (!$uid || (!$from&&$to) || ($from&&!$to)) {
            $rstArr = [
                'error' =>  [
                    'code'  =>  -1,
                    'msg'   =>  '参数有误！',
                ],
            ];
            echo json_encode($rstArr);exit;
        }
        if ($from && $to) {
            $models = SignModel::where('uid',$uid)
                ->where('created_at','>',$from)
                ->where('created_at','<',$to)
                ->orderBy('id','desc')
                ->skip($start)
                ->take($limit)
                ->get();
            $total = SignModel::where('uid',$uid)
                ->where('created_at','>',$from)
                ->where('created_at','<',$to)
                ->count();
        } elseif (!$from && !$to) {
            $models = SignModel::where('uid',$uid)
                ->orderBy('id','desc')
                ->skip($start)
                ->take($limit)
                ->get();
            $total = SignModel::where('uid',$uid)
                ->count();
        }
        if (!count($models)) {
            $rstArr = [
                'error' =>  [
                    'code'  =>  -2,
                    'msg'   =>  '没有数据！',
                ],
            ];
            echo json_encode($rstArr);exit;
        }
        //整理数据
        $datas = array();
        foreach ($models as $k=>$model) {
            $datas[$k] = $this->getArrByModel($model);
        }
        $rstArr = [
            'error' => [
                'code'  =>  0,
                'msg'   =>  '成功获取数据！',
            ],
            'data'  =>  $datas,
            'pagelist'  =>  [
                'total' =>  $total,
            ],
        ];
        echo json_encode($rstArr);exit;
    }

    /**
     * 申请签到
     */
    public function store()
    {
        $uid = $_POST['uid'];
        if (!$uid) {
            $rstArr = [
                'error' =>  [
                    'code'  =>  -1,
                    'msg'   =>  '参数有误！',
                ],
            ];
            echo json_encode($rstArr);exit;
        }
        $zaoshang = strtotime(date('Ymd',time()).'000000');
        $wanshang = strtotime(date('Ymd',time()).'595959');
        $model = SignModel::where('uid',$uid)
            ->where('created_at','>',$zaoshang)
            ->where('created_at','<',$wanshang)
            ->first();
        if ($model) {
            $rstArr = [
                'error' =>  [
                    'code'  =>  -2,
                    'msg'   =>  '今天已签到！',
                ],
            ];
            echo json_encode($rstArr);exit;
        }
        $data = [
            'uid'   =>  $uid,
            'reward'    =>  rand(1,10),
            'created_at'    =>  time(),
        ];
        SignModel::create($data);
        $rstArr = [
            'error' =>  [
                'code'  =>  0,
                'msg'   =>  '签到成功！',
            ],
        ];
        echo json_encode($rstArr);exit;
    }

    /**
     * 将 model 转化为 array
     */
    public function getArrByModel($model)
    {
        $data = $this->objToArr($model);
        $data['signStatus'] = $model->getSignStatus($model,$model->created_at);
        $data['username'] = $model->getUName();
        $data['reward'] = $model->reward();
        $data['createTime'] = $model->createTime();
        $data['updateTime'] = $model->updateTime();
        return $data;
    }
}