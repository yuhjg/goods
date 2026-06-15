<?php
namespace app\index\controller;

use think\Controller;
use app\index\model\Wanted as WantedModel;
use think\facade\Env;

class Wanted extends Controller
{
    /**
     * 求购列表
     */
    public function index()
    {
        $status = input('status/d', 1);
        $list = WantedModel::where('status', $status)
            ->order('id desc')
            ->paginate(15, false, ['query' => request()->param()]);

        $this->assign('list', $list);
        $this->assign('status', $status);
        $this->assign('statusMap', WantedModel::$statusMap);
        return $this->fetch();
    }

    /**
     * 求购详情
     */
    public function detail($id)
    {
        $wanted = WantedModel::find($id);
        if (!$wanted) {
            $this->error('求购信息不存在');
        }

        $this->assign('wanted', $wanted);
        $this->assign('statusMap', WantedModel::$statusMap);
        return $this->fetch();
    }

    /**
     * 发布求购页面
     */
    public function create()
    {
        return $this->fetch();
    }

    /**
     * 保存求购
     */
    public function save()
    {
        $data = [
            'title'          => input('post.title', '', 'trim'),
            'description'    => input('post.description', '', 'trim'),
            'wanted_address' => input('post.wanted_address', '', 'trim'),
            'image_url'      => '',
            'status'         => WantedModel::STATUS_ACTIVE,
            'create_time'    => time(),
            'update_time'    => time(),
        ];

        if (empty($data['title'])) {
            $this->error('请输入求购标题');
        }
        if (empty($data['wanted_address'])) {
            $this->error('请输入求购地址');
        }

        // 处理图片上传（1张）
        $file = request()->file('image');
        if ($file) {
            $info = $file->validate([
                'size' => 5242880,
                'ext'  => 'jpg,jpeg,png,gif,webp'
            ])->move(Env::get('root_path') . 'public' . DIRECTORY_SEPARATOR . 'uploads');
            if ($info) {
                $data['image_url'] = '/uploads/' . str_replace('\\', '/', $info->getSaveName());
            }
        }

        $wanted = WantedModel::create($data);
        $this->success('发布成功', url('wanted/detail', ['id' => $wanted->id]));
    }

    /**
     * 编辑求购页面
     */
    public function edit($id)
    {
        $wanted = WantedModel::find($id);
        if (!$wanted) {
            $this->error('求购信息不存在');
        }
        if ($wanted->status != WantedModel::STATUS_ACTIVE) {
            $this->error('只能编辑进行中的求购');
        }

        $this->assign('wanted', $wanted);
        return $this->fetch();
    }

    /**
     * 更新求购
     */
    public function update($id)
    {
        $wanted = WantedModel::find($id);
        if (!$wanted) {
            $this->error('求购信息不存在');
        }

        $data = [
            'title'          => input('post.title', '', 'trim'),
            'description'    => input('post.description', '', 'trim'),
            'wanted_address' => input('post.wanted_address', '', 'trim'),
            'update_time'    => time(),
        ];

        if (empty($data['title'])) {
            $this->error('请输入求购标题');
        }
        if (empty($data['wanted_address'])) {
            $this->error('请输入求购地址');
        }

        // 处理图片上传
        $file = request()->file('image');
        if ($file) {
            $info = $file->validate([
                'size' => 5242880,
                'ext'  => 'jpg,jpeg,png,gif,webp'
            ])->move(Env::get('root_path') . 'public' . DIRECTORY_SEPARATOR . 'uploads');
            if ($info) {
                // 删除旧图片
                if (!empty($wanted->image_url)) {
                    $oldPath = Env::get('root_path') . 'public' . $wanted->image_url;
                    if (file_exists($oldPath)) @unlink($oldPath);
                }
                $data['image_url'] = '/uploads/' . str_replace('\\', '/', $info->getSaveName());
            }
        }

        $wanted->save($data);
        $this->success('更新成功', url('wanted/detail', ['id' => $id]));
    }

    /**
     * 完成求购
     */
    public function complete($id)
    {
        $wanted = WantedModel::find($id);
        if (!$wanted) {
            $this->error('求购信息不存在');
        }
        if ($wanted->status != WantedModel::STATUS_ACTIVE) {
            $this->error('当前状态不可完成');
        }

        $wanted->save([
            'status'      => WantedModel::STATUS_COMPLETED,
            'update_time' => time(),
        ]);

        $this->success('求购已完成', url('wanted/detail', ['id' => $id]));
    }

    /**
     * 撤消求购
     */
    public function cancel($id)
    {
        $wanted = WantedModel::find($id);
        if (!$wanted) {
            $this->error('求购信息不存在');
        }
        if ($wanted->status != WantedModel::STATUS_ACTIVE) {
            $this->error('当前状态不可撤消');
        }

        $wanted->save([
            'status'      => WantedModel::STATUS_CANCELLED,
            'update_time' => time(),
        ]);

        $this->success('求购已撤消', url('wanted/detail', ['id' => $id]));
    }
}
