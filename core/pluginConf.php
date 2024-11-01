<?php

/*
 * Configuration file of the YourMarketingGuy plugin.
 * 
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
$_CONF['backendServerUrl'] = "https://yourguy.marketing/backend";

$_CONF['logFile'] = 'support';
$_CONF['logExtension'] = '.log';
$_CONF['logPath'] = YMG_PATH.'/log/';

$_CONF['wpOptionsUpdateInterval'] = 10;//60 * 60 * 24 * 7;
$_CONF['wpOptionsWhitelist'] = array(
                                    'siteurl',
                                    'home',
                                    'blogname', 
                                    'blogdescription',
                                    'users_can_register',
                                    'admin_email',
                                    'use_smilies',
                                    'default_comment_status',
                                    'active_plugins',
                                    'ping_sites',
                                    'blog_public',
                                    'WPLANG',
                                    'permalink_structure',
                                    'date_format',
                                    'time_format',
                                    'use_balanceTags',
                                    'require_name_email',
                                    'rss_use_excerpt',
                                    'default_ping_status',
                                    'posts_per_page',
                                    'comment_moderation',
                                    'gzipcompression',
                                    'template',
                                    'comment_registration',
                                    'use_trackback',
                                    'show_on_front',
                                    'page_comments',
                                    'comments_per_page',
                                    'uninstall_plugins',
                                    'page_for_posts',
                                    'page_on_front'
                                    );