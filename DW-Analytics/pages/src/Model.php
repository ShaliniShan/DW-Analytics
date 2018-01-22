<?php
    require $_SERVER['DOCUMENT_ROOT'].'/pages/modules/parseInit.php';

    use Parse\ParseObject;
    use Parse\ParseQuery;
    use Parse\ParseUser;
    use Parse\ParseException;

    abstract class Model {

        abstract protected static function init();
        
        protected static function query() {
            return ParseQuery::query();
        }
    }
