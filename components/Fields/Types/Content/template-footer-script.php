<?php

	use hiweb\components\Fields\Types\Content\Field_Content;
?>
<script>
    hiweb_fields_type_content_default_settings = <?= json_encode( _WP_Editors::parse_settings( 'hiweb-test', Field_Content::$default_settings ) ); ?>;
</script>
