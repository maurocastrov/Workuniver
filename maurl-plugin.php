<?php
/**
	* Plugin Name: Book/Article Forms
	* Description:  Add Your Books And Articles
	* version: 1.0.0
	* Author: maurl
	* Text Domain: maurl-book-form
	* Domain Path: /languages
*/

if (! defined('WPINC')) {
	die;
}

require_once('includes/short_code.php');
require_once('includes/save_book.php');

register_activation_hook(__FILE__, 'maurl_create_tables');
function maurl_create_tables() {
	global $wpdb;
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	$book_formats = $wpdb->prefix . 'book_formats';
	if ( $wpdb->prepare($wpdb->get_var('SHOW TABLES LIKE %s '), $book_formats) != $book_formats ) {
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $book_formats (
			`Код формата книги` mediumint(9) NOT NULL AUTO_INCREMENT,
			`Значение формата книги` text NOT NULL,
			PRIMARY KEY  (`Код формата книги`)
		) $charset_collate;";
		dbDelta( $sql );   
	}

	$book_authors = $wpdb->prefix . 'book_authors';
	if ( $wpdb->prepare($wpdb->get_var('SHOW TABLES LIKE %s '), $book_authors) != $book_authors ) {
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $book_authors (
			`Код книги` mediumint(9) NOT NULL AUTO_INCREMENT,
			`Код сотрудника` int NOT NULL REFERENCES wp_users (ID),
			PRIMARY KEY  (`Код книги`)
		) $charset_collate;";
		dbDelta( $sql );   
	}

	$types_of_edition = $wpdb->prefix . 'types_of_edition';
	if ( $wpdb->prepare($wpdb->get_var('SHOW TABLES LIKE %s '), $types_of_edition) != $types_of_edition ) {
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $types_of_edition (
			`Код вида издания` mediumint(9) NOT NULL AUTO_INCREMENT,
			`Наименование вида издания` text NOT NULL,
			PRIMARY KEY  (`Код вида издания`)
		) $charset_collate;";
		dbDelta( $sql );   
	}

	$manuscript_prepartion_forms = $wpdb->prefix . 'manuscript_prepartion_forms';
	if ( $wpdb->prepare($wpdb->get_var('SHOW TABLES LIKE %s '), $manuscript_prepartion_forms) != $manuscript_prepartion_forms ) {
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $manuscript_prepartion_forms (
			`Код формы подготовки рукописи` mediumint(9) NOT NULL AUTO_INCREMENT,
			`Наименование формы подготовки рукописи` text NOT NULL,
			PRIMARY KEY  (`Код формы подготовки рукописи`)
		) $charset_collate;";
		dbDelta( $sql );   
	}
	
	$books = $wpdb->prefix . 'books';
	if ( $wpdb->prepare($wpdb->get_var('SHOW TABLES LIKE %s '), $books) != $books ) {

		$charset_collate = $wpdb->get_charset_collate();
	
		$sql = "CREATE TABLE $books (
			`Код книги` mediumint(9) NOT NULL AUTO_INCREMENT,
			`Ответственные редакторы` text NOT NULL,
			`Полное название работы` text NOT NULL,
			`Код вида издания` text NOT NULL REFERENCES {$types_of_edition} (`Код вида издания`),
			`Объем, стр.` int NOT NULL,
			`Объем, уч.-изд. л.` text NOT NULL,
			`Код формата книги` int NOT NULL REFERENCES {$book_formats} (`Код формата книги`),
			`Тираж, тыс. экз.` text NOT NULL,
			`Город: Изд-во` text NOT NULL,
			ISBN text NOT NULL,
			`Код формы подготовки рукописи` text NOT NULL REFERENCES {$manuscript_prepartion_forms} (`Код формы подготовки рукописи`),
			`Источник финансирования` text NOT NULL,
			`Объем бюджетных затрат` text NOT NULL,
			`Наличие в библиотеке ИПМ ДВО РАН` boolean NOT NULL,
			`Ссылка на электронный ресурс` text,
			`Количество аффилиаций` int NOT NULL,
			`Примечание` text,
			PRIMARY KEY  (`Код книги`)
		) $charset_collate;";
		dbDelta( $sql );
	}

	$territory_types_of_edition = $wpdb->prefix . 'territory_types_of_edition';
	if ( $wpdb->prepare($wpdb->get_var('SHOW TABLES LIKE %s '), $territory_types_of_edition) != $territory_types_of_edition ) {
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $territory_types_of_edition (
			`Код вида издания` mediumint(9) NOT NULL AUTO_INCREMENT,
			`Наименование вида издания` text NOT NULL,
			PRIMARY KEY  (`Код вида издания`)
		) $charset_collate;";
		dbDelta( $sql );   
	}

	$publication_types = $wpdb->prefix . 'publication_types';
	if ( $wpdb->prepare($wpdb->get_var('SHOW TABLES LIKE %s '), $publication_types) != $publication_types ) {
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $publication_types (
			`Код вида публикации` mediumint(9) NOT NULL AUTO_INCREMENT,
			`Наименование вида публикации` text NOT NULL,
			PRIMARY KEY  (`Код вида публикации`)
		) $charset_collate;";
		dbDelta( $sql );   
	}
	
	$article_authors = $wpdb->prefix . 'article_authors';
	if ( $wpdb->prepare($wpdb->get_var('SHOW TABLES LIKE %s '), $article_authors) != $article_authors ) {
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $article_authors (
			`Код статьи` mediumint(9) NOT NULL AUTO_INCREMENT,
			`Код сотрудника` int NOT NULL REFERENCES wp_users (ID),
			PRIMARY KEY  (`Код статьи`)
		) $charset_collate;";
		dbDelta( $sql );   
	}
	
	$articles = $wpdb->prefix . 'articles';
	if ( $wpdb->prepare($wpdb->get_var('SHOW TABLES LIKE %s '), $articles) != $articles ) {
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $articles (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			PRIMARY KEY  (id)
		) $charset_collate;";
		dbDelta( $sql );   
	}
}

add_action( 'wp_enqueue_scripts', 'register_script' );
function register_script() {
    wp_enqueue_script('script', plugins_url('/includes/static/script.js', __FILE__), array('jquery'));
}
