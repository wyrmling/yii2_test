<?php

namespace app\models;

use yii\base\Model;

/*
 * This is the model class for products display filters
 * @propertys string $... generated by DynamicModel
 */

class Filters extends Model
{

    /**
     * @return array the brands for filter form.
     */
    public static function getBrandsForFilterForm($filterinfo)
    {
        $brands = [];
        foreach ($filterinfo as $fi) {
            $brands['brand' . $fi['brand_id']] = $fi['brand_name'];
        }
        asort($brands);
        return $brands;
    }

    /**
     * @return array the attributes for DynamicModel.
     */
    public static function getBrandsForDynamicModel($filterinfo)
    {
        $brands = [];
        foreach ($filterinfo as $fi) {
            $brands[$fi['brand_id']] = 'brand' . $fi['brand_id'];
        }
        return $brands;
    }

    /**
     * @return array the attributes ID for FilterQuery.
     */
    public static function getBrandsForFilterQuery($filterinfo)
    {
        foreach ($filterinfo as $key => $value) {
            if ($value == 1) {
                $brands[] = substr($key, 5);
            }
        }
        return (isset($brands)) ? $brands : null;
    }

}