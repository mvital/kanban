<script class="template" type="t/template" data-id="board-modal-field-date">

	<div class="panel panel-default" id="board-modal-field-{{%field.id}}" data-field-id="{{%field.id}}">
		<div class="panel-heading">
			<a href="javascript:void(0);" class="btn btn-xs btn-empty pull-right ei ei-menu board-modal-field-handle">
			</a>

			<a class="h4 panel-title" data-toggle="collapse" data-parent="#board-modal-fields-accordion"
			   href="#board-modal-field-{{%field.id}}-options">
				{{field.label}}{{=field.label}}{{:field.label}}<i><?php _e( 'Field name', 'kanban'); ?></i>{{/field.label}}
			</a>

			<small class="text-muted"><?php _e( 'Date', 'kanban'); ?></small>
		</div>
		<div id="board-modal-field-{{%field.id}}-options" class="panel-collapse collapse {{open}}in{{/open}}">
			<div class="panel-body">
				<div class="wrapper-form-group row">
					<?php include KANBAN_APP_DIR . '/inc/board/modal/field/title.php' ?>

					<div class="form-group form-group-toggle col col-sm-12">
						<label><?php _e( 'Count up/down:', 'kanban'); ?></label>

						<div class="btn-group">
							<input type="radio"
							       onchange="kanban.fields[{{%field.id}}].optionOnChange(this);"
								   data-name="show_datecount"
								   name="field-{{%field.id}}-show_datecount"
								   id="field-{{%field.id}}-show_datecount-0"
								   autocomplete="off"
								   {{!field.options.show_datecount}}checked{{/!field.options.show_datecount}}
							value="false">
							<label for="field-{{%field.id}}-show_datecount-0" class="btn btn-green"><?php _e( 'Yes', 'kanban'); ?></label>

							<input type="radio"
							       onchange="kanban.fields[{{%field.id}}].optionOnChange(this);"
								   data-name="show_datecount"
								   name="field-{{%field.id}}-show_datecount"
								   id="field-{{%field.id}}-show_datecount-1"
								   autocomplete="off"
								   {{field.options.show_datecount}}checked{{/field.options.show_datecount}}
							value="true">
							<label for="field-{{%field.id}}-show_datecount-1" class="btn btn-red"><?php _e( 'No', 'kanban'); ?></label>
						</div><!--btn-group-->
					</div><!--form-group -->
					
					<?php include KANBAN_APP_DIR . '/inc/board/modal/field/option-hidden.php' ?>


				</div><!--wrapper-form-group-->

				<?php include KANBAN_APP_DIR . '/inc/board/modal/field/more-options.php' ?>

				<div class="collapse" id="board-modal-field-{{%field.id}}-actions">
					<?php include KANBAN_APP_DIR . '/inc/board/modal/field/option-delete.php' ?>
				</div><!--collapse-->

			</div><!--panel-body-->
		</div><!--panel-collapse-->
	</div><!--panel-->

</script>