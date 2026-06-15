<?php
namespace app\index\model;

use think\Model;

class ProductImage extends Model
{
    protected $name = 'product_image';

    /**
     * 关联商品（反向一对一）
     */
    public function product()
    {
        return $this->belongsTo('Product', 'product_id', 'id');
    }
}
