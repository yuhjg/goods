<?php
namespace app\index\controller;

use think\Controller;
use app\index\model\Product as ProductModel;
use app\index\model\ProductImage as ProductImageModel;
use think\facade\Env;

class Product extends Controller
{
    /**
     * 商品列表
     */
    public function index()
    {
        $status = input('status/d', 1);
        $list = ProductModel::where('status', $status)
            ->order('id desc')
            ->paginate(15, false, ['query' => request()->param()]);

        $this->assign('list', $list);
        $this->assign('status', $status);
        $this->assign('statusMap', ProductModel::$statusMap);
        return $this->fetch();
    }

    /**
     * 商品详情
     */
    public function detail($id)
    {
        $product = ProductModel::with('images')->find($id);
        if (!$product) {
            $this->error('商品不存在');
        }

        $this->assign('product', $product);
        $this->assign('statusMap', ProductModel::$statusMap);
        return $this->fetch();
    }

    /**
     * 发布商品页面
     */
    public function create()
    {
        return $this->fetch();
    }

    /**
     * 保存商品
     */
    public function save()
    {
        $data = [
            'title'          => input('post.title', '', 'trim'),
            'description'    => input('post.description', '', 'trim'),
            'selling_price'  => input('post.selling_price/f', 0),
            'original_price' => input('post.original_price/f', 0),
            'pickup_address' => input('post.pickup_address', '', 'trim'),
            'status'         => ProductModel::STATUS_ACTIVE,
            'create_time'    => time(),
            'update_time'    => time(),
        ];

        if (empty($data['title'])) {
            $this->error('请输入商品标题');
        }
        if ($data['selling_price'] <= 0) {
            $this->error('请输入有效的成交价');
        }
        if (empty($data['pickup_address'])) {
            $this->error('请输入自提地址');
        }

        $product = ProductModel::create($data);

        // 处理图片上传（最多3张）
        $files = request()->file('images');
        if ($files) {
            $uploadedCount = 0;
            foreach ($files as $file) {
                if ($uploadedCount >= 3) break;
                $info = $file->validate([
                    'size' => 5242880,
                    'ext'  => 'jpg,jpeg,png,gif,webp'
                ])->move(Env::get('root_path') . 'public' . DIRECTORY_SEPARATOR . 'uploads');
                if ($info) {
                    $saveName = str_replace('\\', '/', $info->getSaveName());
                    ProductImageModel::create([
                        'product_id'  => $product->id,
                        'image_url'   => '/uploads/' . $saveName,
                        'sort'        => $uploadedCount,
                        'create_time' => time(),
                    ]);
                    $uploadedCount++;
                }
            }
        }

        $this->success('发布成功', url('product/detail', ['id' => $product->id]));
    }

    /**
     * 编辑商品页面
     */
    public function edit($id)
    {
        $product = ProductModel::with('images')->find($id);
        if (!$product) {
            $this->error('商品不存在');
        }
        if ($product->status != ProductModel::STATUS_ACTIVE) {
            $this->error('只能编辑在售商品');
        }

        $this->assign('product', $product);
        return $this->fetch();
    }

    /**
     * 更新商品
     */
    public function update($id)
    {
        $product = ProductModel::find($id);
        if (!$product) {
            $this->error('商品不存在');
        }

        $data = [
            'title'          => input('post.title', '', 'trim'),
            'description'    => input('post.description', '', 'trim'),
            'selling_price'  => input('post.selling_price/f', 0),
            'original_price' => input('post.original_price/f', 0),
            'pickup_address' => input('post.pickup_address', '', 'trim'),
            'update_time'    => time(),
        ];

        if (empty($data['title'])) {
            $this->error('请输入商品标题');
        }

        $product->save($data);

        // 处理图片上传
        $files = request()->file('images');
        if ($files) {
            $existingCount = ProductImageModel::where('product_id', $id)->count();
            $uploadedCount = $existingCount;
            foreach ($files as $file) {
                if ($uploadedCount >= 3) break;
                $info = $file->validate([
                    'size' => 5242880,
                    'ext'  => 'jpg,jpeg,png,gif,webp'
                ])->move(Env::get('root_path') . 'public' . DIRECTORY_SEPARATOR . 'uploads');
                if ($info) {
                    $saveName = str_replace('\\', '/', $info->getSaveName());
                    ProductImageModel::create([
                        'product_id'  => $id,
                        'image_url'   => '/uploads/' . $saveName,
                        'sort'        => $uploadedCount,
                        'create_time' => time(),
                    ]);
                    $uploadedCount++;
                }
            }
        }

        // 删除指定图片
        $deleteImages = input('post.delete_images/a', []);
        if (!empty($deleteImages)) {
            foreach ($deleteImages as $imgId) {
                $img = ProductImageModel::find($imgId);
                if ($img && $img->product_id == $id) {
                    $filePath = Env::get('root_path') . 'public' . $img->image_url;
                    if (file_exists($filePath)) {
                        @unlink($filePath);
                    }
                    $img->delete();
                }
            }
        }

        $this->success('更新成功', url('product/detail', ['id' => $id]));
    }

    /**
     * 完成交易
     */
    public function complete($id)
    {
        $product = ProductModel::find($id);
        if (!$product) {
            $this->error('商品不存在');
        }
        if ($product->status != ProductModel::STATUS_ACTIVE) {
            $this->error('当前状态不可完成交易');
        }

        $product->save([
            'status'      => ProductModel::STATUS_COMPLETED,
            'update_time' => time(),
        ]);

        $this->success('交易已完成', url('product/detail', ['id' => $id]));
    }

    /**
     * 撤消交易
     */
    public function cancel($id)
    {
        $product = ProductModel::find($id);
        if (!$product) {
            $this->error('商品不存在');
        }
        if ($product->status != ProductModel::STATUS_ACTIVE) {
            $this->error('当前状态不可撤消');
        }

        $product->save([
            'status'      => ProductModel::STATUS_CANCELLED,
            'update_time' => time(),
        ]);

        $this->success('交易已撤消', url('product/detail', ['id' => $id]));
    }
}
