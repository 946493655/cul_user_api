<?php
namespace App\Http\Controllers\Member;

use App\Models\UserVoiceModel;

class UserVoiceController extends BaseController
{
    /**
     * 用户心声
     */

    /**
     * 列表
     */
    public function index()
    {
        $limit = isset($_POST['limit'])?$_POST['limit']:$this->limit;     //每页显示记录数
        $page = isset($_POST['page'])?$_POST['page']:1;         //页码，默认第一页
        $start = $limit * ($page - 1);      //记录起始id

        $voiceModels = UserVoiceModel::orderBy('id','desc')
            ->skip($start)
            ->take($limit)
            ->get();
        if (!count($voiceModels)) {
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
        foreach ($voiceModels as $k=>$voiceModel) {
            $datas[$k] = $this->objToArr($voiceModel);
            $datas[$k]['username'] = $voiceModel->getUName();
            $datas[$k]['isShowName'] = $voiceModel->getIsShow();
            $datas[$k]['createTime'] = $voiceModel->createTime();
            $datas[$k]['updateTime'] = $voiceModel->updateTime();
        }
        $rstArr = [
            'error' => [
                'code'  =>  0,
                'msg'   =>  '获取数据成功！',
            ],
            'data'  =>  $datas,
        ];
        echo json_encode($rstArr);exit;
    }

    /**
     * 添加心声
     */
    public function store()
    {
        $name = $_POST['name'];
        $uid = $_POST['uid'];
        $work = $_POST['work'];
        $intro = $_POST['intro'];
        if (!$name || !$uid || !$work || !$intro) {
            $rstArr = [
                'error' =>  [
                    'code'  =>  -1,
                    'msg'   =>  '参数有误！',
                ],
            ];
            echo json_encode($rstArr);exit;
        }
        $data = [
            'name'  =>  $name,
            'uid'   =>  $uid,
            'work'  =>  $work,
            'intro' =>  $intro,
            'created_at'    =>  time(),
        ];
        UserVoiceModel::create($data);
        $rstArr = [
            'error' =>  [
                'code'  =>  0,
                'msg'   =>  '数据添加成功！',
            ],
        ];
        echo json_encode($rstArr);exit;
    }

    public function show()
    {
        $id = $_POST['id'];
        if (!$id) {
            $rstArr = [
                'error' =>  [
                    'code'  =>  -1,
                    'msg'   =>  '参数有误！',
                ],
            ];
            echo json_encode($rstArr);exit;
        }
        $userVoice = UserVoiceModel::find($id);
        if (!$userVoice) {
            $rstArr = [
                'error' =>  [
                    'code'  =>  -2,
                    'msg'   =>  '没有数据！',
                ],
            ];
            echo json_encode($rstArr);exit;
        }
        $datas = $this->objToArr($userVoice);
        $datas['username'] = $userVoice->getUName();
        $datas['isShowName'] = $userVoice->getIsShow();
        $datas['createTime'] = $userVoice->createTime();
        $datas['updateTime'] = $userVoice->updateTime();
        $rstArr = [
            'error' =>  [
                'code'  =>  0,
                'msg'   =>  '获取成功！',
            ],
            'data'  =>  $datas,
        ];
        echo json_encode($rstArr);exit;
    }

    public function update()
    {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $uid = $_POST['uid'];
        $work = $_POST['work'];
        $intro = $_POST['intro'];
        if (!$id || !$name || !$uid || !$work || !$intro) {
            $rstArr = [
                'error' =>  [
                    'code'  =>  -1,
                    'msg'   =>  '参数有误！',
                ],
            ];
            echo json_encode($rstArr);exit;
        }
        $userVoice = UserVoiceModel::find($id);
        if (!$userVoice) {
            $rstArr = [
                'error' =>  [
                    'code'  =>  -2,
                    'msg'   =>  '没有数据！',
                ],
            ];
            echo json_encode($rstArr);exit;
        }
        $data = [
            'name'  =>  $name,
            'uid'   =>  $uid,
            'work'  =>  $work,
            'intro' =>  $intro,
            'updated_at'    =>  time(),
        ];
        UserVoiceModel::where('id',$id)->update($data);
        $rstArr = [
            'error' =>  [
                'code'  =>  0,
                'msg'   =>  '更新成功！',
            ],
        ];
        echo json_encode($rstArr);exit;
    }
}