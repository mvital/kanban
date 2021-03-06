var functions = require('./functions')

function User(record) {

	var _self = {};
	_self.record = record;
	_self.allowedFields = ['capabilities', 'options'];

	_self.optionsApp = {
		first_day_of_week: 'sunday',
		live_updates_check_interval: 10,
		do_live_updates_check: true,
		do_notifications: true,
		date_view_format: 'M d, yyyy',
		current_board: null
	};

	_self.optionsBoard = {
		view: [],
		show_task_id: true
	};

	this.record = function () {
		return functions.cloneObject(_self.record);
	}; // record

	this.id = function () {
		return functions.cloneNumber(_self.record.id);
	}; // id

	this.display_name = function () {
		return functions.cloneString(_self.record.display_name);
	}; // display_name

	this.user_email = function () {
		return functions.cloneString(_self.record.user_email);
	}; // display_name

	this.display_name_long = function () {
		return functions.cloneString(_self.record.display_name_long);
	}; // display_name_long

	this.display_name_short = function () {
		return functions.cloneString(_self.record.display_name_short);
	}; // display_name_short

	this.currentBoardId = function () {

		if ( 'undefined' === typeof _self.record.options.app.current_board ) {
			return null;
		}

		if ( 'undefined' === typeof kanban.boards[_self.record.options.app.current_board] ) {
			return null;
		}

		return _self.record.options.app.current_board;
	}; // currentBoardId

	this.currentBoard = function () {
		var self = this;

		var boardId = self.currentBoardId();

		if ( boardId == null ) {
			return undefined;
		}

		return kanban.boards[boardId];
	}; // currentBoardId

	this.allowedFields = function () {
		return functions.cloneArray(_self.allowedFields);
	}; // allowedFields

	this.follows = function (type) {

		// For the future.
		if ( 'undefined' !== typeof _self.record.follows[type] ) {
			return functions.cloneArray(_self.record.follows[type]);
		}

		// Default to cards.
		return functions.cloneArray(_self.record.follows.cards);

	}; // follows

	this.followsCard = function (cardId) {
		return _self.record.follows.cards.indexOf(cardId) == -1 ? false : true;
	}; // followsCard

	this.followCard = function (cardId) {
		_self.record.follows.cards.push(cardId);
	}; // followsCard

	this.unfollowCard = function (cardId) {
		// https://stackoverflow.com/a/5767357
		var index = _self.record.follows.cards.indexOf(cardId);
		if (index > -1) {
			_self.record.follows.cards.splice(index, 1);
		}
	}; // unfollowCard

	this.optionBoardUpdate = function (option, value, boardId) {
		var self = this;

		if ( 'undefined' === typeof boardId ) {
			boardId = kanban.app.current_board_id();
		}

		if ( 'undefined' === typeof _self.record.options.boards[boardId] ) {
			_self.record.options.boards[boardId] = [];
		}

		// Gets overwritten on ajax done below, but this allows immediate rerendering.
		_self.record.options.boards[boardId][option] = value;

		// Ajax won't send empty arrays, so convert to string.
		if ( Array.isArray(value) && value.length == 0 ) {
			value = '';
		}

		$.ajax({
			data: {
				type: 'user_option',
				action: 'replace',
				option: option,
				value: value,
				board_id: boardId
			}
		})
		.done(function (response) {
			if ( 'undefined' === typeof response.data ) {
				kanban.app.notify(kanban.strings.board.retrieve_error);
				return false;
			}

			var boardId = response.data.board_id;
			var options = response.data.options;

			_self.record.options.boards[boardId] = options;
		});
	}; // optionsUpdate

	this.optionAppUpdate = function (option, value) {
		var self = this;

		if ( 'undefined' === typeof _self.optionsApp[option] ) {
			return false;
		}

		// Gets overwritten on ajax done below, but this allows immediate rerendering.
		_self.record.options.app[option] = value;

		// Ajax won't send empty arrays, so convert to string.
		if ( Array.isArray(value) && value.length == 0 ) {
			value = '';
		}

		$.ajax({
			data: {
				type: 'user_option',
				action: 'replace_app',
				option: option,
				value: value
			}
		})
		.done(function (response) {
			if ( 'undefined' === typeof response.data ) {
				kanban.app.notify(kanban.strings.board.retrieve_error);
				return false;
			}

			var options = response.data.options;

			_self.record.options.app = options;

			// Stop live updates.
			if ( !kanban.app.current_user().do_live_updates_check() ) {
				clearInterval(kanban.app.timers.updates);
				return false;
			}
		});

		var board = kanban.app.current_board();

		if ( 'undefined' !== typeof board ) {
			board.rerender();
		}


	}; // optionAppUpdate

	// this.optionsAppUpdate = function (options) {
	// 	var self = this;
	//
	// 	_self.record.options.app = $.extend(true, _self.record.options.app, options);
	//
	// 	$.ajax({
	// 		data: {
	// 			type: 'user_option',
	// 			action: 'replace_app_all',
	// 			options: _self.record.options.app
	// 		}
	// 	});
	//
	// 	if (Boolean(options.do_live_updates_check) == false) {
	// 		clearInterval(kanban.app.timers().updates);
	// 	} else {
	// 		kanban.app.updatesInit();
	// 	}
	//
	// 	kanban.app.current_board().show();
	// }; // optionsAppUpdate

	this.optionsBoard = function () {
		var self = this;

		return $.extend(true, {}, _self.optionsBoard, _self.record.options.boards[self.currentBoardId()]);
	}; // options

	this.optionsApp = function () {
		return $.extend(true, {}, _self.optionsApp, _self.record.options.app);
	}; // options

	// Dedicated function to this, since we're calling it often.
	this.do_live_updates_check = function () {

		// Default to true.
		return _self.record.options.app.do_live_updates_check === false ? false : true;
	}; // do_live_updates_check

	this.capsAdmin = function () {
		var self = this;
		return _self.record.capabilities.admin.slice();
	}; // caps

	this.capsBoard = function (boardId) {
		var self = this;

		if ( 'undefined' === typeof _self.record.capabilities.boards[boardId] ) {
			return [];
		}

		var capsBoard = _self.record.capabilities.boards[boardId].slice();

		return this.capsAdmin().concat(capsBoard);
	}; // caps

	this.hasCap = function (cap) {

		var self = this;

		if ( self.id() == 0 ) {
			return false;
		}

		var userCaps = self.capsAdmin();

		// Check for admin.
		if ( userCaps.includes('admin') ) {
			return true;
		}

		// Check for cap.
		if ( userCaps.includes(cap) ) {
			return true;
		}

		// If they got this far and no boardId, try to load it.
		if ( 'undefined' === typeof boardId || isNaN(boardId) ) {
			boardId = kanban.app.current_board_id();
		}

		// If they got this far and no boardId, false.
		if ( 'undefined' === typeof boardId || isNaN(boardId) ) {
			return false;
		}

		// Break it up.
		var capArr = cap.split('-');

		var capSection = capArr[0];

		// If user created the board, treat them like a board admin.
		if ( 'undefined' !== typeof kanban.app.current_board() && kanban.app.current_board() != null ) {
			var board = kanban.app.current_board();

			if ( board.record().created_user_id == self.id() && (capSection == 'board' || capSection == 'card' || capSection == 'comment') ) {
				return true;
			}
		}

		var userBoardCaps = self.capsBoard(boardId);

		// Check for section admin.
		if ( userBoardCaps.includes(capSection) ) {
			return true;
		}

		// Check for cap.
		if ( userBoardCaps.includes(cap) ) {
			return true;
		}
		
		// Assume false.
		return false;
	}; // hasCap

	this.renderMention = function () {
		var self = this;
		var mentionHtml = kanban.templates['user-mention'].render({
			user_id: self.id(),
			name: self.display_name_short()
		});

		return mentionHtml;
	}; // renderMention

	// this.hasBoardCap = function (cap) {
	//
	// 	var self = this;
	//
	// 	var userCaps = self.capsBoard(kanban.app.current_board());
	//
	// 	// Check for admin.
	// 	if ( userCaps.includes('admin') ) {
	// 		return true;
	// 	}
	//
	// 	// Break it up.
	// 	var capArr = cap.split('-');
	//
	// 	// Check for section admin.
	// 	var capSection = capArr[0];
	// 	if ( userCaps.indexOf(capSection) !== -1 ) {
	// 		// console.log('capSection', capSection);
	// 		return true;
	// 	}
	//
	// 	// Check for cap.
	// 	if ( userCaps.indexOf(cap) !== -1 ) {
	// 		// console.log('cap', capSection);
	// 		return true;
	// 	}
	//
	// 	// If user created the board, treat them like an admin.
	// 	if ( 'undefined' !== typeof kanban.app.current_board() ) {
	// 		var board = kanban.app.current_board();
	//
	// 		if (board.record().created_user_id == self.id()) {
	// 			return true;
	// 		}
	// 	}
	//
	// 	// Assume false.
	// 	return false;
	// }; // hasCap

} // User


module.exports = User