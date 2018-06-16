<script class="template" type="t/template" data-id="card-modal">

	<div class="modal-dialog modal-lg" id="card-modal" data-id="{{%card.id}}">
		<div class="modal-content" data-id="{{%card.id}}" id="card-modal-{{%card.id}}">

			<div id="modal-header">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#modal-navbar">
						<span class="sr-only"><?php _e( 'Toggle navigation', 'kanban'); ?></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>

					<span class="navbar-brand visible-xs visible-sm">
						<?php echo sprintf(__( 'Card #%s', 'kanban'), '{{%card.id}}'); ?>
					</span>
				</div>
				<div id="modal-navbar" class="collapse navbar-collapse">
					<ul class="nav navbar-nav">
						<li class="active">
							<a href="javascript:void(0);"
							   data-target="options"
							   id="modal-tab-options"
							   onclick="kanban.app.modal.tabChange(this);">
								<?php _e( 'Fields', 'kanban'); ?>
							</a></li>
						</li>
						<li>
							<a href="javascript:void(0);"
							   data-target="comments"
							   id="modal-tab-comments"
							   onclick="kanban.app.modal.tabChange(this);"><?php _e( 'Comments', 'kanban'); ?></a>
						</li>
						<li class="pull-right">
							<a href="javascript:void(0);"
						        onclick="kanban.cards[{{%card.id}}].modal.close(this);">
								<span class="visible-xs-inline-block"><?php _e( 'Close this window', 'kanban'); ?></span>
								<i class="ei ei-close hidden-xs"></i>
							</a>
						</li>
						<li class="pull-right">
							<a href="javascript:void(0);"
							   data-target="actions"
							   id="modal-tab-actions"
							   onclick="kanban.app.modal.tabChange(this);">
								<span class="visible-xs-inline-block"><?php _e( 'More actions', 'kanban'); ?></span>
								<i class="ei ei-cog hidden-xs"></i>
							</a>
						</li>
					</ul>
				</div><!--/.nav-collapse -->
			</div><!--modal-header-->

			<div class="modal-body">

				<div class="tab-content">

					<div class="tab-pane active" id="modal-tab-pane-options">
						<div class="row wrapper-form-group">
							{{=fields}}
						</div><!--row-->

						<hr>

						<div id="card-modal-lane-selector">
							<small>
								<?php _e('Move the card to another lane:', 'kanban') ?>
							</small><br>
							<div class="btn-group">
								{{=lanesSelector}}
							</div>
						</div>

					</div><!--tab-options-->

					<div class="tab-pane" id="modal-tab-pane-comments">

						<div id="wrapper-card-modal-comments">

							<div id="card-modal-comments-list">

								<i class="ei ei-loading"></i>

							</div><!--card-modal-comments-list-->

							{{=commentForm}}
						</div><!--wrapper-card-modal-comments-->

					</div><!--tab-comments-->

					<div  class="tab-pane" id="modal-tab-pane-actions">
						<p>
							<a href="javascript:void(0);" class="btn btn-default btn-sm">
								<?php _e( 'Move this task to another column or board.', 'kanban'); ?>
							</a>
						</p>
						<p>
							<a href="javascript:void(0);" class="btn btn-default btn-sm" onclick="kanban.cards[{{%card.id}}].copy(this)">
								<?php _e( 'Copy this task.', 'kanban'); ?>
							</a>
						</p>
						<p>
							<a href="javascript:void(0);" class="btn btn-warning btn-sm" onclick="kanban.cards[{{%card.id}}].delete(this)">
								<?php _e( 'Archive this task.', 'kanban'); ?>
								<i class="ei ei-loading show-on-loading" style="color: {{%lane.color}}"></i>
							</a>
						</p>
					</div><!--tab-pane-->
				</div><!--tab-panes-->
			</div><!--modal-body-->
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->

</script>