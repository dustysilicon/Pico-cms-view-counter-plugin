<?php
/*  View_Counter - Adds view/read counter to your site pages.
    Plugin for PICO CMS, Blazingly fast!
    Author: Dennis Raney
    Date: 11/28/14
    Instructions: See readme.md file for help or on github https://github.com/dustysilicon/Pico-cms-view-counter-plugin
*/

$current_Page; // Current Page Title
    
class Views_counter {

    public function before_render(&$twig_vars, &$twig, &$template)
	{
		// Add a custom template variable
        $v = set_Reads(); // Get number of reads/views from counter.txt
        $twig_vars['view_count_formatted'] = number_format((float) $v);
        $twig_vars['view_count_raw'] = $v;
        $twig_vars['view_current_year'] = date("Y");
	}
    // Get current page title
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
    
    // open counter file for reading
    $fd = fopen('./counter.txt', 'r');
    if (!$fd) {
        die;
        }

    if (flock($fd, LOCK_EX)) // lock file for reading
    {
        // Read counter.txt file
        $i = 0;
        while (($file_data[$i] = fgets($fd)) !== false) {
            $i++;
        }
        if (!feof($fd)) {
            die;
        }
        
        // Find matching page tile and current counter value
        $next_val = false;
        $counter_val = 0;
        foreach ($file_data as &$value) {
            // found a match so we get the next value which is our counter
            if ($next_val) {
                $inc = $value + 1;
                $value = (string) $inc . "\r\n"; // Important, add carriage return and line feed to string
                $counter_val = $value;
                $next_val = false;
            }
            // find a match
            if (strcmp(trim($current_Page), trim($value)) == 0) {
                // echo '<h4>Match! ' . $value . '& ' . $current_Page . '</h4>';
                $next_val = true; // next value is our count number
                }
        }
        // Page is not found, so we create one and start counter at 1
        if ($counter_val == 0) {
            // echo $current_Page . ' <-- Found a new page, appending. . .';
            $file_data[] = $current_Page . "\r\n";
            $file_data[] = '1' . "\r\n";
            $counter_val = '1';
            }
        fflush($fd);
        flock($fd, LOCK_UN); // unlock file.
    } else {
        fclose($fd);
        die;
        } // lock for read failed, exit.
    
    fclose($fd); // done, close file
    
     // open counter file and re-write
    $fd = fopen('./counter.txt', 'w');
    if (!$fd) {
        die;
        }
    if (flock($fd, LOCK_EX)) // lock for write
    {
        foreach ($file_data as &$value) {
            fwrite($fd, (string) $value);
            }
    } else {
        fclose($fd);
        die;
        }

    fflush($fd);
    flock($fd, LOCK_UN); // unlock 
    
    // close counter file
    fclose($fd);
    
    return ($counter_val);
}
?>