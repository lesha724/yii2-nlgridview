<?php
/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 05.02.2017
 * Time: 23:12
 */

namespace lesha724\grid;


use conquer\select2\Select2Widget;
use yii\base\Model;
use yii\grid\DataColumn;
use yii\helpers\Html;

class NLSelect2Column extends DataColumn
{
    public $language = null;

    public $bootstrap = true;

    protected function renderFilterCellContent()
    {
        if (is_string($this->filter)) {
            return $this->filter;
        }

        $model = $this->grid->filterModel;

        if ($this->filter !== false && $model instanceof Model && $this->attribute !== null && $model->isAttributeActive($this->attribute)) {
            if ($model->hasErrors($this->attribute)) {
                Html::addCssClass($this->filterOptions, 'has-error');
                $error = ' ' . Html::error($model, $this->attribute, $this->grid->filterErrorOptions);
            } else {
                $error = '';
            }
            if (is_array($this->filter)) {
                //$options = $this->filterInputOptions;
                $options = array_merge(['prompt' => '&nbsp;'], $this->filterInputOptions);
                //return Html::activeDropDownList($model, $this->attribute, $this->filter, $options) . $error;
                return Select2Widget::widget([
                    'model'=>$model,
                    'attribute'=>$this->attribute,
                    'language'=>$this->language,
                    'bootstrap'=>$this->bootstrap,
                    'items'=>$this->filter,
                    'options'=>$options,
                ]). $error;
            } else {
                return Html::activeTextInput($model, $this->attribute, $this->filterInputOptions) . $error;
            }
        } else {
            return parent::renderFilterCellContent();
        }
    }
}