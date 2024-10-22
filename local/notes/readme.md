# Add notes plugin to module

# Add below lines in navigationlib.php load_module_settings() function.

$localplugins = get_plugin_list_with_function('local', 'extend_navigation_module', 'lib.php');

foreach ($localplugins as $localplugin) {
    $localplugin($modulenode, $this->page->cm);
}