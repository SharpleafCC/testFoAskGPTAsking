<?php
// Display current year
add_shortcode('year', function() {
  $year = date_i18n ('Y');
  return $year;
});
?>