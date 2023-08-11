        {if="$logged_in"}
        <script>
            var ucp = {};
            ucp.lang = {};
            ucp.v = {};
            ucp.v.dontLetGo = false;
            
            /* Configs */
            ucp.config = {};
            {$conf_js}
            
            /* Languages */
            {$lng_js}
        </script>
        {else}
        <script>
            var ucp = {};
        </script>
        {/if}
        
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
        <script src="{$static_link}js/jquery.timepicker.js"></script>
        <script src="{$static_link}js/formee.js"></script>
        <script src="{$static_link}js/ckeditor/ckeditor.js"></script>
        <script src="{$static_link}js/elFinder/js/elfinder.min.js"></script>
        <script src="{$static_link}js/elFinder/js/i18n/elfinder.{LANG="core.lng"}.js"></script>
        <script src="{$static_link}js/jquery.cookie.js"></script>
        <script src="{$static_link}js/easing.js"></script>
        <script src="{$static_link}js/metadata.js"></script>
        <script src="{$static_link}js/jquery.ibutton.min.js"></script>
        <script src="{$static_link}js/custom.js"></script>
        
        {$admin_js}
        
        {$extended_admin_footer}

    </body>
</html>