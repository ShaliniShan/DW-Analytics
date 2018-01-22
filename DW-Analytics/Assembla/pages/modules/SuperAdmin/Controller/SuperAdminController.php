<?php
    require $_SERVER['DOCUMENT_ROOT'].'/pages/src/Controller.php';

    class SuperAdminController extends Controller {

        public static function init($page_title) {
            parent::get_model("SuperAdminModel");
            SuperAdminModel::init();
            parent::get_base_head($page_title);
            parent::View("SuperAdminView");
        }

    }
