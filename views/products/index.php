<?php

use yii\helpers\Html;

$this->params['breadcrumbs'][] = ['label' => 'Каталог товаров', 'url' => ['/catalog']];
foreach ($fullPath as $path) {
    $this->params['breadcrumbs'][] = ['label' => $path->name, 'url' => ["/catalog/category/$path->category_id"]];
}

$this->registerJs("
    function addproduct(productid) {      
        $.ajax({
            type: 'POST',
            url: 'products/addproduct/',
            data: {id: productid},
             success: function(data) {
                if (JSON.parse(data) !== 'nok') {
                    $('#div_1').text(JSON.parse(data));
                }
            }
        });
    }", \yii\web\View::POS_END);
?>

<div id='div_1'>
    <b> здесь будет количество товара в корзине</b>
</div>
<br>
<div> Название товара:
    <b> <?= Html::encode($product['title']) ?> </b>
</div>
<div> Бренд:
    <?= Html::encode($product['brand_name']) ?>
</div>
<div> SKU:
    <?= Html::encode($product['sku']) ?>
</div>
<div> Артикул:
    <?= Html::encode($product['article']) ?>
</div>
<div> Описание:
    <?= Html::encode($product['description']) ?>
</div>
<div>
    <?= Html::encode("цена: {$product['price']} (специальная цена: {$product['special_price']})") ?>
</div>
<br>
<div>
    <?= Html::a('[добавить в корзину]', ['/cart/add', 'id' => $product['product_id']]) ?>
</div>
<br>
<div>
    <input type="button" value="добавить в корзину" id="addproduct" onclick="addproduct(<?= $product['product_id'] ?>)">
</div>
<br>
<div>
    Атрибуты товара:
</div>

<br><br>
<?php foreach ($attributes as $attribute): ?>
<div>
<?= $attribute['attribute_id'] ?> - <?= $attribute['attribute_name'] ?> -

<?php
    if ($attribute['value']) {
        echo $attribute['value'];
    }
?>

(<?= $attribute['unit'] ?>)

</div>
<?php endforeach; ?>