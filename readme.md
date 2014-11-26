## Views Counter
Pico CMS plugin to add a simple view counter to your pages.

Note: This is alpha software still under development -- use at your own risk.

Copy views_counter.php to your plugins directory.
Create "counter.txt" in your Pico home directory and give it enought file rights to allow pico to write to it.

Make sure your .MD files have unique "Titles" in the comment section at the top of your file.

Example:

    code
    
    Title: About Me
 
The plugin will grab the title from the .MD file and add an entry to the counter.txt file.
It will continue to add counters for each of your individual .MD files as they are viewed.

To display a counter on your page, you'll need to add {{ my_views_var }} to your template.
    Examples: 
    
    code
    
    Views: {{ view_count_raw }}
  
or formatted with comma's:

    code
    
    {{ view_count_formatted }}
   

Added template variable {{ my_date_var }}

Example: 

    code
    
    Copyright &copy; {{ my_date_var }}

## License
Free