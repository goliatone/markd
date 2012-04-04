<?php
define('THEME_DATE_FORMAT', 'F jS, Y');                                                                                                         // Date format for string replacement in $

$themeReplacements = array(
        '{{disqus_shortname}}'        => 'mattwalters',
        '{{google_analytics_id}}'     => 'UA-69817-15',
        '{{jotform_id}}'              => '20201232841',
        '{{google-custom-search-id}}' => '010269824698549405122:WMX-1273292622'
);





/******************************************
You should not need to edit below this line
******************************************/

function msw_add_jotform_contact() {
        global $themeReplacements;
        $jotformId = $themeReplacements['{{jotform_id}}'];

        $jotformContent = '
                <script src="http://www.jotform.us/min/g=feedback" type="text/javascript">
                  new JotformFeedback({
                     formId             : "' . $jotformId . '",
                     buttonText : "Contact Me",
                     base               : "http://www.jotform.us/",
                     background : "#0064CD",
                     fontColor  : "#FFFFFF",
                     buttonSide : "right",
                     buttonAlign        : "center",
                     type               : 2,
                     width              : 700,
                     height             : 500
                  });
                </script>
        ';

        echo $jotformContent;
        return;
}

if ($themeReplacements['{{jotform_id}}'] != '') {
        $hooks->add_action('markd_footer', 'msw_add_jotform_contact');
}
