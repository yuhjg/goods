<?php
namespace app\index\model;

use think\Model;

class Product extends Model
{
    protected $name = 'product';

    // 状态常量
    const STATUS_ACTIVE    = 1; // 在售
    const STATUS_COMPLETED = 2; // 已完成
    const STATUS_CANCELLED = 3; // 已撤消

    public static $statusMap = [
        self::STATUS_ACTIVE    => '在售',
        self::STATUS_COMPLETED => '已完成',
        self::STATUS_CANCELLED => '已撤消',
    ];

    /**
     * 关联图片（一对多，最多3张）
     */
    public function images()
    {
        return $this->hasMany('ProductImage', 'product_id', 'id')->order('sort asc');
    }

    /**
     * 获取器 - 格式化价格
     */
    public function getSellingPriceAttr($value)
    {
        return number_format($value, 2, '.', '');
    }

    public function getOriginalPriceAttr($value)
    {
        return number_format($value, 2, '.', '');
    }

    /**
     * 获取器 - 格式化时间
     */
    public function getCreateTimeAttr($value)
    {
        return $value > 0 ? date('Y-m-d H:i', $value) : '';
    }

    /**
     * 获取状态文本
     */
    public function getStatusTextAttr($value, $data)
    {
        return isset(self::$statusMap[$data['status']]) ? self::$statusMap[$data['status']] : '未知';
    }
}
