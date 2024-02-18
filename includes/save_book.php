<?php
class Save_Book_Form {
	public function __construct() {
		add_filter('wp_ajax_add_book', 'save_book_data');
		add_filter('wp_ajax_nopriv_add_book', 'save_book_data');
		
		function save_book_data() {
			global $wpdb;
			$editor 	  = '';
			$author       = array(); 
			$full_name 	  = '';
			$edition_type = '';
			$volume_lines = '';
			$book_format  = '';
			$circulations = '';
			$neck         = '';
			$city         = '';
			$ISBN         = '';
			$MPF          = '';
			$budget       = '';
			$book_link    = '';
			$book_notes   = '';

			$funding_source = '';
			$IPM_availability = 0;

			if ( !isset($_POST['editor'] ) || empty( $_POST['editor']) ) {
				wp_send_json_error( 'Пожалуйста, введите значение в поле редактора', 'maurl-book-form' );
				
			} else {
				$editor = sanitize_text_field( $_POST['editor'] );

			}

			if ( isset($_POST['author']) || !empty( $_POST['author']) ) {
				$author = array_map( 'sanitize_text_field', wp_unslash( $_POST['author'] ) );
			} else {
				wp_send_json_error( 'Пожалуйста, введите значение в поле "Автор"', 'maurl-book-form' );
			}

			if ( !isset($_POST['full_name']) || empty( $_POST['full_name']) ) {
				wp_send_json_error( 'Пожалуйста, введите значение в поле full_name', 'maurl-book-form' );

			} else {
				$full_name = sanitize_text_field( $_POST['full_name'] );

			}

			if ( !isset($_POST['edition_type']) || empty( $_POST['edition_type']) ) {
				wp_send_json_error( 'Пожалуйста, введите значение в поле edition_type', 'maurl-book-form' );

			} else {
				$edition_type = sanitize_text_field( $_POST['edition_type'] );
			}

			if ( !isset($_POST['volume_lines']) || empty( $_POST['volume_lines']) ) {
				wp_send_json_error( 'Пожалуйста, введите значение в поле volume_lines', 'maurl-book-form' );
			} else {
				$volume_lines = sanitize_text_field( $_POST['volume_lines'] );

			}

			if ( !isset($_POST['book_format']) || empty( $_POST['book_format']) ) {
				wp_send_json_error( 'Пожалуйста, введите значение в поле book_format', 'maurl-book-form' );

			} else {
				$book_format = sanitize_text_field( $_POST['book_format'] );

			}
			if ( !isset($_POST['circulations']) || empty( $_POST['circulations']) ) {
				wp_send_json_error( 'Пожалуйста, введите значение в поле тиражей', 'maurl-book-form' );
			} else {
				$circulations = sanitize_text_field( $_POST['circulations'] );
			}

			if ( !isset($_POST['neck']) || empty( $_POST['neck']) ) {
				wp_send_json_error( 'Пожалуйста, введите значение в поле шеи', 'maurl-book-form' );

			} else {
				$neck = sanitize_text_field( $_POST['neck'] );

			}

			if ( !isset($_POST['city']) || empty( $_POST['city']) ) {
				wp_send_json_error( 'Пожалуйста, введите значение в поле города', 'maurl-book-form' );
			} else {
				$city = sanitize_text_field( $_POST['city'] );

			}
			if ( !isset($_POST['ISBN']) || empty( $_POST['ISBN']) ) {
				wp_send_json_error( 'Пожалуйста, введите значение в поле ISBN', 'maurl-book-form' );
			} else {
				$ISBN = sanitize_text_field( $_POST['ISBN'] );				
			}
			if ( !isset($_POST['MPF']) || empty( $_POST['MPF']) ) {
				wp_send_json_error( 'Пожалуйста, введите значение в поле ', 'maurl-book-form' );
			} else {
				$MPF = sanitize_text_field( $_POST['MPF'] );				
			}
			if ( !isset($_POST['funding_source']) || empty( $_POST['funding_source']) ) {
				wp_send_json_error( 'Пожалуйста, введите значение в поле  источник финансирования', 'maurl-book-form' );
			} else {
				$funding_source = sanitize_text_field( $_POST['funding_source'] );
			}
			if ( !isset($_POST['budget']) || empty( $_POST['budget']) ) {
				wp_send_json_error( 'Пожалуйста, введите значение в поле объем бюджетных затра', 'maurl-book-form' );

			} else {
				$budget = sanitize_text_field( $_POST['budget'] );

			}
			if ( !isset($_POST['IPM_availability']) || empty( $_POST['IPM_availability']) ) {
				wp_send_json_error( 'Пожалуйста, введите значение в пол наличие в библиотеке ИПМ ДВО РАН', 'maurl-book-form' );
				
			} else {
				$IPM_availability = sanitize_text_field( $_POST['IPM_availability'] );

			}

			if ( isset($_POST['book_link']) ) {
				$book_link = sanitize_text_field( $_POST['book_link'] );
			}
			if ( isset($_POST['book_notes']) ) {
				$book_notes = sanitize_text_field( $_POST['book_notes'] );
			}
			$types_of_edition = $wpdb->get_var('SELECT `Код вида издания` FROM `wp_types_of_edition` WHERE `Наименование вида издания` = '.$edition_type);
			if (!$types_of_edition) {
			    $types_of_edition_insert = $wpdb->query($wpdb->prepare('INSERT INTO `wp_types_of_edition` (`Наименование вида издания`) 
				values (%s)', 
				$edition_type));
				$types_of_edition = $wpdb->get_var('SELECT `Код вида издания` FROM `wp_types_of_edition` WHERE `Наименование вида издания` = '.$edition_type);
			}
			
			$book_formats = $wpdb->get_var('SELECT `Код формата книги` FROM `wp_book_formats` WHERE `Значение формата книги` = '.$book_format);
			if (!$book_formats) {
			    $book_formats_insert = $wpdb->query($wpdb->prepare('INSERT INTO `wp_book_formats` (`Значение формата книги`) 
				values (%s)', 
				$book_format));
				$book_formats = $wpdb->get_var('SELECT `Код формата книги` FROM `wp_book_formats` WHERE `Значение формата книги` = '.$book_format);
			}
			
			$manuscript_preparation_forms = $wpdb->get_var('SELECT `Код формы подготовки рукописи` FROM `wp_manuscript_prepartion_forms` WHERE `Наименование формы подготовки рукописи` = '.$MPF);
			if (!$manuscript_preparation_forms) {
			    $manuscript_preparation_forms_insert = $wpdb->query($wpdb->prepare('INSERT INTO `wp_manuscript_prepartion_forms` (`Наименование формы подготовки рукописи`) 
				values (%s)', 
				$MPF));
				$manuscript_preparation_forms = $wpdb->get_var('SELECT `Код формы подготовки рукописи` FROM `wp_manuscript_prepartion_forms` WHERE `Наименование формы подготовки рукописи` = '.$MPF);
			}
			
			foreach ($types_of_edition as $t) {
			    $TOE = $t['Код вида издания'];
			}
			
			foreach ($book_formats as $b) {
			    $BF = $b['Код формата книги'];
			}
			
			foreach ($manuscript_preparation_forms as $m) {
			    $MPF = $m['Код формы подготовки рукописи'];
			}
			
			$TOE = $types_of_edition;
			$BF = $book_formats;
			$MPF = $manuscript_preparation_forms;

			$insert = $wpdb->query($wpdb->prepare('INSERT INTO `wp_books`
			(`Ответственные редакторы`,
			`Полное название работы`,
			`Код вида издания`,
			`Объем, стр.`,
			`Объем, уч.-изд. л.`,
			`Код формата книги`,
			`Тираж, тыс. экз.`,
			`Город: Изд-во`,
			`ISBN`,
			`Код формы подготовки рукописи`,
			`Источник финансирования`,
			`Объем бюджетных затрат`,
			`Наличие в библиотеке ИПМ ДВО РАН`,
			`Ссылка на электронный ресурс`,
			`Количество аффилиаций`,
			`Примечание`) 
			values (%s,%s,%d,%d,%s,%d,%d,%s,%s,%d,%s,%d,%s,%s,%d,%s)', 
			$editor, $full_name, $TOE, $volume_lines , '1', $BF, $circulations, $city, $ISBN, $MPF, $funding_source, $budget, $IPM_availability, $book_link, 1, $book_notes));

            
			
			//foreach($author as a) {
			   // INSERT INTO wp_book_authors a['КОД СОТРУДНИКА'], КОД СОЗДАННОЙ КНИГИ (id запроса $insert)
			//}

			if ($insert) { 
				wp_send_json_success( 'Данные вашей книги успешно сохранены', 'maurl-book-form' );

			} else{
				wp_send_json_error( 'Данные вашей книги не могут быть добавлены'.$TOE.$BF.$MPF, 'maurl-book-form' );

			}
		}
	}
}

new Save_Book_Form();
