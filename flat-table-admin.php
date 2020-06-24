<?php global $wpdb ?>
<h1>Flat Meta Data</h1>
<p>This plugin transforms the tables containing post and user meta data (<?=$wpdb->prefix?>postmeta and <?=$wpdb->prefix?> usermeta) from a normalized form, i.e., multiple rows per post or user, into a flat spreadsheet more suitable for reporting or analysis.</p>
<?php foreach (['post', 'user'] as $k => $v): ?>
    <p>
        <a class="flat-table-link" target="_blank" href="?flat_table_download=<?=$v?>">
            <button>Download <?=$v?> meta data</button>
        </a>
        <!--<br/>
        Last compiled on [date]-->
    </p>
    <script type="text/javascript">
        jQuery('.flat-table-link').off().on('click', function(e) {
            if (!confirm('Generating and downloading meta data can negatively impact system performance. Are you sure you want to continue?')) {
                e.preventDefault();
            }
        });
    </script>
<?php endforeach ?>