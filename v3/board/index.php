<?php

$app_data = Kanban_App::instance()->get_app_data();

?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="noindex,nofollow" />
	<title>Boards - <?php echo $app_data->plugin_data['Name'] ?></title>
	<link rel="icon" href="<?php echo Kanban::instance()->settings()->uri ?>img/kanbanwp-favicon.svg?<?php echo $app_data->plugin_data['Version'] ?>">
	<link rel="stylesheet" href="<?php echo Kanban::instance()->settings()->uri ?>css/elegant-icons.min.css?<?php echo $app_data->plugin_data['Version'] ?>">
	<link rel="stylesheet" href="<?php echo Kanban::instance()->settings()->uri ?>css/bootstrap.min.css?<?php echo $app_data->plugin_data['Version'] ?>">
	<link rel="stylesheet" href="<?php echo Kanban_Router::instance()->get_uri('board') ?>/css/app.css?<?php echo $app_data->plugin_data['Version'] ?>">

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>

<div class="v3-warning modal" tabindex="-1" role="dialog" style="display: block;
    background-color: rgba(0, 0, 0, .5);
">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Warning!</h5>
			</div>
			<div class="modal-body">
				<p>
					Thank you for trying out this beta version of the Kanban for WordPress plugin.
					We've been hard at work on a new version, so it's time for this version to go away.
				</p>
				<p>
					If you have data here, please move your data back to the live version as soon as possible.
					This beta version will be removed March 1, 2020.
					If you have any questions, please contact support at https://KanbanWP.com/support.
				</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" onclick="hide_warnings()">Close</button>
			</div>
		</div>
	</div>

</div>


<div id="wrapper-app">

	<header id="header" class="navbar-fixed-top"></header>

	<div id="wrapper-board">
		<div class="container" id="board-placeholder">
			<i class="ei ei-loading"></i>
		</div>
	</div>
</div>

<footer id="footer" class="navbar"></footer>

<div class="modal" id="modal"></div>

<div id="app-alert"></div>

<?php
do_action(
	'kanban_board_include_templates',
	Kanban_Template::instance()->include_from_path(KANBAN_APP_DIR . '/templates/')
);
?>


<script>
	var kanban = {};

	kanban.templates = {};
	kanban.emojis = [];
	kanban.colors = [];
	kanban.app = null;
	kanban.users = {};
	kanban.usergroups = {};
	kanban.boards = {};
	kanban.lanes = {};
	kanban.cards = {};
	kanban.fields = {};
	kanban.fieldvalues = {};
	kanban.comments = {};
	kanban.ajax = {
		nonce: function () {
			return '<?php echo wp_create_nonce( 'kanban' ) ?>';
		},
		url: function () {
			return '<?php echo Kanban_Router::instance()->get_page_uri( 'ajax' ) ?>';
		}
	};
	kanban.strings = <?php echo json_encode( Kanban_App::instance()->get_strings() ) ?>;
	kanban.new_data = <?php echo json_encode( isset( $app_data ) ? $app_data : array() ) ?>;

	function hide_warnings () {
		var appBanners = document.getElementsByClassName('v3-warning');

		for (var i = 0; i < appBanners.length; i ++) {
			appBanners[i].parentNode.removeChild(appBanners[i]);
		}
	}
</script>


<script src="<?php echo Kanban_Router::instance()->get_uri( 'board' ) ?>js/app.js?<?php echo $app_data->plugin_data['Version'] ?>"></script>

</body>
</html>
