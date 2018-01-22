<!-- CUSTOM JAVASCRIPT -->
<script src="/assets/js/document_handler.js"></script>
<script src="/assets/js/Document_Handlers/ajaxHandler.js"></script>
<?php if(isset($_GET['event']) && ($_GET['event'] == 'new-event') || isset($_GET['action'])) {?>
<script src="/assets/js/Document_Handlers/error_handling.js" type="text/javascript"></script>
<?php } ?>
<?php if($acl->getUserPerm() == 1 && !isset($_GET['page']) || $_GET['action'] == 'analytics') {?>
<script src="/assets/js/APIs/barGraphAPIOrganizer.js"></script>
<script src="/assets/js/APIs/smallBarGraph.js"></script>
<script src="/assets/js/Document_Handlers/download_covert.js"></script>
<?php } if(isset($_GET['specific']) && $parseAPI->isBeacon($_GET['specific'])) { ?>

<?php } ?>
<script src="/assets/js/Document_Handlers/DOM_Handler_Init.js"></script>
<?php if(isset($_GET['page']) == 'Admin' && isset($_GET['event'])) { ?>
<script src="/assets/js/jquery.tablesorter.js"></script>
<script src="/assets/js/Document_Handlers/populate_beacon_selection.js"></script>

<?php } ?>