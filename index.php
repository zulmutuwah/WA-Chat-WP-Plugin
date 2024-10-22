<?php
/*
Plugin Name: WA Chat WP Plugin
Plugin URI: https://yourwebsite.com/
Description: Plugin untuk menambahkan tombol live chat WhatsApp.
Version: 1.1
Author: Nama Anda
Author URI: https://yourwebsite.com/
License: GPL2
*/

// Cegah akses langsung ke file
if (!defined('ABSPATH')) {
    exit; // Exit jika diakses langsung
}

// Fungsi untuk menambahkan menu pengaturan di admin
function wa_chat_wp_settings_menu() {
    add_options_page(
        'WA Chat Settings',
        'WA Chat',
        'manage_options',
        'wa-chat-wp-settings',
        'wa_chat_wp_settings_page'
    );
}
add_action('admin_menu', 'wa_chat_wp_settings_menu');

// Halaman pengaturan plugin
function wa_chat_wp_settings_page() {
    ?>
    <div class="wrap">
        <h1>WA Chat Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('wa_chat_wp_settings_group');
            do_settings_sections('wa-chat-wp-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Inisialisasi pengaturan plugin
function wa_chat_wp_settings_init() {
    register_setting('wa_chat_wp_settings_group', 'wa_chat_wp_phone_number');
    register_setting('wa_chat_wp_settings_group', 'wa_chat_wp_button_text');
    register_setting('wa_chat_wp_settings_group', 'wa_chat_wp_position');
    register_setting('wa_chat_wp_settings_group', 'wa_chat_wp_button_color');
    register_setting('wa_chat_wp_settings_group', 'wa_chat_wp_text_color');

    add_settings_section(
        'wa_chat_wp_settings_section',
        'Pengaturan Tombol WA Chat',
        null,
        'wa-chat-wp-settings'
    );

    add_settings_field(
        'wa_chat_wp_phone_number',
        'Nomor WhatsApp',
        'wa_chat_wp_phone_number_render',
        'wa-chat-wp-settings',
        'wa_chat_wp_settings_section'
    );

    add_settings_field(
        'wa_chat_wp_button_text',
        'Teks Tombol',
        'wa_chat_wp_button_text_render',
        'wa-chat-wp-settings',
        'wa_chat_wp_settings_section'
    );

    add_settings_field(
        'wa_chat_wp_position',
        'Posisi Tombol',
        'wa_chat_wp_position_render',
        'wa-chat-wp-settings',
        'wa_chat_wp_settings_section'
    );

    add_settings_field(
        'wa_chat_wp_button_color',
        'Warna Tombol',
        'wa_chat_wp_button_color_render',
        'wa-chat-wp-settings',
        'wa_chat_wp_settings_section'
    );

    add_settings_field(
        'wa_chat_wp_text_color',
        'Warna Teks',
        'wa_chat_wp_text_color_render',
        'wa-chat-wp-settings',
        'wa_chat_wp_settings_section'
    );
}
add_action('admin_init', 'wa_chat_wp_settings_init');

// Render input untuk nomor WhatsApp
function wa_chat_wp_phone_number_render() {
    $phone_number = get_option('wa_chat_wp_phone_number', '628123456789');
    echo '<input type="text" name="wa_chat_wp_phone_number" value="' . esc_attr($phone_number) . '" />';
}

// Render input untuk teks tombol
function wa_chat_wp_button_text_render() {
    $button_text = get_option('wa_chat_wp_button_text', 'Hubungi Kami');
    echo '<input type="text" name="wa_chat_wp_button_text" value="' . esc_attr($button_text) . '" />';
}

// Render dropdown untuk posisi tombol
function wa_chat_wp_position_render() {
    $position = get_option('wa_chat_wp_position', 'right');
    echo '<select name="wa_chat_wp_position">
            <option value="right" ' . selected($position, 'right', false) . '>Kanan Bawah</option>
            <option value="left" ' . selected($position, 'left', false) . '>Kiri Bawah</option>
          </select>';
}

// Render input untuk warna tombol
function wa_chat_wp_button_color_render() {
    $button_color = get_option('wa_chat_wp_button_color', '#25D366');
    echo '<input type="text" name="wa_chat_wp_button_color" value="' . esc_attr($button_color) . '" class="wp-color-picker-field" data-default-color="#25D366" />';
}

// Render input untuk warna teks
function wa_chat_wp_text_color_render() {
    $text_color = get_option('wa_chat_wp_text_color', '#ffffff');
    echo '<input type="text" name="wa_chat_wp_text_color" value="' . esc_attr($text_color) . '" class="wp-color-picker-field" data-default-color="#ffffff" />';
}

// Fungsi untuk menambahkan tombol WhatsApp di halaman
function wa_chat_wp_button() {
    $phone_number = get_option('wa_chat_wp_phone_number', '628123456789');
    $button_text = get_option('wa_chat_wp_button_text', 'Hubungi Kami');
    $position = get_option('wa_chat_wp_position', 'right');
    $button_color = get_option('wa_chat_wp_button_color', '#25D366');
    $text_color = get_option('wa_chat_wp_text_color', '#ffffff');
    $chat_url = 'https://api.whatsapp.com/send?phone=' . $phone_number;

    $position_style = ($position === 'right') ? 'right: 20px;' : 'left: 20px;';

    echo '<div id="wa-chat-button" style="position: fixed; bottom: 20px; ' . $position_style . ' z-index: 1000;">
            <a href="' . esc_url($chat_url) . '" target="_blank" style="background: ' . esc_attr($button_color) . '; color: ' . esc_attr($text_color) . '; padding: 10px 15px; border-radius: 50px; text-decoration: none; font-weight: bold; font-family: Arial, sans-serif;">
                ' . esc_html($button_text) . '
            </a>
          </div>';
}

// Hook untuk menambahkan tombol ke footer
add_action('wp_footer', 'wa_chat_wp_button');

// Enqueue color picker script
function wa_chat_wp_enqueue_scripts($hook_suffix) {
    if ($hook_suffix === 'settings_page_wa-chat-wp-settings') {
        wp_enqueue_script('wp-color-picker');
        wp_enqueue_style('wp-color-picker');
        wp_add_inline_script('wp-color-picker', 'jQuery(document).ready(function($){ $(".wp-color-picker-field").wpColorPicker(); });');
    }
}
add_action('admin_enqueue_scripts', 'wa_chat_wp_enqueue_scripts');
