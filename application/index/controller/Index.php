<?php
namespace app\index\controller;

use think\Controller;
use app\index\model\Product as ProductModel;
use app\index\model\Wanted as WantedModel;

class Index extends Controller
{
    public function index()
    {
        // 最新在售商品（前10条）
        $products = ProductModel::where('status', ProductModel::STATUS_ACTIVE)
            ->order('id desc')
            ->limit(10)
            ->select();

        // 最新求购（前10条）
        $wanteds = WantedModel::where('status', WantedModel::STATUS_ACTIVE)
            ->order('id desc')
            ->limit(10)
            ->select();

        $this->assign('products', $products);
        $this->assign('wanteds', $wanteds);
        return $this->fetch();
    }
}
