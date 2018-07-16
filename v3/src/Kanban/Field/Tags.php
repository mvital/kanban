<?php


class Kanban_Field_Tags extends Kanban_Field {

	private static $instance;

	public function send_notifications_after_fieldvalue_update ($fieldvalue, $prev_content) {

		if ( $fieldvalue->field_type != 'tags') return;

		$field = Kanban_Field::instance()->get_row($fieldvalue->field_id);

		$formatted_content = $this->format_content_for_emails($fieldvalue->content, $field);

		$board_id = Kanban_Card::instance()->get_board_id_by_card_id($fieldvalue->card_id);

		// Get all users who follow the card.
		$card_user_ids = Kanban_Card_User::instance()->get_user_ids_by_card_id($fieldvalue->card_id);

		// Remove the user who modified the field.
		if ( isset($card_user_ids[$fieldvalue->modified_user_id]) ) {
			unset($card_user_ids[$fieldvalue->modified_user_id]);
		}

		if ( !empty($card_user_ids) ) {

			$subject = sprintf(
				__( 'Card #%d has been updated on board "%s"' ),
				$fieldvalue->card_id,
				Kanban_Board::instance()->get_label( $board_id )
			);

			$message = Kanban_Template::instance()->render(
				Kanban::instance()->settings()->path . '/board/emails/fieldvalue/updated.php',
				array(
					'field_label' => Kanban_Field::instance()->get_label( $fieldvalue->field_id ),
					'card_id'     => $fieldvalue->card_id,
					'content'     => $formatted_content,
					'card_url'    => Kanban_Card::instance()->get_uri( $fieldvalue->card_id )
				)
			);

			Kanban_Notification::instance()->notify_users_with_email_template($card_user_ids, $subject, $message);
		}
	}

	public function format_options_for_db($options) {

		$field_options = parent::format_options_for_db($options);

		foreach ( $options as $key => &$value ) {
			switch ( $key ) {
				case 'select_multiple':
					$value = $this->format_bool_for_db($value);
					break;
				case 'add_new_on_field':
					$value = $this->format_bool_for_db($value);
					break;
				case 'tags':
					$value = $this->format_json_for_db($value);
					break;
				default:
					unset($options[$key]);
					break;
			}
		}

		$options += $field_options;

		return (array) $options;
	}

	public function format_options_for_app($options) {
		$field_options = parent::format_options_for_app($options);

		foreach ( $options as $key => &$value ) {
			switch ( $key ) {
				case 'select_multiple':
					$value = $this->format_bool_for_app($value);
					break;
				case 'add_new_on_field':
					$value = $this->format_bool_for_app($value);
					break;
				case 'tags':
					$value = $this->format_json_for_app($value);
					break;
				default:
					unset($options[$key]);
					break;
			}
		}


		$options += $field_options;

		return (array) $options;
	}

	public function format_content_for_db($content) {

		$content = $this->format_json_for_db($content);

		return $content;
	}

	public function format_content_for_app($content) {

		$content = $this->format_json_for_app($content);

		return $content;
	}


	public function format_content_for_emails ($content, $field) {

		if ( '' == $content ) {
			return $content;
		}

		if ( empty($content) ) {
			return '';
		}

		$return = array();
		foreach ($field->options['tags'] as $tag ) {
			if ( in_array($tag->id, $content)) {
				$return[] = '<span style="background: #DDDDDD; border-radius: 5px; padding: 2px 5px;">' . $tag->content . '</span>';
			}
		}

		return implode(' ', $return);
	}

	public static function instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();

			add_action(
				'kanban_fieldvalue_ajax_replace_set_row_after',
				array( self::$instance, 'send_notifications_after_fieldvalue_update' ),
				0,
				2
			);

		}

		return self::$instance;
	}
}