<?php
    abstract class Controller {

        abstract static protected function init($page_title);

        protected static function View($view = '') {
            if($view == '') return 'Cannot Get Empty View';
            return require_once "Views/$view.php";a
        }

        protected static function get_model($model = '') {
            if($model == '') return 'Cannot Get Empty Model';
            return require_once "Models/$model.php";
        }

        protected static function get_all_views($view_array = []) {
            if(empty($view_array)) return 'Cannot Get Empty File';
            foreach ($view_array as $view) {
                return "$view.php";
            }
        }
        protected static function get_view($view = '') {
            if(empty($view_array)) return 'Cannot Get Empty File';
            return "$view.php";
        }

        protected static function get_base_head($pageTitle) {
            return require_once '/head.php';
        }
    }
