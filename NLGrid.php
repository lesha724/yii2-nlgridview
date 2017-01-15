<?php
/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 11.01.2017
 * Time: 21:06
 */

namespace backend\components\Grid;
use yii\grid\GridView;
use yii\grid\GridViewAsset;
use yii\grid\SerialColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class NLGrid extends GridView
{
    public $pjaxId = null;

    public $layout = "{summary}\n{pager}\n{items}\n{pager}";

    public $tableOptions = ['class' => 'table table-striped table-hovered'];

    const SPINNER_CSS = <<<CSS
    .grid-loading {
        opacity: 0.5;
        background: #ffffff url('/images/loading.gif') center center no-repeat !important;
    }

    .grid-loading tr, .grid-loading td {
        background: transparent !important;
    }

    .grid-loading td {
        border-color: #efefef !important;
    }
CSS;


    public function run()
    {
        $view = $this->getView();
        NLGridAsset::register($view);
        $this->beginPjax();
        parent::run();
        $this->endPjax();
        $this->writeResponsiveCss();
    }

    protected function endPjax()
    {
    }

    protected function beginPjax()
    {
        if (!$this->pjaxId) {
            return;
        }
        $id = $this->options['id'];
        $view = $this->getView();

        $loadingCss = 'grid-loading';
        $grid = 'jQuery("#' . $id . '")';

        $js = "jQuery('#$this->pjaxId').on('pjax:send', function(){{$grid}.addClass('{$loadingCss}')})";

        $postPjaxJs = "{$grid}.removeClass('{$loadingCss}');";
        $event = 'pjax:complete.' . hash('crc32', $postPjaxJs);
        $js .= ".off('{$event}').on('{$event}', function(){{$postPjaxJs}})";

        $view->registerJs("{$js};");
        $view->registerCss(self::SPINNER_CSS);
    }

    public function init()
    {
        parent::init();
    }

    /**
     *### .writeResponsiveCss()
     *
     * Writes responsiveCSS
     */
    protected function writeResponsiveCss()
    {
        $id = $this->options['id'];

        $cnt = 1;
        $labels = '';
        foreach ($this->columns as $column) {
            /** @var \yii\grid\DataColumn $column */
            if($column instanceof SerialColumn)
                $name = $column->header;
            else
                $name = strip_tags($column->renderHeaderCell());
            $labels .= "#$id td:nth-of-type($cnt):before { content: '{$name}'; }\n";
            $cnt++;
        }

        $css = <<<CSS
@media
	only screen and (max-width: 760px),
	(min-device-width: 768px) and (max-device-width: 1024px)  {

		/* Force table to not be like tables anymore */
		#{$id} table,#{$id} thead,#{$id} tbody,#{$id} th,#{$id} td,#{$id} tr {
			display: block;
		}

		/* Hide table headers (but not display: none;, for accessibility) */
		#{$id} thead tr {
			position: absolute;
			top: -9999px;
			left: -9999px;
		}

		#{$id} tr { border: 1px solid #ccc; }

		#{$id} td {
			/* Behave  like a "row" */
			border: none;
			border-bottom: 1px solid #eee;
			position: relative;
			padding-left: 50%;
		}

		#{$id} td:before {
			/* Now like a table header */
			position: absolute;
			/* Top/left values mimic padding */
			top: 6px;
			left: 6px;
			width: 45%;
			padding-right: 10px;
			white-space: nowrap;
		}
		.grid-view .button-column {
			text-align: left;
			width:auto;
		}
		/*
		Label the data
		*/
		{$labels}
	}
CSS;
        $view = $this->getView();
        $view->registerCss($css);
    }
}