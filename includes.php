<?php
namespace Ababilithub\FlexMasterPro;

defined( __NAMESPACE__.'\PLUGIN_NAME' ) || define( __NAMESPACE__.'\PLUGIN_NAME', plugin_basename(__FILE__) );
defined( __NAMESPACE__.'\PLUGIN_VERSION' ) || define( __NAMESPACE__.'\PLUGIN_VERSION', '1.0.0' );
defined( __NAMESPACE__.'\PLUGIN_DIR' ) || define( __NAMESPACE__.'\PLUGIN_DIR', dirname( __FILE__ ) );
defined( __NAMESPACE__.'\PLUGIN_FILE' ) || define( __NAMESPACE__.'\PLUGIN_FILE', __FILE__ );
defined( __NAMESPACE__.'\PLUGIN_URL' ) || define( __NAMESPACE__.'\PLUGIN_URL', plugins_url() . '/' . plugin_basename( dirname( __FILE__ ) ) );
defined( __NAMESPACE__.'\PLUGIN_PRE_UNDS' ) || define( __NAMESPACE__.'\PLUGIN_PRE_UNDS', 'flex_master_pro' );
defined( __NAMESPACE__.'\PLUGIN_PRE_HYPH' ) || define( __NAMESPACE__.'\PLUGIN_PRE_HYPH', 'flex-master-pro' );
defined( __NAMESPACE__.'\PLUGIN_OPTION_NAME' ) || define( __NAMESPACE__.'\PLUGIN_OPTION_NAME', PLUGIN_PRE_UNDS.'_'.'options');
defined( __NAMESPACE__.'\PLUGIN_OPTION_VALUE' ) || define( __NAMESPACE__.'\PLUGIN_OPTION_VALUE', get_option(PLUGIN_OPTION_NAME,[]) );
