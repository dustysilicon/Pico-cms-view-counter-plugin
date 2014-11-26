<?php

$current_Page; // global current page holding var.
    
class Views_counter {

    public function before_render(&$twig_vars, &$twig, &$template)
	{
		// Add a custom template variable
        $twig_vars['my_views_var'] = set_Reads();
	}
    
    public function file_meta(&$meta) {
        global $current_Page;
        
        $current_Page = $meta['title'];
    }
} // End read counter class

// read and increment read counter
function set_Reads() {
    global $current_Page;
    
    // array for holding counter file contents
    $file_data[] = '';
    
    // open counter file
    $fd = fopen('./counter.txt', 'r+');
    if (!$fd) {
        echo "Error opening counter.txt";
        die;
        }

    // Read counter text file
    $i = 0;
    while (($file_data[$i] = fgets($fd)) !== false) {
        $i++;
    }
    if (!feof($fd)) {
        echo "Error: unexpected fgets() fail\n";
    }
    
    // Find matching page tile and current counter value
    $next_val = false;
    $counter_val = 0;
    foreach ($file_data as &$value) {
        // found a match so we get the next value which is our counter
        if ($next_val) {
            $inc = $value + 1;
            $value = (string) $inc . "\r\n";
            $counter_val = $value;
            $next_val = false;
        }
        // find a match
        if (strcmp(trim($current_Page), trim($value)) == 0) {
            // echo '<h4>Match! ' . $value . '& ' . $current_Page . '</h4>';
            $next_val = true;
            }
    }
    // Page is not found, so we create one and start counter at 1
    if ($counter_val == 0) {
        // echo $current_Page . ' <-- Found a new page, appending. . .';
        $file_data[] = $current_Page . "\r\n";
        $file_data[] = '1' . "\r\n";
        $counter_val = '1';
        }
        
    fclose($fd);
    
     // open counter file and re-write
    $fd = fopen('./counter.txt', 'w');
    if (!$fd) {
        echo "Error opening counter.txt";
        die;
        }
    foreach ($file_data as &$value) {
        // echo $value . '<br>';
        // echo gettype($value) . '<br>';
        fwrite($fd, (string) $value);
        }
    // close counter file
    fclose($fd);
    
    // echo '<h1>' . $current_Page . ' - Counter Value: ' . $counter_val . '</h1>';
    
    return ($counter_val);
}
?>