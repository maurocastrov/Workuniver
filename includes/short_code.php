<?php
class Short_Code {
	public function __construct() {
		//Enqqueing style & Scripts for admin
		add_action('wp_enqueue_scripts', 'book_public_enqueue_styles_scripts');
		function book_public_enqueue_styles_scripts() {
			wp_register_style('public_style', plugins_url('/static/style.css', __FILE__), array(), '1.0');
			wp_enqueue_style('public_style');

			wp_register_script('public_script', plugins_url('/static/script.js' , __FILE__), array('jquery') , '1.0', true);
			wp_enqueue_script('public_script');

			wp_localize_script(
				'public_script',
				'localizedObject',
				array(
				'ajaxurl'    => admin_url('admin-ajax.php'),
				'nonce' => wp_create_nonce('ajax-nonce')
			)
			);
		}
		add_shortcode( 'display_book_form', 'display_book_form');
		function display_book_form () {
			ob_start();
			global $wpdb;
			
			$users = get_users( array( 'fields' => array( 'ID' ) ) );
			$edition_names = $wpdb->get_results($wpdb->prepare('SELECT * FROM `wp_types_of_edition` WHERE %d', '1'));
			$book_formats = $wpdb->get_results($wpdb->prepare('SELECT * FROM `wp_book_formats` WHERE %d', '1'));
			$manu_scripts_form = $wpdb->get_results($wpdb->prepare('SELECT * FROM `wp_manuscript_prepartion_forms` WHERE %d', '1'));
			

			$all_users =array();
			foreach ($users as $user) {
					array_push($all_users, get_user_meta($user->ID));
			}
			?>
			<div class="book-form-container">
				<form class="book-form" method="POST">
					
					
					<?php wp_nonce_field( 'contact_form_submit', 'cform_generate_nonce'); ?>
					<div class="res-editors">
						<label for="editor"><?php esc_html_e('Ответственные авторы', 'maurl-book-form'); ?></label>
						<input type="text" id="editor">
					</div>
					
					<div class="author">
						<label>
							<button type="button" id="add_author"> <?php esc_html_e('Добавить автора', 'maurl-book-form'); ?> </button>
						</label>
						<div class="show_authors">
								<input list="authors" name="author" class="author_data">

								<datalist id="authors">
									<?php
									foreach ($all_users as $user) {
									?>
										<option value="<?php esc_html_e($user['nickname'][0], 'maurl-book-form'); ?>">
									<?php
									}
									?>
								</datalist>
						</div>


					</div>
					<div class="full_name">
						<label for="full_name"><?php esc_html_e('Полное название работы', 'maurl-book-form'); ?></label>
						<input type="text"  id="full_name">
					</div>
					<div class="edition-type">
						<label for="edition-type"><?php esc_html_e('Вид издания', 'maurl-book-form'); ?></label>
						<input list="edition_types" name="edition_type" id="edition_type">

						<datalist id="edition_types">
							<?php
							foreach ($edition_names as $edition_type) {
							?>
								<option value="<?php esc_html_e($edition_type->name, 'maurl-book-form'); ?>">
							<?php
							}
							?>

						</datalist>
					</div>
					<div class="volume_lines">
						<label for="volume_lines"><?php esc_html_e('Объем, ст', 'maurl-book-form'); ?></label>
						<input type="number" name="volume_lines" id="volume_lines">
					</div>
					<div class="book_format">
						<label for="book_format"><?php esc_html_e('Формат книги', 'maurl-book-form'); ?></label>
						<input list="book_formats" name="book_format" id="book_format">
						<datalist id="book_formats">
							<?php
							foreach ($book_formats as $format) {
							?>
								<option value="<?php esc_html_e($format->Book_Format, 'maurl-book-form'); ?>">
							<?php
							}
							?>
						</datalist>
					</div>
					<div class="circulations">
						<label for="circulations"><?php esc_html_e('Тираж, тыс. экз.', 'maurl-book-form'); ?></label>
						<input type="number" id="circulations">
					</div>
					<div class="neck">
						<label for="neck"><?php esc_html_e('Гриф, указанный в книг', 'maurl-book-form'); ?></label>
						<input type="text" id="neck">
					</div>
					<div class="city-publisher">
						<label for="city-publisher"><?php esc_html_e('Город: Изд-во', 'maurl-book-form'); ?></label>
						<input type="text" id="city-publisher">
					</div>
					<div class="ISBN">
						<label for="ISBN"><?php esc_html_e('ISBN', 'maurl-book-form'); ?></label>
						<input type="text" id="ISBN">
					</div>
					<div class="MPF">
					<label for="MPF"><?php esc_html_e('Форма подготовки рукописи', 'maurl-book-form'); ?></label>
						<input list="MPFs" name="MPF" id="MPF">
						<datalist id="MPFs">
							<?php
							foreach ($manu_scripts_form as $MPF) {
							?>
								<option value="<?php esc_html_e($MPF->name, 'maurl-book-form'); ?>">
							<?php
							}
							?>
						</datalist>
					</div>
					<div class="funding-source">
						<label for="funding-source"><?php esc_html_e('Источник финансировани', 'maurl-book-form'); ?></label>
						<input type="text"  id="funding-source">
					</div>

					<div class="budget">
						<label for="budget"><?php esc_html_e('Объем бюджетных затрат', 'maurl-book-form'); ?></label>
						<input type="text"  id="budget">
					</div>

					<div class="IPM-available">
						<label for="IPM-available"><?php esc_html_e('Наличие в библиотеке ИПМ ДВО РАН, 'maurl-book-form'); ?></label>
						<input type="checkbox" name="IPM_availability" id="IPM_availability">
					</div>
					
					<div class="book-link">
						<label for="book-link"><?php esc_html_e('Ссылка на электронный ресурс', 'maurl-book-form'); ?></label>
						<input type="text" id="book-link">
					</div>
					<div class="book-notes">
						<label for="book-notes"><?php esc_html_e('Примечание', 'maurl-book-form'); ?></label>
						<input type="text"  id="book-notes">
					</div>

					<div class="submit">
						<input type="submit" name="submit" id="submit_book_data">
					</div>

				</form>
				
			</div>
			<?php
			return ob_get_clean();
		}
	}
}

$obj = new Short_code();
